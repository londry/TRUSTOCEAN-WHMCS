<?php
/**
 * File: Product.php
 * Author: Jason Long
 * Tim: 2019-06-24
 */
namespace Londry\TrustOceanSSL\model;
use Londry\TrustOceanSSL\TrustoceanException;

/**
 * Class Product
 * @package Londry\TrustOceanSSLCertificate\model
 * Product Configuration Loader
 */
class Product{
    /**
     * @var array
     * The Config data for All TrustOcean SSL Product.
     */
    private $product_list = [
        'TrustOcean Encryption365 SSL'                   => [
            'id'       => 1,
            'type'     => 'DV',
            'coverage' => ['san' => ['IP', 'FQDN domain', 'Wildcard domain']],
            'period'   => ['quarterly']
        ],
        'TrustOcean DV Single Domain Secure SSL'         => [
            'id'       => 46,
            'type'     => 'DV',
            'coverage' => ['single' => ['FQDN domain']],
            'period'   => ['annually', 'biennially']
        ],
        'TrustOcean DV Multi Domain Secure SSL'         => [
            'id'       => 47,
            'type'     => 'DV',
            'coverage' => ['san' => ['FQDN domain']],
            'period'   => ['annually', 'biennially']
        ],
        'TrustOcean DV Wildcard Domain Secure SSL'       => [
            'id'       => 48,
            'type'     => 'DV',
            'coverage' => ['single' => ['Wildcard']],
            'period'   => ['annually', 'biennially']
        ],
        'TrustOcean DV Multi Wildcard Domain Secure SSL' => [
            'id'       => 49,
            'type'     => 'DV',
            'coverage' => ['san' => ['FQDN domain', 'Wildcard']],
            'period'   => ['annually', 'biennially']
        ],
        'TrustOcean DV Public IP Secure SSL'             => [
            'id'       => 50,
            'type'     => 'DV',
            'coverage' => ['san' => ['IP', 'FQDN domain']],
            'period'   => ['annually', 'biennially']
        ],
        'Comodo DV SSL Certificate'                      => [
            'id'       => 51,
            'type'     => 'DV',
            'coverage' => ['single' => ['FQDN domain']],
            'period'   => ['annually', 'biennially']
        ],
        'Comodo DV SSL Wildcard Certificate'             => [
            'id'       => 52,
            'type'     => 'DV',
            'coverage' => ['single' => ['Wildcard']],
            'period'   => ['annually', 'biennially']
        ],
        'Comodo DV SSL UCC Certificate'                  => [
            'id'       => 53,
            'type'     => 'DV',
            'coverage' => ['single' => ['FQDN domain']],
            'period'   => ['annually', 'biennially']
        ],
        'Comodo DV SSL UCC Wildcard Certificate'         => [
            'id'       => 54,
            'type'     => 'DV',
            'coverage' => ['san' => ['FQDN domain', 'Wildcard']],
            'period'   => ['annually', 'biennially']
        ],
        'Comodo DV Positive Single Domain SSL'           => [
            'id'       => 55,
            'type'     => 'DV',
            'coverage' => ['single' => ['FQDN domain']],
            'period'   => ['annually', 'biennially']
        ],
        'Comodo DV Positive Multi Domain SSL'            => [
            'id'       => 56,
            'type'     => 'DV',
            'coverage' => ['san' => ['FQDN domain']],
            'period'   => ['annually', 'biennially']
        ],
        'Comodo DV Positive Wildcard Domain SSL'         => [
            'id'       => 57,
            'type'     => 'DV',
            'coverage' => ['single' => ['Wildcard']],
            'period'   => ['annually', 'biennially']
        ],
        'Comodo DV Positive Multi Wildcard Domain SSL'   => [
            'id'       => 58,
            'type'     => 'DV',
            'coverage' => ['san' => ['FQDN domain', 'Wildcard']],
            'period'   => ['annually', 'biennially']
        ],
        'Comodo OV Instant SSL'                          => [
            'id'       => 59,
            'type'     => 'OV',
            'coverage' => ['single' => ['FQDN domain']],
            'period'   => ['annually', 'biennially']
        ],
        'Comodo OV UCC SSL'                              => [
            'id'       => 60,
            'type'     => 'OV',
            'coverage' => ['san' => ['FQDN domain']],
            'period'   => ['annually', 'biennially']
        ],
        'Comodo OV Premium Wildcard SSL'                 => [
            'id'       => 61,
            'type'     => 'OV',
            'coverage' => ['single' => ['Wildcard']],
            'period'   => ['annually', 'biennially']
        ],
        'Comodo OV UCC Wildcard SSL'                     => [
            'id'       => 62,
            'type'     => 'OV',
            'coverage' => ['san' => ['FQDN domain', 'Wildcard']],
            'period'   => ['annually', 'biennially']
        ],
        'Comodo EV SSL'                                  => [
            'id'       => 63,
            'type'     => 'EV',
            'coverage' => ['single' => ['FQDN domain']],
            'period'   => ['annually', 'biennially']
        ],
        'Comodo EV Multi Domain SSL'                     => [
            'id'       => 64,
            'type'     => 'EV',
            'coverage' => ['san' => ['FQDN domain']],
            'period'   => ['annually', 'biennially']
        ],
        'Comodo EV Positive Single Domain SSL'           => [
            'id'       => 65,
            'type'     => 'EV',
            'coverage' => ['single' => ['FQDN domain']],
            'period'   => ['annually', 'biennially']
        ],
        'Comodo EV Positive Multi Domain SSL'            => [
            'id'       => 66,
            'type'     => 'EV',
            'coverage' => ['san' => ['FQDN domain']],
            'period'   => ['annually', 'biennially']
        ],
    ];

