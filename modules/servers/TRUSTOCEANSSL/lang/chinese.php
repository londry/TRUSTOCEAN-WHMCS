<?php


$TRUSTOCEAN_LANG['trustoceanssl']['clientnav']['mysslcertificate'] = "管理证书";
/* 新的语言文件for TRUSTOCEANSSL 产品模块*/
# 第一步提交CSR
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setupone']['title'] = "第一步： 创建证书密钥对（CSR&KEY）";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setupone']['desc'] = "您可以粘贴已经创建好的CSR代码或者使用我们的X509助手进行在线创建，记得妥善保存您的私钥";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setupone']['uploadcsr'] = "上传已生成的CSR代码";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setupone']['generatecsr'] = "使用X509助手在线创建";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setupone']['inputcsr'] = "填写CSR代码";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setupone']['csrplaceholder'] = "输入已经创建的CSR代码内容（支持ECC和RSA）";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setupone']['commonname'] = "主题名称 (证书主域名)";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setupone']['commonnameplaceholder'] = "主题名称 用于证书主域名";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setupone']['emailaddress'] = "邮箱地址";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setupone']['emailaddressplaceholder'] = "请您输入您的邮箱地址";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setupone']['signtype'] = "签名类型";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setupone']['signtypeplacesafe'] = "安全";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setupone']['signtypeplacefaster'] = "更快";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setupone']['signaglor'] = "签名算法";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setupone']['privatetoken'] = "私钥密码";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setupone']['privatetokenplaceholder'] = "您可以设置一个私钥保护密码";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setupone']['nextsetup'] = "下一步";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setupone']['btn']['reissuesubmit'] = "执行重签";

$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setupone']['reissuesubmit'] = "保存重签";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['useoldcsr'] = "使用当前的CSR代码";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['generatenewcsr'] = "自动创建新的CSR代码";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['uploadnewcsr'] = "上传新的CSR代码";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['reissue']['title'] = "修改您的证书";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['reissue']['description'] = "您可以使用重签功能修改您的私钥, 多域名证书可以修改, 增加, 删除证书内的域名, 修改后只需要验证新的域名. 我们会签发信的证书给您使用.";

$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['upgrade']['cannotdowngrade'] = "SSL证书额度只能增加, 无法降级额度, CA处不允许自动降级域名额度. ";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['upgrade']['cannotupgrade'] = "当前状态无法修改附加域名额度, 只有已经完成签发和等待配置的证书才可以增加附加域名额度";

#第二步提交域名列表
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup2']['title'] = "第二步： 填写需要保护的域名";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup2']['desc'] = "请填写所有您需要保护的域名,每行一条域名或IPv4地址，CSR中的主域名也需要在此填写，通配符域名的顶级域名也需要在此填写，不支持验证IPv6地址。通配符域名需要额外填写顶级域名才可受到保护。
<br>可提交：:domainCount 条域名 ，如需保护更多域名，请先增加域名额度。";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup2']['inputdomain'] = "每行填写一条域名 (可提交 :domainCount 条)";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup2']['next2'] = "下一步";

#第三步 选择域名验证方式
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['title'] = "第三步： 完成域名验证";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['desc'] = "证书签发之前，您需要为每条域名分别选择域名验证方式并完成域名控制权验证";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['dndinfo'] = "查看域名/IPv4地址验证方法说明";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['dcvdns1'] = "1. DNS记录验证：";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['dcvdns2'] = "您需要为采用DNS验证方式的域名创建指定的DNS记录，记录类型为CNAME（别名），同一本证书，所有的DNS记录指向相同，记录名称不同。系统将通过您域名的权威DNS服务器查询记录结果，一旦所有域名验证成功，您的证书将会签发。";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['dcvhttp1'] = "2. HTTP访问验证：";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['dcvhttp2'] = "请您为选择了采用HTTP文件验证方式的域名下载指定的验证文件，并上传至网站指定目录内。同一本证书验证文件相同。我们将会通过HTTP(80端口)协议访问指定目录下的验证文件进行自动验证。";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['dcvhttps1'] = "3. HTTPS访问验证：";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['dcvhttps2'] = "请您为选择了采用HTTPS文件验证方式的域名下载指定的验证文件，并上传至网站指定目录内。同一本证书验证文件相同。我们将会通过HTTPS(443端口)协议访问指定目录下的验证文件进行自动验证。";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['domain'] = "域名";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['status'] = "状态";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['method'] = "方式";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['selectall'] = "应用全部";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['selectalldesc'] = "将选择的验证方式应用于全部待验证的域名";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['dns'] = "DNS记录";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['http'] = "HTTP访问";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['https'] = "HTTPS访问";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['remove'] = "移除";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['removedesc'] = "若您此条域名暂时无法通过验证，您可以选择移除";

