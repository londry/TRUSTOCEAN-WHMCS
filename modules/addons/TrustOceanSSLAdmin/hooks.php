<?php
/**
 * 这里可以添加全局hook, 仅当激活TrustOceanSSLAdmin模块生效时才会被调用
 * 参考可用的Hooks: https://developers.whmcs.com/hooks/hook-index/
 */


use WHMCS\View\Menu\Item as MenuItem;

/**
 * https://docs.whmcs.com/Client_Area_Navigation_Menus_Cheatsheet#Adding_a_Menu_Item
 */
add_hook('ClientAreaPrimaryNavbar', 1, function (MenuItem $primaryNavbar)
{
    if (!is_null($primaryNavbar->getChild('Support'))) {
        $primaryNavbar->getChild('Services')
            ->addChild('SSL Management', array(
                'label' => 'SSL证书管理',
                'uri' => 'index.php?m=TrustOceanSSLAdmin',
                'order' => '5',
            ));
    }
});