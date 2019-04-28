<html>
<head>
    <title>Certification Secure Center - TRUSTOCEAN™ SSL CA</title>
    <link href="https://www.trustocean.com/templates/lagom/core/styles/depth/assets/css/theme.min.css" rel="stylesheet">
    <link href="https://yanque-static-1251538015.cos.ap-shanghai.myqcloud.com/assets/open-ionic/css/open-iconic.min.css" rel="stylesheet">
    <link href="/templates/lagom/assets/css/flue-app.css" rel="stylesheet">
    <script src="https://yanque-static-1251538015.cos.ap-shanghai.myqcloud.com/templates/lagom/assets/js/scripts.min.js?v=ab64dc"></script>
    <script src="https://yanque-static-1251538015.cos.ap-shanghai.myqcloud.com/templates/lagom/assets/js/core.min.js"></script>
    <script src="https://yanque-static-1251538015.cos.ap-shanghai.myqcloud.com/templates/lagom/assets/js/flue-app.js"></script>
    <link rel="shortcut icon" href="https://yanque-static-1251538015.cos.ap-shanghai.myqcloud.com/templates/lagom/assets/img/favicons/favicon.ico">
    <link rel="icon" sizes="16x16 32x32 64x64" href="https://yanque-static-1251538015.cos.ap-shanghai.myqcloud.com/templates/lagom/assets/img/favicons/favicon.ico">
    <link rel="icon" type="image/png" sizes="196x196" href="https://yanque-static-1251538015.cos.ap-shanghai.myqcloud.com/templates/lagom/assets/img/favicons/favicon-192.png">
    <link rel="icon" type="image/png" sizes="160x160" href="https://yanque-static-1251538015.cos.ap-shanghai.myqcloud.com/templates/lagom/assets/img/favicons/favicon-160.png">
    <link rel="icon" type="image/png" sizes="96x96" href="https://yanque-static-1251538015.cos.ap-shanghai.myqcloud.com/templates/lagom/assets/img/favicons/favicon-96.png">
    <link rel="icon" type="image/png" sizes="64x64" href="https://yanque-static-1251538015.cos.ap-shanghai.myqcloud.com/templates/lagom/assets/img/favicons/favicon-64.png">
    <link rel="icon" type="image/png" sizes="32x32" href="https://yanque-static-1251538015.cos.ap-shanghai.myqcloud.com/templates/lagom/assets/img/favicons/favicon-32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="https://yanque-static-1251538015.cos.ap-shanghai.myqcloud.com/templates/lagom/assets/img/favicons/favicon-16.png">
    <link rel="apple-touch-icon" href="https://yanque-static-1251538015.cos.ap-shanghai.myqcloud.com/templates/lagom/assets/img/favicons/favicon-57.png">
    <link rel="apple-touch-icon" sizes="114x114" href="https://yanque-static-1251538015.cos.ap-shanghai.myqcloud.com/templates/lagom/assets/img/favicons/favicon-114.png">
    <link rel="apple-touch-icon" sizes="72x72" href="https://yanque-static-1251538015.cos.ap-shanghai.myqcloud.com/templates/lagom/assets/img/favicons/favicon-72.png">
    <link rel="apple-touch-icon" sizes="144x144" href="https://yanque-static-1251538015.cos.ap-shanghai.myqcloud.com/templates/lagom/assets/img/favicons/favicon-144.png">
    <link rel="apple-touch-icon" sizes="60x60" href="https://yanque-static-1251538015.cos.ap-shanghai.myqcloud.com/templates/lagom/assets/img/favicons/favicon-60.png">
    <link rel="apple-touch-icon" sizes="120x120" href="https://yanque-static-1251538015.cos.ap-shanghai.myqcloud.com/templates/lagom/assets/img/favicons/favicon-120.png">
    <link rel="apple-touch-icon" sizes="76x76" href="https://yanque-static-1251538015.cos.ap-shanghai.myqcloud.com/templates/lagom/assets/img/favicons/favicon-76.png">
    <link rel="apple-touch-icon" sizes="152x152" href="https://yanque-static-1251538015.cos.ap-shanghai.myqcloud.com/templates/lagom/assets/img/favicons/favicon-152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="https://yanque-static-1251538015.cos.ap-shanghai.myqcloud.com/templates/lagom/assets/img/favicons/favicon-180.png">
    <meta name="msapplication-TileColor" content="#FFFFFF">
    <meta name="msapplication-TileImage" content="https://yanque-static-1251538015.cos.ap-shanghai.myqcloud.com/templates/lagom/assets/img/favicons/favicon-144.png">
    <meta name="msapplication-config" content="https://yanque-static-1251538015.cos.ap-shanghai.myqcloud.com/templates/lagom/assets/img/favicons/browserconfig.xml">
