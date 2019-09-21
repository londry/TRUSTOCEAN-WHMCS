<?php

use WHMCS\Database\Capsule;

/**
 * 统计SSL订单
 */
add_hook('ClientAreaHomepage', 1, function ()
{
     $servicesTotal = Capsule::table('tbltrustocean_certificate')->where('uid', $_SESSION['uid'])->count();
     $_SESSION['ssl_count'] = $servicesTotal;
});

/**
 * 检查SAN增加的条件 并从新生成价格
 * @param $vars
 * @return mixed|void
 */


/**
 * 添加SAN订单 并且生成SAN账单
 * @param $vars
 * @return mixed
 */
function trustoceanSslAddSanToCertOrderAndGetInvoice($vars){

        GLOBAL $MODLANG;

        $certificate_id = $vars['serviceid'];
        $service = Capsule::table('tbltrustocean_certificate')->where('serviceid', $vars['serviceid'])->first();

        // 获取ORDER信息 参考 WHMCS数据库 tblhosting
        $command = 'GetClientsProducts';
        $values = array(
            'serviceid' => $vars['serviceid'],
        );
        // Call the localAPI function
        $results = localAPI($command, $values);
        $hosting = $results['products']['product'][0];

        // todo:: 获取产品
        $package = Capsule::table('tblproducts')->where('id', $hosting['pid'])->first();

        if($service->status === "cancelled"){
            $_SESSION['TRUSTOCEANSSL_configOpsCustomValidateError'] = $MODLANG['trustoceanssl']['hookupgrade']['cancelledorder'];
            redir('type=configoptions&id=' . $vars['serviceid']);
        }
        // 检查是否支持多域名证书
        if($package->configoption4 !== "on"){
            $_SESSION['TRUSTOCEANSSL_configOpsCustomValidateError'] = $MODLANG['trustoceanssl']['hookupgrade']['notmdcorder'];
            redir('type=configoptions&id=' . $vars['serviceid']);
        }

        // 找到当前的配置
        foreach ($hosting['configoptions']['configoption'] as $opt){
            if($opt['type'] === "quantity"){
                $domaincount = $opt;
            }
        }
        // todo:: 获取 WHMCS 用户模型
        $whmcsClient = Capsule::table('tblclients')->where('id', $service->uid)->first();

        // 获取产品的optionID
        // $optionId = Capsule::table('tblhostingconfigoptions')->where('relid', $vars['serviceid'])->where('configid', $domaincount['id'])->value('optionid');
        $optionId = $domaincount["id"];

        // 修复多个配置项导致ID不匹配不扣费问题
        // 获取绝对的配置项价格configoptions的tblpricing.relid 对应  tblproductconfigoptionssub.id
        $relIdforConfigoptionPricingCheck = Capsule::table('tblproductconfigoptionssub')
            ->where('configid', $optionId)->where('optionname','LIKE','DomainCount%')
            ->first();
        $relIdforConfigoptionPricing = $relIdforConfigoptionPricingCheck->id;

        // 获取period内的单个SAN价格
        $netPrice = Capsule::table('tblpricing')->where('type','configoptions')
            ->where('relid', $relIdforConfigoptionPricing)
            ->where('currency', $whmcsClient->currency)
            ->value(strtolower($hosting['billingcycle']));

        // todo::检查是否存在未完成的升级订单
        $haveInvoice = false;
        $invoiceID = 0000;
        $invoice2 = Capsule::table('tbltrustocean_upgradeinvoice')->where('service_id', $vars['serviceid'])->where('status', 'processing')->first();
        $invoice2Status = Capsule::table('tblinvoices')->where('id', $invoice2->invoice_id)->value('status');
        if(!empty($invoice2) && $invoice2Status === "Unpaid"){
                $haveInvoice = true;
                $invoiceID = $invoice2->invoice_id;
        }

        if(!empty($invoice2) && $invoice2Status === "Unpaid"){
            $_SESSION['TRUSTOCEANSSL_configOpsCustomValidateError'] = $MODLANG['trustoceanssl']['hookupgrade']['unpaidInvoice'] .$invoiceID;
            redir('type=configoptions&id=' . $vars['serviceid']);
        }else{
            // AN总计的价格 保留2位小数
            $needSan = $vars['newSan'];
            $amount = round($needSan * $netPrice,2);

            if($amount > 0){
                // 新开账单并确认付款状态 参考 https://developers.whmcs.com/api-reference/createinvoice/
                $invoice = localAPI('CreateInvoice', array(
                    'userid' => $hosting['clientid'],
                    'status' => 'Unpaid',
                    'sendinvoice' => '0',
                    'paymentmethod' => 'mailin',
                    'date' => date('Y-m-d', time()),
                    'itemdescription1' => "Add $needSan SAN(s) To Order#" . $hosting['orderid'] . ' And Certificate#' . $certificate_id,
                    'itemamount1' => "$amount",
                    'itemtaxed1' => '0',
                    'autoapplycredit' => '0',
                    'notes' => $MODLANG['trustoceanssl']['hookupgrade']['invoiceNotes'],
                ));
                if ($invoice['result'] !== "success") {
                    $_SESSION['TRUSTOCEANSSL_configOpsCustomValidateError'] = $MODLANG['trustoceanssl']['hookupgrade']['internalApiError'];
                    redir('type=configoptions&id=' . $vars['serviceid']);
                }
                // 写入 tbltrustocean_upgradesan 数据表备份
                Capsule::table('tbltrustocean_upgradeinvoice')->insert(array(
                    'invoice_id' => $invoice['invoiceid'],
                    'service_id' => $vars['serviceid'],
                    'status' => 'processing',
                    'type' => 'trustocean-san',
                    'qty' => $needSan, //需要增加的SAN数量
                ));

                //账单未支付成功的情况下 取消刚才创建的订单
                $invoiceStatus = Capsule::table('tblinvoices')->where('id', $invoice['invoiceid'])->value('status');
                if ($invoiceStatus !== 'Paid') {
                    header('Location: /viewinvoice.php?id='.$invoice['invoiceid']);
                }
            }else{
                // 如果附加域名是免费的 则不生成账单 直接添加域名额度 但是不得超过250条
                $sanAllTotal = $vars['sanTotal'];
                $rlt = localAPI('UpdateClientProduct', array(
                    'serviceid' => $certificate_id,
                    'configoptions' => base64_encode(serialize(array($domaincount['id'] => array('optionid'=>$optionId, 'qty'=>$sanAllTotal)))),
                    'notes' => $MODLANG['trustoceanssl']['hookupgrade']['invoiceDesc'],
                ));
                redir('type=configoptions&id=' . $vars['serviceid']);
            }
        }

    }