$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['dcv']['host'] = "主机名";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['dcv']['type'] = "类型";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['dcv']['point'] = "指向";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['dcv']['typecname'] = "CNAME(别名)";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['dcv']['copy'] = "复制";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['dcv']['download'] = "下载";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['dcv']['upload'] = "上传至";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['dcv']['folder'] = "文件夹";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['dcv']['access'] = "访问验证";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['dcv']['emaildesc'] = "↓选择邮件地址验证↓";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['dcv']['status']['needverification'] = "待验证";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['dcv']['status']['waitingemail'] = "待发送验证邮件";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['dcv']['status']['verified'] = "已验证";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['dcv']['change']['process'] = "请您稍等, 正在修改验证方式...";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['dcv']['submit']['desc'] = "系统通常会每隔5-30分钟自动检查并验证您的域名，队列高峰期可能有所延迟, 一旦验证完毕我们将会通过电子邮件通知您。系统队列不会自动验证创建超过24小时的证书订单, 您也可以使用下列按钮来进行手动执行验证。";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['dcv']['submit']['btn'] = "保存 & 提交";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['setup3']['table']['dcv']['submit']['returnlist'] = "返回证书列表";

# 即将签发证书页面预览
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['caprocessing']['title'] = "即将签发您的证书";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['caprocessing']['desc'] = "1.域名验证DV版本证书，通常只需要5-10分钟完成签发。<br>2.企业验证OV和EV版本证书，通常需要3-7个工作日完成审核和签发。<br>3.证书一旦签发，我们会通过电子邮件通知您。若您在签发过程中存在任何疑问，可通过工单，在线客服联系我们。";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['caprocessing']['desc2'] = "DV证书超过1小时未签发请提交工单处理, 企业版EV、OV证书可能需要2-3个工作日审核信息签发证书。";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['caprocessing']['resenddcv'] = "重新发送验证邮件";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['caprocessing']['checkdcv'] = "查看证书签发状态";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['caprocessing']['submitticket'] = "提交工单";

$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['caprocessing']['resenderror'] = "当前订单无法执行重发操作!";

$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['title'] = "证书签发完成";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['desc'] = "恭喜您，您的证书已经完成验证和签发，可通过下列功能按钮操作来管理您的的证书，若存在疑问，您可以参考我们帮助中心的文档，我们为您准备了数篇您可能用到的配置文档和验证文档。";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['btn']['downloadcert'] = "下载证书+证书链";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['btn']['convertcert'] = "转换&下载证书";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['btn']['reissue'] = "吊销并重新签发证书(更换证书)";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['btn']['subticket'] = "提交工单";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['btn']['techdocs'] = "技术文档";

$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['info']['cert'] = "证书内容";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['info']['chaincert'] = "证书链内容";



// 企业证书注册字段
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['organization']['organization_name'] = "组织(企业)名称";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['organization']['organizationalUnitName'] = "申请人所在部门";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['organization']['registered_address_line1'] = "企业注册地址";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['organization']['registerted_no'] = "统一社会信用代码或(组织机构代码)";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['organization']['country'] = "国家";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['organization']['state'] = "省/自治区/直辖市";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['organization']['city'] = "城市";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['organization']['postal_code'] = "邮编";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['organization']['organization_phone'] = "企业联系电话";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['organization']['date_of_incorporation'] = "企业注册日期";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['organization']['contact_name'] = "办理联系人";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['organization']['contact_title'] = "联系人职位";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['organization']['contact_phone'] = "联系人电话";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['organization']['notice'] = "注意：填写您的企业基本, 所有的企业信息请保持和营业执照一致, 我们将会在您提交后开始检查.";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['organization']['contactnotice'] = "注意: 请填写证书申请联系人信息, 联系人必须是企业内部员工或经过授权的代理人, 电子邮箱将作为EV申请确认信的接收邮箱.";

$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['sannotice'] = "注意: CSR中的域名不会应用到证书当中, 因此您需要将所有需要保护的域名都填写到下面的域名列表中. 列表中的首个域名将作为证书主域名.";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['inputcsr'] = "输入CSR代码";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['inputcsr']['placeholder'] = "输入您已经创建好的CSR代码";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['inputsandomains'] = "填写需要保护的域名";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['inputsandomains']['placeholder'] = "每行输入一条需要保护的域名";

