<?php
namespace WHMCS\Module\Addon\TrustOceanSSLAdmin\Controller;
use WHMCS\Database\Capsule;

class AdminController
{
    public function index($vars){
        $smarty = new \Smarty();
        $smarty->display(__DIR__."/../../template/adminarea.tpl");
    }

    /**
     * 查询用户证书订单
     * @param $vars
     */
    public function getCertificateList($vars){
        // 判断是否为搜索证书记录
        if(isset($_REQUEST['search']) && $_REQUEST['search']['value'] != ""){
            $this->searchCertificateList($vars);
        }

        // 默认的分页查询参数 起始页
        if(!$_REQUEST['start']){
            $pageStart = 0;
        }else{
            $pageStart = (int)$_REQUEST['start'];
        }

        // 每页长度
        if(!$_REQUEST['length']){
            $pageLength = 10;
        }else{
            $pageLength = (int)$_REQUEST['length'];
            if($pageLength > 50){
                $pageLength = 50;
            }
        }

        // 根据查询类型选择状态条件
        switch ($_REQUEST['status']) {
            case 'configuration':
                $whereIn = ['configuration', 'enroll_dcv'];
                break;
            case 'processing':
                $whereIn = ['enroll_caprocessing', 'enroll_organization_pre'];
                break;
            case 'expired':
                $whereIn = ['expired', 'cancelled'];
                break;
            default:
                $whereIn = ['configuration', 'enroll_dcv','expired', 'cancelled','enroll_caprocessing', 'issued_active', 'enroll_organization','enroll_organization_pre'];
        }

        $allCertificates = Capsule::table('tbltrustocean_certificate')
            ->whereIn('status', $whereIn)->forPage($pageStart/$pageLength+1, $pageLength)
            ->orderBy('id','desc')
            ->get();

        $recordsCount = Capsule::table('tbltrustocean_certificate')
            ->whereIn('status', $whereIn)->count('id');


        header("Cache-Control:no-cache, must-revalidate");
        header('Content-type: application/json');

        $sslArray = $this->formatOutputAndClean($allCertificates);
        exit(json_encode([
            'draw'=>$_REQUEST['draw'],
            'recordsTotal'=>count($sslArray),
            'data'=>$sslArray,
            'recordsFiltered'=>$recordsCount,
        ]));
    }

    /**
     * 根据条件查询 Datatable JSON Object
     * @param $vars
     */
    public function searchCertificateList($vars){

        // 默认的分页查询参数 起始页
        if(!$_REQUEST['start']){
            $pageStart = 1;
        }else{
            $pageStart = (int)$_REQUEST['start'];
        }

        // 每页长度
        if(!$_REQUEST['length']){
            $pageLength = 10;
        }else{
            $pageLength = (int)$_REQUEST['length'];
            if($pageLength > 50){
                $pageLength = 50;
            }
        }

        $columKey = "domains";
        $searchValue = trim($_REQUEST['search']['value']);

        $allCertificates = Capsule::table('tbltrustocean_certificate')
            ->where($columKey, 'LIKE',"%".$searchValue."%")
            ->forPage($pageStart/$pageLength+1, $pageLength)
            ->orderBy('id','desc')
            ->get();
        $countCheck = Capsule::table('tbltrustocean_certificate')
            ->where($columKey, 'LIKE',"%".$searchValue."%")
            ->count('id');

        $recordsTotal = $countCheck;

        header("Cache-Control:no-cache, must-revalidate");
        header('Content-type: application/json');

        $sslArray = $this->formatOutputAndClean($allCertificates);
        exit(json_encode([
            'draw'=>$_REQUEST['draw'],
            'recordsTotal'=>count($sslArray),
            'data'=>$sslArray,
            'recordsFiltered'=>$recordsTotal,
        ]));
    }

