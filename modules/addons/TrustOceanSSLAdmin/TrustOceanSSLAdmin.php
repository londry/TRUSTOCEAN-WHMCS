<?php

use WHMCS\Database\Capsule;
use WHMCS\Module\Addon\TrustOceanSSLAdmin\Dispatcher\ClientDispatcher;
use WHMCS\Module\Addon\TrustOceanSSLAdmin\Dispatcher\AdminDispatcher;
/**
 * Function for installation activate
 * @return array
 */
function TrustOceanSSLAdmin_activate(){
    # create database table for TRUSTOCEANSSL server module, all the cert data will stored in that table
    $schema = Capsule::schema();
    // 创建 tbltrustocean_certificate 数据表
    if(!$schema->hasTable("tbltrustocean_certificate")){
        $schema->create("tbltrustocean_certificate", function($table){
            // 基本字段
            $table->increments('id');
            $table->integer('uid');
            $table->integer('serviceid');
            $table->char('status', 200);
            $table->char('class', 200);
            $table->integer('multidomain');
            $table->longText('csr_code')->nullable();
            $table->longText('cert_code')->nullable();
            $table->longText('ca_code')->nullable();
            $table->longText('key_code')->nullable();
            $table->longText('domains')->nullable();
            $table->char('contact_email', 200)->nullable();
            $table->longText('admin_info')->nullable();
            $table->longText('tech_info')->nullable();
            $table->longText('org_info')->nullable();
            $table->longText('dcv_info')->nullable();
            $table->text('name');
            $table->text('vendor_id')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->char('paidcertificate_status', 200)->nullable();
            $table->char('paidcertificate_delivery_time', 200)->nullable();
            $table->text('unique_id');
            $table->integer('reissue')->default(0);
            $table->integer('renew')->default(0);
            $table->text('certificate_id')->nullable();
            $table->integer('trustocean_id')->nullable();
            $table->text('period');
            $table->timestamp('issued_at')->default('0000-00-00 00:00:00');
            $table->timestamp('created_at');
            $table->timestamp('dcvredo_clicked')->nullable();
            $table->timestamp('checkcert_cliecked')->nullable();
            $table->timestamp('expiration90_sent_at')->nullable();
            $table->timestamp('expiration30_sent_at')->nullable();
            $table->timestamp('expiration7_sent_at')->nullable();
            $table->timestamp('expiration1_sent_at')->nullable();
            $table->timestamp('expired_sent_at')->nullable();
            $table->integer("is_requested_refund")->default(0);
            $table->text("refund_status")->nullable();

            // 添加唯一索引
            $table->unique('serviceid');
        });
    }

    # todo:: add san upgradeInvoice function to here
    if(!$schema->hasTable("tbltrustocean_upgradeinvoice")){
        $schema->create('tbltrustocean_upgradeinvoice', function($table){
            // 基本字段
            $table->increments('id');
            $table->integer('invoice_id');
            $table->char('type',200);
            $table->integer('service_id');
            $table->integer('qty');
            $table->char('status', 200);
            $table->timestamp('created_at');

            // 添加唯一索引
            $table->unique('invoice_id');
        });
    }

    #todo:: add configuration table
    if(!$schema->hasTable('tbltrustocean_configuration')){
        $schema->create('tbltrustocean_configuration', function($table){
            // 基本字段
            $table->char('setting', 200);
            $table->text('value');

            // 添加唯一索引
            $table->unique('setting');
        });
        // 插入默认的配置信息
        Capsule::table('tbltrustocean_configuration')->insert([
            ["setting"=>"expiration-cronjob-status", "value"=>"finished"],
            ["setting"=>"siteseal", "value"=>"show"]
        ]);
    }

    // SSL 证书到期通知
    if(empty(Capsule::table('tblemailtemplates')->where('name', 'TrustOcean SSL Expiration Notification')->get())){
        Capsule::table('tblemailtemplates')->insert([
            [
                "type"      =>"general",
                "name"      =>"TrustOcean SSL Expiration Notification",
                "subject"   =>'[#{$trustocean_cert_serviceid}]SSL证书过期提醒({$trustocean_cert_commonname})',
                "message"   =>'<p>亲爱的  {$client_name},</p>\r\n<p>我们正式的通知您, 您在 <span style=\"background-color: #ffffff; color: #626262;\"> </span><span style=\"background-color: #ffffff; color: #626262;\">{$company_name} 申购的一本SSL安全加密证书即将到期</span>. 请您尽快点击下列续定链接更新您的SSL安全证书.</p>\r\n<p>续订链接:  {$whmcs_link}</p>\r\n<hr />\r\n<p>证书类型:  {$trustocean_cert_type}</p>\r\n<p>签发日期: {$trustocean_cert_issue_date}</p>\r\n<p>过期日期: {$trustocean_expire_date}</p>\r\n<p>证书过期后可能会影响下列这些域名, 过期后这些域名也可能会无法正常访问:</p>\r\n<p>{$trustocean_domain_string}</p>\r\n<hr />\r\n<p>{$signature}</p>',
                "language"  =>"chinese",
            ],
            [
                "type"      =>"general",
                "name"      =>"TrustOcean SSL Expiration Notification",
                "subject"   =>'[#{$trustocean_cert_serviceid}]SSL Expiration Reminder{$trustocean_cert_commonname})',
                "message"   =>'<p>Dear  {$client_name} ,</p>\r\n<p>We hereby inform you that one SSL certificate you purchased from us ( {$company_name} ) is about to expire. Please log in to our website as soon as possible to renew your SSL Certificate.</p>\r\n<p>Renewal URL:  {$whmcs_link}</p>\r\n<hr />\r\n<p>Certificate type:  {$trustocean_cert_type}</p>\r\n<p>Date of issue: {$trustocean_cert_issue_date}</p>\r\n<p>Expiration date: {$trustocean_expire_date}</p>\r\n<p>These domains will be affected or not accessible after expiration:</p>\r\n<p>{$trustocean_domain_string}</p>\r\n<hr />\r\n<p>{$signature}</p>',
                "language"  => ""
            ]
        ]);
    }
    // 证书订单开通 配置通知
    if(empty(Capsule::table('tblemailtemplates')->where('name', 'TrustOcean SSL Configuration')->get())){
        Capsule::table('tblemailtemplates')->insert([
            [
                "type"      =>"product",
                "name"      =>"TrustOcean SSL Configuration",
                "subject"   =>'[#{$service_order_id}]SSL Certificate Configuration',
                "message"   =>'<p>Dear {$client_name} ,</p>\r\n<p>It is our pleasure to provide  high secure SSL certificate service to you, we have setup your SSL order, and now, please access this configuration link to get start:</p>\r\n<p>Configuration URL:  {$whmcs_url}clientarea.php?action=productdetails&amp;id={$service_order_id}</p>\r\n<p>Please feel free to contact us by open one new ticket if you get any trouble.</p>\r\n<p><span style=\"color: #000000;font-size: 13.3px; background-color: #ffffff;\">{$signature}</span></p>',
                "language"  => ""
            ],
            [
                "type"      =>"product",
                "name"      =>"TrustOcean SSL Configuration",
                "subject"   =>'[#{$service_order_id}]请配置您的SSL证书',
                "message"   =>'<p style=\"color: #626262;\">亲爱的 {$client_name} ,</p>\r\n<p style=\"color: #626262;\">非常荣幸您能够选择使用由我们提供的全球信任安全加密SSL证书服务, 现在, 请您登陆下列配置链接开始配置您的SSL证书申请:</p>\r\n<p style=\"color: #626262;\">SSL证书配置链接:  {$whmcs_url}clientarea.php?action=productdetails&amp;id={$service_order_id}</p>\r\n<p style=\"color: #626262;\">配置过程中遇到任何问题, 请通过在线客服或提交工单联系我们获取帮助。</p>\r\n<p style=\"color: #626262;\"><span style=\"background-color: #ffffff; color: #000000; font-size: 13.3px;\">{$signature}</span></p>',
                "language"  =>"chinese",
            ]
        ]);
    }

    return array('status'=>'success','description'=>'Module Actived successfully, we have create one database table `tbltrustocean_certificate` for you , to store the customer ssl certificate. Next, you need config your API username and password etc.');

}

