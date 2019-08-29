<link rel="stylesheet" href="/modules/servers/TRUSTOCEANSSL/assets/css/clientarea.css">
<script src="/modules/servers/TRUSTOCEANSSL/assets/js/clipboard.min.js"></script>
{* must included after JQuery and Clipboard.js *}



{*<script src="/modules/servers/TRUSTOCEANSSL/assets/js/clientarea.js"></script>*}
{* LANGUAGE FILE LIB*}
<script src="/modules/servers/TRUSTOCEANSSL/assets/js/lang/chinese.js"></script>
{include file="$assetsPath/js/clientarea-js.tpl" MODLANG=$MODLANG }

<script type="application/javascript">

</script>
<style>
    .tab-content.product-details-tab-container{
        background-color: whitesmoke;
    }
</style>
<div style="text-align: left !important;background: #fff;border: 1px solid #eee;padding: 10px;">

    <div style="margin-bottom:15px;background-color: black; padding: 10px;margin-left: -10px; margin-right: -10px; margin-top: -10px;">
        <ul class="nav nav-tabs admin-tabs" style="padding: 5px 10px; background-color: #000000; border-radius: 3px;">
                <li style="padding-left: 0px;">
                    <img src="/modules/servers/TRUSTOCEANSSL/assets/img/sslmanagement.svg" height="25px;">
                </li>
            {if $status==='issued_active'}
                <li class="active">
                    <a href="#certdetails" data-toggle="tab"><i class="ls ls-configure"></i>{$MODLANG.trustoceanssl.enroll.issued.info.certinfo}</a>
                </li>
                <li>
                    <a href="#convertssl" data-convertssl data-toggle="tab"><i class="ls ls-configure"></i>证书转换</a>
                </li>
                <li>
                    <a href="#certificate" data-toggle="tab"><i class="ls ls-configure"></i>{$MODLANG.trustoceanssl.enroll.issued.info.cert}</a>
                </li>
                <li>
                    <a href="#chaincertificate" data-toggle="tab"><i class="ls ls-configure"></i>{$MODLANG.trustoceanssl.enroll.issued.info.chaincert}</a>
                </li>
                <li>
                    <a href="#domains" data-toggle="tab"><i class="ls ls-configure"></i>域名</a>
                </li>
                <li>
                    <a href="#csrcode" data-toggle="tab"><i class="ls ls-configure"></i>CSR</a>
                </li>
                <li>
                    <a href="#securesiteseal" data-toggle="tab"><i class="ls ls-configure"></i>网站签章</a>
                </li>
            {/if}
            </ul>
    </div>

{if $status==='configuration'}
    {include file="./enroll_csr.tpl"}
{/if}

{if $status==='enroll_domains'}
    {include file="./enroll_domains.tpl"}
{/if}

{if $status==='enroll_organization'}
    {include file="./enroll_organization.tpl"}
{/if}

{if $status==='enroll_organization_pre'}
    {include file="./enroll_organization_prevalidation.tpl"}
{/if}

{if $status==='enroll_dcv'}
    {include file="./enroll_dcv.tpl"}
{/if}

{if $status==='enroll_ca' || $status==='enroll_submithand' || $status==='dcv_hand'}
    {include file="./enroll_ca.tpl"}
{/if}

{if $status==='enroll_caprocessing'}
    {include file="./enroll_ca.tpl"}
{/if}


{*证书已经签发*}
{if $status === 'issued_active'}
<div class="section" style="text-align: left;" >
    <div class="">
        <div class="">
        </div>
        <div class="tab-content">
            <div class="tab-pane" id="certificate">
                <ul class="list-info list-info-50 list-info-bordered cert-info-tb">
                    <li>
                        <span class="list-info-title">{$MODLANG.trustoceanssl.enroll.issued.info.cert}</span>
                        <textarea class="form-control" rows="25" style="width: 80%;">{$vars.cert}</textarea>
                    </li>
                </ul>
            </div>

            <div class="tab-pane" id="chaincertificate">
                <ul class="list-info list-info-50 list-info-bordered cert-info-tb">
                    <li>
                        <span class="list-info-title">{$MODLANG.trustoceanssl.enroll.issued.info.chaincert}</span>
                        <textarea class="form-control" rows="25" style="width: 80%;">{$vars.chainscert}</textarea>
                    </li>
                </ul>
            </div>

            <div class="tab-pane" id="csrcode">
                <ul class="list-info list-info-50 list-info-bordered cert-info-tb">
                    <li>
                        <textarea class="form-control" rows="25" style="width: 80%;">{$vars.csr}</textarea>
                    </li>
                </ul>
            </div>

            <div class="tab-pane" id="domains">
                <ul class="list-info list-info-50 list-info-bordered cert-info-tb">
                    <li>
                        <pre style="width: 100%; min-height: 250px;">{$x509.extensions.subjectAltName}</pre>
                    </li>
                </ul>
            </div>

            <div class="tab-pane" id="securesiteseal">
                <div class="panel-body">
                    <p>请您将下列网站安全签章代码 复制安装到您网站的 <code>< /body ></code> 标签之前需要展示安全签章的位置: </p>
                    <textarea style="width: 100%;background-color: whitesmoke;">{if $vars.isseal eq true}{$vars.sealid|escape:''}{else}您的证书还未签发, 签发后才可获取安全签章代码{/if}</textarea>
                    <p>如果您想通过CSS来控制安全签章图片的展示位置和样式，您可以在CSS样式文件中增加选择器定义如下:</p>
                    <pre>
