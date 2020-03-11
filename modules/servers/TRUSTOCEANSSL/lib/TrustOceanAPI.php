<?php
namespace WHMCS\Module\Server\TRUSTOCEANSSL;

use WHMCS\Database\Capsule;

class TrustOceanAPI
{
    /**
     * TrustOcean API 用户名
     * @var string
     */
    private $api_username = "";

    /**
     * TrustOcean API 密码
     * @var string
     */
    private $api_password = "";

    /**
     * 此版本 API 端点
     * @var string
     */
    private $endPointUrl = "https://api.crazyssl.com/ssl/v2";


    function __construct($api_username = "", $api_password = "")
    {
        // todo:: 检查设置的API版本
        $location  = Capsule::table('tbladdonmodules')->where('module','TrustOceanSSLAdmin')->where('setting','apiservertype')->value('value');
        if($location === "CN-Beijing"){
            $this->endPointUrl = "https://api.crazyssl.com/ssl/v2"; // API located in Beijing CN
        }else{
            $this->endPointUrl = "https://api.crazyssl.com/ssl/v2"; // API located in London UK
        }

        $this->api_username = Capsule::table('tbladdonmodules')->where('module','TrustOceanSSLAdmin')->where('setting','apiusername')->value('value');
        $this->api_password = Capsule::table('tbladdonmodules')->where('module','TrustOceanSSLAdmin')->where('setting','apipassword')->value('value');
    }

    /**
     * 获取产品列表
     * @return mixed
     */
    public function getProductList(){
        $rlt =  $this->makeCurlCall('getProductList');
        return $rlt['products'];
    }

    /**
     * 测试到API服务器的连接
     * @return bool
     */
    public function testApiConnect(){
        $connecting = $this->makeCurlCall("ping");
        if($connecting['status'] === "success"){
            return true;
        }else{
            false;
        }
    }
    /**
     * 使用CURL模拟提交请求信息
     * @param $method
     * @param array $params
     * @return mixed
     */
    function makeCurlCall($method, array $params = [])
    {
        # 用于认证的账户信息
        $authParams = array(

            "username"      =>  "$this->api_username",
            "password"      =>  "$this->api_password",
        );
        $apiEndpoint = $this->endPointUrl."/$method";
        $curl = curl_init($apiEndpoint);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        $header = array();
        $header[] = 'User-Agent: Mozilla/5.0 (X11; Linux i686) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.835.186 Safari/535.1';
        $header[] = 'Cache-Control:max-age=0';
        $header[] = 'Content-Type:application/x-www-form-urlencoded';
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array_merge($authParams, $params)));
        $result = curl_exec($curl);
        curl_close($curl);
        return json_decode($result, 1);
    }


}