<?php

use WHMCS\Database\Capsule;

/**
 * Function for installation activate
 * @return array
 */
function TrustOceanSSLAdmin_activate(){
    # create database table for TRUSTOCEANSSL server module, all the cert data will stored in that table
    $query01 = "CREATE TABLE IF NOT EXISTS `tbltrustocean_certificate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `serviceid` int(11) NOT NULL,
  `status` varchar(200) NOT NULL,
  `class` varchar(200) NOT NULL,
  `multidomain` int(11) NOT NULL,
  `csr_code` text,
  `cert_code` text NOT NULL,
  `ca_code` text,
  `key_code` text,
  `domains` text NOT NULL,
  `contact_email` varchar(200) DEFAULT NULL,
  `admin_info` text,
  `tech_info` text,
  `org_info` text,
  `dcv_info` text NOT NULL,
  `name` text,
  `vendor_id` text NOT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `paidcertificate_status` varchar(200) DEFAULT NULL,
  `paidcertificate_delivery_time` varchar(200) DEFAULT NULL,
  `unique_id` text NOT NULL,
  `reissue` int(11) NOT NULL,
  `renew` int(11) NOT NULL,
  `certificate_id` text NOT NULL,
  `trustocean_id` int(11) NOT NULL COMMENT 'TRUSTOCEAN_ORDER_ID',
  `period` text NOT NULL,
  `issued_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `reissued_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dcvredo_clicked` timestamp NOT NULL COMMENT 'DCV点击时间',
  `checkcert_clicked` timestamp NOT NULL COMMENT 'checkCert点击时间',
  `expiration90_sent_at` timestamp NULL DEFAULT NULL,
  `expiration30_sent_at` timestamp NULL DEFAULT NULL,
  `expiration7_sent_at` timestamp NULL DEFAULT NULL,
  `expiration1_sent_at` timestamp NULL DEFAULT NULL,
  `expired_sent_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;";

    # set primary key
    $query02 = "ALTER TABLE `tbltrustocean_certificate`
  ADD PRIMARY KEY (`id`);";

    # auto increment for table
    $query03 = "ALTER TABLE `tbltrustocean_certificate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";

    # query the database
    full_query($query01);
    full_query($query02);
    full_query($query03);

    # todo:: add san upgradeInvoice function to here
    $query04 = "CREATE TABLE IF NOT EXISTS `tbltrustocean_upgradeinvoice` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `type` varchar(200) NOT NULL,
  `service_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `status` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $query05 = "ALTER TABLE `tbltrustocean_upgradeinvoice`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_2` (`id`),
  ADD KEY `id` (`id`);";
    $query06 = "ALTER TABLE `tbltrustocean_upgradeinvoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";

    # query for upgradeinvoice
    full_query($query04);
    full_query($query05);
    full_query($query06);

    #todo:: add configuration table
    $query07 = "CREATE TABLE IF NOT EXISTS`tbltrustocean_configuration` (
  `setting` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $query08 = "INSERT INTO `tbltrustocean_configuration` VALUES ('expiration-cronjob-status', 'finished');";

    #query for configuration
    full_query($query07);
    full_query($query08);

    #todo:: custom email templates query insert to
    if(empty(Capsule::table('tblemailtemplates')->where('name', 'TrustOcean SSL Expiration Notification')->get())){
        $query09 = 'INSERT INTO `tblemailtemplates` (`id`, `type`, `name`, `subject`, `message`, `attachments`, `fromname`, `fromemail`, `disabled`, `custom`, `language`, `copyto`, `blind_copy_to`, `plaintext`, `created_at`, `updated_at`) VALUES (NULL, \'general\', \'TrustOcean SSL Expiration Notification\', \'[#{$trustocean_cert_serviceid}]SSL证书过期提醒({$trustocean_cert_commonname})\', \'<p>亲爱的  {$client_name},</p>\r\n<p>我们正式的通知您, 您在 <span style=\"background-color: #ffffff; color: #626262;\"> </span><span style=\"background-color: #ffffff; color: #626262;\">{$company_name} 申购的一本SSL安全加密证书即将到期</span>. 请您尽快点击下列续定链接更新您的SSL安全证书.</p>\r\n<p>续订链接:  {$whmcs_link}</p>\r\n<hr />\r\n<p>证书类型:  {$trustocean_cert_type}</p>\r\n<p>签发日期: {$trustocean_cert_issue_date}</p>\r\n<p>过期日期: {$trustocean_expire_date}</p>\r\n<p>证书过期后可能会影响下列这些域名, 过期后这些域名也可能会无法正常访问:</p>\r\n<p>{$trustocean_domain_string}</p>\r\n<hr />\r\n<p>{$signature}</p>\', \'\', \'\', \'\', \'0\', \'0\', \'chinese\', \'\', \'\', \'0\', \'2018-12-22 16:36:20\', \'2018-12-24 13:23:23\');';
        $query10 = 'INSERT INTO `tblemailtemplates` (`id`, `type`, `name`, `subject`, `message`, `attachments`, `fromname`, `fromemail`, `disabled`, `custom`, `language`, `copyto`, `blind_copy_to`, `plaintext`, `created_at`, `updated_at`) VALUES (NULL, \'general\', \'TrustOcean SSL Expiration Notification\', \'[#{$trustocean_cert_serviceid}]SSL Expiration Reminder{$trustocean_cert_commonname})\', \'<p>Dear  {$client_name} ,</p>\r\n<p>We hereby inform you that one SSL certificate you purchased from us ( {$company_name} ) is about to expire. Please log in to our website as soon as possible to renew your SSL Certificate.</p>\r\n<p>Renewal URL:  {$whmcs_link}</p>\r\n<hr />\r\n<p>Certificate type:  {$trustocean_cert_type}</p>\r\n<p>Date of issue: {$trustocean_cert_issue_date}</p>\r\n<p>Expiration date: {$trustocean_expire_date}</p>\r\n<p>These domains will be affected or not accessible after expiration:</p>\r\n<p>{$trustocean_domain_string}</p>\r\n<hr />\r\n<p>{$signature}</p>\', \'\', \'\', \'\', \'0\', \'1\', \'\', \'\', \'\', \'0\', \'2018-12-22 16:27:41\', \'2018-12-24 13:23:23\');';
        #query for email templates
        full_query($query09);
        full_query($query10);
    }

    if(empty(Capsule::table('tblemailtemplates')->where('name', 'TrustOcean SSL Configuration')->get())){
        $query11 = 'INSERT INTO `tblemailtemplates` (`id`, `type`, `name`, `subject`, `message`, `attachments`, `fromname`, `fromemail`, `disabled`, `custom`, `language`, `copyto`, `blind_copy_to`, `plaintext`, `created_at`, `updated_at`) VALUES (NULL, \'product\', \'TrustOcean SSL Configuration\', \'[#{$service_order_id}]SSL Certificate Configuration\', \'<p>Dear {$client_name} ,</p>\r\n<p>It is our pleasure to provide  high secure SSL certificate service to you, we have setup your SSL order, and now, please access this configuration link to get start:</p>\r\n<p>Configuration URL:  {$whmcs_url}clientarea.php?action=productdetails&amp;id={$service_order_id}</p>\r\n<p>Please feel free to contact us by open one new ticket if you get any trouble.</p>\r\n<p><span style=\"color: #000000;font-size: 13.3px; background-color: #ffffff;\">{$signature}</span></p>\', \'\', \'\', \'\', \'0\', \'1\', \'\', \'\', \'\', \'0\', \'2018-12-22 16:55:59\', \'2018-12-24 14:40:43\');';
        $query12 = 'INSERT INTO `tblemailtemplates` (`id`, `type`, `name`, `subject`, `message`, `attachments`, `fromname`, `fromemail`, `disabled`, `custom`, `language`, `copyto`, `blind_copy_to`, `plaintext`, `created_at`, `updated_at`) VALUES (NULL, \'product\', \'TrustOcean SSL Configuration\', \'[#{$service_order_id}]请配置您的SSL证书\', \'<p style=\"color: #626262;\">亲爱的 {$client_name} ,</p>\r\n<p style=\"color: #626262;\">非常荣幸您能够选择使用由我们提供的全球信任安全加密SSL证书服务, 现在, 请您登陆下列配置链接开始配置您的SSL证书申请:</p>\r\n<p style=\"color: #626262;\">SSL证书配置链接:  {$whmcs_url}clientarea.php?action=productdetails&amp;id={$service_order_id}</p>\r\n<p style=\"color: #626262;\">配置过程中遇到任何问题, 请通过在线客服或提交工单联系我们获取帮助。</p>\r\n<p style=\"color: #626262;\"><span style=\"background-color: #ffffff; color: #000000; font-size: 13.3px;\">{$signature}</span></p>\', \'\', \'\', \'\', \'0\', \'0\', \'chinese\', \'\', \'\', \'0\', \'2018-12-22 16:55:59\', \'2018-12-24 14:40:43\');';
        #query for email templates
        full_query($query11);
        full_query($query12);
    }

    return array('status'=>'success','description'=>'Module Actived successfully, we have create one database table `tbltrustocean_certificate` for you , to store the customer ssl certificate. Next, you need config your API username and password etc.');

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
    "version" => "1.0",
    "language" => "chinese",
    "author" => "QiaoKr Corporation Limited",
    "fields" => array(
        "apiusername" => array ("FriendlyName" => "API Username", "Type" => "text", "Size" => "35",
                              "Description" => "API UserName"),
        "apipassword" => array ("FriendlyName" => "API password", "Type" => "password", "Size" => "35",
                              "Description" => "API Token", ),
        "apiunicodesalt" => array ("FriendlyName" => "API Salt", "Type" => "text", "Size" => "35",
                              "Description" => "API Salt" ),
        "apiservertype" => array("FriendlyName" => "API Server Location", "Type" => "dropdown", "Options" =>
                              "CN-Beijing,UK-London", "Description" => "Select the API server location", "Default" => "CN-Beijin",)
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
        if($opt['option'] === "DomainCount" && $opt['type'] === "quantity"){
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