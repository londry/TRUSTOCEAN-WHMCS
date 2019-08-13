<?php
namespace Londry\TrustOceanSSL\model;
use blobfolio\domain\domain;
use Londry\TrustOceanSSL\TrustoceanException;

class Csr{


    /**
     * @var string
     * Validated CSR content
     */
    protected $validCsrContent;

    public function getValidaCsrContent(){
        return $this->validCsrContent;
    }

    protected $commonName;

    /**
     * @param $commonName
     * @throws TrustoceanException
     * Validate and set commonName
     */
    private function setCommonName($commonName){
        $commonName = new domain($commonName);
        if(!$commonName->is_ascii()){
            throw new TrustoceanException('Invalid CommonName of your CSR, please convert it to ASCII format', 25004);
        }
        if($commonName->is_ip()){
            throw new TrustoceanException('Invalid CommonName of your CSR, please choose a valid domain name except an IP address.', 25005);
        }
        if(trim($commonName) !== $commonName){
            throw new TrustoceanException('Invalid CommonName of your CSR, please remove any spaces.', 25006);
        }
        if(trim($commonName) == ""){
            throw new TrustoceanException('Invalid CommonName of your CSR.', 25007);
        }
        $this->commonName = $commonName;
    }

    /**
     * @return string
     */
    public function getCommonName(){
        return $this->commonName;
    }

    protected $signatureAlgorithm;

    /**
     * @var string
     * The Contact email address of CSR [Certificate Entity]
     */
    protected $emailAddress;

    private function setEmailAddress($emailAddress){
        $this->emailAddress = $emailAddress;
    }

    public function getEmailAddress(){
        return $this->emailAddress;
    }

    /**
     * @var string
     * The Organization Name of CSR [Certificate Entity]
     */
    protected $organizationName;

    private function setOrganizationName($organizationName){
        $this->organizationName = $organizationName;
    }

    public function getOrganizationName(){
        return $this->organizationName;
    }

    /**
     * @var string
     * The Organizational Unit Name of CSR [Certificate Entity]
     */
    protected $organizationalUnitName;

    private function setOrganizationalUnitName($organizationalUnitName){
        $this->organizationalUnitName = $organizationalUnitName;
    }
    public function getOrganizatinalUnitName(){
        return $this->organizationalUnitName;
    }

    /**
     * @var string
     * The International Country Code of CSR [Certificate Entity]
     */
    protected $countryCode;

    private function setCountryCode($countryCode){
        $this->countryCode = $countryCode;
    }
    public function getCountryCode(){
        return $this->countryCode;
    }

    /**
     * Csr constructor.
     * @param $csrPemCode
     * @throws TrustoceanException
     */
    public function __construct($csrPemCode)
    {
        $fields = openssl_csr_get_subject($csrPemCode, TRUE);
        if($fields === FALSE){
            throw new TrustoceanException('Invalid PEM Format CSR Code, Cannot decode your CSR code.', 25003);
        }
        $this->setCommonName($fields['CN']);
        $this->setCountryCode($fields['C']);
        $this->setEmailAddress($fields['emailAddress']);
        $this->setOrganizationName($fields['O']);
        $this->setOrganizationalUnitName($fields['OU']);
        $this->validCsrContent = trim($csrPemCode);
    }

    /**
     * @return bool
     * Is the CommonName is a Wildcard Domain Name?
     */
    public function isWildcardCommonName(){
        return strstr($this->commonName, '*') !== FALSE;
    }

    /**
     * @return bool
     * Is the EmailAddress is empty?
     */
    public function isEmptyEmailAddress(){
        return $this->emailAddress == "";
    }
}