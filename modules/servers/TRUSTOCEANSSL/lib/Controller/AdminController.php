<?php
namespace WHMCS\Module\Server\TRUSTOCEANSSL\Controller;
use WHMCS\Module\Server\TRUSTOCEANSSL\Model\CertificateModel;
use WHMCS\Module\Server\TRUSTOCEANSSL\ServiceProvider\TrustOceanSSL\RemoteService;
use WHMCS\Module\Server\TRUSTOCEANSSL\TrustOceanAPI;
use WHMCS\Database\Capsule;

class AdminController
{
    // API 服务
    private $apiService;

    private $apiApplication;

    private $serviceModel;

    private $remoteService_apiusername;

    private $remoteService_apipassword;

    function __construct($serviceid = "")
    {
        // 加载本地证书服务模型
        if($serviceid != NULL){
            $this->serviceModel = new CertificateModel($serviceid);
        }

        // 初始化 API 服务
        $this->apiService = new TrustOceanAPI();
        $this->remoteService_apiusername = Capsule::table('tbladdonmodules')->where('module','TrustOceanSSLAdmin')->where('setting','apiusername')->value('value');
        $this->remoteService_apipassword = Capsule::table('tbladdonmodules')->where('module','TrustOceanSSLAdmin')->where('setting','apipassword')->value('value');
    }

    /**
     * @param $userName
     * @param $apiToken
     * @return RemoteService
     */
    private function getRemoteService(){
        return new RemoteService($this->remoteService_apiusername, $this->remoteService_apipassword);
    }

    /**
     * WHMCS 产品配置页面 Module Setting Tab Fields
     * @return array
     */
    public function ConfigOptions(){

        $rlt = $this->apiService->getProductList();

        $options = [];

        foreach ($rlt as $pid => $product){
            $options[$pid] = $product['name'];
        }

        return array(
            // a text field type allows for single line text input
            'Product' => array(
                'Type' => 'dropdown',
                'Options' => $options,
            ),
            'Class' => array(
                'Type' => 'dropdown',
                'Options' => array(
                    'dv' => "Domain Validation",
                    'ov' => "Organization Validation",
                    'ev' => "Extend Validation"
                ),
                'Description' => 'Validation Class',
            ),
            // the radio field type displays a series of radio button options
            'Wildcard' => array(
                'Type' => 'yesno',
                'Description' => 'Is Support Wildcard?',
            ),
            'MultiDomain' => array(
                'Type' => 'yesno',
                'Description' => 'Is Support MultiDomain?',
            ),
            'IP' => array(
                'Type' => 'yesno',
                'Description' => 'Is Support IP address?',
            )
        );
    }