</head>
<body style="padding-top: 0px;">
<div style="padding: 20px;background-color: #00b373;color: #fff;">
    <span style="font-size: 25px;">TrustOcean™ Certification Secure Center</span> - 订单验证 {$lang}
</div>
<section style="padding: 20px;">
<div class="tab-content">
<div class="tab-pane active" id="certdetails">
    <h2>订单验证状态 #{$cert->vendor_id}</h2>
</div>
</div>
    {if $resendsuccess == "true"}
        {include file="/home/wwwroot/my.trustocean.com/templates/lagom/includes/alert.tpl" type="success" msg="验证邮件发送成功 / 执行DCV检查成功 (可能需要1-10分钟完成)" idname="alertModuleCustomButtonSuccess"}
    {/if}
    {if $resenderror == "true"}
        {include file="/home/wwwroot/my.trustocean.com/templates/lagom/includes/alert.tpl" type="error" msg="验证邮件发送失败 / 执行DCV检查失败" idname="alertModuleCustomButtonSuccess"}
    {/if}
    {if $removesuccess == "true"}
        {include file="/home/wwwroot/my.trustocean.com/templates/lagom/includes/alert.tpl" type="success" msg="域名移除成功！" idname="alertModuleCustomButtonSuccess"}
    {/if}
    {if $removeerror == "true"}
        {include file="/home/wwwroot/my.trustocean.com/templates/lagom/includes/alert.tpl" type="error" msg="域名移除失败" idname="alertModuleCustomButtonSuccess"}
    {/if}
<div class="tab-pane active" id="certdetails" style="border: 1px solid #a2a2a2; margin-bottom: 20px; border-radius: 3px;">
                <ul class="list-info list-info-50 list-info-bordered">
                    <li>
                        <span class="list-info-title">域验证</span>
                        <span class="list-info-text">{if $sync.dcvStatusCode==="1"}<span class="text-success">已完成</span>{else}<span class="text-warning">未完成</span>{/if}</span>
                    </li>
                    <li>
                        <span class="list-info-title">CA服务</span>
                        <span class="list-info-text"><span class="text-success">已链接</span></span>
                    </li>
                    {if $sync.organizationValidationStatus !== "-1"}
                        <li>
                            <span class="list-info-title">企业验证状态</span>
                            <span class="list-info-text">{$sync.organizationValidationStatus}</span>
                        </li>
                        <li>
                            <span class="list-info-title">电话验证状态</span>
                            <span class="list-info-text">
                                {$sync.ovCallbackStatus}
                            </span>
                        </li>
                    {/if}
                </ul>
            </div>
<ul class="tabul list-group list-group-tab-nav">
    <button onclick="$('.dnd-default').toggle();" data-dcv="" class="btn btn-sm btn-success" data-toggle="tab"><span class="oi" data-glyph="spreadsheet" style="top: -2px;padding-right: 5px;"></span> 点此查看域名验证信息</button>
</ul>
<div class="dnd-default" style="display:none;background-color: #e3effc; border: 1px solid #ddd; padding: 15px;border-radius: 3px;">
            <p>
                1. DNS记录验证：
            </p>
            <p>
                为域名创建指定的CNAME记录，主机头和记录值如下：
                <br>主机头: <code>{$csrhash.dns.purehost}</code> (若域名为子域名，则需要依次追加子域名名称至此)
                <br>记录值: <code>{$csrhash.dns.purevalue}.{$cert->unique_id|strtolower}.comodoca.com</code>
            </p>
            <p>
                2. HTTP访问验证：
            </p>
            <p>
                在域名所在的网站上创建TXT文件: <code>http://example.com/.well-known/pki-validation/{$csrhash.http.filename}</code> 文件内容(共3行)如下：
                <pre>{$csrhash.http.firstline}