/**版本升级导致的数据库变动
 * @param $vars
 */
function TrustOceanSSLAdmin_upgrade($vars){
    $version = $vars['version'];

    # 为小于 v1.1.1 版本的之前模块修复数据库字段
    if($version < "1.1.1"){
        $schema = Capsule::schema();

        if($schema->hasTable("tbltrustocean_certificate")){
            if(!$schema->hasColumn('tbltrustocean_certificate','is_requested_refund')){
                $schema->table("tbltrustocean_certificate", function($table){
                    $table->integer("is_requested_refund");
                });
            }
            if(!$schema->hasColumn('tbltrustocean_certificate','refund_status')){
                $schema->table("tbltrustocean_certificate", function($table){
                    $table->text("refund_status");
                });
            }
        }

        // 检查是否存在 siteseal 的配置项
        if(empty(Capsule::table('tbltrustocean_configuration')->where('setting','siteseal')->first())){
            Capsule::table('tbltrustocean_configuration')->insert(
                ["setting"=>"siteseal", "value"=>"show"]
            );
        }
    }
}

/**
 * 配置模块
 * @return array
 */
function TrustOceanSSLAdmin_config() {
    //var_dump($vars['_lang']);
    $configarray = array(
    "name" => "TRUSTOCEAN SSL Admin",
    "description" => "This is a WHMCS Admin module for TrustOcean SSL Partner",
    "version" => "1.1.1",
    "language" => "chinese",
    "author" => "QiaoKr Corporation Limited",
    "fields" => array(
        "apiusername" => array ("FriendlyName" => "API Username", "Type" => "text", "Size" => "35",
                              "Description" => "It's the email address of your TrustOcean Reseller Account."),
        "apipassword" => array ("FriendlyName" => "API Token", "Type" => "password", "Size" => "35",
                              "Description" => "Generate your API Token at <a target='_blank' href='https://console.trustocean.com/partner/api-setting'>Partner API Setting Page</a>.", ),
        "apiunicodesalt" => array ("FriendlyName" => "API Salt", "Type" => "text", "Size" => "35",
                              "Description" => "Check your API Salt at <a target='_blank' href='https://console.trustocean.com/partner/api-setting'>Partner API Setting Page</a>." ),
        "apiservertype" => array("FriendlyName" => "API Server Location", "Type" => "dropdown", "Options" =>
                              "CN-Beijing,UK-London", "Description" => "Choose the best API access point based on your server location.", "Default" => "CN-Beijin",),
        "privatekey" => array("FriendlyName" => "PUSH Private Key", "Type" => "textarea", "Description" => "1024 bit RSA Private Key in PEM format.")
    ));
    return $configarray;
}