/**
 * 处理SAN升级业务
 */
add_hook('ClientAreaPageUpgrade', 1, function($vars)
{
    # 语言文件
    GLOBAL $MODLANG;
    require_once __DIR__.'/libary/languageLoader.php';
    $langLoader = new lanaguageLoader($_SESSION);
    $MODLANG = $langLoader->loading();

    if (isset($vars['filename'], $vars['templatefile'], $_REQUEST['type']) && $vars['filename'] == 'upgrade' && $_REQUEST['type'] == 'configoptions')
    {
        if(isset($_SESSION['TRUSTOCEANSSL_configOpsCustomValidateError']) && $_SESSION['TRUSTOCEANSSL_configOpsCustomValidateError'] != '')
        {
            //diplay downgrade error message
            global $smarty;
            $error = $_SESSION['TRUSTOCEANSSL_configOpsCustomValidateError'];
            $_SESSION['TRUSTOCEANSSL_configOpsCustomValidateError'] = '';
            unset($_SESSION['TRUSTOCEANSSL_configOpsCustomValidateError']);

            $smarty->assign("errormessage", $error);
        }

        if(!isset($_REQUEST['step']) ||  $_REQUEST['step'] != '2')
            return;

        $serviceID = NULL;
        if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id']))
            $serviceID = $_REQUEST['id'];

        if ($serviceID === NULL)
            return;

        $sslService        = Capsule::table('tbltrustocean_certificate')->where('serviceid', $serviceID)->first();

        //check if service id goget product
        if (empty($sslService))
            return;
        //get config option id
        // 获取ORDER信息 参考 WHMCS数据库 tblhosting
        // Define parameters
        $command = 'GetClientsProducts';
        $values = array(
            'serviceid' => $serviceID,
        );

        // Call the localAPI function
        $results = localAPI($command, $values);
        $hosting = $results['products']['product'][0];

        // 找到当前的配置
        foreach ($hosting['configoptions']['configoption'] as $opt){
            if($opt['type'] === "quantity"){
                $domaincount = $opt;
            }
        }

        $whmcs = WHMCS\Application::getInstance();
        $configoption = $whmcs->get_req_var("configoption");

        //$optionId = Capsule::table('tblhostingconfigoptions')->where('relid', $serviceID)->where('configid', $domaincount['id'])->value('optionid');
        $optionId = $domaincount["id"];

        // todo:: 检查是不是降级
        if((int)$configoption["$optionId"] < $domaincount['value']){
            $_SESSION['TRUSTOCEANSSL_configOpsCustomValidateError'] = $MODLANG['trustoceanssl']['enroll']['upgrade']['cannotdowngrade'];
            redir('type=configoptions&id=' . $serviceID);
        }

        // todo:: 检查订单状态
        if ($sslService->status !== 'issued_active' AND $sslService->status !== 'configuration' AND $sslService->status !== 'enroll_domains')
        {
            $_SESSION['TRUSTOCEANSSL_configOpsCustomValidateError'] = $MODLANG['trustoceanssl']['enroll']['upgrade']['cannotupgrade'];
            redir('type=configoptions&id=' . $serviceID);
        }


        $requestParams = array(
            'serviceid'=>$serviceID,
            'newSan' => (int)$configoption["$optionId"] - $domaincount['value'],
            'sanTotal' => (int)$configoption["$optionId"],
        );

        return trustoceanSslAddSanToCertOrderAndGetInvoice($requestParams);
    }
});

