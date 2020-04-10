<?php
/**
 * TRUSTOCEAN SSL Module
 * Powered By QiaoKr Corporation Limited
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

use WHMCS\Database\Capsule;
use WHMCS\View\Menu\Item as MenuItem;

require __DIR__.'/service/globalService.php';

function TRUSTOCEANSSL_MetaData()
{
    return array(
        'DisplayName' => 'TRUSTOCEAN™ SSL',
        'APIVersion' => '1.1', // Use API Version 1.1
        'RequiresServer' => false, // Set true if module requires a server to work
        'DefaultNonSSLPort' => '80', // Default Non-SSL Connection Port
        'DefaultSSLPort' => '443', // Default SSL Connection Port
    );
}

/**
 * 产品配置参数
 * @return array
 */
function TRUSTOCEANSSL_ConfigOptions()
{
    $adminController = new \WHMCS\Module\Server\TRUSTOCEANSSL\Controller\AdminController();
    return $adminController->ConfigOptions();
}

/**
 * 管理员订单详情面板 输出证书信息
 * @param $params
 * @return array
 */
function TRUSTOCEANSSL_AdminServicesTabFields($vars) {
    $adminController = new \WHMCS\Module\Server\TRUSTOCEANSSL\Controller\AdminController();
    return $adminController->AdminServiceTab($vars);
}

/**
 * 移除未通过验证的域名
 * @param $vars
 * @return string
 */
function TRUSTOCEANSSL_removeDomain($vars){
    $adminController = new \WHMCS\Module\Server\TRUSTOCEANSSL\Controller\AdminController($vars['serviceid']);
    return $adminController->removeDomainName($_POST);
}

/**
 * 首次订单, 设置续费赠送30~90天
 * @param $vars
 * @return string
 */
function TRUSTOCEANSSL_setOrderAsRenewal($vars){
    $adminController = new \WHMCS\Module\Server\TRUSTOCEANSSL\Controller\AdminController($vars['serviceid']);
    return $adminController->setOrderAsRenewal();
}

/**
 * 同步签发系统信息至本地WHMCS系统
 * @param $vars
 * @return string
 */
function TRUSTOCEANSSL_syncOrderInformation($vars){
    $adminController = new \WHMCS\Module\Server\TRUSTOCEANSSL\Controller\AdminController($vars['serviceid']);
    return $adminController->syncOrderInformation();
}

/**
 * 申请吊销证书、取消订单、从签发系统中退款
 * @param $vars
 * @return string
 */
function TRUSTOCEANSSL_cancelAndRefundOrder($vars){
    $adminController = new \WHMCS\Module\Server\TRUSTOCEANSSL\Controller\AdminController($vars['serviceid']);
    return $adminController->cancelAndRefundOrder();
}

/**
 * 直接从颁发机构吊销证书, 不申请退款
 * @param $vars
 * @return string
 */
function TRUSTOCEANSSL_revokeSSLWithReason($vars){
    $adminController = new \WHMCS\Module\Server\TRUSTOCEANSSL\Controller\AdminController($vars['serviceid']);
    return $adminController->revokeSSLWithReason($_POST['revocationReason']);
}

/**
 * 重新执行域名验证信息检查
 * @param $vars
 * @return string
 */
function TRUSTOCEANSSL_retryDcvProcess($vars){
    $adminController = new \WHMCS\Module\Server\TRUSTOCEANSSL\Controller\AdminController($vars['serviceid']);
    return $adminController->retryDcvProcess();
}

/**
 * 升级证书的 SAN 域名额度
 * @param $vars
 * @return string
 */
function TRUSTOCEANSSL_ChangePackage($vars){
    $adminController = new \WHMCS\Module\Server\TRUSTOCEANSSL\Controller\AdminController($vars['serviceid']);
    $adminController->upgradeSanCount($vars);
}

/**
 * 客户端异步获取域名验证状态
 * @param $vars
 * @throws Exception
 */
function TRUSTOCEANSSL_clientarteaSyncOrderStatus($vars){

    $service = Capsule::table('tbltrustocean_certificate')->where('serviceid', $vars['serviceid'])->first();
    $result = TRUSTOCEANSSL_CALLAPI(array(
        'action'=>'getDomainValidationStatus',
        'trustocean_id'=>$service->trustocean_id,
    ));
    TRUSTOCEANSSL_clientApiResponse($result);
}

/**
 * 检查必要的企业信息
 * @param array $required
 * @param array $params
 */
function TRUSTOCEANSSL_checkOrgParams(array $required,array $params){
    global $MODLANG;

    foreach ($required as $key){
        if(!isset($params[$key]) || $params[$key] == ""){
            TRUSTOCEANSSL_APIRESPONSE(['status'=>'error','message'=>$MODLANG['trustoceanssl']['apierror']['orginfoe2']]);
        }
    }
}

/**
 * 重置订单状态为初始配置
 * @param $vars
 * @return string
 * @throws Exception
 */
function TRUSTOCEANSSL_resetorderstatus($vars) {
    $service = Capsule::table('tbltrustocean_certificate')->where('serviceid',$vars['serviceid'])->first();
    global $MODLANG;

    if($service->status === "enroll_submithand" || $service->status === "enroll_ca" || $service->status === "enroll_caprocessing" || $service->status === "issued_active"){
        return $MODLANG['trustoceanssl']['apierror']['cannotresetorder'];
    }else{
        Capsule::table('tbltrustocean_certificate')->where('serviceid',$vars['serviceid'])->update(array(
            'status'  => 'configuration',
            'dcv_info' => "",
            'domains' => "",
            'csr_code' => "",
        ));
        return "success";
    }
}

/**
 * 设置为续费订单
 * @param $vars
 * @return string
 */
function TRUSTOCEANSSL_setRenewOrder($vars){
    $service = Capsule::table('tbltrustocean_certificate')->where('serviceid',$vars['serviceid'])->update(array(
        "renew"=>1,
    ));
    return "success";
}

/**
 * 管理员获取证书信息
 * @param $vars
 * @return string
 * @throws Exception
 */
function TRUSTOCEANSSL_adminSynccertorderdata($vars) {
    global $MODLANG;

    $service = Capsule::table('tbltrustocean_certificate')->where('serviceid',$vars['serviceid'])->first();
    if($service->status !== "issued_active"){
        return "未签发的证书无法拉取远端信息";
    }

    if($service->trustocean_id === ""){
        return "未提交到TrustOcean的订单无法拉取远端信息";
    }

    $result = TRUSTOCEANSSL_CALLAPI(array(
        'action'=>'getOrderStatus',
        'trustocean_id'=>$service->trustocean_id,
    ));
    //todo:: 证书已经签发了 需要更新本地数据库
    if($result['status'] === "success" AND $result['cert_status'] === "issued_active"){
        $order = TRUSTOCEANSSL_CALLAPI(array(
            'action'=>'getSSLDetails',
            'trustocean_id'=>$service->trustocean_id,
        ));
        Capsule::table('tbltrustocean_certificate')->where('serviceid',$vars['serviceid'])->update(array(
                'cert_code' => $order['cert_code'],
                'csr_code' => $order['csr_code'],
                'ca_code' => $order['ca_code'],
                'status'  => 'issued_active',
                'org_info'  => $order['org_info'],
                'domains'  => json_encode($order['domains']),
                'dcv_info' =>json_encode($order['dcv_info']),
                'issued_at' => date('Y-m-d H:i:s'),
        ));
    }
    TRUSTOCEANSSL_clientApiResponse($result);
}

/**
 * 用户Ajax获取证书信息
 * @param $vars
 * @return string
 * @throws Exception
 */
function TRUSTOCEANSSL_clientSynccertorderdata($vars) {
    global $MODLANG;

    $service = Capsule::table('tbltrustocean_certificate')->where('serviceid',$vars['serviceid'])->first();
    //fetchcert检查
    if(time() < (strtotime($service->checkcert_clicked)+60*3)){
        $waitingTime =  round($waitingTime = ((strtotime($service->checkcert_clicked)+60*3) - time())/60, 2);
        TRUSTOCEANSSL_clientApiResponse(['status'=>'error',"message"=>$MODLANG['trustoceanssl']['apierror']['wait5']]);
    }
    // 更新fetchcert执行时间
    Capsule::table('tbltrustocean_certificate')->where('serviceid',$vars['serviceid'])->update(array(
        "checkcert_clicked"=>date('Y-m-d H:i:s'),
    ));

    $result = TRUSTOCEANSSL_CALLAPI(array(
        'action'=>'getOrderStatus',
        'trustocean_id'=>$service->trustocean_id,
    ));
    //todo:: 证书已经签发了 需要更新本地数据库
    if($result['status'] === "success" AND $result['cert_status'] === "issued_active"){
        $order = TRUSTOCEANSSL_CALLAPI(array(
            'action'=>'getSSLDetails', 
            'trustocean_id'=>$service->trustocean_id,
        ));
        Capsule::table('tbltrustocean_certificate')->where('serviceid',$vars['serviceid'])->update(array(
                'cert_code' => $order['cert_code'],
                'csr_code' => $order['csr_code'],
                'ca_code' => $order['ca_code'],
                'status'  => 'issued_active',
                'org_info'  => $order['org_info'],
                'domains'  => json_encode($order['domains']),
                'reissue' => 0,
                'dcv_info' =>json_encode($order['dcv_info']),
                'renew' => 0,
                'issued_at' => date('Y-m-d H:i:s'),
        ));
    }
    TRUSTOCEANSSL_clientApiResponse($result);
}

