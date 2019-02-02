<?php
/**
  * CertificateAuthority Events Push
  * Process the Push notificateion and Reseller callback
  *
**/
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/../../../init.php';

use WHMCS\Database\Capsule;

$params = filterWords($_POST); #ca push-POST params

// 判断是否是有效的证书PUSH
if(isset($params['trustocean_id'])){
  // 检查是否已经签发证书
  if($params['status'] === 'issued_active'){
    // 获取本地的Certificate Capsule对象
    $certificate = Capsule::table('tbltrustocean_certificate')->where('trustocean_id',$params['trustocean_id'])->where('status','enroll_caprocessing')->first();
    // 如果不存在证书对象, 停止执行
    if(empty($certificate)){
      exit('cert processing error');
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
      "renew"                 =>  0,
      "reissue"               =>  0
    ));
    // 下面的内容应该使用 fastcgi 完成，释放当前的会话
    //fastcgi_finish_request(); # 响应完成, 关闭连接 后续语句继续在服务器端执行

    // 发送电子邮件通知给用户
    TRUSTOCEANSSL_RA_sendEmailNotificationForCertIssuance($certificate, $params['cert_code'], $params['ca_code']);
  }
}

//过滤post
function filterWords(&$str)
{
 $farr = array(
  "/<(\\/?)(script|i?frame|style|html|body|title|link|meta|object|\\?|\\%)([^>]*?)>/isU",
  "/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU",
  "/select\b|insert\b|update\b|delete\b|drop\b|;|\"|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile|dump/is"
  );
 $str = preg_replace($farr,'',$str);
 $str = strip_tags($str);
 return $str;
}


/**
 * 为用户发送证书签发的邮件通知
 *
 */
function TRUSTOCEANSSL_RA_sendEmailNotificationForCertIssuance($service, $cert_code, $ca_code){

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
        'custommessage' => file_get_contents(__DIR__.'/templates/email_notification_issued.tpl'),
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
