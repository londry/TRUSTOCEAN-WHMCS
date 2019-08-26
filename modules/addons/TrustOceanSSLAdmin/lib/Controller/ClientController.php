<?php
namespace WHMCS\Module\Addon\TrustOceanAdmin\Controller;
//use WHMCS\Database\Capsule;

class ClientController
{
    public function clientArea($vars){
        return array(
            'pagetitle' => 'SSL Management',
            'breadcrumb' => array('index.php?m=TrustOceanSSLAdmin'=>'SSL Management'),
            'templatefile' => 'template/clientarea',
            'requirelogin' => true, # accepts true/false
            'forcessl' => false, # accepts true/false
            'vars' => array(
                'testvar' => 'demo',
                'anothervar' => 'value',
                'sample' => 'test',
            ),
        );
    }
}