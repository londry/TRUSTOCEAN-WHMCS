<?php

require __DIR__.'/../../autoload.php';
require __DIR__.'/../../../init.php';

use WHMCS\Database\Capsule;

echo "=================Starting Expiration Check================\r\n";

# todo:: Check the expiration cronjob status
$expirationStatus = Capsule::table('tbltrustocean_configuration')->where('setting','expiration-cronjob-status')->value('value');
if($expirationStatus !== "finished"){
    exit('Pre Cronjob not finished, so we need exit this time.'."\r\n");
}

// todo:: 标记正在执行
Capsule::table('tbltrustocean_configuration')->where('setting','expiration-cronjob-status')->update(array(
    'value'=>'processing',
));

// todo:: 循环分块(分页)处理, 每次处理100条数据
Capsule::table('tbltrustocean_certificate')->where('status','issued_active')->chunk(100, function($services){
   foreach ($services as $service){
        // todo:: 判断是否有已经签发的证书代码

        if(isset($service) && $service->cert_code !== ""){
            echo "\r\nChecking cert#".$service->serviceid;
            // todo:: 获取证书过期日期
            $cert = openssl_x509_parse($service->cert_code , true);
            $validTo_time_t = $cert['validTo_time_t'];
            $validFrom_time_t = $cert['validFrom_time_t'];

            $domainString = str_replace('IPAddress:',',', str_replace('DNS:',',', $cert['extensions']['subjectAltName']));
            $domainString = substr($domainString, 1);
            // todo:: 检查90天过期日期
            if(($validTo_time_t - time()) < 60*60*24*90 && $service->expiration90_sent_at == ""){
                sendEmailForNotification($service, 90,$validTo_time_t, $validFrom_time_t,$domainString, $cert['subject']['CN']);
            }
            // todo:: 检查30天过期日期
            if(($validTo_time_t - time()) < 60*60*24*30 && $service->expiration30_sent_at == ""){
                sendEmailForNotification($service, 30,$validTo_time_t, $validFrom_time_t,$domainString, $cert['subject']['CN']);
            }
            // todo:: 检查7天过期日期
            if(($validTo_time_t - time()) < 60*60*24*7 && $service->expiration7_sent_at == ""){
                sendEmailForNotification($service, 7,$validTo_time_t, $validFrom_time_t,$domainString, $cert['subject']['CN']);
            }
            // todo:: 检查1天过期日期
            if(($validTo_time_t - time()) < 60*60*24*1 && $service->expiration1_sent_at == ""){
                sendEmailForNotification($service, 1,$validTo_time_t, $validFrom_time_t,$domainString, $cert['subject']['CN']);
            }
            // todo:: 检查已经过期的证书
            if($validTo_time_t < time() && $service->expired_sent_at == ""){
                sendEmailForNotification($service, 0,$validTo_time_t, $validFrom_time_t,$domainString, $cert['subject']['CN']);
            }
        }
   }
});

// todo:: 标记完成执行
Capsule::table('tbltrustocean_configuration')->where('setting','expiration-cronjob-status')->update(array(
    'value'=>'finished',
));

echo "\r\n=================Expiration Check Finished================\r\n";

/**
 * 发送到期邮件提醒
 * @param $service
 * @param $days
 * @param $validTo_time_t
 * @param $validFrom_time_t
 * @param $domainString
 */
function sendEmailForNotification($service, $days, $validTo_time_t, $validFrom_time_t, $domainString, $commonName){

    $updateParam = array();
    if($days === 90){
        $updateParam['expiration90_sent_at'] = date('Y-m-d H:i:s', time());
    }
    if($days === 30){
        $updateParam['expiration30_sent_at'] = date('Y-m-d H:i:s', time());
    }
    if($days === 7){
        $updateParam['expiration7_sent_at'] = date('Y-m-d H:i:s', time());
    }
    if($days === 1){
        $updateParam['expiration1_sent_at'] = date('Y-m-d H:i:s', time());
    }
    if($days === 0 ){
        $updateParam['expired_sent_at'] = date('Y-m-d H:i:s', time());
        $updateParam['status'] = "expired";
    }

    $sendEmail = localAPI('SendEmail', array(
        'messagename'=>'TrustOcean SSL Expiration Notification',
        'id'=>$service->uid,
        'customvars'=>base64_encode(serialize(array(
                "trustocean_cert_type"=>$service->name,
                "trustocean_cert_issue_date"=>date('Y-m-d H:i:s', $validFrom_time_t),
                'trustocean_expire_date'=>date('Y-m-d H:i:s', $validTo_time_t),
                'trustocean_domain_string'=>$domainString,
                'trustocean_cert_serviceid'=>$service->serviceid,
                'trustocean_cert_commonname'=>$commonName,

            )
        )),
    ));
    if($sendEmail['result'] !== "success"){
        echo "\r\nSend mail faild!";
        WHMCSLogActivity('[Faild] TrustOceanSSLAdmin try to send one notification email to client#'.$service->uid.' for SSL Expiration Notification certOrder#'.$service->serviceid);
    }else{
        echo "\r\nSend mail successfully!";
        Capsule::table('tbltrustocean_certificate')->where('id', $service->id)->update($updateParam);
        WHMCSLogActivity("\r\n".'[Success] TrustOceanSSLAdmin has sent one notification email to client#'.$service->uid.' for SSL Expiration Notification certOrder#'.$service->serviceid);
    }
}

// todo:: 写入WHMCS LOGO Activity日志
function WHMCSLogActivity($logString){
    LocalAPI('logActivity',array(
        'description'=>$logString,
    ));
}