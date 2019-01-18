<link rel="stylesheet" href="/modules/servers/TRUSTOCEANSSL/assets/css/clientarea.css">
<script src="/modules/servers/TRUSTOCEANSSL/assets/js/clipboard.min.js"></script>

{if $language === "chinese"}
    <script src="/modules/servers/TRUSTOCEANSSL/assets/js/lang/chinese.js"></script>
{else}
    <script src="/modules/servers/TRUSTOCEANSSL/assets/js/lang/english.js"></script>
{/if}

{include file="$assetsPath/js/clientarea-js.tpl" MODLANG=$MODLANG }

<div class="section">
    <div class="section-header">
        <h3>{$MODLANG.trustoceanssl.enroll.reissue.title}</h3>
        <p>{$MODLANG.trustoceanssl.enroll.reissue.description}</p>
        {if $result}
			{include file="$template/includes/alert.tpl" type="warning" msg=$result textcenter=true}
		{/if}
    </div>
    <div class="section-body" style="text-align: left;">
        <form data-certificate-reissueinfo  role="form" method="post">
            <input type="hidden" name="id" value="{$serviceid}">
            <input type="hidden" name="responsetype" value="json">
            <div class="panel-group panel-group-condensed is-selected" data-inputs-container="">
                <script type="text/javascript">
                    $(document).ready(function(){
                        var multidomain = "{$ismultidomain}";
                        reissueCsrFunction(multidomain);
                    });
                </script>
                <div class="panel panel-check" data-virtual-input="">
                    <div class="panel-collapse collapse in" data-input-collapse="" aria-expanded="true" style="">
                        <div class="panel-body">
                            <div class="m-w-416">
                                <div class="form-group">
                                    <label for="inputCsr" class="control-label">{$MODLANG.trustoceanssl.enroll.choosecsr}</label>
                                    <select name="csroption" class="form-control" onclick="reissueCsrFunction('{$ismultidomain}');">
                                        <option value="seamcsr">{$MODLANG.trustoceanssl.enroll.useoldcsr}</option>
                                        <option value="generate">{$MODLANG.trustoceanssl.enroll.generatenewcsr}</option>
                                        <option value="upload">{$MODLANG.trustoceanssl.enroll.uploadnewcsr}</option>
                                    </select>
                                </div>
                                <div class="form-group" data-input-csr  style="display: none;">
                                    <label for="inputNs1" class="control-label">{$MODLANG.trustoceanssl.enroll.setupone.inputcsr}</label>
                                    <textarea type="text" name="csrcode" autocomplete="new-password" class="form-control domnsinputs" placeholder="{$MODLANG.trustoceanssl.enroll.setupone.csrplaceholder}" value="" style="min-height: 200px;"></textarea>
                                </div>
                                {if $ismultidomain !== "on"}
                                <div class="form-group" data-domainNameInput>
                                    <label for="inputNs1" class="control-label">{$MODLANG.trustoceanssl.enroll.setupone.commonname}</label>
                                    <input type="text" name="domain" autocomplete="new-password" class="form-control domnsinputs" placeholder="{$MODLANG.trustoceanssl.enroll.setupone.commonnameplaceholder}" value="">
                                </div>
                                {else}
                                <div class="form-group" data-domainNamesInput>
                                    <label for="inputNs1" class="control-label">{$MODLANG.trustoceanssl.enroll.setupone.inputdomain}</label>
                                    <textarea type="text" name="domainlist" autocomplete="new-password" class="form-control domnsinputs" style="min-height: 120px" value="">{foreach from=$domains key=ik item=domain}{$domain}
{/foreach}</textarea>
                                </div>
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
            <div class="form-actions">
                <button class="btn btn-primary"  onclick="tryReissueCertificate(this)" data-serviceid="{$serviceid}" data-loading-text="{$MODLANG.trustoceanssl.enroll.status.processin}...">{$MODLANG.trustoceanssl.enroll.setupone.reissuesubmit}</button>
            </div>
    </div>
</div>