/**
 * 获取SAN数量
 * @param $vars
 * @return mixed
 */
function TrustOceanSSLAdmin_getProductSANValue($vars){
    // 获取ORDER信息 参考 WHMCS数据库 tblhosting
    $command = 'GetClientsProducts';
    $values = array(
        'serviceid' => $vars['serviceid'],
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

    if(!isset($domaincount)){
        return false;
    }

    return $domaincount['value'];

}

/**
 * 检查证书到期日期和即将到期状态
 * @param $cert_code
 * @return array
 */
function checkExpireDate($cert_code){
    $info = openssl_x509_parse($cert_code, TRUE);
    return $info;
}

/**
 * 客户区域 证书管理详情页
 * @param $vars
 * @return array
 */
function TrustOceanSSLAdmin_clientarea($vars){
    $action = isset($_REQUEST['action']) ? $_REQUEST['action']:'';
    $dispatcher = new ClientDispatcher();
    return $dispatcher->dispatch($action, $vars);
}

/**
 * 管理员区域输出面板
 * @param $vars
 * @return mixed
 */
function TrustOceanSSLAdmin_output($vars){
    $action = isset($_REQUEST['action']) ? $_REQUEST['action']:'';
    $dispatcher = new AdminDispatcher();
    return $dispatcher->dispatch($action, $vars);
}