#trustoceanseal{
/* 然后在此开始编写CSS代码*/
    max-width: 100%;
    height:37px;
}</pre>
                    <p class="text-danger">签章提醒: <ul><li>重签和续费证书将需要重新安装新的安全签章代码，原有的安全签章将会失效。</li><li>通配符证书无法安装和使用安全签章。</li></ul></p>
                </div>
            </div>

            <div class="tab-pane active" id="certdetails">
                <div class="section">
                    <div class="section-header">
                        {if $vars.error}
                            {include file="$template/includes/alert.tpl" type="error" msg=$vars.error textcenter=false idname="alertModuleCustomButtonFailed"}
                        {/if}
                        {if $vars.info}
                            {include file="$template/includes/alert.tpl" type="success" msg=$vars.info textcenter=false idname="alertModuleCustomButtonFailed"}
                        {/if}
                    </div>
                </div>
                <table class="datatable to-table" width="100%">
                    <tbody>
                        <tr>
                            <td colspan="2">
                                <a style="margin-right: 12px;" href="/clientarea.php?action=productdetails&id={$serviceid}&modop=custom&a=downloadcertificate" class="btn btn-success btn-sm" data-toggle="tooltip" data-title="{$MODLANG.trustoceanssl.enroll.issued.btn.dia1}">{$MODLANG.trustoceanssl.enroll.issued.btn.downloadcert}</a>
                                <a style="margin-right: 12px;" href="#" onclick="$('a[data-convertssl]').click();" data-toggle="tab" class="btn btn-success btn-sm btn-checkout" data-toggle="tooltip" data-title="{$MODLANG.trustoceanssl.enroll.issued.btn.dia2}"> {$MODLANG.trustoceanssl.enroll.issued.btn.convertcert}</a>
                                <a href="/clientarea.php?action=productdetails&id={$serviceid}&modop=custom&a=prepareForReissue" class="btn btn-success btn-sm" data-toggle="tooltip" data-title="{$MODLANG.trustoceanssl.enroll.issued.btn.dia3}">{$MODLANG.trustoceanssl.enroll.issued.btn.reissue}</a>
                            </td>
                        </tr>
                        <tr>
                            <td>{$MODLANG.trustoceanssl.enroll.issued.info.certno}</td>
                            <td class="list-info-text">{$x509.serialNumber}</td>
                        </tr>
                        <tr>
                            <td class="list-info-title">{$MODLANG.trustoceanssl.enroll.issued.info.cnname}</td>
                            <td class="list-info-text">{$x509.subject.CN}</td>
                        </tr>
                        <tr>
                            <td class="list-info-title">{$MODLANG.trustoceanssl.enroll.issued.info.status}</td>
                            <td class="list-info-text">
                                <i class="text-success" style="font-style: normal;"><span class="fa fa-check" style="margin-right: 5px;"></span> Valid</i>
                            </td>
                        </tr>
                        <tr>
                            <td class="list-info-title">{$MODLANG.trustoceanssl.enroll.issued.info.ca}</td>
                            <td class="list-info-text">{$x509.issuer.O}</td>
                        </tr>
                        <tr>
                            <td class="list-info-title">{$MODLANG.trustoceanssl.enroll.issued.info.subca}</td>
                            <td class="list-info-text">{$x509.issuer.CN}</td>
                        </tr>
                        <tr>
                            <td class="list-info-title">{$MODLANG.trustoceanssl.enroll.issued.info.signaglor}</td>
                            <td class="list-info-text">SN: {$x509.signatureTypeSN}<br>LN: {$x509.signatureTypeLN}</td>
                        </tr>
                        <tr>
                            <td class="list-info-title">{$MODLANG.trustoceanssl.enroll.issued.info.valid}</td>
                            <td class="list-info-text">{$MODLANG.trustoceanssl.enroll.issued.info.from}: {$x509.validFrom}<br>{$MODLANG.trustoceanssl.enroll.issued.info.to}: {$x509.validTo}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="convertssl">
                <div class="panel-body">
                    <form action="/clientarea.php?action=productdetails&id={$serviceid}" method="post">
                        <input name="modop" value="custom" hidden>
                        <input name="a" value="convertssl" hidden>
                        <div class="form-group">
                            <label class="text-success">粘贴您保存的私钥内容, 点击转换下载适用于各类环境的SSL证书格式。</label>
                        </div>
                        <div class="m-w-416">
                            <div class="form-group">
                                <label for="inputNs1" class="control-label">{$MODLANG.trustoceanssl.enroll.issued.convert.privatekey}</label>
                                <textarea type="text" name="keycode" autocomplete="new-password" class="form-control domnsinputs" placeholder="{$MODLANG.trustoceanssl.enroll.issued.convert.privatekeydesc}" style="min-height: 250px;"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="inputNs1" class="control-label">{$MODLANG.trustoceanssl.enroll.issued.convert.keytoken}(设置 .pfx 密码)</label>
                                <input type="password" name="ktoken" autocomplete="new-password" class="form-control domnsinputs" placeholder="{$MODLANG.trustoceanssl.enroll.issued.convert.keytokendesc}" value="">
                            </div>
                            <div class="form-group">
                                <input type="submit"  class="btn btn-sm btn-success " placeholder="" value="{$MODLANG.trustoceanssl.enroll.issued.convert.convertbtn}">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
{/if}
</div>