/**
 * 检查SAN账单支付情况并且新增额度到现在的数据库
 */
add_hook('InvoicePaid', 1, function($vars) {

    # 语言文件
    GLOBAL $MODLANG;
    require_once __DIR__.'/libary/languageLoader.php';
    $langLoader = new lanaguageLoader($_SESSION);
    $MODLANG = $langLoader->loading();

    // Perform hook code here...
    $check = Capsule::table('tbltrustocean_upgradeinvoice')->where('invoice_id', $vars['invoiceid'])->where('status', 'processing')->first();
    // 增加新的SAN额度
    // 获取ORDER信息 参考 WHMCS数据库 tblhosting
    // Define parameters
    $command = 'GetClientsProducts';
    $values = array(
        'serviceid' => $check->service_id,
    );

    // Call the localAPI function
    $results = localAPI($command, $values);
    $hosting = $results['products']['product'][0];

    // 找到当前的配置
    foreach ($hosting['configoptions']['configoption'] as $opt){
        if($opt['type'] === "quantity"){
            $domaincount = $opt;
        }
    }
    // 获取产品的optionID
    // $optionId = Capsule::table('tblhostingconfigoptions')->where('relid', $check->service_id)->where('configid', $domaincount['id'])->value('optionid');
    $optionId = $domaincount["id"];

    if(!empty($check)){
        // 更新产品配置
        localAPI('UpdateClientProduct', array(
            'serviceid' => $check->service_id,
            'configoptions' => base64_encode(serialize(array($domaincount['id'] => array('optionid'=>$optionId, 'qty'=>$check->qty + $domaincount['value'])))),
            'notes' => $MODLANG['trustoceanssl']['hookupgrade']['invoiceDesc'],
        ));
        // 更新升级账单状态
        Capsule::table('tbltrustocean_upgradeinvoice')->where('service_id', $check->service_id)->where('status', 'processing')->update(
            array(
                'status' => 'processed',
            )
        );
    }
});