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
<body>
<style type="text/css">
    .tohide{
        display: none;
    }
    .tohide2{
        display: none;
    }
</style>

<div class="section">
    <div class="section-header">
        <h3>{$MODLANG.trustoceanssl.enroll.setup3.title}</h3>
        <p>{$MODLANG.trustoceanssl.enroll.setup3.desc}</p>
        {if $vars.error}
            {include file="$template/includes/alert.tpl" type="error" msg=$vars.error textcenter=false idname="alertModuleCustomButtonFailed"}
        {/if}
        {if $vars.info}
            {include file="$template/includes/alert.tpl" type="success" msg=$vars.info textcenter=false idname="alertModuleCustomButtonFailed"}
        {/if}
    </div>
    <ul class="tabul list-group list-group-tab-nav">
        <button onclick="$('.dnd-info').toggle();" data-dcv class="btn btn-sm btn-info" data-toggle="tab">{$MODLANG.trustoceanssl.enroll.setup3.dndinfo}</button>
    </ul>
    <div class="dnd-info" style="display: none;">
        <p>
            {$MODLANG.trustoceanssl.enroll.setup3.dcvdns1}
        </p>
        <p>
            {$MODLANG.trustoceanssl.enroll.setup3.dcvdns2} </p>
        <p>
            {$MODLANG.trustoceanssl.enroll.setup3.dcvhttp1}
        </p>
        <p>
            {$MODLANG.trustoceanssl.enroll.setup3.dcvhttp2}</p>
        <p>
            {$MODLANG.trustoceanssl.enroll.setup3.dcvhttps1}
        </p>
        <p>
            {$MODLANG.trustoceanssl.enroll.setup3.dcvhttps2}</p>
    </div>
    <div class="section-body">
        <div class="panel panel-default" data-inputs-container="">
            <div class="panel-body">
                <div class="table-container loading clearfix" style="border:none;max-height: none;">
                    <table id="tableDCVDomainList" class="table table-list">
                        <thead>
                            <tr>
                                <th data-priority="1" ><span><span>{$MODLANG.trustoceanssl.enroll.setup3.table.domain}</span><span class="sorting-arrows"></span></span></th>
                                <th data-priority="2"><span><span>{$MODLANG.trustoceanssl.enroll.setup3.table.status}</span><span class="sorting-arrows"></span></span></th>
                                <th data-priority="3"><span><span>{$MODLANG.trustoceanssl.enroll.setup3.table.method}</span><span class="sorting-arrows"></span></span></th>
                            </tr>
                        </thead>
                        <tbody>
                        {foreach from=$dcvinfo key=domain item=info}
                            <tr>
                                <td>{$domain}</td>
                                <td>
                                    {if $info.status eq 'needverification'}
                                        <span class="text-warning" style="padding-left: 10px;"><i class="oi fa fa-spin" data-glyph="aperture" style="margin-left: -18px;margin-top: 2px; position: absolute; padding-bottom: 2px;"></i> {$MODLANG.trustoceanssl.enroll.status.processing}</span>
                                    {elseif $info.status eq 'verified'}
                                        {if $info.method === 'email'}
                                            <span class="text-info" style="padding-left: 10px;"><i class="oi" data-glyph="circle-check" style="margin-left: -18px;margin-top: 2px; position: absolute; padding-bottom: 2px;"></i> {$MODLANG.trustoceanssl.enroll.status.emailsent}</span><br>{$info.email}
                                        {else}
                                            <span class="text-success" style="padding-left: 10px;"><i class="oi" data-glyph="circle-check" style="margin-left: -18px;margin-top: 2px; position: absolute; padding-bottom: 2px;"></i> {$MODLANG.trustoceanssl.enroll.status.verified}</span>
                                        {/if}
                                    {/if}
                                </td>
                                <td style="display: flex;">
                                {*<form style="display: flex;" action="{$smarty.server.PHP_SELF}?action=productdetails&id={$vars.serviceid}&modop=custom&a=setdcvforall" method="post">*}
                                 <select name="domaindcvmathod" {if $domain === $vars.domains.0}data-maindcv="true"{else}data-sandcv="true"{/if} dcv-cert-id={$serviceid} dcv-domain-name={$domain} dcv-domain={$domain|md5} class="form-control input-sm" style="width: 120px; text-align: center;" {if $info.method neq 'email'}{if $info.status eq 'verified'}disabled{/if}{/if}>
                                     <option data-method="dns" value="dns" {if $info.method eq 'dns'}selected="selected"{/if} {if $info.isip eq 'true'}disabled{/if} {if $info.isip eq 'true'}style="display:none;"{/if}>{$MODLANG.trustoceanssl.enroll.setup3.table.dns}</option>
                                     <option data-method="http" value="http" {if $info.method eq 'http'}selected="selected"{/if}>{$MODLANG.trustoceanssl.enroll.setup3.table.http}</option>
                                     <option data-method="https" value="https" {if $info.method eq 'https'}selected="selected"{/if}>{$MODLANG.trustoceanssl.enroll.setup3.table.https}</option>
                                    {if $info.isip eq 'false'}
                                        <option disabled> {$MODLANG.trustoceanssl.enroll.setup3.table.dcv.emaildesc}</option>
                                        {foreach from=$info.dcvemails key=emailkey item=email}
                                            {assign var=emailVar value="@"|explode:$email}
                                            <option value="{$email}" data-method="email" data-mailname="{$emailVar.0}" data-emailkey="{$emailkey}" data-maildomain="{$emailVar.1}" {if $info.email}{if $info.email eq $email}selected="selected"{/if}{/if}>{$email}</option>
                                        {/foreach}
                                    {/if}
                                 </select>

                                    {if $domain === $vars.domains.0}
                                    <input type="button" onclick="selectAllDcvMethod()" data-toggle="tooltip" data-title="{$MODLANG.trustoceanssl.enroll.setup3.table.selectalldesc}" data-original-title="" title="" class="btn btn-xs btn-default" value="{$LANG.trustoceanssl.enroll.setup3.table.selectall}">
                                    {/if}
                                    {*</form>*}
                                </td>
                                <td>
                                    {if $info.status === "needverification"}
                                    <form action="{$smarty.server.PHP_SELF}?action=productdetails&id={$vars.serviceid}&modop=custom&a=removeDomain" method="post">
                                    <input name="domain" value="{$domain}" hidden>
                                        <input type="submit" data-toggle="tooltip" data-title="{$MODLANG.trustoceanssl.enroll.setup3.table.removedesc}" data-original-title="" title="" class="btn btn-xs btn-danger" value="{$LANG.trustoceanssl.enroll.setup3.table.remove}">
                                    </form>
                                    {/if}
                                </td>
                            </tr>
                            <tr dcv-tds="{$domain|md5}" {if $info.status eq 'verified'}style="display: none;"{/if}>
                                <td colspan="4" dcv-change style="display: none;">
                                    <p>{$MODLANG.trustoceanssl.enroll.setup3.table.dcv.change.process}</p>
                                </td>
                                <td colspan="4" dcv-tab="dns" {if $info.method neq 'dns'}style="display: none;"{/if}>
                                    <p>{$MODLANG.trustoceanssl.enroll.setup3.table.dcv.host}: <span class="yate" style="padding: 3px 15px; background: #f6f7f8; border-radius: 5px;">{$vars.csrhash.dns.purehost}{if $info.subdomain neq ''}.{$info.subdomain}{/if}</span> <button class="btn btn-xs btn-info" data-clipbutton data-clipboard-text="{$vars.csrhash.dns.purehost}{if $info.subdomain neq ''}.{$info.subdomain}{/if}">{$LANG.trustoceanssl.enroll.setup3.table.dcv.copy}</button>
                                        <br>{$MODLANG.trustoceanssl.enroll.setup3.table.dcv.type}: <span class="yate">{$MODLANG.trustoceanssl.enroll.setup3.table.dcv.typecname}</span>
                                        <br>{$MODLANG.trustoceanssl.enroll.setup3.table.dcv.point}: <span class="yate" style="padding: 3px 15px; background: #f6f7f8; border-radius: 5px;">{$vars.csrhash.dns.purevalue|strtolower}.{$vars.uniqueid|strtolower}.comodoca.com</span> <button class="btn btn-xs btn-info" data-clipbutton data-clipboard-text="{$vars.csrhash.dns.purevalue|strtolower}.{$vars.uniqueid|strtolower}.comodoca.com">{$LANG.trustoceanssl.enroll.setup3.table.dcv.copy}</button>
                                    </p>
                                </td>
                                <td colspan="4" dcv-tab="http" {if $info.method neq 'http'}style="display: none;"{/if}>
                                    <p>{$MODLANG.trustoceanssl.enroll.setup3.table.dcv.download}: <span class="yate"><a href="javascript:;" onclick="dcvDownloadFile('{$vars.csrhash.http.firstline}\ncomodoca.com\n{$vars.uniqueid|strtolower}','{$vars.csrhash.http.filename}');">{$vars.csrhash.http.filename}</a></span>
                                        <br>{$MODLANG.trustoceanssl.enroll.setup3.table.dcv.upload}: http://{if $info.subdomain}{$info.subdomain}.{/if}{$info.topdomain}/.well-known/pki-validation/  {$LANG.trustoceanssl.enroll.setup3.table.dcv.folder}
                                        <br>{$MODLANG.trustoceanssl.enroll.setup3.table.dcv.access}: <span class="yate">
                                            <a href="http://{if $info.subdomain}{$info.subdomain}.{/if}{$info.topdomain}/.well-known/pki-validation/{$vars.csrhash.http.filename}" target="_blank">
                                                http://{if $info.subdomain}{$info.subdomain}.{/if}{$info.topdomain}/.well‐known/pki‐validation/{$vars.csrhash.http.filename}
                                            </a>
                                        </span>
                                    </p>
                                </td>
                                <td colspan="4" dcv-tab="https" {if $info.method neq 'https'}style="display: none;"{/if}>
                                    <p>{$MODLANG.trustoceanssl.enroll.setup3.table.dcv.download}: <span class="yate"><a href="javascript:;" onclick="dcvDownloadFile('{$vars.csrhash.http.firstline}\ncomodoca.com\n{$vars.uniqueid|strtolower}','{$vars.csrhash.http.filename}');">{$vars.csrhash.http.filename}</a></span>
                                        <br>{$MODLANG.trustoceanssl.enroll.setup3.table.dcv.upload}: https://{if $info.subdomain}{$info.subdomain}.{/if}{$info.topdomain}/.well-known/pki-validation/ {$LANG.trustoceanssl.enroll.setup3.table.dcv.folder}
                                        <br>{$MODLANG.trustoceanssl.enroll.setup3.table.dcv.access}: <span class="yate">
                                            <a href="https://{if $info.subdomain}{$info.subdomain}.{/if}{$info.topdomain}/.well-known/pki-validation/{$vars.csrhash.http.filename}" target="_blank">
                                                https://{if $info.subdomain}{$info.subdomain}.{/if}{$info.topdomain}/.well‐known/pki‐validation/{$vars.csrhash.http.filename}
                                            </a>
                                        </span>
                                    </p>
                                </td>
                            </tr>
                        {/foreach}
                        {literal}
                        <script>
                            function selectAllDcvMethod(){var b=$("select[data-maindcv] option:selected").attr("data-method");if("email"===b){var c=$("select[data-maindcv] option:selected");$("select[data-sandcv]").each(function(){var a=$(this).children("option[data-emailkey="+c.attr("data-emailkey")+"]");$(a).each(function(){$(this).attr("selected",!0).trigger("change")})})}"dns"===b&&(c=$("select[data-maindcv] option:selected"),$("select[data-sandcv]").each(function(){var a=$(this).children("option[data-method=dns]");$(a).each(function(){$(this).attr("selected",
!0).trigger("change")})}));"http"===b&&(c=$("select[data-maindcv] option:selected"),$("select[data-sandcv]").each(function(){var a=$(this).children("option[data-method=http]");$(a).each(function(){$(this).attr("selected",!0).trigger("change")})}));"https"===b&&(c=$("select[data-maindcv] option:selected"),$("select[data-sandcv]").each(function(){var a=$(this).children("option[data-method=https]");$(a).each(function(){$(this).attr("selected",!0).trigger("change")})}))};
                        </script>
                        {/literal}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <p style="color: #909090;">
            {$MODLANG.trustoceanssl.enroll.setup3.table.dcv.submit.desc}</p>
        <div class="form-actions">
            <a class="btn btn-primary" href="{$smarty.server.PHP_SELF}?action=productdetails&id={$vars.serviceid}&modop=custom&a=clientdoDcvVerification" style="margin-right: 30px;"/>{$LANG.trustoceanssl.enroll.setup3.table.dcv.submit.btn}</a>  <a class="btn btn-info" href="/myssl.php" style="margin-right: 30px;"/>{$LANG.trustoceanssl.enroll.setup3.table.dcv.submit.returnlist}</a>
        </div>
    </div>
</div>
</body>
</html>