    protected $productName;

    protected $productConfig;

    /**
     * Product constructor.
     * @param $CertificateType
     * @throws TrustoceanException
     * Query and set Product configuration data.
     */
    public function __construct($CertificateType)
    {
        if(!isset($this->product_list[$CertificateType])){
            throw new TrustoceanException('Undefined product type key: '.$CertificateType, 25002);
        }
        $this->productConfig = $this->product_list[$CertificateType];
        $this->productName = $CertificateType;
    }

    /**
     * @return bool
     * What's the validation class of the product,
     * both EV and OV SSL need validation Organization Information.
     */
    public function isOrganizationProduct(){
        return ($this->productConfig['type'] === "EV" || $this->productConfig['type'] === "OV");
    }

    /**
     * @return bool
     * Is the product support wildcard domains?
     */
    public function isWildcardProduct(){
        return ((isset($this->productConfig['coverage']['san']) && in_array("Wildcard", $this->productConfig['coverage']['san'])) || in_array("Wildcard", $this->productConfig['coverage']['single']));
    }

    /**
     * @return string
     * Query the name of the product.
     */
    public function getName(){
        return $this->productName;
    }

    /**
     * @return bool
     * Is the product support protect multi domains ?
     */
    public function isMultiDomainProduct(){
        return isset($this->productConfig['coverage']['san']);
    }

    /**
     * @return bool
     * Is the product support protect Public IP Address ?
     */
    public function isSupportIpAddress(){
        return (isset($this->productConfig['coverage']['san']) && in_array("IP", $this->productConfig['coverage']['san']));
    }

    /**
     * @return string
     * Check the Min Period supported by this product.
     */
    public function getMinPeriod(){
        return $this->productConfig['period'][0];
    }

    /**
     * @return string
     * Check the Max Period supported by this product.
     */
    public function getMaxPeriod(){
        return $this->productConfig['period'][-1];
    }

    /**
     * @return array
     * Check the Supported Periods of this product.
     */
    public function getAvaliablePeriod(){
        return $this->productConfig['period'];
    }

    /**
     * @return int
     * Get the Pid of this product.
     */
    public function getPid(){
        return $this->productConfig['id'];
    }
}