<?php
namespace WHMCS\Module\Server\TRUSTOCEANSSL\Controller;
use Londry\TrustOceanSSL\TrustoceanException;
use WHMCS\Database\Capsule;

class PublicController
{
    /**
     * @param $data
     * @return string
     * @throws TrustoceanException
     */
    public function verifyAndGetRsaEncodedContent($data){

        $moduleSetting = Capsule::table('tbladdonmodules')->where('module','TrustOceanSSLAdmin')
            ->where('setting','privatekey')->first();
        if(!$moduleSetting){
            throw new TrustoceanException("管理员并未配置用于PUSH验证的私钥");
        }
        $privateKey = $moduleSetting->value;
        if($privateKey == ""){
            throw new TrustoceanException("管理员并未配置用于PUSH验证的私钥");
        }

        $privateKey = openssl_get_privatekey($privateKey);
        if($privateKey === false){
            throw new TrustoceanException("管理员配置的PUSH私钥无效或格式不正确");
        }

        $header = "-----BEGIN TRUSTOCEAN ENCRYPTED DATA-----";
        $footer = "-----END TRUSTOCEAN ENCRYPTED DATA-----";
        $data = str_replace($footer, "", str_replace($header, "", $data));
        $encodedStringArray = str_split($data, 172);
        $decodedString = "";
        foreach ($encodedStringArray as $chunk){
            $result = openssl_private_decrypt(base64_decode($chunk),$temp,$privateKey);
            if(!$result){
                throw new TrustoceanException("私钥或传递的PUSH消息无效");
            }
            $decodedString .= $temp;
        }
        openssl_free_key($privateKey);
        return $decodedString; // 返回解密后的 array
    }

    /**
     * 处理 PUSH 消息
     * @param $vars
     * @return bool
     * @throws TrustoceanException
     */
    public function processPushEvent($vars){
        if (isset($_REQUEST['trustocean_id'])){
            if(isset($_REQUEST['encrypted_data'])){
                $request = $this->verifyAndGetRsaEncodedContent($_REQUEST['encrypted_data']);
            }else{
                $request = $_REQUEST;
            }

            if($request['type'] === 'cert_issued'){
                return $this->processForIssued($request);
            }
        }else{
            throw new TrustoceanException("找不到对应的证书订单");
        }
    }

    /**
     * 处理签发通知 事件
     * @param $params
     * @return bool
     * @throws TrustoceanException
     */
    protected function processForIssued($params){
        // 获取本地的Certificate Capsule对象
        $certificate = Capsule::table('tbltrustocean_certificate')->where('trustocean_id',$params['trustocean_id'])->where('status','enroll_caprocessing')->first();
        // 如果不存在证书对象, 停止执行
        if(empty($certificate)){
          throw new TrustoceanException("对应的证书不存在");
        }
        // 正式执行证书更新和通知
        $domains = json_decode($certificate->domains, 1); # 获取域名列表
        $submittedAt = $certificate->submitted_at; # 证书提交时间
        $issuedAt = $params['issued_at']; # 证书签发时间, 推送时间

        // 更新DCV验证状态
        $dcvInfo = json_decode($certificate->dcv_info, 1);
        foreach($dcvInfo as $domain => $info){
          $dcvInfo[$domain]['status'] = 'verified';
        }

        // 更新证书对象
        Capsule::table('tbltrustocean_certificate')->where('id', $certificate->id)->update(array(
          "ca_code"               =>  $params['ca_code'],
          "cert_code"             =>  $params['cert_code'],
          "status"                =>  "issued_active",
          "issued_at"             =>  $issuedAt,
          "dcv_info"              =>  $dcvInfo,
          "paidcertificate_status"=>  $params['paidcertificate_status'],
          "reissue"               =>  0
        ));

        // 发送电子邮件通知给用户
        $this->sendEmailNotificationForCertIssuance($certificate, $params['cert_code'], $params['ca_code']);

        return true;
    }

    /**
     * 发送签发通知给用户
     * @param $service
     * @param $cert_code
     * @param $ca_code
     */
    private function sendEmailNotificationForCertIssuance($service, $cert_code, $ca_code){
        $cert = openssl_x509_parse($cert_code , true); # 使用openssl获取证书详情信息
        $validTo_time_t = $cert['validTo_time_t']; # 证书有效期截止
        $validFrom_time_t = $cert['validFrom_time_t']; # 证书有效期从
        $domainString = str_replace('IPAddress:',',', str_replace('DNS:',',', $cert['extensions']['subjectAltName']));
        $domainString = substr($domainString, 1); # 域名列表字符串
        // 通过WHMCS内部API进行邮件发送
        $apiresult = localAPI('SendEmail', array(
            'messagename'=>'Client Signup Email',
            'id'=> $service->uid,
            'customtype' => 'general',
            'customsubject' => 'TLS/SSL Certificate Iussed Successfully!',
            'custommessage' => file_get_contents(__DIR__.'/../../templates/email_notification_issued.tpl'),
            'customvars'=>base64_encode(serialize(array(
                    "cert_name"=>$service->name,
                    "trustocean_id"=>$service->serviceid,
                    'cert_created_at'=>$service->created_at,
                    'cert_domain_list'=>$domainString,
                    'cert_valid_at'=>date('Y-m-d H:i:s', $validFrom_time_t),
                    'cert_expire_at'=>date('Y-m-d H:i:s', $validTo_time_t),
                    'cert_cert_code'=>$cert_code,
                    'cert_ca_code'=>$ca_code,
                )
            )),
        ));
    }
}