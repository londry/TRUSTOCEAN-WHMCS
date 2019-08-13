<?php
namespace Londry\TrustOceanSSL\model;

use blobfolio\domain\domain;
use Londry\TrustOceanSSL\TrustoceanException;

class Order{

    /**
     * @var string
     * The Account Email Address of the Reseller,
     * to register an account please access https://www.trustocean.com ,
     * and submit an ticket or contact online-staff to upgrade your account to
     * a Reseller Account, then you can access this API.
     */
    protected $username;

    /**
     * @var string
     * The API Token of your Reseller Account,
     * you can generate one new API Token from
     * https://console.trustocean.com/partner/api-setting
     */
    protected $password;

    /**
     * @var int
     * The Product ID of products provide by TrustOcean.
     */
    protected $pid;

    /**
     * @var Product
     * The instanct of product.
     */
    protected $product;

    /**
     * @param $CertificateType
     * Set the product pid for Api Order.
     */
    public function setCertificateType($CertificateType){
        $this->product = new Product($CertificateType);
        $this->pid = $this->product->getPid();
    }

    protected $period;

    /**
     * @param $period
     * @throws TrustoceanException
     */
    public function setCertificatePeriod($period){
        if(!in_array($period, $this->product->getAvaliablePeriod())){
            throw new TrustoceanException('It\'s not a valid Period('.$period.') option for Product '.$this->product->getName(), 25001);
        }
        $this->period = $period;
    }

    protected $csr_code = NULL;

    /**
     * @param Csr $csrCode
     * @throws TrustoceanException
     */
    public function setCsrCode(Csr $csrCode){
        if($csrCode->isWildcardCommonName() && !$this->product->isWildcardProduct()){
            throw new TrustoceanException('Invalid CommonName of your CSR, this product not allowed protect wildcard domain.', 25008);
        }
        if(!$csrCode->isEmptyEmailAddress()){
            $this->contact_email = $csrCode->getEmailAddress();
        }
        $this->csr_code = $csrCode->getValidaCsrContent();
    }

    /**
     * @return null|string
     */
    public function getCsrCode(){
        return $this->csr_code;
    }

    /**
     * @var null|string
     */
    protected $ca_code = NULL;

    /**
     * @return null|string
     */
    public function getCaCode(){
        return $this->ca_code;
    }

    /**
     * @var null|string
     */
    protected $cert_code = NULL;

    /**
     * @return null|string
     */
    public function getCertCode(){
        return $this->cert_code;
    }
    protected $contact_email = NULL;

    /**
     * @param string $contactEmail
     */
    public function setContactEmail($contactEmail){
        $this->contact_email = $contactEmail;
    }

    /**
     * @return null|string
     */
    public function getContactEmail(){
        return $this->contact_email;
    }

    /**
     * @var string
     * The dcv_method string must much the domains string, and use the same order.
     * The available dcv_method option includes:
     * `dns`,`http`,`https`,`admin@domain.tld`,`postmaster@domain.tld`,`webmaster@domain.tld`,
     * `hostmaster@domain.tld`,`administrator@domain.tld`
     * <you need replace the `domain.tld` to your corresponding domain name you choose dcv_method for.>
     */
    protected $dcv_method = '';

    /**
     * @param array $dcvMethodArray
     */
    public function setDcvMethod($dcvMethodArray){
        $this->dcv_method = implode(',', $dcvMethodArray);
    }

    /**
     * @return array
     */
    public function getDcvMethod(){
        return explode(',', $this->dcv_method);
    }

    /**
     * @var string
     * The unique_id string is used to mark the uniqueness of the order, must be provided every time when you place
     * new order/reissue order/rekey order/renew order`, must not be in the existing record conflict.
     */
    protected $unique_id = '';

    /**
     * @param string $uniqueId
     */
    public function setUniqueId($uniqueId){
        $this->unique_id = $uniqueId;
    }

    /**
     * @return string
     */
    public function getUniqueId(){
        return $this->unique_id;
    }

    /**
     * @var string
     * The domains string only be provided when you place a MultiDomain SSL order.
     * Otherwise, there is no need to set this field.
     * When you place a MultiDomain SSL order, the domain name provided by CSR code will be ignored.
     * For SingleDomain SSL order, we only use the domain name provided by your CSR code.
     */
    protected $domains = '';

    /**
     * @param array $domainArray
     * @throws TrustoceanException
     */
    public function setDomains($domainArray){
        foreach ($domainArray as $key => $domainName){
            $theDomain = new domain($domainName);
            if(!$theDomain->is_ascii()){
                throw new TrustoceanException('Invalid DomainName('.$domainName.'), please convert it to ASCII format', 25009);
            }
            if($theDomain->is_ip() && $this->product->isSupportIpAddress() === FALSE){
                throw new TrustoceanException('Invalid DomainName('.$domainName.'), the product you choose does not support an IP address.', 25010);
            }
            if(strpos($domainName, '*.') !== FALSE && $this->product->isWildcardProduct() === FALSE){
                throw new TrustoceanException('Invalid DomainName('.$domainName.'), the product you choose does not support wildcard domain name.', 250014);
            }
            if(trim($domainName) !== $domainName){
                throw new TrustoceanException('Invalid DomainName('.$domainName.'), please remove any spaces.', 25011);
            }
            if(trim($theDomain) == ""){
                throw new TrustoceanException('Invalid DomainName('.$domainName.').', 25012);
            }
        }

        if(count($domainArray) != count(array_unique($domainArray))){
            throw new TrustoceanException('Duplicate domain name found, please check your domain names.', 25013);
        }

        $this->domains = implode(',', $domainArray);
    }