    /**
     * 移除证书中的域名
     * @param $domainName
     * @return string
     */
    public function removeDomainName($requestParams){
        $domainName = trim($requestParams['domain']);
        try{

            $result = $this->getRemoteService()->getService($this->serviceModel->getTrustoceanId())->removeDomainName($domainName);

            if($result === TRUE){
                $dcvInfo = $this->serviceModel->getDcvInfo();
                unset($dcvInfo[$domainName]);
                $this->serviceModel->setDcvInfo($dcvInfo);

                $domains = $this->serviceModel->getDomains();
                $newDomains = [];
                foreach ($domains as $key => $theDomainName){
                    if($theDomainName != $domainName){
                        array_push($newDomains, $theDomainName);
                    }
                }
                $this->serviceModel->setDomains($newDomains);
                // 保存修改到数据库
                $this->serviceModel->flush();

                // 保存新的主域名至数据库字段 tblhosting.domain
                Capsule::table('tblhosting')->where('userid', $this->serviceModel->getUid())
                    ->where('id', $this->serviceModel->getServiceid())
                    ->update([
                        'domain' => $newDomains[0]
                    ]);

                return "success";

            }else{
                return "删除域名时出现错误, 请您检查后再试.";
            }
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * WHMCS 订单详情页面附加信息
     * @param $vars
     * @return array
     */
    public function AdminServiceTab($vars){
        $service = Capsule::table('tbltrustocean_certificate')->where('serviceid',$vars['serviceid'])->first();
        // 获取 Smarty 服务
        $smarty = new \Smarty();
        $smarty->assign('service', $service);

        $serviceModel = new CertificateModel($service->serviceid);

        $certInfo = openssl_x509_parse($serviceModel->getCertCode());
        if(!$certInfo){
            $expireAt = "----";
        }else{
            $expireAt = date('Y-m-d H:i:s', $certInfo['validTo_time_t']);
        }
        $smarty->assign('validTo', $expireAt);
        $smarty->assign('dcvInformation', $serviceModel->getDcvInfo());
        $smarty->assign('certCode', $serviceModel->getCertCode());
        $smarty->assign('certModel', $serviceModel);
        $smarty->assign('csrInfo', openssl_csr_get_subject($serviceModel->getCsrCode()), 1);
        $smarty->assign('orgInfo', $serviceModel->getOrgInfo());

        $html = $smarty->fetch(__DIR__.'/../../templates/admin/testTab.tpl');
        return [
            'SSL Management' => $html
        ];
    }

    /**
     * 首次提交到TRUSTOCEAN之前, 可设置订单为续费订单
     * @return string
     */
    public function setOrderAsRenewal(){
        // 只有未完成首次提交的订单方可设置为续费订单
        if($this->serviceModel->getTrustoceanId() !== ""){
            return "无法标记为续费订单, 因为此订单已经完成了首次提交至签发系统";
        }
        $this->serviceModel->setRenew(1);
        $this->serviceModel->flush();
        return "success";
    }

    /**
     * 同步签发系统的订单信息至本地WHMCS系统
     * @return string
     */
    public function syncOrderInformation(){
        // 检查是否已经提交至签发系统
        if($this->serviceModel->getTrustoceanId() == ""){
            return "同步信息失败, 当前订单并未提交至签发系统";
        }

        try{
            // 本地WHMCS订单
            $localOrder = $this->serviceModel;
            // 取回远端签发系统中的订单
            $remoteOrder = $this->getRemoteService()->getService($this->serviceModel->getTrustoceanId());

            // 更新本地订单
            $localOrder->setDomains($remoteOrder->getDomains());
            $localOrder->setDcvInfo($remoteOrder->getDcvInfo());
            $localOrder->setCsrCode($remoteOrder->getCsrCode());
            $localOrder->setStatus($remoteOrder->getOrderStatus());
            $localOrder->setCertCode($remoteOrder->getCertCode());
            $localOrder->setCaCode($remoteOrder->getCaCode());
            $localOrder->setContactEmail($remoteOrder->getContactEmail());
            $localOrder->setRefundStatus($remoteOrder->getRefundStatus());
            $localOrder->setCertificateId($remoteOrder->getCertificateId());
            $localOrder->flush();

            // 保存新的主域名至数据库字段 tblhosting.domain
            $newDomains = $remoteOrder->getDomains();
            Capsule::table('tblhosting')->where('userid', $this->serviceModel->getUid())
                ->where('id', $this->serviceModel->getServiceid())
                ->update([
                    'domain' => $newDomains[0]
                ]);

            return "success";
        }catch(\Exception $exception){
            return $exception->getMessage();
        }

    }

    /**
     * 申请吊销并退款
     * @return string
     */
    public function cancelAndRefundOrder(){
        // 检查是否已经提交至签发系统
        if($this->serviceModel->getTrustoceanId() == ""){
            return "同步信息失败, 当前订单并未提交至签发系统";
        }

        try{
            // 取回远端签发系统中的订单
            $this->getRemoteService()->getService($this->serviceModel->getTrustoceanId())->cancelAndRefund();

            // 本地WHMCS订单
            $localOrder = $this->serviceModel;
            $localOrder->setIsRequestedRefund(1);
            $localOrder->setRefundStatus('processing');
            $localOrder->flush();

            return "success";
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * 直接从CA处吊销证书, 不申请退款
     * @param $vars
     * @return string
     */
    public function revokeSSLWithReason($revocationReason){
        // 检查是否已经提交至签发系统
        if($this->serviceModel->getTrustoceanId() == ""){
            return "同步信息失败, 当前订单并未提交至签发系统";
        }
        try {
            // 取回远端签发系统中的订单
            $this->getRemoteService()->getService($this->serviceModel->getTrustoceanId())->revoke($revocationReason);
            return "success";
        }catch(\Exception $exception){
            return $exception->getMessage();
        }
    }

    /**
     * 重新检查域名验证状态
     * @return string
     */
    public function retryDcvProcess(){
        // 检查是否已经提交至签发系统
        if($this->serviceModel->getTrustoceanId() == ""){
            return "同步信息失败, 当前订单并未提交至签发系统";
        }
        try {
            // 取回远端签发系统中的订单
            $this->getRemoteService()->getService($this->serviceModel->getTrustoceanId())->retryDcvProcess();
            return "success";
        }catch(\Exception $exception){
            return $exception->getMessage();
        }
    }

    /**
     * 升级 SAN 域名额度
     * @param $vars
     * @return string
     */
    public function upgradeSanCount($vars){
        return "success";
    }

    /**
     * 用户区域输出
     * @param $vars
     * @return array
     */
    public function clientArea($vars){
        GLOBAL $MODLANG;
        $localOrder = $this->serviceModel;
        // 是否为多域名证书
        $isMultiDomain = $vars['configoption4'] === "on"?true:false;
        // 域名额度
        $domainCount = $vars['configoptions']['DomainCount'];

        $x509 = openssl_x509_parse($localOrder->getCertCode(), TRUE);
        $x509['extensions']['subjectAltName'] = str_replace(' ',"<br/>",str_replace(',',"", trim(str_replace('IP_ADDRESS:',',', str_replace('DNS:',',', $x509['extensions']['subjectAltName'])))));
        $x509['validFrom'] = date('Y-m-d H:i:s', $x509['validFrom_time_t']);
        $x509['validTo'] = date('Y-m-d H:i:s', $x509['validTo_time_t']);
        $returnvars = array();
        $returnvars['x509'] = $x509;

        $returnvars['orginfo'] = $localOrder->getOrgInfo();
        $returnvars['domains'] = $localOrder->getDomains();
        $returnvars['status'] = $localOrder->getStatus();
        $dcvInfo = $localOrder->getDcvInfo();
        $returnvars['hasemail'] = false;
        foreach ($dcvInfo as $key => $info){
            if($info['method'] === 'email'){
                $returnvars['hasemail'] = true;
            }
        }
        //添加 DCVEMAIL 地址
        foreach ($dcvInfo as $key => $info){
            $dcvInfo[$key]['dcvemails'] = $this->generateDcvEmails($key);
        }
        $returnvars['serviceid'] = $localOrder->getServiceid();
        $returnvars['domaintotal'] = $domainCount == ""?1:$domainCount;

        if($localOrder->getReissue() === 1){
            $returnvars['reissue'] = true;
            $returnvars['csr'] = $localOrder->getCsrCode();
            $domains = "";
            foreach ($localOrder->getDomains() as $domain){
                $domains .= $domain."\r\n";
            }
            $returnvars['domains'] = $domains;
        }

        if($localOrder->getStatus() === "issued_active"){
            $returnvars['domains'] = $localOrder->getDomains();
            $returnvars['csr'] = $localOrder->getCsrCode();
            $returnvars['cert'] = $localOrder->getCertCode();
            $returnvars['chainscert'] = $localOrder->getCaCode();
            $returnvars['isseal'] = true;
            $returnvars['sealid'] = '<script data-sealid="'.$localOrder->getCertificateId().'" id="trustoceansealapp" type="text/javascript" src="https://www.trustocean.com/app/seal.min.js?version=1.0.1"></script>';
            $returnvars['csrobj'] = openssl_csr_get_subject($localOrder->getCsrCode(), false);
        }

        $returnvars['ismultidomain'] = $vars['configoption4'];
        $returnvars['trustoceanid'] = $localOrder->getTrustoceanId();
        $returnvars['configoption2'] = $vars['configoption2'];

        #todo:: 提交到CA后 使用async方式查看域名验证信息
        if($localOrder->getStatus() === "enroll_dcv" || $localOrder->getStatus() === "enroll_ca" || $localOrder->getStatus() === "submit_hand" || $localOrder->getStatus() === "check_hand" || $localOrder->getStatus() === "enroll_caprocessing"){
            $returnvars['domains'] = $localOrder->getDomains();
            $returnvars['dcvinfo'] = $dcvInfo;
            $returnvars['csrhash'] = TRUSTOCEANSSL_getCsrHash($localOrder->getCsrCode());
            $returnvars['uniqueid'] = $localOrder->getUniqueId();
            $returnvars['ismultidomain'] = $vars['configoption4'];
        }

        $user = Capsule::table('tblclients')->where('id', $_SESSION['uid'])->first();
        $returnvars['email'] = $user->email;

        // 站点签章设置
        $siteSeal = Capsule::table('tbltrustocean_configuration')->where('setting','siteseal')->first();
        $returnvars['show_siteseal'] = $siteSeal->value === "hidden"?false:true;

        return array(
            'templatefile' => 'templates/cert_view',
            'vars' => array(
                'status'=>$localOrder->getStatus(),
                'assetsPath'=>__DIR__.'/../../assets',
                'x509' => $x509,
                'vars' => $returnvars,
                'MODLANG' => $MODLANG,
                'TOLANG'  => $vars['_lang'],
                "localOrder"=>$localOrder,
            ),
        );
    }

    /**
     * 计算csr hash
     * @param $csrCode
     * @return array
     */
    private function getCsrHash($csrCode){
        #convert to .der code type
        $stringBegin    =   "CERTIFICATE REQUEST-----";
        $stringEnd      =   "-----END";
        $pureCsrData    =   substr($csrCode, strpos($csrCode, $stringBegin)+strlen($stringBegin));
        $pureCsr        =   substr($pureCsrData, 0, strpos($pureCsrData, $stringEnd));
        $csrDer         =   base64_decode($pureCsr);

        $md5 = md5($csrDer);
        $hash256 = hash('sha256', $csrDer);

        #return the encode hash value
        return array(
            'md5'       =>  $md5,
            'sha256'      =>  $hash256,
            'dns'   =>  array(
                'purehost'  =>  "_".strtolower($md5),
                'purevalue' =>  strtolower(substr($hash256, 0,32).'.'.substr($hash256, 32,32))
            ),
            'http'   =>  array(
                'filename'  =>  strtoupper($md5).'.txt',
                'firstline' =>  strtolower($hash256)
            ),
        );
    }

    /**
     * 生成 DCV emails 地址
     * @param $domain
     * @return array
     */
    private function generateDcvEmails($domain){
        // 创建域名对象
        $domain = new \blobfolio\domain\domain(str_replace('*.', '', $domain));
        //判断是否是IP地址
        if($domain->is_ip()){
            return [];
        }
        // 找出顶级域名
        $topLevelDomain = $domain->get_domain().'.'.$domain->get_suffix();
        // 找到level1 domain
        $lastLevelDomain = $domain->get_host();
        // 找出其他level domain
        $otherLevelDomains = [];
        $subString = explode('.', $domain->get_subdomain());
        // 去除空格
        foreach ($subString as $key => $st){
            if($st == ""){
                unset($subString[$key]);
            }
        }
        //组成其他级别的子域名字符
        foreach ( $subString as $x => $subdomain){
            // 计算每次的值
            // www.my.staff.center
            $m = count($subString);
            $st2 = "";
            for($n = $x; $n < $m; $n++){
                $st2 .= $subString[$n].'.';
            }
            $st2 = substr($st2, 0, strlen($st2)-1);
            // 添加子域名前缀到数组
            array_push($otherLevelDomains, $st2);

        }
        // 组织预验证的邮箱地址
        $supportAddress = [
            'admin@','administrator@','hostmaster@','postmaster@','webmaster@'
        ];
        $emails = [];
        //添加顶级域名的邮箱
        foreach ($supportAddress as $address){
            array_push($emails, $address.$topLevelDomain);
        }
        //添加其他级别的域名邮箱地址
        foreach ($otherLevelDomains as $subdomain){
            foreach ($supportAddress as $address){
                array_push($emails, $address.$subdomain.'.'.$topLevelDomain);
            }
        }

        return $emails;
    }
}