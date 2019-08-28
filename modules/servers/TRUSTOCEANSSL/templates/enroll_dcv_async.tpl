<style type="text/css">
    .tohide{
        display: none;
    }
    .tohide2{
        display: none;
    }
</style>

<div class="section" style="text-align: left;">
    <div class="section-header" style="padding-left: 15px;">
        <p>请您为域名选择验证方式并按照验证说明完成验证, 若同时存在多条子域名, 仅需要为顶级域名做验证即可。</p>
        {if $vars.error}
            {include file="$template/includes/alert.tpl" type="error" msg=$vars.error textcenter=false idname="alertModuleCustomButtonFailed"}
        {/if}
        {if $vars.info}
            {include file="$template/includes/alert.tpl" type="success" msg=$vars.info textcenter=false idname="alertModuleCustomButtonFailed"}
        {/if}
    </div>
    {if $vars.configoption2 !== "dv"}
        <div class="text-success" style="padding: 10px; border: 1px solid #4CAF50; border-radius: 3px;margin-bottom: 10px;">
            <h3 style="color:#198810!important">{$MODLANG.trustoceanssl.enroll.organization.info2}</h3>
            <p>{$MODLANG.trustoceanssl.enroll.organization.info2desc}{$vars.trustoceanid}</p>
        </div>
    {/if}
    <ul class="tabul list-group list-group-tab-nav" style="padding-left: 15px;">
        <button onclick="$('.dnd-info').toggle();" data-dcv class="btn btn-sm btn-default btn-se1" data-toggle="tab" style="margin-right: 10px;margin-top: 10px;">{$MODLANG.trustoceanssl.enroll.setup3.dndinfo}</button>
        {if $vars.ismultidomain === "on"}
            <button onclick="syncMDCDomainStatus();" data-fetch-dcvstatus data-loading-text="{$MODLANG.trustoceanssl.enroll.btn.loadingtext}..." class="btn btn-sm btn-primary" style="margin-right: 10px;margin-top: 10px;">{$MODLANG.trustoceanssl.enroll.btn.updatedcv}</button>
        {/if}
        <button onclick="syncGetCertStatus(this);" data-serviceid="{$serviceid}" data-loading-text="{$MODLANG.trustoceanssl.enroll.btn.loadingtext}..." class="btn btn-sm btn-primary" style="margin-right: 10px;margin-top: 10px;">{$MODLANG.trustoceanssl.enroll.btn.checkissue}</button>
        <button onclick="tryToRecheckDCV(this);" data-loading-text="{$MODLANG.trustoceanssl.enroll.btn.loadingtext}..." data-redo-dcv="{$serviceid}" data-dcv class="btn btn-sm btn-success" style="margin-right: 10px;margin-top: 10px;">{$MODLANG.trustoceanssl.enroll.btn.retrydcv}</button>
    </ul>
    <div class="dnd-info" style="display: none;margin-left: 15px; margin-right: 15px;">
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
        <div class="" data-inputs-container="">
            <div class="panel-body">
                <div class="table-container clearfix" style="border:none;max-height: none;">
                    <table id="tableDCVDomainList" data-serviceid="{$serviceid}" class="table to-dcv-table">
                        <thead>
                            <tr>
                                <th data-priority="1" style="text-align: left;"><span><span>{$MODLANG.trustoceanssl.enroll.setup3.table.domain}</span><span class="sorting-arrows"></span></span></th>
                                <th data-priority="2" style="text-align: left;"><span><span>{$MODLANG.trustoceanssl.enroll.setup3.table.status}</span><span class="sorting-arrows"></span></span></th>
                                <th data-priority="3" style="text-align: left;"><span><span>{$MODLANG.trustoceanssl.enroll.setup3.table.method}</span><span class="sorting-arrows"></span></span></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        {foreach from=$vars.dcvinfo key=domain item=info}
                            <tr data-ramoval-div="{$domain|md5}" class="domaininfo">
                                <td>{$domain}</td>
                                <td data-ca-status-td="{$domain|md5}">
                                    {if $info.status eq 'needverification'}
                                        <span class="text-warning" style="padding-left: 10px;"><i class="oi fa fa-spin" data-glyph="aperture" style="margin-left: -18px;margin-top: 2px; position: absolute; padding-bottom: 2px;"></i> Fetching...</span>
                                    {elseif $info.status eq 'verified'}
                                        {if $info.method === 'email'}
                                            <span class="" style="padding-left: 10px;"><i class="oi" data-glyph="circle-check" style="margin-left: -18px;margin-top: 2px; position: absolute; padding-bottom: 2px;"></i> {$MODLANG.trustoceanssl.enroll.status.emailsent}</span><br>{$info.email}
                                        {else}
                                            <span class="text-success" style="padding-left: 10px;"><i class="oi" data-glyph="circle-check" style="margin-left: -18px;margin-top: 2px; position: absolute; padding-bottom: 2px;"></i> {$MODLANG.trustoceanssl.enroll.status.verified}</span>
                                        {/if}
                                    {/if}
                                </td>
                                <td style="display: flex;" >
                                {*<form style="display: flex;" action="{$smarty.server.PHP_SELF}?action=productdetails&id={$vars.serviceid}&modop=custom&a=setdcvforall" method="post">*}
                                    <div data-dcv-contain="{$domain|md5}" style="display: none;">
                                    <select name="domaindcvmathod" {if $domain === $vars.domains.0}data-maindcv="true"{else}data-sandcv="true"{/if} dcv-cert-id={$serviceid} dcv-domain-name={$domain} ca-dcv-domain={$domain|md5} class="form-control input-sm" style="width: 120px; text-align: center;" {if $info.method neq 'email'}{if $info.status eq 'verified'}disabled{/if}{/if}>
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
                                    {* 部分服务器可能会设置CC防护，为了避免触发防御，同时降低单个操作的多个请求，暂时禁用提交到签发系统之后的批量化修改DCV操作 *}
                                    {*{if $vars.ismultidomain === "on"}*}
                                        {*{if $domain === $vars.domains.0}*}
                                        {*<input type="button" onclick="selectAllDcvMethod()" data-toggle="tooltip" data-title="{$MODLANG.trustoceanssl.enroll.setup3.table.selectalldesc}" data-original-title="" title="" class="btn btn-xs btn-default" value="{$MODLANG.trustoceanssl.enroll.setup3.table.selectall}">*}
                                        {*{/if}*}
                                    {*{/if}*}
                                    {*</form>*}
                                    </div>
                                </td>
                                <td>
                                    {if $vars.ismultidomain === "on"}
                                        {if $info.status === "needverification"}
                                            <button style="display: none;"  data-ca-remove-btn="{$domain|md5}" data-toggle="tooltip" data-serviceid="{$serviceid}" data-title="{$MODLANG.trustoceanssl.enroll.setup3.table.removedesc}" onclick="tryRemoveDomain(this, '{$domain}','{$domain|md5}')" data-loading-text="移除中.." data-original-title="" title="" class="btn btn-xs btn-danger" value="">{$MODLANG.trustoceanssl.enroll.setup3.table.remove}</button>
                                        {/if}
                                    {/if}
                                </td>
                            </tr>
                            <tr dcv-tds="{$domain|md5}" {if $info.status eq 'verified'}style="display: none;"{/if}>
                                <td colspan="4" dcv-change style="display: none;">
                                    <p>{$MODLANG.trustoceanssl.enroll.setup3.table.dcv.change.process}</p>
                                </td>
                                <td colspan="4" dcv-tab="dns" style="display: none;">
                                    <p>{$MODLANG.trustoceanssl.enroll.setup3.table.dcv.host}: <span class="yate dcv-dnsinfo">{$vars.csrhash.dns.purehost}{if $info.subdomain neq ''}.{$info.subdomain}{/if}</span> <button class="btn btn-xs btn-info" data-clipbutton data-clipboard-text="{$vars.csrhash.dns.purehost}{if $info.subdomain neq ''}.{$info.subdomain}{/if}">{$MODLANG.trustoceanssl.enroll.setup3.table.dcv.copy}</button>
                                        <br>{$MODLANG.trustoceanssl.enroll.setup3.table.dcv.type}: <span class="yate">{$MODLANG.trustoceanssl.enroll.setup3.table.dcv.typecname}</span>
                                        <br>{$MODLANG.trustoceanssl.enroll.setup3.table.dcv.point}: <p class="yate dcv-dnsinfo">{$vars.csrhash.dns.purevalue|strtolower}.{$vars.uniqueid|strtolower}.comodoca.com</p> <button class="btn btn-xs btn-info" data-clipbutton data-clipboard-text="{$vars.csrhash.dns.purevalue|strtolower}.{$vars.uniqueid|strtolower}.comodoca.com">{$MODLANG.trustoceanssl.enroll.setup3.table.dcv.copy}</button>
                                    </p>
                                </td>
                                <td colspan="4" dcv-tab="http" style="display: none;">
                                    <p>{$MODLANG.trustoceanssl.enroll.setup3.table.dcv.download}: <span class="yate"><a href="javascript:;" onclick="dcvDownloadFile('{$vars.csrhash.http.firstline}\ncomodoca.com\n{$vars.uniqueid|strtolower}','{$vars.csrhash.http.filename}');">{$vars.csrhash.http.filename}</a></span>
                                        <br>{$MODLANG.trustoceanssl.enroll.setup3.table.dcv.upload}: http://{if $info.subdomain}{$info.subdomain}.{/if}{$info.topdomain}/.well-known/pki-validation/  {$MODLANG.trustoceanssl.enroll.setup3.table.dcv.folder}
                                        <br>{$MODLANG.trustoceanssl.enroll.setup3.table.dcv.access}: <span class="yate">
                                            <a href="http://{if $info.subdomain}{$info.subdomain}.{/if}{$info.topdomain}/.well-known/pki-validation/{$vars.csrhash.http.filename}" target="_blank">
                                                http://{if $info.subdomain}{$info.subdomain}.{/if}{$info.topdomain}/.well‐known/pki‐validation/{$vars.csrhash.http.filename}
                                            </a>
                                        </span>
                                    </p>
                                </td>
                                <td colspan="4" dcv-tab="https" style="display: none;">
                                    <p>{$MODLANG.trustoceanssl.enroll.setup3.table.dcv.download}: <span class="yate"><a href="javascript:;" onclick="dcvDownloadFile('{$vars.csrhash.http.firstline}\ncomodoca.com\n{$vars.uniqueid|strtolower}','{$vars.csrhash.http.filename}');">{$vars.csrhash.http.filename}</a></span>
                                        <br>{$MODLANG.trustoceanssl.enroll.setup3.table.dcv.upload}: https://{if $info.subdomain}{$info.subdomain}.{/if}{$info.topdomain}/.well-known/pki-validation/ {$MODLANG.trustoceanssl.enroll.setup3.table.dcv.folder}
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
                            $(document).ready(function(){
                                syncMDCDomainStatus();
                            });

                            function selectAllDcvMethod(){var b=$("select[data-maindcv] option:selected").attr("data-method");if("email"===b){var c=$("select[data-maindcv] option:selected");$("select[data-sandcv]").each(function(){var a=$(this).children("option[data-emailkey="+c.attr("data-emailkey")+"]");$(a).each(function(){$(this).attr("selected",!0).trigger("change")})})}"dns"===b&&(c=$("select[data-maindcv] option:selected"),$("select[data-sandcv]").each(function(){var a=$(this).children("option[data-method=dns]");$(a).each(function(){$(this).attr("selected",
!0).trigger("change")})}));"http"===b&&(c=$("select[data-maindcv] option:selected"),$("select[data-sandcv]").each(function(){var a=$(this).children("option[data-method=http]");$(a).each(function(){$(this).attr("selected",!0).trigger("change")})}));"https"===b&&(c=$("select[data-maindcv] option:selected"),$("select[data-sandcv]").each(function(){var a=$(this).children("option[data-method=https]");$(a).each(function(){$(this).attr("selected",!0).trigger("change")})}))};
                        </script>
                        {/literal}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <p style="color: #909090;margin: 10px 15px;">
            {$MODLANG.trustoceanssl.enroll.setup3.table.dcv.submit.desc}</p>
        <p style="margin: 10px 15px;">{$MODLANG.trustoceanssl.enroll.desc90}</p>

    </div>
</div>