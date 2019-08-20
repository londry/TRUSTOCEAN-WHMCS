<?php
if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

// 从模块内的 vendor 库中载入 autoload.php
require_once __DIR__ . '/../vendor/autoload.php';

// 加载 TRUSTOCEAN API Libary
# require_once  __DIR__.'/../libary/TrustOceanAPI.php';


// 加载模块内的语言文件
GLOBAL $MODLANG;
require_once __DIR__.'/../libary/languageLoader.php';
$langLoader = new lanaguageLoader($_SESSION);
$MODLANG = $langLoader->loading();

// 设置木块所使用的openssl环境
define("OPEN_SSL_CONF_PATH", __DIR__."/../openssl.cnf");