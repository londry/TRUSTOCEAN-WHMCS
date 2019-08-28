<?php
/**
  * CertificateAuthority Events Push
  * Process the Push notificateion and Reseller callback
  *
**/
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/../../../init.php';

use WHMCS\Module\Server\TRUSTOCEANSSL\Controller\PublicController;

header('Content-Type:application/json');

$pushProcesser = new PublicController();

try{
    $pushProcesser->processPushEvent($_REQUEST);
    echo json_encode([
        "status" => "success"
    ]);
}catch(Exception $exception){
    // 记录出错的 PUSH 事件
    LocalAPI("LogActivity", [
        "description" => json_encode([
            "Module" => "TrustOceanSSLAdmin",
            "event"  => "pushNotification",
            "status" => "error",
            "errorMessage" => $exception->getMessage(),
            "requestParams"=> $_REQUEST
        ])
    ]);

    echo json_encode([
        "status" => "error"
    ]);
}