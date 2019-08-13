<?php
namespace Londry\TrustOceanSSL\api;

use Londry\TrustOceanSSL\model\Order;
use Londry\TrustOceanSSL\TrustoceanException;

class SslOrder extends Order{

    public function __construct($username, $token)
    {
        $this->username = $username;
        $this->password = $token;
    }

    /**
     * @param null|string $orderId
     * @return $this
     * @throws TrustoceanException
     */
    public function callInit($orderId = NULL){
        if(!empty($orderId)){
            // check order and try to get remote order details
            $this->order_id = $orderId;
            $requestParams = [
                'trustocean_id' => $this->order_id
            ];
            $callResult = $this->callTrustOceanAPI('getSSLDetails', $requestParams);

            $this->updateSelfParams($callResult, [
                'order_status'  =>  'cert_status',
                'dcv_info'      =>  'dcv_info',
                'unique_id'     =>  'unique_id',
                'csr_code'      =>  'csr_code',
                'cert_code'     =>  'cert_code',
                'ca_code'       =>  'ca_code',
                'domains'       =>  'domains',
                'contact_email' =>  'contact_email',
                'created_at'    =>  'created_at'
            ]);

            if($callResult['org_info'] !== NULL){
                $this->updateSelfParams($callResult['org_info'], [
                    'organization_name'         =>  'organization_name',
                    'organizationalUnitName'    =>  'organizationalUnitName',
                    'registered_address_line1'  =>  'registered_address_line1',
                    'registered_no'             =>  'registered_no',
                    'country'                   =>  'country',
                    'state'                     =>  'state',
                    'city'                      =>  'city',
                    'postal_code'               =>  'postal_code',
                    'organization_phone'        =>  'organization_phone',
                    'date_of_incorporation'     =>  'date_of_incorporation',
                    'contact_name'              =>  'contact_name',
                    'contact_title'             =>  'contact_title',
                    'contact_phone'             =>  'contact_phone'
                ]);
            }
        }
        // check login credential and unique_id by callTrustOceanAPI
        if(empty($this->order_id)){
            $requestParams = [
                'unique_id' =>  $this->unique_id
            ];
            $this->callTrustOceanAPI('checkUniqueId', $requestParams);
        }
        return $this;
    }

    /**
     * @return $this
     * @throws TrustoceanException
     * Create new ssl order by API, before call this function,
     * you also need set required params whit `set function` provide by this class own.
     */
    public function callCreate(){
        // check required params for new dv singleDomain/multiDomain ssl order
        $requiredParamKeys = [
            'pid',
            'period',
            'csr_code',
            'contact_email',
            'dcv_method',
            'unique_id'
        ];
        if($this->product->isMultiDomainProduct() === TRUE){
            $requiredParamKeys[] = 'domains';
        }
        // check required params for new ov/ev singleDomain/multiDomain ssl order
        if($this->product->isOrganizationProduct() === TRUE){
            array_push($requiredParamKeys, [
                'organization_name',
                'organizationalUnitName',
                'registered_address_line1',
                'registered_no',
                'country',
                'state',
                'city',
                'postal_code',
                'organization_phone',
                'date_of_incorporation',
                'contact_name',
                'contact_title',
                'contact_phone'
            ]);
        }
        // Is this order will be a renew order?
        if($this->renew !== FALSE){
            $requiredParamKeys[] = 'renew';
        }
        // check required param keys
        $this->checkRequiredParams($requiredParamKeys);

        // build request data array
        $requestParams = $this->prepareRequestParams($requiredParamKeys);

        $callResult = $this->callTrustOceanAPI('addSSLOrder', $requestParams);
        $this->updateSelfParams($callResult, [
            'dcv_info'      =>  'dcv_info',
            'order_status'  =>  'cert_status',
            'order_id'      =>  'trustocean_id',
            'created_at'    =>  'created_at'
        ]);

        // return created and updated order object
        return $this;

    }

    /**
     * @return $this
     * @throws TrustoceanException
     */
    public function callReissue(){
        // check required params for reissue singleDomain/multiDomain ssl order
        $requiredParamKeys = [
            'trustocean_id',
            'csr_code',
            'dcv_method',
            'unique_id'
        ];
        if($this->product->isMultiDomainProduct() === TRUE){
            $requiredParamKeys[] = 'domains';
        }
        $this->checkRequiredParams($requiredParamKeys);

        $requestParams = $this->prepareRequestParams($requiredParamKeys);

        $callResult = $this->callTrustOceanAPI('reissueSSLOrder', $requestParams);
        $this->updateSelfParams($callResult, [
            'dcv_info'      =>  'dcv_info',
            'order_status'  =>  'cert_status'
        ]);

        return $this;

    }