$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['info']['certinfo'] = "证书信息";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['info']['orginfo'] = "组织信息";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['info']['orderinfo'] = "订单信息";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['info']['convertcert'] = "转换证书";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['info']['certno'] = "证书编号";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['info']['cnname'] = "主题名称";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['info']['status'] = "证书状态";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['info']['ca'] = "颁发机构";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['info']['subca'] = "中级证书";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['info']['signaglor'] = "签名算法";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['info']['valid'] = "有效日期";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['info']['sanname'] = "备用名称";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['info']['ct'] = "透明度CT";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['info']['from'] = "从";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['info']['to'] = "至";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['btn']['dia1'] = "注意！此处下载的证书不含私钥! 您需要配合已经解密的私钥进行实际使用 (.ZIP压缩包) ";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['btn']['dia2'] = "不错！此功能让你能够容易地将证书合成IIS Nginx Apache CDN所使用的格式";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['btn']['dia3'] = "小心！此操作不可逆转，会吊销您当前的证书并重置订单状态，重置后，您需要重新配置证书，此操作可能会影响您正在使用证书的网站";

$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['convert']['desc'] = "提示：此助手帮助您将证书和私钥合并为常用的Nginx、Apahce、IIS、CDN格式的证书文件, 为了安全,我们并未在系统中存储您在申请证书时创建的私钥信息, 因此在转换之前, 您需要填写系统发送给您邮箱(:email)内的私钥内容, 和您设置的私钥保护密码来进行转换。";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['convert']['privatekey'] = "证书私钥";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['convert']['privatekeydesc'] = "请您检查邮件, 输入证书私钥内容";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['convert']['keytoken'] = "私钥密码";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['convert']['keytokendesc'] = "请您输入私钥密码以解密私钥";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['issued']['convert']['convertbtn'] = "立即转换";


$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['sider']['type'] = "类型";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['sider']['class'] = "级别";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['sider']['valid'] = "创建日";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['sider']['trustoceanno'] = "证书服务编号";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['sider']['comodono'] = "COMODO 订单号";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['sider']['certinfo'] = "证书详情";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['sider']['certlist'] = "返回列表";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['sider']['dcvmanagement'] = "DCV管理";

$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['info']['status']['dv'] = "域名验证";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['info']['status']['ov'] = "企业验证";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['sider']['title'] = "订单信息";

# SAN增加页面
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['addsan']['desc'] = "现在，您可以为多域名或UCC证书订单增加域名额度，然后通过重签功能来添加更多的域名到证书上进行保护。";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['addsan']['btn'] = "增加域名额度";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['addsan']['desc2'] = "当前域名额度: :total 条";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['addsan']['after'] = "条, ￥ :price 元/条";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['addsan']['btn2'] = "点击继续";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['addsan']['desc3'] = "您想增加多少条域名?";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['addsan']['desc4'] = "输入域名条数";

$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['addsan']['erpage']['title'] = "请先支付之前的账单 #:invoiceid";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['addsan']['erpage']['desc'] = "您当前无法进行域名数量添加, 因为当前证书有未完成的升级账单。<br><br>如需继续，请先支付未付账单，当账单支付后，您才可以继续操作。<br><br>如果您认为您此消息有误，请提交故障工单给我们。";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['addsan']['erpage']['paybtn'] = "立即支付";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['addsan']['erpage']['submitticket'] = "提交工单";

#全局自定义菜单
$TRUSTOCEAN_LANG['trustoceanssl']['customnav']['top']['applyssl'] = "申请证书";
$TRUSTOCEAN_LANG['trustoceanssl']['customnav']['top']['myssl'] = "我的证书";

# 在下面添加更多的语言翻译, 同时需要修改模板文件进行使用

$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['status']['verified'] = "Verified";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['status']['processing'] = "Processing";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['status']['emailsent'] = "Email Sent";


$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['btn']['updatedcv'] = "刷新域名状态";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['btn']['loadingtext'] = "请稍等";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['btn']['checkissue'] = "查询签发状态";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['btn']['retrydcv'] = "重新发送验证邮件 / 执行DCV校验";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['desc90'] = "提示: 每隔5分钟可执行一次, 重新发送验证邮件、和执行DCV校验, 完成过程可能需要1-10分钟。遇到大批量订单排队时, 可能会有所延迟。";

$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['choosecsr'] = "选择CSR方式";

$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['organization']['info2'] = "企业订单验证";
$TRUSTOCEAN_LANG['trustoceanssl']['enroll']['organization']['info2desc'] = "您正在申请企业OV、EV SSL证书, 环洋诚信™的认证客服将会在1-2个工作日内为您取得联系并协助您进行企业验证签发证书, 如果您想尽快完成企业信息验证, 您可以发送电子邮件到 verification@ticket.trustocean.com 并在邮件标题内附上订单识别号码#";

