<?php
namespace WHMCS\Module\Server\TRUSTOCEANSSL\Controller;
use Londry\TrustOceanSSL\api\SslOrder;
use WHMCS\Module\Server\TRUSTOCEANSSL\Model\CertificateModel;
use WHMCS\Module\Server\TRUSTOCEANSSL\TrustOceanAPI;
use WHMCS\Database\Capsule;

class AdminController
{
    // API 服务
    private $apiService;

    private $apiApplication;

    private $serviceModel;

    function __construct($serviceid = "")
    {
        // 加载本地证书服务模型
        if($serviceid != NULL){
            $this->serviceModel = new CertificateModel($serviceid);
        }

        // 初始化 API 服务
        $this->apiService = new TrustOceanAPI();
        $api_username = Capsule::table('tbladdonmodules')->where('module','TrustOceanSSLAdmin')->where('setting','apiusername')->value('value');
        $api_password = Capsule::table('tbladdonmodules')->where('module','TrustOceanSSLAdmin')->where('setting','apipassword')->value('value');
        $this->initApiApplication($api_username, $api_password);
    }

    /**
     * @param $userName
     * @param $apiToken
     */
    private function initApiApplication($userName, $apiToken){
        $this->apiApplication = new SslOrder($userName, $apiToken);
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
        $this->apiApplication->callInit($this->serviceModel->getTrustoceanId());
        $domainName = trim($requestParams['domain']);
        try{
            $result = $this->apiApplication->callRemoveDomainName($domainName);

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
        // 本地WHMCS订单
        $localOrder = $this->serviceModel;
        // 取回远端签发系统中的订单
        $remoteOrder = $this->apiApplication->callInit($this->serviceModel->getTrustoceanId());
        // 更新本地订单
        $localOrder->setDomains($remoteOrder->getDomains());
        $localOrder->setDcvInfo($remoteOrder->getDcvInfo());
        $localOrder->setCsrCode($remoteOrder->getCsrCode());
        $localOrder->setStatus($remoteOrder->getOrderStatus());
        $localOrder->setCsrCode($remoteOrder->getCertCode());
        $localOrder->setCaCode($remoteOrder->getCaCode());
        $localOrder->setContactEmail($remoteOrder->getContactEmail());
        $localOrder->setRefundStatus($remoteOrder->getRefundStatus());
        $localOrder->flush();

        return "success";
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
            $remoteOrder = $this->apiApplication->callInit($this->serviceModel->getTrustoceanId());
            $remoteOrder->callCancelAndRevokeCertificate();
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
            $remoteOrder = $this->apiApplication->callInit($this->serviceModel->getTrustoceanId());
            $remoteOrder->callRevokeCertificate($revocationReason);
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
            $remoteOrder = $this->apiApplication->callInit($this->serviceModel->getTrustoceanId());
            $remoteOrder->callRetryDcvProcess();
            return "success";
        }catch(\Exception $exception){
            return $exception->getMessage();
        }
    }
}