/**
 * 将不规范的PEM证书转换为规范的格式
 * @param $datastring
 * @return string
 */
function TRUSTOCEANSSL_x509stringtopem($datastring){
    $datastring = "-----BEGIN CERTIFICATE-----\n".chunk_split($datastring, 64, PHP_EOL)."-----END CERTIFICATE-----";
    return $datastring;
}

/**
 * CALL TRUSTOCEAN API
 * @param $method
 * @param $params
 * @return array
 * @throws Exception
 */
function TRUSTOCEANSSL_CALLAPI($params){
    $method = $params['action'];
    unset($params['action']);
    # Partner Login Details
    $params['username'] = Capsule::table('tbladdonmodules')->where('module','TrustOceanSSLAdmin')->where('setting','apiusername')->value('value');
    $params['password'] =  Capsule::table('tbladdonmodules')->where('module','TrustOceanSSLAdmin')->where('setting','apipassword')->value('value');

    $postVars = http_build_query ($params);

    // todo:: 检查设置的API版本
    $apiURL = 'https://api.crazyssl.com/ssl/v2/'.$method;

    $location  = Capsule::table('tbladdonmodules')->where('module','TrustOceanSSLAdmin')->where('setting','apiservertype')->value('value');
    if($location === "CN-Beijing"){
        $apiURL = "https://api.crazyssl.com/ssl/v2/$method"; // API located in Beijing CN
    }else{
        $apiURL = "https://api.crazyssl.com/ssl/v2/$method"; // API located in London UK
    }

    $curlHandle = curl_init ();
    curl_setopt ($curlHandle, CURLOPT_URL, $apiURL);
    curl_setopt ($curlHandle, CURLOPT_POST, 1);
    curl_setopt ($curlHandle, CURLOPT_SSL_VERIFYPEER, TRUE);
    curl_setopt ($curlHandle, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt ($curlHandle, CURLOPT_POSTFIELDS, $postVars);
    $callResult = curl_exec ($curlHandle);
    if (!curl_error ($curlHandle)) {
        curl_close ($curlHandle);
        $result = json_decode($callResult, 1);
        if($result['status'] === 'error'){
            return array(
                "status"         =>  "error",
                'message'  =>  $result['message'],
            );
        }else{
            return $result;
        }
    }else{
        return array(
            "status"         =>  "error",
            "message"       => "CURL ERROR, Please check your API call function",
        );
    }
}


/**
 * 创建证书账户
 * @param $vars
 * @return string
 * @throws Exception
 */
function TRUSTOCEANSSL_CreateAccount($vars){

    // todo:: 检查是否已经开通过了
    $check = Capsule::table('tbltrustocean_certificate')->where('uid', $vars['clientsdetails']['id'])
    ->where('serviceid', $vars['serviceid'])->get();
    if(!empty($check)){
        throw new Exception('Already have an active service created in database');
    }
    $period = Capsule::table('tblhosting')->where('id', $vars['serviceid'])->value('billingcycle');

    // todo:: 域名数量
    if($vars['configoption4'] === "on"){
        $domain_count = (int)$vars['configoptions']['DomainCount'];
    }else{
        $domain_count = 1;
    }

    // 认证级别
    $product = Capsule::table('tblproducts')->where('id', $vars['pid'])->first();
    Capsule::table('tbltrustocean_certificate')
        ->insert(array(
            "uid"       =>  $vars['clientsdetails']['id'],
            "serviceid" =>  $vars['serviceid'],
            "name" =>  $product->name,
            "status"    =>  "configuration",
            "period"    =>  $period,
            "contact_email" => $vars['clientsdetails']['email'],
            "submitted_at" => date('Y-m-d H:i:s'),
            "class"     =>  $vars['configoption2'],
            'multidomain'  =>  $vars['configoption4'] === "on" ?1:0,
            'created_at'    => date('Y-m-d H:i:s', time()),
        ));

    return "success";

}

/**
 * 检查域名格式和数量限制
 * @param $domains
 * @param $service
 * @param $vars
 * @return array
 */
function TRUSTOCEANSSL_checkDomains($domains, $service, $vars){
    global $MODLANG;

    // todo:: 生成域名列表
    // 检查多域名证书
    if($service->multidomain === 1 || $vars['configoption4'] === "on"){
        $domainArray = [];
        foreach (explode(PHP_EOL, $domains) as $domain){
            if(trim($domain) !== ""){
                $domainArray[] = $domain;
            }
        }
        // 检查域名是否含有重复的域名
        if(count($domainArray) != count(array_unique($domainArray))){
             return ['error'=>$MODLANG['trustoceanssl']['apierror']['repeatdomain']];
        }
        // 检查域名数量限制
        if(count($domainArray) > $service->domain_count){
            return ['error'=>$MODLANG['trustoceanssl']['apierror']['amountsan']];
        }
        // 检查域名格式是否正确
        $errorDomains = "";
        foreach ($domainArray as $domain){
            // 适配通配符证书
            if($vars['configoption3'] !== 'on'){
                if(substr($domain, 0,2) == "*."){
                    return ['error'=>$MODLANG['trustoceanssl']['apierror']['wildcard'].$domain];
                }
            }
            if(
                $vars['configoption3'] === 'on'
                && substr($domain, 0,2) == "*."
            ){
                $domain = substr($domain, 2);
                $domain0 = new \blobfolio\domain\domain($domain);
                $domain1 = new blobfolio\domain\domain($domain0->get_host());
                if($vars['configoption5'] !== 'on'){
                    if($domain1->is_ip()){
                        return ['error'=>$MODLANG['trustoceanssl']['apierror']['noip'].$domain];
                    }
                }
                // 检查是否是完全限定域名 或 公开访问的IP地址
                if(
                    !$domain1->is_fqdn()
                    || stristr($domain, '。')
                    || stristr($domain, '，')
                    || stristr($domain, '；')
                    || stristr($domain, '*')
                    || stristr($domain, '—')
                    || stristr($domain, '_')
                    || stristr($domain, '?')
                    || stristr($domain, '/')
                    || stristr($domain, '！')
                    || stristr($domain, '=')
                    || stristr($domain, '）')
                    || stristr($domain, '（')
                    || stristr($domain, ')')
                    || stristr($domain, '(')
                )
                {
                    $errorDomains .= '*.'.$domain.'  ,  ';
                }
            }else{
                $domain0 = new \blobfolio\domain\domain($domain);
                $domain1 = new blobfolio\domain\domain($domain0->get_host());
                if($vars['configoption5'] !== 'on'){
                    if($domain1->is_ip()){
                        return ['error'=>$MODLANG['trustoceanssl']['apierror']['noip'].$domain];
                    }
                }
                // 检查是否是完全限定域名 或 公开访问的IP地址
                if(
                    !$domain1->is_fqdn()
                    || stristr($domain, '。')
                    || stristr($domain, '，')
                    || stristr($domain, '；')
                    || stristr($domain, '*')
                    || stristr($domain, '—')
                    || stristr($domain, '_')
                    || stristr($domain, '?')
                    || stristr($domain, '/')
                    || stristr($domain, '！')
                    || stristr($domain, '=')
                    || stristr($domain, '）')
                    || stristr($domain, '（')
                    || stristr($domain, ')')
                    || stristr($domain, '(')
                )
                {
                    $errorDomains .= $domain.'  ,  ';
                }
            }
        }
        if($errorDomains !== ""){
            return ['error'=> $MODLANG['trustoceanssl']['apierror']['notcorrectdomain'].$errorDomains];
        }

    }
    else
        {
        // 单域名证书，仅包括1条域名
        $domainArray = [$domains];
        $errorDomains = "";

        foreach ($domainArray as $domain){

            // 适配通配符证书
            if($vars['configoption3'] !== 'on'){
                if(substr($domain, 0,2) == "*."){
                    return ['error'=>$MODLANG['trustoceanssl']['apierror']['wildcard'].$domain];
                }
                //检查域名 域名格式
                $domainA = new \blobfolio\domain\domain($domain);
                $domainAA = new blobfolio\domain\domain($domainA->get_host());
                if($domainAA->is_ip()){
                    return ['error'=>$MODLANG['trustoceanssl']['apierror']['noip']];
                }
                if(
                        !$domainAA->is_fqdn()
                        || stristr($domain, '。')
                        || stristr($domain, '，')
                        || stristr($domain, '；')
                        || stristr($domain, '—')
                        || stristr($domain, '_')
                        || stristr($domain, '?')
                        || stristr($domain, '/')
                        || stristr($domain, '！')
                        || stristr($domain, '=')
                        || stristr($domain, '）')
                        || stristr($domain, '（')
                        || stristr($domain, ')')
                        || stristr($domain, '(')
                    ){
                    //echo "232";
                    return ['error'=>$MODLANG['trustoceanssl']['apierror']['nocsrcode']];
                }
            }else{
                // 通配符证书判断域名格式
                if(substr($domain, 0,2) !== "*."){
                    return ['error'=>$MODLANG['trustoceanssl']['apierror']['iswildcard']];
                }
                // 通配符域名检查域名格式
                $domain = substr($domain, 2);
                //检查域名 域名格式
                $domainA = new \blobfolio\domain\domain($domain);
                $domainAA = new blobfolio\domain\domain($domainA->get_host());
                if($domainAA->is_ip()){
                    return ['error'=>$MODLANG['trustoceanssl']['apierror']['noip']];
                }
                if(
                        !$domainAA->is_fqdn()
                        || stristr($domain, '。')
                        || stristr($domain, '，')
                        || stristr($domain, '；')
                        || stristr($domain, '—')
                        || stristr($domain, '_')
                        || stristr($domain, '?')
                        || stristr($domain, '/')
                        || stristr($domain, '！')
                        || stristr($domain, '=')
                        || stristr($domain, '）')
                        || stristr($domain, '（')
                        || stristr($domain, ')')
                        || stristr($domain, '(')
                    ){
                    //echo "232";
                    return ['error'=>$MODLANG['trustoceanssl']['apierror']['nocsrcode']];
                }
            }



            //echo 200;die();
            // 检查多域名证书
            if(
                $vars['configoption3'] === 'on'
                && substr($domain, 0,2) == "*."
            ){
                $domain = substr($domain, 2);
                $domain0 = new \blobfolio\domain\domain($domain);
                $domain1 = new blobfolio\domain\domain($domain0->get_host());
                if($vars['configoption4'] !== 'on'){
                    if($domain1->is_ip()){
                        return ['error'=>$MODLANG['trustoceanssl']['apierror']['noip'].$domain];
                    }
                }
                // 检查是否是完全限定域名 或 公开访问的IP地址
                if(
                    !$domain1->is_fqdn()
                    || stristr($domain, '。')
                    || stristr($domain, '，')
                    || stristr($domain, '；')
                    || stristr($domain, '*')
                    || stristr($domain, '—')
                    || stristr($domain, '_')
                    || stristr($domain, '?')
                    || stristr($domain, '/')
                    || stristr($domain, '！')
                    || stristr($domain, '=')
                    || stristr($domain, '）')
                    || stristr($domain, '（')
                    || stristr($domain, ')')
                    || stristr($domain, '(')
                )
                {
                    $errorDomains .= '*.'.$domain.'  ,  ';
                }
            }else{
                $domain0 = new \blobfolio\domain\domain($domain);
                $domain1 = new blobfolio\domain\domain($domain0->get_host());
                if($vars['configoption5'] !== 'on'){
                    if($domain1->is_ip()){
                        return ['error'=>$MODLANG['trustoceanssl']['apierror']['noip'].$domain];
                    }
                }
                // 检查是否是完全限定域名 或 公开访问的IP地址
                if(
                    !$domain1->is_fqdn()
                    || stristr($domain, '。')
                    || stristr($domain, '，')
                    || stristr($domain, '；')
                    || stristr($domain, '*')
                    || stristr($domain, '—')
                    || stristr($domain, '_')
                    || stristr($domain, '?')
                    || stristr($domain, '/')
                    || stristr($domain, '！')
                    || stristr($domain, '=')
                    || stristr($domain, '）')
                    || stristr($domain, '（')
                    || stristr($domain, ')')
                    || stristr($domain, '(')
                )
                {
                    $errorDomains .= $domain.'  ,  ';
                }
            }
        }
        if($errorDomains !== ""){
            return ['error'=>$MODLANG['trustoceanssl']['apierror']['notcorrectdomain'].$errorDomains];
        }
    }

    if(empty($domainArray)){
        return ['error'=>$MODLANG['trustoceanssl']['apierror']['nothaovedoamin']];
    }

    $domainResult = [];
    foreach ($domainArray as $domain){
        if(
            $vars['configoption3'] === 'on'
            && substr($domain, 0,2) == "*."
        ){
            $domain = substr($domain, 2);
            $domain3 = new \blobfolio\domain\domain($domain);
            if($domain3->get_host() != ""){
                $domainResult[] = '*.'.$domain3->get_host();
            }
        }else{
            $domain3 = new \blobfolio\domain\domain($domain);
            if(
                $vars['configoption3'] !=='on'
                && $vars['configoption4'] !=='on'
                && $vars['configoption5'] !=='on'
                && stristr($domain, 'www.')
            ){
                $domainResult[] = substr($domain3->get_host(), 4);
            }else{
                if($domain3->get_host() != ""){
                    $domainResult[] = $domain3->get_host();
                }
            }
        }
    }
    //echo json_encode($domainResult);die();
    return ['domains'=>$domainResult];
}

/**
 * 本地生成UniqueId
 * @return string
 */
function TRUSTOCEANSSL_genUniqueValue(){
    $tony      =   rand(00,99);
    $luucho    =   substr((string)time(),5,5);
    $jason     =   Capsule::table('tbladdonmodules')->where('module','TrustOceanSSLAdmin')->where('setting', 'apiunicodesalt')->value('value');
    return $jason.$tony.$luucho;
}

/**
 * 产生随机字符串
 * @param int $length
 * @param string $char
 * @return bool|string
 */
function TRUSTOCEANSSL_strRand($length = 32, $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'){
    if(!is_int($length) || $length < 0) {
            return false;
    }
    $string = '';
    for($i = $length; $i > 0; $i--) {
        $string .= $char[mt_rand(0, strlen($char) - 1)];
    }
    return $string;
}
/**
 * UniqueId通过数据库查重, unique_id大小写不敏感 ，因此全部采用小写
 */
function TRUSTOCEANSSL_getUniqueValue(){
    $absoluteUniqueId = '';
    for($i=1; $i<=20; $i++){
        $uniqueId = TRUSTOCEANSSL_genUniqueValue();
        $checkUnique = Capsule::table('tbltrustocean_certificate')->where('unique_id', strtolower($uniqueId))->first();
        if(empty($checkUnique)){
            $absoluteUniqueId = $uniqueId;
            break;
        }
    }
    return strtolower($absoluteUniqueId);
}

/**
 * 准备重新签发证书
 * @param $vars
 * @return array
 */
function TRUSTOCEANSSL_prepareForReissue($vars){
    $service = Capsule::table('tbltrustocean_certificate')->where('serviceid', $vars['serviceid'])->first();

    // todo:: 自定义语言文件
    global $MODLANG;

    return array(
        'templatefile' => 'templates/prepaire_reissue',
        'vars' => array(
            'status'=>$service->status,
            'ismultidomain'=>$vars['configoption4'],
            'class'=>$vars['configoption2'],
            'assetsPath'=>__DIR__.'/assets',
            'domain_count'=>$vars['configoptions']['DomainCount'],
            'domains' => json_decode($service->domains, 1),
            'csr_code' => $service->csr_code,
            'MODLANG'=>$MODLANG,
        ),
    );
}

/**
 * 客户端通过Ajax尝试重新签发
 * @param $vars
 * @throws Exception
 */
function TRUSTOCEANSSL_ajaxTryToReissueSSL($vars){
    # 请求的POST参数
    $requestParams = $_POST;

    global $MODLANG;

    $service = Capsule::table('tbltrustocean_certificate')->where('serviceid', $vars['serviceid'])->first();
    // todo:: 使用可配置选项替代数据库中的domain_count信息
    $service->domain_count = $vars['configoptions']['DomainCount'];

    // todo:: 如果使用的是原来的CSR
    if($requestParams['csroption'] === 'seamcsr'){
        $requestParams['csrcode'] = $service->csr_code;
        // todo:: 为上传的CSR解析出主域名
        $csrInfo = openssl_csr_get_subject($requestParams['csrcode'], true);
        $requestParams['domain'] = $csrInfo['CN'];
    }

    // todo:: check require information
    if($requestParams['csroption'] === "upload"){
        if(openssl_csr_get_subject($requestParams['csrcode']) === false){
            TRUSTOCEANSSL_APIRESPONSE(['status'=>'error','message'=> $MODLANG['trustoceanssl']['apierror']['nocsrcode']]);
        }else{
            // todo:: 为上传的CSR解析出主域名
            $csrInfo = openssl_csr_get_subject($requestParams['csrcode'], true);
            $requestParams['domain'] = $csrInfo['CN'];
        }
    }


    if($vars['configoption4'] === "on" && count(explode("\r\n", $requestParams['domainlist'])) <= 0){
        TRUSTOCEANSSL_APIRESPONSE(['status'=>'error','message'=>$MODLANG['trustoceanssl']['apierror']['domainincorrect']]);
    }
    // todo:: 检查域名
    if($vars['configoption4'] === 'on'){
        $domains = TRUSTOCEANSSL_checkDomains($requestParams['domainlist'], $service, $vars);
        // todo:: 检查多域名证书的第一个域名
        $firstDN = new \blobfolio\domain\domain($domains['domains'][0]);
        if($firstDN->is_ip()){
            //TRUSTOCEANSSL_APIRESPONSE(['status'=>'error','message'=>$MODLANG['trustoceanssl']['apierror']['notfirstip']]);
        }else{
            // todo:: 设置多域名证书的第一个域名用于创建CSR DN
            $requestParams['domain'] = $domains['domains'][0];
        }
    }else{
        if($requestParams['domain'] == ""){
            TRUSTOCEANSSL_APIRESPONSE(['status'=>'error','message'=>$MODLANG['trustoceanssl']['apierror']['domainincorrect']]);
        }
        $domains = TRUSTOCEANSSL_checkDomains($requestParams['domain'], $service, $vars);
    }
    if(isset($domains['error'])){
        TRUSTOCEANSSL_APIRESPONSE(['status'=>'error','message'=>$domains['error']]);
    }
    // todo:: 检查CSR信息, 不能无效，不能为IP地址
    if($requestParams['csroption'] === "upload"){
        $csrInfo = openssl_csr_get_subject($requestParams['csrcode'], true);
        $csrDN = new \blobfolio\domain\domain($csrInfo['CN']);
        if($csrDN->is_ip()){
            TRUSTOCEANSSL_APIRESPONSE(['status'=>'error','message'=>$MODLANG['trustoceanssl']['apierror']['ipincsr']]);
        }
    }else{
        $csrDN = new \blobfolio\domain\domain($requestParams['domain']);
        if($csrDN->is_ip()){
            TRUSTOCEANSSL_APIRESPONSE(['status'=>'error','message'=>$MODLANG['trustoceanssl']['apierror']['ipincsrcommon']]);
        }
    }

    // todo:: 检查所有者邮箱,  如果没有提供就是用默认的账户邮箱
    if($requestParams['email'] == ""){
       $requestParams['email'] = $vars['clientsdetails']['email'];
    }

    // todo:: 所有的检查已经完成, 如果需要, 现在创建CSR代码
    if($requestParams['csroption'] === "generate"){
        $keypairs =  TRUSTOCEANSSL_genkeypaire($requestParams, $service);
        $csr_code = $keypairs['csr'];
        $key_code = $keypairs['key'];
    }elseif($requestParams['csroption'] === "seamcsr"){
        $csr_code = $requestParams['csrcode'];
        $key_code  = $service->key_code;
    }else{
        $csr_code = $requestParams['csrcode'];
        $key_code  = NULL;
    }


    // todo:: tryToReissue on TRUSTOCEAN SSL
    $caprams = array();
    $caprams['action'] = "reissueSSLOrder";
    $caprams['csr_code'] = $csr_code;
    $caprams['unique_id'] = TRUSTOCEANSSL_genUniqueValue();
    $caprams['trustocean_id'] = $service->trustocean_id;

    # todo:: 多域名 域名列表
    if($vars['configoption4'] === "on"){
        $domainString = "";
        foreach ($domains['domains'] as $domain){
            $domainString  = $domainString.$domain.",";
        }
        $domainString = substr($domainString, 0, strlen($domainString)-1);
        $caprams['domains'] = $domainString;
    }

    // 更新主域名至数据库字段 tblhosting.domain
    Capsule::table('tblhosting')->where('userid', $_SESSION['uid'])
        ->where('id', $vars['serviceid'])
        ->update([
            'domain' => $domains['domains'][0]
        ]);

    # todo:: DCV信息列表
    $dcvString = "";
    foreach (TRUSTOCEANSSL_findDcvDomains($domains['domains']) as $domain => $info){
        if($info['method'] === "email"){
            $dcvString = $dcvString.$info['email'].",";
        }else{
            $dcvString = $dcvString.$info['method'].",";
        }
    }
    $dcvString = substr($dcvString, 0, strlen($dcvString)-1);
    $caprams['dcv_method'] = $dcvString;

    $result = TRUSTOCEANSSL_CALLAPI($caprams);
    if($result['status'] === "success"){
        Capsule::table('tbltrustocean_certificate')->where('serviceid', $service->serviceid)->update(array(
            'csr_code'=>$csr_code,
            'key_code'=>$key_code,
            'unique_id'=>$result['unique_id'],
            'vendor_id'=>$result['vendor_id'],
            'status'=>$result['cert_status'],
            'domains'=>json_encode($domains['domains']),
            'dcv_info'=>json_encode(TRUSTOCEANSSL_findDcvDomains($domains['domains'])),
            'certificate_id'=>$result['certificate_id']
        ));
        TRUSTOCEANSSL_APIRESPONSE(['status'=>'success']);
    }else{
        TRUSTOCEANSSL_APIRESPONSE(array_merge($result,['code'=>299,'params'=>$caprams]));
    }

}

/**
 * 尝试提交到 TRUSTOCEAN SSL CA
 * @param $vars
 * @throws Exception
 */
function TRUSTOCEANSSL_ajaxTrySubmittoca($vars){
    global $MODLANG;

    $service = Capsule::table('tbltrustocean_certificate')->where('serviceid',$vars['serviceid'])->first();
    # todo:: 检查证书的状态是否可以提交到CA
    if($service->status !== "enroll_dcv"){
        TRUSTOCEANSSL_APIRESPONSE(['status'=>'error','message'=>$MODLANG['trustoceanssl']['apierror']['cannotsubmitca']]);
    }
    // todo:: 尝试提交到 TRUSTOCEAN CA

    //todo:: ca params
    $caprams = array(
        'action'    =>'addSSLOrder',
        'pid'       =>$vars['configoption1'],
    );

    # todo:: 多域名 域名列表
    if($vars['configoption4'] === "on"){
        $domainString = "";
        foreach (json_decode($service->domains, 1) as $domain){
            $domainString  = $domainString.$domain.",";
        }
        $domainString = substr($domainString, 0, strlen($domainString)-1);
        $caprams['domains'] = $domainString;
    }

    # todo:: DCV信息列表
    $dcvString = "";
    foreach (json_decode($service->dcv_info, 1) as $domain => $info){
        if($info['method'] === "email"){
            $dcvString = $dcvString.$info['email'].",";
        }else{
            $dcvString = $dcvString.$info['method'].",";
        }
    }
    $dcvString = substr($dcvString, 0, strlen($dcvString)-1);
    $caprams['dcv_method'] = $dcvString;

    // todo:: CSR信息
    $caprams['csr_code'] = $service->csr_code;
    // todo:: contact_email
    $caprams['contact_email'] = $service->contact_email;
    // todo:: period
    $caprams['period'] = $service->period;
    // todo:; unique_id
    $caprams['unique_id'] = $service->unique_id;

    // todo:: 企业联系信息
    if($vars['configoption2'] !== "dv"){
        $org_info = json_decode($service->org_info, 1);
        foreach ($org_info as $key => $value){
            $caprams[$key] = $value;
        }
    }

    $result = TRUSTOCEANSSL_CALLAPI($caprams);
    //TRUSTOCEANSSL_APIRESPONSE($result);
    // todo:: 检查CA错误
    if($result['status'] === "error"){
        TRUSTOCEANSSL_APIRESPONSE($result);
    }
    // todo:: 提交成功, 更新本地数据库
    Capsule::table('tbltrustocean_certificate')->where('id', $service->id)->update(array(
        'status'=>$result['cert_status'],
        'vendor_id'=>$result['vendor_id'],
        'unique_id'=>$result['unique_id'],
        'paidcertificate_delivery_time'=>$result['certificate_delivery_time'],
        'reissue'=>$result['reissue'],
        'renew'=>$result['renew'],
        'trustocean_id'=>$result['trustocean_id'],
        'certificate_id'=>$result['certificate_id']
    ));
    TRUSTOCEANSSL_APIRESPONSE(['status'=>'success']);
}

/**
 * 尝试提交到 TRUSTOCEAN SSL CA
 * @param $vars
 * @throws Exception
 */
function TRUSTOCEANSSL_trySubmittoca($vars){
    global $MODLANG;

    $service = Capsule::table('tbltrustocean_certificate')->where('serviceid',$vars['serviceid'])->first();
    # todo:: 检查证书的状态是否可以提交到CA
    if($service->status !== "enroll_dcv"){
        return $MODLANG['trustoceanssl']['apierror']['cannotsubmitca'];
    }
    // todo:: 尝试提交到 TRUSTOCEAN CA

    //todo:: ca params
    $caprams = array(
        'action'    =>'addSSLOrder',
        'pid'       =>$vars['configoption1'],
    );

    // DEBUG FOR NEW WHMCS VARS
//    file_put_contents(__DIR__.'/crazyssl_debuy_logs.txt', json_encode($vars));

    $doaminArray = json_decode($service->domains, 1);
    $domain = str_replace("*",'_issuewild', $doaminArray[0]);
    $dcvString = $_POST['domaindcvmathod'][$domain];

    # todo:: 多域名 域名列表
    if($vars['configoption4'] === "on"){
        $domainString = "";
        foreach ($doaminArray as $domain){
            $domainString  = $domainString.$domain.",";
        }
        $domainString = substr($domainString, 0, strlen($domainString)-1);
        $caprams['domains'] = $domainString;

        # todo:: DCV信息列表
        $dcvString = "";
        foreach ($doaminArray as $domain){
            // 修复 form 表单数组使用*无法识别问题
            $domain = str_replace("*",'_issuewild', $domain);

            $dcvString = $dcvString.$_POST['domaindcvmathod'][$domain].",";
        }
        $dcvString = substr($dcvString, 0, strlen($dcvString)-1);
    }


    // 其他非主域名还需要使用相同的method进行填充后并核对顺序才可以使用

    $caprams['dcv_method'] = $dcvString;

    // todo:: CSR信息
    $caprams['csr_code'] = $service->csr_code;
    // todo:: contact_email
    $caprams['contact_email'] = $service->contact_email;
    // todo:: period
    $caprams['period'] = $service->period;
    // todo:; unique_id
    $caprams['unique_id'] = $service->unique_id;

    // todo:: 企业联系信息
    if($vars['configoption2'] !== "dv"){
        $org_info = json_decode($service->org_info, 1);
        foreach ($org_info as $key => $value){
            $caprams[$key] = $value;
        }
    }

//    file_put_contents(__DIR__.'/crazyssl_debuy_logs.txt', json_encode($caprams), FILE_APPEND);

    $result = TRUSTOCEANSSL_CALLAPI($caprams);

    // todo:: 检查CA错误
    if($result['status'] === "error"){
        return $result['message'];
    }
    // todo:: 提交成功, 更新本地数据库
    Capsule::table('tbltrustocean_certificate')->where('id', $service->id)->update(array(
        'status'=>$result['cert_status'],
        'vendor_id'=>$result['vendor_id'],
        'unique_id'=>$result['unique_id'],
        'paidcertificate_delivery_time'=>$result['certificate_delivery_time'],
        'reissue'=>$result['reissue'],
        'renew'=>$result['renew'],
        'trustocean_id'=>$result['trustocean_id'],
        'certificate_id'=>$result['certificate_id']
    ));

    // 提交成功 返回至详情页面
    return "success";
}

/**
 * 计算CSR的Hash值
 * @param $csrCode
 * @return array
 */
function TRUSTOCEANSSL_getCsrHash($csrCode){
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
 * 生成DCV用途的邮箱地址
 * @param $domain
 * @return array
 */
function TRUSTOCEANSSL_generateDcvEmails($domain){
    // 创建域名对象
    $domain = new blobfolio\domain\domain(str_replace('*.', '', $domain));
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
/**
 * 修改DCV方式
 * @param $service
 * @param $domain
 * @param $newdcv
 */
function TRUSTOCEANSSL_changedcv($service, $domain, $newdcv){
    global $MODLANG;

    //检查是否为邮件验证
    if($newdcv !== "dns" && $newdcv !== "http" && $newdcv !== "https"){
        // 这里应该要先检查DCV信息中提供的EMAIL地址是否符合当前域名的要求
        if(!in_array($newdcv, TRUSTOCEANSSL_generateDcvEmails($domain))){
            echo json_encode(['status' => 'error','message'=>'email address not accept!']);
            die();
        }else{
            $dcvEmail = $newdcv;
            $newdcv = 'email';
        }
    }

    //获取当前的DCV信息
    $dcvinfo = json_decode($service->dcv_info, 1);
    $domains = json_decode($service->domains, 1);
    if(!in_array($domain, $domains)){
        header('content-type: application/json');
        echo json_encode(['status' => 'error','message'=>'domain not found!']);
        die();
    }
    // 域名已通过验证 无法修改验证方式

    // 常用验证方式
    if($newdcv === 'dns' || $newdcv === 'http' || $newdcv === 'https'){

        if($dcvinfo[$domain]['isip'] === true && $newdcv === 'dns'){
            TRUSTOCEANSSL_APIRESPONSE(['status'=>'error','message'=>$MODLANG['trustoceanssl']['apierror']['ipdcverr']]);
        }else{
            $dcvinfo[$domain]['method'] = $newdcv;
            $dcvinfo[$domain]['status'] = 'needverification';
            $dcvinfo[$domain]['email'] = "";
        }
    }
    // 邮件验证方式
    if($newdcv === 'email'){
        if($dcvinfo[$domain]['isip'] === true){
            TRUSTOCEANSSL_APIRESPONSE(['status'=>'error','message'=>$MODLANG['trustoceanssl']['apierror']['ipdcverr']]);
        }else{
            $dcvinfo[$domain]['method'] = 'email';
            $dcvinfo[$domain]['email'] = $dcvEmail;
            $dcvinfo[$domain]['status'] = 'needverification';
        }
    }

    Capsule::table('tbltrustocean_certificate')->where('serviceid',$service->serviceid)
        ->update(array(
            'dcv_info' => json_encode($dcvinfo)
        ));

    header('content-type: application/json');
    echo json_encode(['status' => 'success']);
    die();
}

/**
 * 找出需要DCV验证的域名
 * @param $domains
 * @param $vars
 * @return array
 */
function TRUSTOCEANSSL_findDcvDomains($domains){
    $topLevelDomains = [];

    // search repeat domains
    $dcvDomains = array_values($domains);
    $dcvInfo = [];
    foreach ($dcvDomains as $domain){

        $domain1 = new \blobfolio\domain\domain($domain);
        if(substr($domain, 0,2) == "*."){
            $domain2 = new \blobfolio\domain\domain(substr($domain, 2));
            $topdomain = $domain2->get_domain().'.'.$domain2->get_suffix();
            $subdomain = $domain2->get_subdomain();
        }elseif($domain1->is_ip()){
            $subdomain = "";
            $topdomain = $domain;
        }else{
            $subdomain = $domain1->get_subdomain();
            $topdomain = $domain1->get_domain().'.'.$domain1->get_suffix();
        }
        if($domain1->is_ip()){
            $dcvInfo[$domain] =[
                'status' => 'needverification',
                'method' => 'http',
                'email'   => '',
                'isip'    => 'true',
                'subdomain' =>  $subdomain,
                'topdomain' =>  $topdomain,
            ];
        }else{
            $dcvInfo[$domain] =[
                'status' => 'needverification',
                'method' => 'dns',
                'email'   => '',
                'isip'  =>  'false',
                'subdomain' =>  $subdomain,
                'topdomain' =>  $topdomain,
            ];
        }
    }
    return $dcvInfo;
}

/**
 * 转换证书+KEY
 * @param $param
 * @return string
 */
function TRUSTOCEANSSL_convertssl($param){
    $cert = Capsule::table('tbltrustocean_certificate')->where('serviceid',$param['serviceid'])->first();

    $domains = json_decode($cert->domains, 1);
    // 开始准备下载ZIP文件
    $filename = str_replace('.','-',$domains[0]);
    $certfilename = str_replace('*','START',$filename); # 格式化之后域名作为文件名
    $filename = str_replace('*','START',$certfilename).'-'.sha1(time()); # 确定文件夹和文件名称
    $filepath = "/tmp/cert/customer_certs/";
    mkdir($filepath.$filename,0777,TRUE);
    mkdir($filepath.$filename.'/Apache',0777,TRUE);
    mkdir($filepath.$filename.'/Nginx',0777,TRUE);
    mkdir($filepath.$filename.'/CDN',0777,TRUE);
    mkdir($filepath.$filename.'/IIS',0777,TRUE);

    $IISFile2 = fopen($filepath.$filename.'/IIS/'."CAChains.crt", "w+");
    fwrite($IISFile2, trim($cert->ca_code));
    fclose($IISFile2);

    // 新建Apache 证书文件
    $apacheFile1 = fopen($filepath.$filename.'/Apache/'.$certfilename.".crt", "w+");
    fwrite($apacheFile1, trim($cert->cert_code));
    fclose($apacheFile1);
    $apacheFile2 = fopen($filepath.$filename.'/Apache/'."CAChains.crt", "w+");
    fwrite($apacheFile2, trim($cert->ca_code));
    fclose($apacheFile2);
    // 新建CDN 证书文件
    $cdnFile1 = fopen($filepath.$filename.'/CDN/[1]'.$certfilename.".crt", "w+");
    fwrite($cdnFile1, trim($cert->cert_code));
    fclose($cdnFile1);
    $cdnFile2 = fopen($filepath.$filename.'/CDN/[2]'."CAChains.crt", "w+");
    fwrite($cdnFile2, trim($cert->ca_code));
    fclose($cdnFile2);
    // 新建Nginx 证书文件 合并证书链
    $nginxFile1 = fopen($filepath.$filename.'/Nginx/'.$certfilename.".pem", "w+");
    fwrite($nginxFile1, trim($cert->cert_code).PHP_EOL);
    fwrite($nginxFile1, trim($cert->ca_code));
    fclose($nginxFile1);

    // todo::解密 密码保护的私钥
    if($_POST['keycode'] != ''){
        $encrypt_key = $_POST['keycode']; #获取KEY资源流
        $decrypt_key = "";
        $decrypt_key = openssl_get_privatekey(trim($encrypt_key), trim($_POST['ktoken']));

        $dkey = "";

        // decrypt private key
        openssl_pkey_export($decrypt_key,$dkey); # 输出KEY资源到字符串
        // 新建Apache KEY文件
        $apacheKEY = fopen($filepath.$filename.'/Apache/'.$certfilename.".key", "w+");
        fwrite($apacheKEY, trim($dkey));
        fclose($apacheKEY);
        // 新建Nginx KEY文件
        $nginxKEY = fopen($filepath.$filename.'/Nginx/'.$certfilename.".key", "w+");
        fwrite($nginxKEY, trim($dkey));
        fclose($nginxKEY);
        // 新建Nginx KEY文件
        $cdnKEY = fopen($filepath.$filename.'/CDN/'.$certfilename.".key", "w+");
        fwrite($cdnKEY, trim($dkey));
        fclose($cdnKEY);

        // 生成IIS .pfx 证书文件
        mkdir($filepath.$filename.'/IIS',0777,TRUE);
        $pfx_content = "";
        $re = openssl_pkcs12_export(
            file_get_contents($filepath.$filename.'/Apache/'.$certfilename.".crt"),
            $pfx_content,
            trim($_POST['keycode']),
            $_POST['ktoken'],
            array(
                'extracerts'=>file_get_contents($filepath.$filename.'/Apache/'."CAChains.crt")
            )
        );
        if(!$re){
            return '私钥不正确，或私钥保护密码不正确, 请您检查后重试！';
        }
        $pfx = fopen($filepath.$filename.'/IIS/'.$certfilename.".pfx", "w+");
        fwrite($pfx, $pfx_content);
        fclose($pfx);
    }

    $zip = new \ZipArchive;
    if ($zip->open($filepath.$filename.'.zip',\ZIPARCHIVE::CREATE) === TRUE) {
        $zip->addEmptyDir('Apache');
        $zip->addEmptyDir('Nginx');

        $zip->addFile($filepath.$filename.'/Apache/'.$certfilename.".crt", 'Apache/'.$certfilename.".crt");
        $zip->addFile($filepath.$filename.'/Apache/'.$certfilename.".key", 'Apache/'.$certfilename.".key");
        $zip->addFile($filepath.$filename.'/Apache/'."CAChains.crt",'Apache/'."CAChains.crt");

        $zip->addFile($filepath.$filename.'/Nginx/'.$certfilename.".pem", 'Nginx/'.$certfilename.".pem");
        $zip->addFile($filepath.$filename.'/Nginx/'.$certfilename.".key", 'Nginx/'.$certfilename.".key");
        $zip->addFile($filepath.$filename.'/IIS/'.$certfilename.".pfx", 'IIS/'.$certfilename.".pfx");
        $zip->addFile($filepath.$filename.'/IIS/CAChains.crt', 'IIS/CAChains.crt');
        $zip->close();
        header('Content-type: application/force-download');
        header('Content-Disposition: attachment; filename="'.$certfilename.'.zip"');
        // @readfile($filepath.$filename.'.zip');
        if(TRUSTOCEANSSL_readFileForDownload($filepath.$filename.'.zip') === true){
            fastcgi_finish_request();
            TRUSTOCEANSSL_massDeletePathAndFiles($filepath.$filename);
        }
        unlink($filepath.$filename.'.zip'); #删除临时压缩包文件
    } else {
        return 'Failed To Download Certificate,Please Contact Us For Help!';
    }
}

/**
 * 创建CSRKEY 密钥对
 * @param $params
 * @return mixed
 */
function TRUSTOCEANSSL_genkeypaire($params, $service){

        $service = Capsule::table('tbltrustocean_certificate')->where('serviceid', $service->id)->first();
        if($service->multidomain === 1){
            $dn = array(
                "countryName" => 'CN', //所在国家名称
                "stateOrProvinceName" => "Shaanxi", //所在省份名称
                "localityName" => "Xian", //所在城市名称
                "organizationName" => "COMPANY NAME OMIT,NOT HERE",   //注册人姓名
                "organizationalUnitName" => "CyberSecure Research Department", //组织名称
                "commonName" => $params['domain'], //公共名称
                "emailAddress" => $params['email'] //邮箱
            );
        }else{
            $dn = array(
                "countryName" => 'CN', //所在国家名称
                "stateOrProvinceName" => "Shaanxi", //所在省份名称
                "localityName" => "Xian", //所在城市名称
                "organizationName" => "COMPANY NAME OMIT,NOT HERE",   //注册人姓名
                "organizationalUnitName" => "CyberSecure Research Department", //组织名称
                "commonName" => $params['domain'], //公共名称
                "emailAddress" => $params['email'] //邮箱
            );
        }

        $privkey = openssl_pkey_new(array(
            "config"=> __DIR__."/openssl.cnf",
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA
        ));

        $csr = openssl_csr_new($dn, $privkey, array("config"=>__DIR__."/openssl.cnf"));

        $csrtext = '';
        $pkeytext = '';

        openssl_csr_export($csr, $csrtext);
        openssl_pkey_export($privkey, $pkeytext, NULL, array("config"=>__DIR__."/openssl.cnf"));

        $resp['csr'] = $csrtext;
        $resp['key'] = $pkeytext;

        return $resp;
}

/**
 * 获取用户的产品信息
 * @param $serviceid
 * @return mixed
 */
function TRUSTOCEANSSL_getClientProduct($serviceid){
    // Define parameters
    $command = 'GetClientsProducts';
    $values = array(
        'serviceid' => $serviceid,
    );

    // Call the localAPI function
    $results = localAPI($command, $values);
    return $results['products']['product'][0];
}

/**
 * 响应WHMCS客户区的json响应
 * @param $params
 */
function TRUSTOCEANSSL_clientApiResponse($params){
    header('Content-Type:application/json; charset=utf-8');
    exit(json_encode($params));die();
}

/**
 * WHMCS区域在提交证书到CA后进行DCV验证修改
 * @param $vars
 * @return string
 */
function TRUSTOCEANSSL_clientAreaChangeDCVMethod($vars){
    //检查是否为邮件验证
    $newdcv = $_POST['method'];
    $domain = $_POST['domain'];
    $service = Capsule::table('tbltrustocean_certificate')->where('serviceid', $vars['serviceid'])->first();

    $result = TRUSTOCEANSSL_CALLAPI(array(
        'action'=>'changeDCVMethod',
        'trustocean_id'=>$service->trustocean_id,
        'domain'=>$domain,
        'method'=>$newdcv,
    ));

    TRUSTOCEANSSL_clientApiResponse($result);
}

/**
 * 从CA处删除没有通过验证的域名 WHMCS客户控制台方法
 * @param $vars
 * @throws Exception
 */
function TRUSTOCEANSSL_clientAreaRemoveDomain($vars){
    header('Content-Type:application/json; charset=utf-8');
    global $MODLANG;

    $domainname = $_POST['domain'];
    $service = Capsule::table('tbltrustocean_certificate')->where('serviceid', $vars['serviceid'])->first();
    // 检查是否为多域名证书
    if($service->multidomain !== 1){
        TRUSTOCEANSSL_clientApiResponse(['status'=>'error','message'=>$MODLANG['trustoceanssl']['apierror']['cannotremovesan']]);
    }
    // 至少保留1个域名
    if(count(json_decode($service->domains, 1))<2){
        TRUSTOCEANSSL_clientApiResponse(['status'=>'error','message'=>$MODLANG['trustoceanssl']['apierror']['cannotremovesanone']."$domainname"]);
    }
    // 检查域名是否已经通过验证
    $dcvinfo = json_encode($service->dcv_info, 1);
    if($dcvinfo[$domainname]['status'] === 'verified'){
        TRUSTOCEANSSL_clientApiResponse(['status'=>'error','message'=>$MODLANG['trustoceanssl']['apierror']['cannotremovesanverified']."$domainname"]);
    }

    $params = array(
        'orderNumber'=>$service->vendor_id,
        'domainName'=> $_POST['domain'],
    );

    // 执行CA删除域名
    $remove = TRUSTOCEANSSL_CALLAPI(array(
        'action'=>'removeSanDomain',
        'trustocean_id'=>$service->trustocean_id,
        'domain'=>$domainname,
    ));
    if($remove['status'] === "success"){
        //执行本地删除域名
        $newDomains = [];
        $dcvinfo = json_decode($service->dcv_info, 1);
        foreach ($dcvinfo as $domain => $info){
            if($domain === $_POST['domain']){
                unset($dcvinfo[$domain]);
            }else{
                array_push($newDomains, $domain);
            }
        }
        Capsule::table('tbltrustocean_certificate')->where('serviceid', $service->serviceid)
        ->update(array(
            'dcv_info'=>json_encode($dcvinfo),
            'domains' => json_encode($newDomains),
        ));
        TRUSTOCEANSSL_clientApiResponse(['status'=>'success']);
    }else{
         TRUSTOCEANSSL_clientApiResponse(['status'=>'error','message'=>$remove['message']]);
    }
}

/**
 * 用户WHMCS Ajax删除SAN域名
 * @param $vars
 */
function TRUSTOCEANSSL_syncRemoveSANDomain($vars){
    $service = Capsule::table('tbltrustocean_certificate')->where('serviceid', $vars['serviceid'])->first();

    $_POST['domain'] = trim($_POST['domain']);

    #检查空格
    if(trim($_POST['domain']) == ""){
        TRUSTOCEANSSL_clientApiResponse(['status'=>'danger','message'=>'删除域名失败, 您输入的域名不合法']);
    }
    #检查状态
    if($service->status !== "issued_active"){
        TRUSTOCEANSSL_clientApiResponse(['status'=>'danger','message'=>'删除域名失败, 只有已经签发完成的证书才可以删除。']);
    }
    #检查是否为最后一个域名
    if(count(json_decode($service->domains, 1))-1 <= 0){
        TRUSTOCEANSSL_clientApiResponse(['status'=>'error','message'=>"添加删除失败, 证书至少需要保留1条域名"]);
    }
    #检查是否为多域名证书
    if($vars['configoption4'] !== "on"){
            TRUSTOCEANSSL_clientApiResponse(['status'=>'error','message'=>'删除域名失败, 只有多域名证书才可以删除域名。']);
    }
    $domains = json_decode($service->domains, 1);
    $dcvInfo = json_decode($service->dcv_info, 1);

    # 检查是否存在域名
    if(!in_array($_POST['domain'],$domains)){
            TRUSTOCEANSSL_clientApiResponse(['status'=>'error','message'=>'删除域名失败, 证书中不存在此域名']);
    }
    foreach ($dcvInfo as $domain=>$info){
        if($domain == $_POST['domain']){
            unset($dcvInfo[$domain]);
        }
    }
    foreach ($domains as $key=>$domain){
        if($domain === $_POST['domain']){
            unset($domains[$key]);
        }
    }
    Capsule::table('tbltrustocean_certificate')->where('serviceid',$vars['serviceid'])
        ->update(array(
            'dcv_info'  =>  json_encode($dcvInfo),
            'domains'   =>  json_encode(array_values($domains)),
        ));
    TRUSTOCEANSSL_clientApiResponse(['status'=>'success','md5hash'=>md5($_POST['domain']),'domain'=>$_POST['domain']]);

}

/**
 * 客户区域输出
 * @param $vars
 * @return array
 */
function TRUSTOCEANSSL_ClientArea($vars) {
    $adminController = new \WHMCS\Module\Server\TRUSTOCEANSSL\Controller\AdminController($vars['serviceid']);
    return $adminController->clientArea($vars);
}


/**
 * 通过Ajax统一添加证书申请信息
 * 对于EV OV证书, 还需要同时添加企业认证信息
 * @param $vars
 */
function TRUSTOCEANSSL_ajaxUploadCertInfo($vars){
    #请求的POST参数
    $requestParams = $_POST;

    global $MODLANG;

    $service = Capsule::table('tbltrustocean_certificate')->where('serviceid', $vars['serviceid'])->first();
    // todo:: 使用可配置选项替代数据库中的domain_count信息
    $service->domain_count = $vars['configoptions']['DomainCount'];

    // todo:: check require information
    if($requestParams['csroption'] === "upload"){
        if(openssl_csr_get_subject($requestParams['csrcode']) === false){
            TRUSTOCEANSSL_APIRESPONSE(['status'=>'error','message'=>$MODLANG['trustoceanssl']['apierror']['incorrectcsrcode']]);
        }else{
            // todo:: 为上传的CSR解析出主域名
            $csrInfo = openssl_csr_get_subject($requestParams['csrcode']);
            $requestParams['domain'] = $csrInfo['CN'];
        }

    }
    if($vars['configoption4'] === "on" && count(explode("\r\n", $requestParams['domainlist'])) <= 0){
        TRUSTOCEANSSL_APIRESPONSE(['status'=>'error','message'=>$MODLANG['trustoceanssl']['apierror']['domainincorrect']]);
    }
    // todo:: 检查域名
    if($vars['configoption4'] === 'on'){
        $domains = TRUSTOCEANSSL_checkDomains($requestParams['domainlist'], $service, $vars);
        // todo:: 检查多域名证书的第一个域名
        $firstDN = new \blobfolio\domain\domain($domains['domains'][0]);
        if($firstDN->is_ip()){
            //TRUSTOCEANSSL_APIRESPONSE(['status'=>'error','message'=>$MODLANG['trustoceanssl']['apierror']['notfirstip']]);
        }else{
            // todo:: 设置多域名证书的第一个域名用于创建CSR DN
            $requestParams['domain'] = $domains['domains'][0];
        }
    }else{
        if($requestParams['domain'] == ""){
            TRUSTOCEANSSL_APIRESPONSE(['status'=>'error','message'=>$MODLANG['trustoceanssl']['apierror']['domainincorrect']]);
        }
        $domains = TRUSTOCEANSSL_checkDomains($requestParams['domain'], $service, $vars);
    }
    if(isset($domains['error'])){
        TRUSTOCEANSSL_APIRESPONSE(['status'=>'error','message'=>$domains['error']]);
    }
    // todo:: 检查CSR信息, 不能无效，不能为IP地址
    if($requestParams['csroption'] === "upload"){
        $csrInfo = openssl_csr_get_subject($requestParams['csrcode'], true);
        $csrDN = new \blobfolio\domain\domain($csrInfo['CN']);
        if($csrDN->is_ip()){
            TRUSTOCEANSSL_APIRESPONSE(['status'=>'error','message'=>$MODLANG['trustoceanssl']['apierror']['ipincsr']]);
        }
    }else{
        $csrDN = new \blobfolio\domain\domain($requestParams['domain']);
        if($csrDN->is_ip()){
            TRUSTOCEANSSL_APIRESPONSE(['status'=>'error','message'=>$MODLANG['trustoceanssl']['apierror']['ipincsrcommon']]);
        }
    }

    //todo:: 检查企业信息, 不能为空, 全部都需要提交
    if($vars['configoption2'] !== "dv"){
        $requestParams['contact_email'] = $requestParams['email'];
        TRUSTOCEANSSL_checkOrgParams(array(
            'organization_name',
            'organizationalUnitName',
            'registered_address_line1',
            'registerted_no',
            'country',
            'state',
            'city',
            'postal_code',
            'organization_phone',
            'date_of_incorporation',
            'contact_name',
            'contact_title',
            'contact_phone',
            'contact_email'
        ), $requestParams);
    }

    // todo:: 检查所有者邮箱,  如果没有提供就是用默认的账户邮箱
    if($requestParams['email'] == ""){
       $requestParams['email'] = $vars['clientsdetails']['email'];
    }

    // todo:: 所有的检查已经完成, 如果需要, 现在创建CSR代码
    if($requestParams['csroption'] === "generate"){
        $keypairs =  TRUSTOCEANSSL_genkeypaire($requestParams, $service);
        $csr_code = $keypairs['csr'];
        $key_code = $keypairs['key'];
    }else{
        $csr_code = $requestParams['csrcode'];
        $key_code  = NULL;
    }

    // 更新主域名至数据库字段 tblhosting.domain
    Capsule::table('tblhosting')->where('userid', $_SESSION['uid'])
        ->where('id', $vars['serviceid'])
        ->update([
            'domain' => $domains['domains'][0]
        ]);

    // 更新数据库信息
    $updateParams = array(
            'csr_code'=>$csr_code,
            'key_code'=>$key_code,
            'contact_email'=>$requestParams['email'],
            'status'=>'enroll_dcv',
            'domains'=>json_encode($domains['domains']),
            'unique_id'=>TRUSTOCEANSSL_genUniqueValue(),
            'dcv_info'=>json_encode(TRUSTOCEANSSL_findDcvDomains($domains['domains'])),
        );
    //todo:: 企业订单应该存储企业信息
    if($vars['configoption2'] !== "dv"){
        $updateParams['org_info'] = json_encode(array(
            'organization_name' => $requestParams['organization_name'],
            'organizationalUnitName' => $requestParams['organizationalUnitName'],
            'registered_address_line1' => $requestParams['registered_address_line1'],
            'registerted_no' => $requestParams['registerted_no'],
            'country' => $requestParams['country'],
            'state' => $requestParams['state'],
            'city' => $requestParams['city'],
            'postal_code' => $requestParams['postal_code'],
            'organization_phone' => $requestParams['organization_phone'],
            'date_of_incorporation' => $requestParams['date_of_incorporation'],
            'contact_name' => $requestParams['contact_name'],
            'contact_title' => $requestParams['contact_title'],
            'contact_phone' => $requestParams['contact_phone'],
            'contact_email' => $requestParams['contact_email']
        ));
    }

    Capsule::table('tbltrustocean_certificate')->where('serviceid',$vars['serviceid'])->update($updateParams);

    TRUSTOCEANSSL_APIRESPONSE(['status'=>'success']);

}


/**
 * 管理员发送签发通知
 * @param $vars
 * @return string
 */
function TRUSTOCEANSSL_adminSendIssuedNotification($vars){
    $service = Capsule::table('tbltrustocean_certificate')->where('serviceid', $vars['serviceid'])->first();

    if($service->status !== "issued_active"){
        return "证书还为签发, 无法发送签发通知!";
    }
    TRUSTOCEANSSL_sendEmailNotificationForCertIssuance($service, $service->cert_code, $service->ca_code);
    return "success";
}

/**
 * 为用户发送证书签发的邮件通知
 *
 */
function TRUSTOCEANSSL_sendEmailNotificationForCertIssuance($service, $cert_code, $ca_code){

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

/**
 * TRUSTOCEAN API响应 application/json 格式
 * @param $params
 */
function TRUSTOCEANSSL_APIRESPONSE($params){
    if(isset($_POST['responsetype']) && $_POST['responsetype'] === 'json'){
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode($params);
        die();
    }else{
        return $params['message'];
    }
}

/**
 * 重新检查DCV信息或重新发送DCV验证邮件
 * @param $vars
 * @throws Exception
 */
function TRUSTOCEANSSL_clientarearesenddcvemail($vars){

    global $MODLANG;

    $service = Capsule::table('tbltrustocean_certificate')->where('serviceid',$vars['serviceid'])->first();

    //fetchcert检查
    if(time() < (strtotime($service->dcvredo_clicked)+60*3)){
        $waitingTime =  round($waitingTime = ((strtotime($service->	dcvredo_clicked)+60*3) - time())/60, 2);
        TRUSTOCEANSSL_clientApiResponse(['status'=>'error',"message"=>$MODLANG['trustoceanssl']['apierror']['dcvwait5']]);
    }
    // 更新fetchcert执行时间
    Capsule::table('tbltrustocean_certificate')->where('serviceid',$vars['serviceid'])->update(array(
        "dcvredo_clicked"=>date('Y-m-d H:i:s'),
    ));

    $caprams = array(
        'action'    =>'reTryDcvEmailOrDCVCheck',
        'trustocean_id'       =>$service->trustocean_id,
    );

    $result = TRUSTOCEANSSL_CALLAPI($caprams);

    if($result['status'] === "error"){
        TRUSTOCEANSSL_APIRESPONSE($result);
    }else{
        TRUSTOCEANSSL_APIRESPONSE(['status'=>'success']);
    }

}

/**
 * 取消用户的订单
 * @param $vars
 * @return string
 */
function TRUSTOCEANSSL_TerminateAccount($vars){
    global $MODLANG;

    $service = Capsule::table('tbltrustocean_certificate')->where('serviceid', $vars['serviceid'])->first();

    Capsule::table('tbltrustocean_certificate')->where('id', $service->id)->update(array(
            'status'=>'cancelled',
        ));
    return "success";
}

/**
 * 用户功能按钮
 */
function TRUSTOCEANSSL_ClientAreaAllowedFunctions(){
    return array(
        "下载SSL证书" => 'downloadcertificate',
        "转换证书"  =>  'convertssl',
        '重新发送DCV邮件'=>'resendDCVEmails',
        'clientAreaChangeDCVMethod'=>'clientAreaChangeDCVMethod',
        'clientarearesenddcvemail'=>'clientarearesenddcvemail',
        'clientAreaRemoveDomain'=>'clientAreaRemoveDomain',
        'clientarteaSyncOrderStatus'=>'clientarteaSyncOrderStatus',
        'clientSynccertorderdata'=>'clientSynccertorderdata',
        'syncRemoveSANDomain'=>'syncRemoveSANDomain',
        'ajaxUploadCertInfo'=>'ajaxUploadCertInfo',
        'ajaxTrySubmittoca'=>'ajaxTrySubmittoca',
        'trySubmittoca' => 'trySubmittoca',
        'prepareForReissue'=>'prepareForReissue',
        'ajaxTryToReissueSSL','ajaxTryToReissueSSL',
    );
}

/**
 * 管理员产品详情页 功能
 * @return array
 */
function TRUSTOCEANSSL_AdminCustomButtonArray(){
    return array(
//        "同步订单信息" => 'adminSynccertorderdata',
//        "设为新订单" => 'resetorderstatus',
//        "发送签发通知" => 'adminSendIssuedNotification',
//        "设为续费订单"=>'setRenewOrder',
//        "removeDomain"=>"removeDomain",
    );
}

/**
 * 下载证书压缩包
 * @param array $param
 */
function TRUSTOCEANSSL_downloadcertificate($param){
        $cert = Capsule::table('tbltrustocean_certificate')->where('serviceid',$param['serviceid'])->first();

        $domains = json_decode($cert->domains, 1);
        // 开始准备下载ZIP文件
        $filename = str_replace('.','-',$domains[0]);
        $certfilename = str_replace('*','START',$filename); # 格式化之后域名作为文件名
        $filename = str_replace('*','START',$certfilename).'-'.sha1(time()); # 确定文件夹和文件名称
        $filepath = "/tmp/cert/customer_certs/";
        mkdir($filepath.$filename,0777,TRUE);
        mkdir($filepath.$filename.'/Apache',0777,TRUE);
        mkdir($filepath.$filename.'/Nginx',0777,TRUE);
        mkdir($filepath.$filename.'/CDN',0777,TRUE);

        // 新建Apache 证书文件
        $apacheFile1 = fopen($filepath.$filename.'/Apache/'.$certfilename.".crt", "w+");
        fwrite($apacheFile1, trim($cert->cert_code));
        fclose($apacheFile1);
        $apacheFile2 = fopen($filepath.$filename.'/Apache/'."MustInstallThis-CAChains.crt", "w+");
        fwrite($apacheFile2, trim($cert->ca_code));
        fclose($apacheFile2);
        if($cert->key_code !== ""){
            $apacheKey1 = fopen($filepath.$filename.'/Apache/'.$certfilename."-privatekey.pem", "w+");
            fwrite($apacheKey1, trim($cert->key_code));
            fclose($apacheKey1);
        }

        // 新建CDN 证书文件
        $cdnFile1 = fopen($filepath.$filename.'/CDN/[1]'.$certfilename.".crt", "w+");
        fwrite($cdnFile1, trim($cert->cert_code));
        fclose($cdnFile1);
        $cdnFile2 = fopen($filepath.$filename.'/CDN/[2]'."MustInstallThis-CAChains.crt", "w+");
        fwrite($cdnFile2, trim($cert->ca_code));
        fclose($cdnFile2);
        if($cert->key_code !== ""){
            $cdnKey1 = fopen($filepath.$filename.'/CDN/'.$certfilename."-privatekey.pem", "w+");
            fwrite($cdnKey1, trim($cert->key_code));
            fclose($cdnKey1);
        }

        // 新建Nginx 证书文件 合并证书链
        $nginxFile1 = fopen($filepath.$filename.'/Nginx/'.$certfilename.".pem", "w+");
        fwrite($nginxFile1, trim($cert->cert_code));
        fwrite($nginxFile1, PHP_EOL);
        fwrite($nginxFile1, trim($cert->ca_code));
        fclose($nginxFile1);
        if($cert->key_code !== ""){
            $nginxKey1 = fopen($filepath.$filename.'/Nginx/'.$certfilename."-privatekey.pem", "w+");
            fwrite($nginxKey1, trim($cert->key_code));
            fclose($nginxKey1);
        }

        // 生成IIS .pfx 证书文件
        if($cert->key_code !== "" && openssl_get_privatekey($cert->key_code) !== false){
                 mkdir($filepath.$filename.'/IIS',0777,TRUE);
                $pfx_content = "";
                $re = openssl_pkcs12_export(
                    file_get_contents($filepath.$filename.'/Apache/'.$certfilename.".crt"),
                    $pfx_content,
                    $cert->key_code,
                    '',
                    array(
                        'extracerts'=>file_get_contents($filepath.$filename.'/Apache/'."MustInstallThis-CAChains.crt")
                    )
                );
                if(!$re){
                    return 'Convert Faild!';
                }
                $pfx = fopen($filepath.$filename.'/IIS/'.$certfilename.".pfx", "w+");
                fwrite($pfx, $pfx_content);
                fclose($pfx);
        }

        $zip = new \ZipArchive;
        if ($zip->open($filepath.$filename.'.zip',\ZIPARCHIVE::CREATE) === TRUE) {
            $zip->addEmptyDir('Apache');
            $zip->addEmptyDir('Nginx');
            $zip->addEmptyDir('CDN');
            $zip->addEmptyDir('IIS');

            $zip->addFile($filepath.$filename.'/Apache/'.$certfilename.".crt", 'Apache/'.$certfilename.".crt");
            $zip->addFile($filepath.$filename.'/Apache/'."MustInstallThis-CAChains.crt",'Apache/'."MustInstallThis-CAChains.crt");
            $zip->addFile($filepath.$filename.'/CDN/[1]'.$certfilename.".crt", 'CDN/[1]'.$certfilename.".crt");
            $zip->addFile($filepath.$filename.'/CDN/[2]'."MustInstallThis-CAChains.crt",'CDN/[2]'."MustInstallThis-CAChains.crt");
            $zip->addFile($filepath.$filename.'/Nginx/'.$certfilename.".pem", 'Nginx/'.$certfilename.".pem");

            // key file
            if($cert->key_code !== ""){
                $zip->addFile($filepath.$filename.'/CDN/'.$certfilename."-privatekey.pem", 'CDN/'.$certfilename."-privatekey.pem");
                $zip->addFile($filepath.$filename.'/Apache/'.$certfilename."-privatekey.pem", 'Apache/'.$certfilename."-privatekey.pem");
                $zip->addFile($filepath.$filename.'/Nginx/'.$certfilename."-privatekey.pem", 'Nginx/'.$certfilename."-privatekey.pem");
                $zip->addFile($filepath.$filename.'/IIS/'.$certfilename.".pfx", 'IIS/'.$certfilename.".pfx");
            }
            $zip->close();

            header('Content-type: application/force-download');
            header('Content-Disposition: attachment; filename="'.$certfilename.'.zip"');
            // @readfile();
            if(TRUSTOCEANSSL_readFileForDownload($filepath.$filename.'.zip') === true){
                fastcgi_finish_request();
                TRUSTOCEANSSL_massDeletePathAndFiles($filepath.$filename);
            }
            unlink($filepath.$filename.'.zip'); #删除临时压缩包文件
        } else {
            return 'Failed To Download Certificate,Please Contact Us For Help!';
        }
}

/**
 * 删除临时文件夹
 * @param $path
 */
function TRUSTOCEANSSL_massDeletePathAndFiles($path){
    $pathTotal = TRUSTOCEANSSL_scanDir($path);
    $pathToDelete = [];
    foreach ($pathTotal as $paths){
        if(!empty($paths)){
            foreach ($paths as $path1){
                if(is_array($path1)){
                    foreach ($path1 as $path2){
                        if(is_array($path2)){
                            foreach ($path2 as $path3){
                               $pathToDelete[] = $path3;
                            }
                        }else{
                            $pathToDelete[] = $path2;
                        }
                    }
                }else{
                    $pathToDelete[] = $path1;
                }

            }
        }
    }
    foreach ($pathToDelete as $path){
        if(is_file($path)){
            unlink($path);
        }
    }
    foreach ($pathToDelete as $path){
        if(is_dir($path)){
            rmdir($path);
        }
    }
}

/**
 * 扫描获取文件夹下的所有路径和文件
 * @param $basePath
 * @return array
 */
function TRUSTOCEANSSL_scanDir($basePath){
    $paths = [];
    $files = [];
    $first = scandir($basePath);
    foreach ($first as $path){
        if($path !== "." && $path !== ".."){
            $currentPath = $basePath."/".$path."";
            if(is_dir($currentPath)){
                $paths[] = $currentPath;
                $paths[] = TRUSTOCEANSSL_scanDir($currentPath);
            }
            if(is_file($currentPath)){
                $files[] = $currentPath;
            }
        }
    }
    return [
        "paths" => $paths,
        "files" => $files
    ];
}

/**
 * 读取文件下载
 * @param $filePath
 */
function TRUSTOCEANSSL_readFileForDownload($filePath){
    $fp=fopen($filePath,"r");
    $file_size=filesize($filePath);
    $buffer=1024;
    $file_count=0;
    while(!feof($fp) && $file_count<$file_size) {
        $file_con = fread($fp, $buffer);
        $file_count += $buffer;
        echo $file_con;
    }
    fclose($fp);
    if($file_count >= $file_size){
        unlink($filePath);
        return true;
    }
}