// API错误信息
$TRUSTOCEAN_LANG['trustoceanssl']['apierror']['orginfoe2'] = "企业信息不完整, 请您补全后重试!";
$TRUSTOCEAN_LANG['trustoceanssl']['apierror']['wait5'] = "每隔3分钟可获取证书状态, 请您稍后再试.";
$TRUSTOCEAN_LANG['trustoceanssl']['apierror']['dcvwait5'] = "每隔5分钟可重新发送DCV操作或域名验证邮件, 请稍后再试.";
$TRUSTOCEAN_LANG['trustoceanssl']['apierror']['repeatdomain'] = "您的某条域名或/IP地址重复了, 请您检查域名列表.";
$TRUSTOCEAN_LANG['trustoceanssl']['apierror']['amountsan'] = "域名数量超出限制, 请您增加域名额度后重试.";
$TRUSTOCEAN_LANG['trustoceanssl']['apierror']['wildcard'] = "当前证书不支持通配符域名，请移除：";
$TRUSTOCEAN_LANG['trustoceanssl']['apierror']['noip'] = "当前证书不支持保护IP地址，请移除";
$TRUSTOCEAN_LANG['trustoceanssl']['apierror']['notcorrectdomain'] = "这些域名格式不正确: ";
$TRUSTOCEAN_LANG['trustoceanssl']['apierror']['nothaovedoamin'] = "必须为您的证书输入合法的域名才能继续进行签发";
$TRUSTOCEAN_LANG['trustoceanssl']['apierror']['nocsrcode'] = "您CSR中的域名格式不正确";
$TRUSTOCEAN_LANG['trustoceanssl']['apierror']['iswildcard'] = "您当前正在配置通配符证书, 请输入通配符格式域名, 比如：*.trustocean.com";
$TRUSTOCEAN_LANG['trustoceanssl']['apierror']['domainincorrect'] = "您提供的域名格式不正确, 请您检查后重试";
$TRUSTOCEAN_LANG['trustoceanssl']['apierror']['notfirstip'] = "此证书的第一个域名不能为IP地址, 请您调整位置后重试";
$TRUSTOCEAN_LANG['trustoceanssl']['apierror']['ipincsr'] = "CSR中不能添加IP地址! 请重新生成您的CSR代码";
$TRUSTOCEAN_LANG['trustoceanssl']['apierror']['ipincsrcommon'] = "主域名不能为IP地址, 如果您需要为IP地址提供保护, 请您购买公网IP地址证书";
$TRUSTOCEAN_LANG['trustoceanssl']['apierror']['cannotsubmitca'] = "当前证书无法提交到CA, 请联系技术支持";
$TRUSTOCEAN_LANG['trustoceanssl']['apierror']['ipdcverr'] = "IP 地址仅支持 HTTP、HTTPS两种方式验证。";
$TRUSTOCEAN_LANG['trustoceanssl']['apierror']['incorrectcsrcode'] = "未上传CSR代码或CSR代码格式不正确, 请您检查后重试。";
$TRUSTOCEAN_LANG['trustoceanssl']['apierror']['cannotrefundfor30days'] = "订单超过30天退款有效期, 无法进行退款操作";

$TRUSTOCEAN_LANG['trustoceanssl']['apierror']['cannotremovesan'] = "非多域名证书无法删除域名.";
$TRUSTOCEAN_LANG['trustoceanssl']['apierror']['cannotremovesanone'] = "每个证书至少保留1条域名, 无法删除：";
$TRUSTOCEAN_LANG['trustoceanssl']['apierror']['cannotremovesanverified'] = "该域名已经通过验证, 无法删除：";
$TRUSTOCEAN_LANG['trustoceanssl']['apierror']['privatekeyincorrect'] = "私钥不正确，或私钥保护密码不正确, 请您检查后重试！";
$TRUSTOCEAN_LANG['trustoceanssl']['apierror']['cannotresetorder'] = "此订单已经签发完成，或此证书订单已经或将要提交至CA处，无法重置为新订单，建议先同步证书信息，或可通过重签进行修改数据！";

$TRUSTOCEAN_LANG['trustoceanssl']['hookupgrade']['cancelledorder'] = "已取消的订单无法修改域名信息";
$TRUSTOCEAN_LANG['trustoceanssl']['hookupgrade']['notmdcorder'] = "当前证书不支持保护多个域名, 无法添加。";
$TRUSTOCEAN_LANG['trustoceanssl']['hookupgrade']['unpaidInvoice'] = "无法进行升级, 请您先支付之前的升级账单 #";
$TRUSTOCEAN_LANG['trustoceanssl']['hookupgrade']['internalApiError'] = "无法创建账单, 请提交工单联系我们检查您的账户";
$TRUSTOCEAN_LANG['trustoceanssl']['hookupgrade']['invoiceDesc'] = "通过API修改了域名SAN数量额度";
$TRUSTOCEAN_LANG['trustoceanssl']['hookupgrade']['invoiceNotes'] = "这是一个TrustOcean SAN订单, 手动标记支付将不会进行SAN升级";

return $TRUSTOCEAN_LANG;