<?php


require __DIR__.'/../../autoload.php';
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/../../../init.php';


use WHMCS\Database\Capsule;

echo "=================STARTING DCV CHECK================\r\n";
$services = Capsule::table('tbltrustocean_certificate')->where('status','enroll_dcv')->get();
foreach ($services as $service){
    # TRUSTOCEANSSL_doDcvVerification($service);
}
echo "=================DCV CHECK FINISHED================\r\n";

/**
 * 自动执行域名验证
 * @param $service
 */
function TRUSTOCEANSSL_doDcvVerification($service){
    $tblhosting = Capsule::table('tblhosting')->where('id',$service->serviceid)->first();
    $tblproducts = Capsule::table('tblproducts')->where('id', $tblhosting->packageid)->first();
    $class = $tblproducts->configoption6;

    $dcvinfo = json_decode($service->dcv_info, 1);
    $csrhash = TRUSTOCEANSSL_getCsrHash($service->csr_code);

    $alldomainVerified = true;

    foreach ($dcvinfo as $domain => $info){
        echo "Now do DCV for $domain \r\n";
        if($info['method']  === 'dns' && $info['status']  === 'needverification'){
            $subdomain = $info['subdomain'] != ""?$info['subdomain'].'.':"";
            $dns_host_code = $csrhash['dns']['purehost'].'.'.$subdomain.$info['topdomain'];
            $dns_value_code = $csrhash['dns']['purevalue'].'.'.$service->unique_id.'.COMODOCA.COM';
            if(strtolower(findCnameResult($info['topdomain'], $dns_host_code)) === strtolower($dns_value_code)){
                $dcvinfo[$domain]['status'] = 'verified';
                echo "...success \r\n";
            }else{
                echo "...faild \r\n";
                $alldomainVerified = false;
            }
        }
        if($info['method']  === 'http' && $info['status']  === 'needverification'){
            if($info['isip'] === "true"){
                $fqdn = $info['topdomain'];
            }else{
                $subdomain = $info['subdomain'] != ""?$info['subdomain'].'.':"";
                $fqdn = $subdomain.$info['topdomain'];
            }
            $httpResponse = file_get_contents('http://'.$fqdn.'/.well‐known/pki‐validation/'.$csrhash['http']['filename']);
            $httpcontent = $csrhash['http']['firstline'].PHP_EOL."comodoca.com".PHP_EOL.strtolower($service->unique_id);
            if($httpResponse === $httpcontent){
                $dcvinfo[$domain]['status'] = 'verified';
                echo "...success \r\n";
            }else{
                echo "...faild \r\n";
                $alldomainVerified = false;
            }
        }
        if($info['method']  === 'https' && $info['status']  === 'needverification'){
            if($info['isip'] === "true"){
                $fqdn = $info['topdomain'];
            }else{
                $subdomain = $info['subdomain'] != ""?$info['subdomain'].'.':"";
                $fqdn = $subdomain.$info['topdomain'];
            }
            $httpsResponse = file_get_contents('https://'.$fqdn.'/.well‐known/pki‐validation/'.$csrhash['http']['filename']);
            $httpscontent = $csrhash['http']['firstline'].PHP_EOL."comodoca.com".PHP_EOL.strtolower($service->unique_id);
            if($httpsResponse === $httpscontent){
                $dcvinfo[$domain]['status'] = 'verified';
                echo "...success \r\n";
            }else{
                echo "...faild \r\n";
                $alldomainVerified = false;
            }
        }
    }

    if($alldomainVerified === true){
        if($class === "dv"){
            Capsule::table('tbltrustocean_certificate')->where('id',$service->id)->update(array(
                'dcv_info'  =>  json_encode($dcvinfo),
                'dcvfinished_at'    =>  date('Y-m-d H:i:s', time()),
                'status'    =>  'enroll_ca',
            ));
        }
        if($class === "ov" || $class === "ev"){
            Capsule::table('tbltrustocean_certificate')->where('id',$service->id)->update(array(
                'dcv_info'  =>  json_encode($dcvinfo),
                'status'    =>  'enroll_organization',
                'dcvfinished_at'    =>  date('Y-m-d H:i:s', time()),
            ));
        }
    }else{
        Capsule::table('tbltrustocean_certificate')->where('id',$service->id)->update(array(
            'dcv_info'=>json_encode($dcvinfo),
        ));
    }

}

/**
 * 查找域名的 权威 NS IP地址
 * @param $domain
 * @return array
 */
function findAuthNSIps($domain){
    require __DIR__.'/vendor/autoload.php';
    $r = new Net_DNS2_Resolver(array('nameservers'=>array('114.114.114.114','114.114.115.115','8.8.8.8','8.8.4.4')));
    $result =  $r->query($domain,'NS');
    foreach ($result->answer as $ans){
        $ns = $ans->nsdname;
        break;
    }
    $nsq = $r->query($ns,'A');
    $nsip = array();
    foreach ($nsq->answer as $ans){
        array_push($nsip, $ans->address);
    }
    return $nsip;
}

/**
 * 查找指定 fqdn 的 CNAME 结果
 * @param $toplevelDomain
 * @param $fqdn
 * @return string
 */
function findCnameResult($toplevelDomain, $fqdn){
    require __DIR__.'/vendor/autoload.php';
    $r = new Net_DNS2_Resolver(array('nameservers'=>findAuthNSIps($toplevelDomain)));
    $result = $r->query($fqdn, 'CNAME');
    $address = "";
    foreach ($result->answer as $ans){
        $address = $ans->cname;
        break;
    }
    return $address;
}

/**
 * 计算CSR HASH值
 * @param $csrCode
 * @return array
 */
function TRUSTOCEANSSL_getCsrHash($csrCode){
    #convert to .der code type
    $stringBegin    =   "BEGIN CERTIFICATE REQUEST-----";
    $stringEnd      =   "-----END";
    $pureCsrData    =   substr($csrCode, strpos($csrCode, $stringBegin)+strlen($stringBegin));
    $pureCsr        =   substr($pureCsrData, 0, strpos($pureCsrData, $stringEnd));
    $csrDer         =   base64_decode($pureCsr);

    $md5 = md5($csrDer);
    $hash256 = hash('sha256', $csrDer);

    #return the encode hash value
    return array(
        'md5'       =>  $md5,
        'sha256'      =>  $hash256,
        'dns'   =>  array(
            'purehost'  =>  "_".strtolower($md5),
            'purevalue' =>  strtolower(substr($hash256, 0,32).'.'.substr($hash256, 32,32))
        ),
        'http'   =>  array(
            'filename'  =>  strtoupper($md5).'.txt',
            'firstline' =>  strtolower($hash256)
        ),
    );
}