    /**
     * @param $domainName
     * @param $newMethod
     * @return bool
     * @throws TrustoceanException
     */
    public function callChangeDcvMethod($domainName, $newMethod){
        if($domainName == ""){
            throw new TrustoceanException('Required param(domain) cannot be empty.', 25015);
        }
        if($newMethod == ""){
            throw new TrustoceanException('Required param(method) cannot be empty.', 25015);
        }
        $requestParams = [
            'domain'        =>  $domainName,
            'method'        =>  $newMethod,
            'trustocean_id' =>  $this->order_id
        ];

        $this->callTrustOceanAPI('changeDCVMethod', $requestParams);

        return TRUE;
    }

    /**
     * @param $domainName
     * @return bool
     * @throws TrustoceanException
     */
    public function callRemoveDomainName($domainName){
        if($domainName == ""){
            throw new TrustoceanException('Required param(domain) cannot be empty.', 25015);
        }
        $requestParams = [
            'domain'    =>  $domainName,
            'trustocean_id' =>  $this->order_id
        ];
        $this->callTrustOceanAPI('removeSanDomain', $requestParams);

        return TRUE;
    }

    /**
     * @return bool
     * @throws TrustoceanException
     */
    public function callRetryDcvProcess(){
        $requestParam = [
            'trustocean_id' =>  $this->order_id
        ];
        $this->callTrustOceanAPI('reTryDcvEmailOrDCVCheck', $requestParam);

        return TRUE;
    }

    /**
     * @return bool
     * @throws TrustoceanException
     */
    public function callResendDcvEmails(){
        $requestParam = [
            'trustocean_id' =>  $this->order_id
        ];
        $this->callTrustOceanAPI('reTryDcvEmailOrDCVCheck', $requestParam);

        return TRUE;
    }

    /**
     * @return $this
     * @throws TrustoceanException
     */
    public function callGetDcvDetails(){
        $requestParam = [
            'trustocean_id' =>  $this->order_id
        ];
        $callResult = $this->callTrustOceanAPI('getDomainValidationStatus', $requestParam);
        $this->updateSelfParams($callResult, [
            'dcv_info'  =>  'dcvinfo'
        ]);

        return $this;
    }

    /**
     * @param $revocationReason
     * @return $this
     * @throws TrustoceanException
     */
    public function callRevokeCertificate($revocationReason){
        if($revocationReason == ""){
            throw new TrustoceanException('Required param(revocationReason) cannot be empty.', 25015);
        }
        $requestParam = [
            'trustocean_id'     =>  $this->order_id,
            'revocationReason'  =>  $revocationReason
        ];
        $callResult = $this->callTrustOceanAPI('revokeSSL', $requestParam);
        $this->order_status = 'revoked';
        return $this;
    }

    /**
     * @return $this
     * @throws TrustoceanException
     */
    public function callCancelAndRevokeCertificate(){
        $requestParam = [
            'trustocean_id'     =>  $this->order_id
        ];
        $this->callTrustOceanAPI('cancelAndRefund', $requestParam);
        $this->refund_status = 'processing';
        return $this;
    }

    /**
     * @param array $updateKeys
     * @param array $callResult
     */
    private function updateSelfParams($callResult, $updateKeys){
        foreach ($updateKeys as $key => $keyName){
            $this->$key = $callResult[$keyName];
        }
    }

    /**
     * @param $requiredParamKeys
     * @throws TrustoceanException
     */
    private function checkRequiredParams($requiredParamKeys){
        foreach ($requiredParamKeys as $keyName){
            if($this->$keyName === NULL){
                throw new TrustoceanException('Required param('.$keyName.') cannot be empty.', 25015);
            }
        }
    }

    /**
     * @param $requiredParamKeys
     * @return array
     */
    private function prepareRequestParams($requiredParamKeys){
        $requestParams = [];
        foreach ($requiredParamKeys as $keyName){
            $requestParams[$keyName] = $this->$keyName;
        }
        return $requestParams;
    }
    /**
     * @param string $method
     * @param array $params
     * @return array
     * @throws TrustoceanException
     */
    protected function callTrustOceanAPI($method,$params){
        # Partner Login Details
        $params['username'] = $this->username;
        $params['password'] =  $this->password;
        $postVars = http_build_query($params);

        $apiURL = "https://api.trustocean.com/ssl/v3/$method"; // API Endpoint located in Beijing CN
        // $apiURL = "https://sapi.trustocean.com/ssl/v3/$method"; // API Endpoint located in London UK

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
                throw new TrustoceanException($result['message'], 25000);
            }else{
                return $result;
            }
        }else{
            throw new TrustoceanException('CURL error found, please check your network and api params, try it again or contact us for help.', 25014);
        }
    }

}