    /**
     * @return array
     */
    public function getDomains(){
        return explode(',', $this->domains);
    }

    protected $renew = FALSE;

    /**
     * @param bool $isRenew
     */
    public function setRenew($isRenew = FALSE){
        if($isRenew == FALSE){
            $this->renew = 'no';
        }else{
            $this->renew = 'yes';
        }
    }

    protected $organization_name = NULL;

    /**
     * @param string $organizationName
     */
    public function setOrganizationName($organizationName){
        $this->organization_name = $organizationName;
    }

    /**
     * @return string|NULL
     */
    public function getOrganizationName(){
        return $this->organization_name;
    }

    protected $organizationalUnitName = NULL;

    /**
     * @param string $organizationalUnitName
     */
    public function setOrganizationalUnitName($organizationalUnitName){
        $this->organizationalUnitName = $organizationalUnitName;
    }

    /**
     * @return string|null
     */
    public function getOrganizationalUnitName(){
        return $this->organizationalUnitName;
    }

    protected $registered_address_line1 = NULL;

    /**
     * @param string $registered_address_line1
     */
    public function setRegisteredAddressLine1($registered_address_line1){
        $this->registered_address_line1 = $registered_address_line1;
    }

    /**
     * @return string|null
     */
    public function getRegisteredAddressLine1(){
        return $this->registered_address_line1;
    }

    protected $registered_no = NULL;

    /**
     * @param string $registered_no
     */
    public function setRegisteredNo($registered_no){
        $this->registered_no = $registered_no;
    }

    /**
     * @return null|string
     */
    public function getRegisteredNo(){
        return $this->registered_no;
    }

    protected $country = NULL;

    /**
     * @param string $country
     */
    public function setCountry($country){
        $this->country = $country;
    }

    /**
     * @return null|string
     */
    public function getCountry(){
        return $this->country;
    }

    protected $state = NULL;

    /**
     * @param string $state
     */
    public function setState($state){
        $this->state = $state;
    }

    /**
     * @return null|string
     */
    public function getState(){
        return $this->state;
    }

    protected $city = NULL;

    /**
     * @param string $city
     */
    public function setCity($city){
        $this->city = $city;
    }

    /**
     * @return null|string
     */
    public function getCity(){
        return $this->city;
    }

    protected $postal_code = NULL;

    /**
     * @param string $postalCode
     */
    public function setPostalCode($postalCode){
        $this->postal_code = $postalCode;
    }

    /**
     * @return null|string
     */
    public function getPostalCode(){
        return $this->postal_code;
    }

    protected $organization_phone = NULL;

    /**
     * @param string $organizationPhone
     */
    public function setOrganizationPhone($organizationPhone){
        $this->organization_phone = $organizationPhone;
    }

    /**
     * @return null|string
     */
    public function getOrganizationPhone(){
        return $this->organization_phone;
    }

    protected $date_of_incorporation = NULL;

    /**
     * @param string $dateOfIncorporation
     */
    public function setDateOfIncorporation($dateOfIncorporation){
        $this->date_of_incorporation = $dateOfIncorporation;
    }

    /**
     * @return null|string
     */
    public function getDateOfIncorporation(){
        return $this->date_of_incorporation;
    }

    protected $contact_name = NULL;

    /**
     * @param string $contactName
     */
    public function setContactName($contactName){
        $this->contact_name = $contactName;
    }

    /**
     * @return null|string
     */
    public function getContactName(){
        return $this->contact_name;
    }

    protected $contact_title = NULL;

    /**
     * @param string $contactTitle
     */
    public function setContactTitle($contactTitle){
        $this->contact_title = $contactTitle;
    }

    /**
     * @return null|string
     */
    public function getContactTitle(){
        return $this->contact_title;
    }

    protected $contact_phone = NULL;

    /**
     * @param string $contactPhone
     */
    public function setContactPhone($contactPhone){
        $this->contact_phone = $contactPhone;
    }

    /**
     * @return null|string
     */
    public function getContactPhone(){
        return $this->contact_phone;
    }

    /**
     * @var array
     * The details of Domain Validation Process
     */
    protected $dcv_info = [];

    /**
     * @return array
     */
    public function getDcvInfo(){
        return $this->dcv_info;
    }

    /**
     * @var null|string
     * The status of this order.
     */
    protected $order_status = NULL;

    /**
     * @return null|string
     */
    public function getOrderStatus(){
        return $this->order_status;
    }

    /**
     * @var null|string
     * When this order created on TrustOcean API system.
     */
    protected $created_at = NULL;

    /**
     * @return null|string
     */
    public function getCreatedAt(){
        return $this->created_at;
    }

    /**
     * @var null|integer
     * Order ID on TrustOcean API system.
     */
    protected $order_id = NULL;

    /**
     * @return null|integer
     */
    public function getOrderId(){
        return $this->order_id;
    }

    /**
     * @var null|string
     */
    protected $refund_status = NULL;

    /**
     * @return null|string
     */
    public function getRefundStatus(){
        return $this->refund_status;
    }
}

