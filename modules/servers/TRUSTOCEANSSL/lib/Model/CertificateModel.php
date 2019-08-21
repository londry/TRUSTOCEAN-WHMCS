<?php
namespace WHMCS\Module\Server\TRUSTOCEANSSL\Model;

use WHMCS\Database\Capsule;

class CertificateModel extends DatabaseModel
{

    /**
     * @var string
     */
    private $tableName = 'tbltrustocean_certificate';

    /**
     * @var integer
     */
    protected $id;

    public function getId(){
        return $this->id;
    }

    /**
     * @var integer 用户ID
     */
    protected $uid;

    /**
     * @param $uid integer
     */
    public function setUid($uid){
        $this->uid = $uid;
    }

    /**
     * @return int
     */
    public function getUid(){
        return $this->uid;
    }

    /**
     * @var integer 对应WHMCS的服务ID
     */
    protected $serviceid;

    /**
     * @return int
     */
    public function getServiceid(){
        return $this->serviceid;
    }

    /**
     * @param $serviceid integer
     */
    public function setServiceid($serviceid){
        $this->serviceid = $serviceid;
    }

    /**
     * @var string
     */
    protected $status;

    /**
     * @param $status string
     */
    public function setStatus($status){
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus(){
        return $this->status;
    }

    /**
     * @var string
     */
    protected $class;

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @var int
     */
    protected $multidomain;

    /**
     * @param int $multidomain
     */
    public function setMultidomain($multidomain)
    {
        $this->multidomain = $multidomain;
    }

    /**
     * @return int
     */
    public function getMultidomain()
    {
        return $this->multidomain;
    }

    /**
     * @var string
     */
    protected $csr_code;

    /**
     * @param string $csr_code
     */
    public function setCsrCode($csr_code)
    {
        $this->csr_code = $csr_code;
    }

    /**
     * @return string
     */
    public function getCsrCode()
    {
        return $this->csr_code;
    }

    /**
     * @var string
     */
    protected $cert_code;

    /**
     * @param string $cert_code
     */
    public function setCertCode($cert_code)
    {
        $this->cert_code = $cert_code;
    }

    /**
     * @return string
     */
    public function getCertCode()
    {
        return $this->cert_code;
    }

    /**
     * @var string
     */
    protected $ca_code;

    /**
     * @param string $ca_code
     */
    public function setCaCode($ca_code)
    {
        $this->ca_code = $ca_code;
    }

    /**
     * @return string
     */
    public function getCaCode()
    {
        return $this->ca_code;
    }

    /**
     * @var array
     */
    protected $domains;

    /**
     * @param array $domains
     */
    public function setDomains(array $domains = [])
    {
        $this->domains = $domains;
    }

    /**
     * @return array
     */
    public function getDomains()
    {
        return $this->domains;
    }

    /**
     * @var string
     */
    protected $contact_email;

    /**
     * @param string $contact_email
     */
    public function setContactEmail($contact_email)
    {
        $this->contact_email = $contact_email;
    }

    /**
     * @return string
     */
    public function getContactEmail()
    {
        return $this->contact_email;
    }

    /**
     * @var array
     */
    protected $admin_info;

    /**
     * @param array $admin_info
     */
    public function setAdminInfo(array $admin_info)
    {
        $this->admin_info = $admin_info;
    }

    /**
     * @return array
     */
    public function getAdminInfo()
    {
        return $this->admin_info;
    }

    /**
     * @var array
     */
    protected $org_info;

    /**
     * @param array $org_info
     */
    public function setOrgInfo(array $org_info)
    {
        $this->org_info = $org_info;
    }

    /**
     * @return array
     */
    public function getOrgInfo()
    {
        return $this->org_info;
    }

    /**
     * @var array
     */
    protected $dcv_info;

    /**
     * @param array $dcv_info
     */
    public function setDcvInfo(array $dcv_info)
    {
        $this->dcv_info = $dcv_info;
    }

    /**
     * @return array
     */
    public function getDcvInfo()
    {
        return $this->dcv_info;
    }

    /**
     * @var string
     */
    protected $name;

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @var string
     */
    protected $vendor_id;

    /**
     * @param string $vendor_id
     */
    public function setVendorId($vendor_id)
    {
        $this->vendor_id = $vendor_id;
    }

    /**
     * @return string
     */
    public function getVendorId()
    {
        return $this->vendor_id;
    }

    /**
     * @var string
     */
    protected $submitted_at;

    /**
     * @param string $submitted_at
     */
    public function setSubmittedAt($submitted_at)
    {
        $this->submitted_at = $submitted_at;
    }

    /**
     * @return string
     */
    public function getSubmittedAt()
    {
        return $this->submitted_at;
    }

    /**
     * @var string
     */
    protected $paidcertificate_status;

    /**
     * @param string $paidcertificate_status
     */
    public function setPaidcertificateStatus($paidcertificate_status)
    {
        $this->paidcertificate_status = $paidcertificate_status;
    }

    /**
     * @return string
     */
    public function getPaidcertificateStatus()
    {
        return $this->paidcertificate_status;
    }

    /**
     * @var string
     */
    protected $unique_id;

    /**
     * @param string $unique_id
     */
    public function setUniqueId($unique_id)
    {
        $this->unique_id = $unique_id;
    }

    /**
     * @return string
     */
    public function getUniqueId()
    {
        return $this->unique_id;
    }

    /**
     * @var int
     */
    protected $reissue;

    /**
     * @param int $reissue
     */
    public function setReissue($reissue)
    {
        $this->reissue = $reissue;
    }

    /**
     * @return int
     */
    public function getReissue()
    {
        return $this->reissue;
    }

    /**
     * @var int
     */
    protected $renew;

    /**
     * @param int $renew
     */
    public function setRenew($renew)
    {
        $this->renew = $renew;
    }

    /**
     * @return int
     */
    public function getRenew()
    {
        return $this->renew;
    }

    /**
     * @var int
     */
    protected $is_requested_refund;

    /**
     * @param int $is_requested_refund
     */
    public function setIsRequestedRefund($is_requested_refund)
    {
        $this->is_requested_refund = $is_requested_refund;
    }

    /**
     * @return int
     */
    public function getisRequestedRefund()
    {
        return $this->is_requested_refund;
    }

    /**
     * @var string
     */
    protected $refund_status;

    /**
     * @param string $refund_status
     */
    public function setRefundStatus($refund_status)
    {
        $this->refund_status = $refund_status;
    }

    /**
     * @return string
     */
    public function getRefundStatus()
    {
        return $this->refund_status;
    }

    /**
     * @var string
     */
    protected $certificate_id;

    /**
     * @param string $certificate_id
     */
    public function setCertificateId($certificate_id)
    {
        $this->certificate_id = $certificate_id;
    }

    /**
     * @return string
     */
    public function getCertificateId()
    {
        return $this->certificate_id;
    }

    /**
     * @var int
     */
    protected $trustocean_id;

    /**
     * @param int $trustocean_id
     */
    public function setTrustoceanId($trustocean_id)
    {
        $this->trustocean_id = $trustocean_id;
    }

    /**
     * @return int
     */
    public function getTrustoceanId()
    {
        return $this->trustocean_id;
    }

    /**
     * @var string
     */
    protected $period;

    /**
     * @param string $period
     */
    public function setPeriod($period)
    {
        $this->period = $period;
    }

    /**
     * @return string
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @var string
     */
    protected $issued_at;

    /**
     * @param string $issued_at
     */
    public function setIssuedAt($issued_at)
    {
        $this->issued_at = $issued_at;
    }

    /**
     * @return string
     */
    public function getIssuedAt()
    {
        return $this->issued_at;
    }

    /**
     * @var string
     */
    protected $reissued_at;

    /**
     * @param string $reissued_at
     */
    public function setReissuedAt($reissued_at)
    {
        $this->reissued_at = $reissued_at;
    }

    /**
     * @return string
     */
    public function getReissuedAt()
    {
        return $this->reissued_at;
    }

    /**
     * @var string
     */
    protected $created_at;

    /**
     * @param string $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @var string
     */
    protected $dcvredo_clicked;

    /**
     * @param string $dcvredo_clicked
     */
    public function setDcvredoClicked($dcvredo_clicked)
    {
        $this->dcvredo_clicked = $dcvredo_clicked;
    }

    /**
     * @return string
     */
    public function getDcvredoClicked()
    {
        return $this->dcvredo_clicked;
    }

    /**
     * @var string
     */
    protected $checkcert_cliecked;

    /**
     * @param string $checkcert_cliecked
     */
    public function setCheckcertCliecked($checkcert_cliecked)
    {
        $this->checkcert_cliecked = $checkcert_cliecked;
    }

    /**
     * @return string
     */
    public function getCheckcertCliecked()
    {
        return $this->checkcert_cliecked;
    }

    /**
     * @var string
     */
    protected $expiration90_sent_at;

    /**
     * @param string $expiration90_sent_at
     */
    public function setExpiration90SentAt($expiration90_sent_at)
    {
        $this->expiration90_sent_at = $expiration90_sent_at;
    }

    /**
     * @return string
     */
    public function getExpiration90SentAt()
    {
        return $this->expiration90_sent_at;
    }

    /**
     * @var string
     */
    protected $expiration30_sent_at;

    /**
     * @param string $expiration30_sent_at
     */
    public function setExpiration30SentAt($expiration30_sent_at)
    {
        $this->expiration30_sent_at = $expiration30_sent_at;
    }

    /**
     * @return string
     */
    public function getExpiration30SentAt()
    {
        return $this->expiration30_sent_at;
    }

    /**
     * @var string
     */
    protected $expiration7_sent_at;

    /**
     * @param string $expiration7_sent_at
     */
    public function setExpiration7SentAt($expiration7_sent_at)
    {
        $this->expiration7_sent_at = $expiration7_sent_at;
    }

    /**
     * @return string
     */
    public function getExpiration7SentAt()
    {
        return $this->expiration7_sent_at;
    }

    /**
     * @var string
     */
    protected $expiration1_sent_at;

    /**
     * @param string $expiration1_sent_at
     */
    public function setExpiration1SentAt($expiration1_sent_at)
    {
        $this->expiration1_sent_at = $expiration1_sent_at;
    }

    /**
     * @return string
     */
    public function getExpiration1SentAt()
    {
        return $this->expiration1_sent_at;
    }

    /**
     * @var string
     */
    protected $expired_sent_at ;

    /**
     * @param string $expired_sent_at
     */
    public function setExpiredSentAt($expired_sent_at)
    {
        $this->expired_sent_at = $expired_sent_at;
    }

    /**
     * @return string
     */
    public function getExpiredSentAt()
    {
        return $this->expired_sent_at;
    }

    function __construct($serviceid = NULL)
    {
        // 从数据库加载
        if($serviceid !== NULL){
            $certificate = Capsule::table($this->tableName)->where('serviceid', $serviceid)->first();

            if($certificate != NULL){
                $this->fillModel($this, $certificate);
            }
        }
    }

    /**
     * 从数据库加载记录到模型中
     * @param $model
     * @param $databaseRecord
     */
    protected function fillModel(CertificateModel $model, $databaseRecord){
        foreach ($databaseRecord as $databaseKey => $value){
            $keyArray = explode('_', $databaseKey);
            $keyName = "";
            foreach ($keyArray as $key){
                $keyName .= ucfirst($key);
            }
            $functionName = "set$keyName";
            if(method_exists($model, $functionName)){
                $reflectionFunction = new \ReflectionMethod($model, $functionName);
                $reflectionFunctionParameter = $reflectionFunction->getParameters();
                if($reflectionFunctionParameter[0]->isArray()){
                    if(gettype($value) === 'string'){
                        $valueDecoded = json_decode(json_decode(json_encode($value, true, 10), true), true);
                        if($valueDecoded === NULL){
                            $valueDecoded = [];
                        }
                        call_user_func_array([$model, $functionName], [$valueDecoded]);
                    }elseif(gettype($value) === 'NULL'){
                        call_user_func_array([$model, $functionName], [[]]);
                    }else{
                        call_user_func_array([$model, $functionName], [[]]);
                    }
                }else{
                    call_user_func_array([$model, $functionName], [$value]);
                }
            }else{

            }
        }
    }

    /**
     * 更新模型到数据库中
     */
    public function flush(){
        $updateParams = [];
        $unspported = [];
        foreach (get_class_vars(get_class($this)) as $name => $value){
            $keyName = $name;
            for ($i = 0; $i < substr_count($keyName, "_"); $i++) {
                $keyName = preg_replace_callback('/_([a-zA-Z]{1})/', function ($matches) {
                    return str_replace($matches[0], strtoupper(str_replace('_', '', $matches[0])), $matches[0]);
                }, $keyName);
            }

            $setterName = "set".ucfirst($keyName);
            $getterName = "get".ucfirst($keyName);

            if(method_exists($this, $setterName)){
                $dataKeyName = preg_replace_callback('/([A-Z]{1})/', function($matches){
                    return '_'.strtolower($matches[0]);
                }, lcfirst(str_replace("set","",$name)));

                $dataValue = $this->$getterName();

                if(gettype($dataValue) === "array"){
                    $dataValue = json_encode($dataValue, true, 10);
                }

                if($dataValue !== NULL){
                    $updateParams[$dataKeyName] = $dataValue;
                }
            }else{
                $unspported[] = $setterName;
            }
        }

        Capsule::table($this->tableName)->where('serviceid', $this->getServiceid())
            ->update($updateParams);
    }
}