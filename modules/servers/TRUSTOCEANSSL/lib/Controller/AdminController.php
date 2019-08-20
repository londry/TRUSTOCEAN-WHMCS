<?php
namespace WHMCS\Module\Server\TRUSTOCEANSSL\Controller;
use WHMCS\Module\Server\TRUSTOCEANSSL\Model\CertificateModel;
use WHMCS\Module\Server\TRUSTOCEANSSL\TrustOceanAPI;
use WHMCS\Database\Capsule;

class AdminController
{
    // API 服务
    private $apiService;

    function __construct()
    {
        // 初始化 API 服务
        $this->apiService = new TrustOceanAPI();
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

        $html = $smarty->fetch(__DIR__.'/../../templates/admin/testTab.tpl');
        return [
            'SSL Management' => $html
        ];
    }
}