    private function formatOutputAndClean($certificateObject){
        $sslArray = json_decode(json_encode($certificateObject, 1, 10), 1, 10);
        foreach ($sslArray as $key=>$sslitem){
            unset($sslArray[$key]['key_code']);
            unset($sslArray[$key]['trustocean_id']);
            unset($sslArray[$key]['ca_code']);
            unset($sslArray[$key]['cert_code']);
            unset($sslArray[$key]['id']);
            unset($sslArray[$key]['caproductid']);
            unset($sslArray[$key]['caid']);
            unset($sslArray[$key]['csr_code']);
            unset($sslArray[$key]['unique_id']);
            unset($sslArray[$key]['vendor_id']);

            $sslArray[$key]['expire_at'] = '---- --:--:--';
            $sslArray[$key]['domains'] = json_decode($sslArray[$key]['domains'], 1);
            switch ($sslitem['class']){
                case 'dv':
                    $classItem = '<span class="tag-w3">域名(DV)</span>';
                    break;
                case 'ov':
                    $classItem = '<span class="tag-w2">企业(OV)</span>';
                    break;
                case 'ev':
                    $classItem = '<span class="tag-w4">企业(EV)</span>';
                    break;
                default:
                    $classItem = '<span class="tag-w3">域名(DV)</span>';

            }

            $sslArray[$key]['class'] = $classItem;

            switch ($sslitem['status']){
                case 'enroll_caprocessing':
                    $sslStatus = '签发中';
                    break;
                case 'enroll_domains':
                    $sslStatus = '待提交域名';
                    break;
                case 'configuration':
                    $sslStatus = '待提交CSR';
                    break;
                case 'enroll_organization':
                    $sslStatus = '填写组织信息';
                    break;
                case 'enroll_organization_pre':
                    $sslStatus = '组织信息预审';
                    break;
                case 'issued_active':
                    $sslStatus = '已签发';
                    break;
                case 'enroll_dcv':
                    $sslStatus = '待完成域验证';
                    break;
                case 'enroll_ca':
                    $sslStatus = '待签发';
                    break;
                case 'enroll_apierror':
                    $sslStatus = '请提交工单处理';
                    break;
                case 'cancelled':
                    $sslStatus = '已取消';
                    break;
                case 'expired':
                    $sslStatus = '已过期';
                    break;
                case 'submit_hand':
                    $sslStatus = '队列提交中';
                    break;
                case 'dcv_hand':
                    $sslStatus = '队列验证中';
                    break;
                case 'check_hand':
                    $sslStatus = '请提交工单处理';
                    break;
                default:
                    $sslStatus = '签发中';

            }

            $sslArray[$key]['status'] = $sslStatus;

            $sslArray[$key]['name'] = '<div>'.$sslArray[$key]['name'].'</div><span style="font-weight: 500; font-size: 16px;">'.$sslArray[$key]['domains'][0].'</span><div>'.$periodItem.$sanitem.'</div>';

            $sslArray[$key]['manage'] = '<a href="clientsservices.php?userid='.$sslitem['uid'].'&amp;id='.$sslitem['serviceid'].'" target="_blank" class="btn btn-sm btn-primary">管理</a>';



            $domainString = "";
            if(strlen($sslitem['domains']) > 2){
                $domainsArray = json_decode($sslitem['domains'], 1);
                foreach ($domainsArray as $domain){
                    $domainString = $domainString.$domain."\r\n";
                }
                $sslArray[$key]['domain_string'] = '<button class="btn btn-xs btn-info" data-toggle="modal" data-target="#myDomainModal'.md5($sslitem['serviceid']).'">查看域名</button><div class="modal fade" id="myDomainModal'.md5($sslitem['serviceid']).'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        查看证书(#'.$sslitem['serviceid'].')中的域名
                    </h4>
                </div>
                <div class="modal-body">
<pre style="height: 280px;">'.$domainString.'</pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">
                        关闭
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>';
            }else{
                $sslArray[$key]['domain_string'] = '----';
            }

            unset($sslArray[$key]['api_error']);
            unset($sslArray[$key]['certificate_id']);
            unset($sslArray[$key]['admin_info']);
            unset($sslArray[$key]['contact_email']);
            unset($sslArray[$key]['dcv_info']);
            unset($sslArray[$key]['dcv_info']);
            unset($sslArray[$key]['dcvfinished_at']);

            # 丢弃其他的信息
            $diuqi = [
                "dcv_2hour",
                "dcv_5min",
                "dcv_8min",
                "dcv_12hour",
                "dcv_15min",
                "dcv_24hour",
                "dcv_30min",
                "dcv_72hour",
                "dcvredo_clicked",
                "domain_count",
                "domains",
                "errorreset_status",
                "expiration1_sent_at",
                "expiration7_sent_at",
                "expiration30_sent_at",
                "expiration90_sent_at",
                "expired_sent_at",
                "fetchdcv_clicked",
                "is_apiorder",
                "issued_at",
                "org_info",
                "org_submitted",
                "orgverified_at",
                "paidcertificate_delivery_time",
                "paidcertificate_status",
                "partner_order_id",
                "period",
                "reissue",
                "reissued_at",
                "renew",
                "renewal_unsubscribe",
                "tech_info"
            ];
            foreach ($diuqi as $keyname){
                unset($sslArray[$key][$keyname]);
            }


        }

        return $sslArray;
    }
}