comodoca.com
{$cert->unique_id|strtolower}</pre>
            </p>
            <p>
                3. HTTPS访问验证：
            </p>
            <p>
                在域名所在的网站上创建TXT文件: <code>https://example.com/.well-known/pki-validation/{$csrhash.http.filename}</code> 文件内容(共3行)如下：
                <pre>{$csrhash.http.firstline}
comodoca.com
{$cert->unique_id|strtolower}</pre>
            </p>
        </div>

<table>
    <thead>
    <tr>
        <th>域名</th>
        <th>状态</th>
        <th>验证方式</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    {foreach from=$dcvinfo key=index item=info}
        <tr>
            <td>{$info.domain}</td>
            <td>{if $info.status === "Validated"}<span class="text-success">已验证</span>{else}<span class="text-warning">未验证</span>{/if}</td>
            <td>
                <select onchange="updateDCV({$cert->serviceid}, this, '{$info.domain}', $(this).val(), '{$info.domain|md5}')" {if $info.status === "Validated"}disabled style="width: 100px;background-color: #dddddd;"{else}style="width: 100px;"{/if}>
                    <option value="CNAME_CSR_HASH" {if $info.method==='CNAME CSR Hash'}selected{/if}>DNS</option>
                    <option value="HTTP_CSR_HASH" {if $info.method==='HTTP CSR Hash'}selected{/if}>HTTP</option>
                    <option value="HTTPS_CSR_HASH" {if $info.method==='HTTPS CSR Hash'}selected{/if}>HTTPS</option>
                    {foreach from=$info.emails key=index2 item=email}
                        <option value="{$email}" {if $info.method===$email}selected{/if}>{$email}</option>
                    {/foreach}
                </select>
                <span class="text-warning" id="spin-{$info.domain|md5}" style="padding-left: 20px;display: none;"><i class="oi fa fa-spin" data-glyph="aperture" style="margin-left: -18px;margin-top: 2px; position: absolute; padding-bottom: 3px;"></i> 修改中..</span>

            </td>
            <td>
                {if $info.status !== "Validated"}
                    <form method="post">
                        <input value="{$cert->serviceid}" name="serviceid" hidden>
                        <input value="{$info.domain}" name="domain" hidden>
                        <input value="autoremovedomain" name="act" hidden>
                        <button type="submit" class="btn btn-sm btn-danger">移除</button>
                    </form>
                    {else}
                    -
                {/if}
            </td>
        </tr>
    {/foreach}
    </tbody>
    <script type="application/javascript">
    {literal}

            function updateDCV(serviceid, elem, domain, method, domainmd5){
                $('#spin-'+domainmd5).show();
                $.post('/clientarea.php?action=productdetails&id='+serviceid+'&modop=custom&a=syncDcvManagement', {act:'updateDCV', domain:domain, method:method}, function(resp){
                    //
                    if(resp.status === 'success'){
                        console.log('update success');
                    }
                    $('#spin-'+domainmd5).hide();
                })
            }

    {/literal}
    </script>
</table>
    <p>
        <a href="/user/certificate/{$cert->serviceid}/status" class="btn btn-md btn-success">刷新验证状态</a>
        <form method="post">
            <input value="{$cert->serviceid}" name="serviceid" hidden>
            <input value="resenddcvemail" name="act" hidden>
            <button type="submit" class="btn btn-md btn-warning">重新发送验证邮件 / 执行DCV校验</button>
        </form>
    </p>
    <p>提示: 重新发送验证邮件、和执行DCV校验将在CA实时执行, 完成过程可能需要1-10分钟。遇到大批量订单排队时, 可能会有所延迟。</p>
</section>
</body>
</html>
