<?php
namespace WHMCS\Module\Addon\TrustOceanSSLAdmin\Dispatcher;
use WHMCS\Module\Addon\TrustOceanSSLAdmin\Controller\ClientController;

class ClientDispatcher
{
    /**
     * 调度客户端请求
     * @param $action
     * @param $paramters
     * @return mixed
     */
    public function dispatch($action, $paramters){
        if(!$action){
            // 默认的控制器方法
            $action = 'index';
        }

        $controller = new ClientController();
        // 验证请求是否是有效并且可执行
        if(is_callable(ClientController::class, $action)){
            return $controller->$action($paramters);
        }
    }
}