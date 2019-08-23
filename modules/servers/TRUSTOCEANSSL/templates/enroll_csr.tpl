<style type="text/css">
    .tohide{
        display: none;
    }
    .tohide2{
        display: none;
    }
</style>
<div class="section">
    <div class="section-header" style="margin: 0px 15px;">
        <h3>{$MODLANG.trustoceanssl.enroll.setupone.title}</h3>
        <p>{$MODLANG.trustoceanssl.enroll.setupone.desc}</p>
        {if $result}
			{include file="$template/includes/alert.tpl" type="warning" msg=$result textcenter=true}
		{/if}
    </div>
    <div class="section-body" style="text-align: left;">
        <form data-certificate-baseinfo role="form" method="post">
            <input type="hidden" name="id" value="{$vars.serviceid}">
            <input type="hidden" name="responsetype" value="json">
            <div class="panel-group panel-group-condensed is-selected" >
                <div class="panel panel-check" >
                    <div class="panel-collapse collapse in"  style="">
                        <div class="panel-body">
                            <div class="m-w-416">
                                <div class="form-group">
                                    <label for="inputCsr" class="control-label">{$MODLANG.trustoceanssl.enroll.choosecsr}</label>
                                    <select name="csroption" class="form-control" onclick="csrchecker2('{$vars.ismultidomain}');">
                                        <option value="generate">{$MODLANG.trustoceanssl.enroll.generatenewcsr}</option>
                                        <option value="upload">{$MODLANG.trustoceanssl.enroll.uploadnewcsr}</option>
                                    </select>
                                </div>
                                <div class="form-group" data-input-csr  style="display: none;">
                                    <label for="inputNs1" class="control-label">{$MODLANG.trustoceanssl.enroll.issued.inputcsr}</label>
                                    <textarea type="text" name="csrcode" autocomplete="new-password" class="form-control domnsinputs" placeholder="{$MODLANG.trustoceanssl.enroll.issued.inputcsrplaceholder}" value="" style="min-height: 200px;"></textarea>
                                </div>
                                {if $vars.ismultidomain !== "on"}
                                <div class="form-group" data-sinledomaininput>
                                    <label for="inputNs1" class="control-label">{$MODLANG.trustoceanssl.enroll.setupone.commonname}</label>
                                    <input type="text" name="domain" autocomplete="new-password" class="form-control domnsinputs" placeholder="{$MODLANG.trustoceanssl.enroll.setupone.commonnameplaceholder}" value="">
                                </div>
                                {else}
                                <div class="text-info">{$MODLANG.trustoceanssl.enroll.issued.sannotice}</div>
                                <div class="form-group">
                                    <label for="inputNs1" class="control-label">{$MODLANG.trustoceanssl.enroll.issued.inputsandomains}</label>
                                    <textarea type="text" name="domainlist" autocomplete="new-password" class="form-control domnsinputs" placeholder="{$MODLANG.trustoceanssl.enroll.issued.inputsandomainsplaceholder}" style="min-height: 120px" value=""></textarea>
                                </div>
                                {/if}

                                {if $vars.configoption2 !== "dv"}
                                    <hr>
                                    <div class="text-info">{$MODLANG.trustoceanssl.enroll.organization.notice}</div>
                                    <div class="form-group">
                                        <label for="inputNs1" class="control-label">{$MODLANG.trustoceanssl.enroll.organization.organization_name}</label>
                                        <input type="text" name="organization_name" required autocomplete="new-password" class="form-control domnsinputs" placeholder="" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputNs1" class="control-label">{$MODLANG.trustoceanssl.enroll.organization.organizationalUnitName}</label>
                                        <input type="text" name="organizationalUnitName" autocomplete="new-password" class="form-control domnsinputs" placeholder="" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputNs1" class="control-label">{$MODLANG.trustoceanssl.enroll.organization.registered_address_line1}</label>
                                        <input type="text" name="registered_address_line1" autocomplete="new-password" class="form-control domnsinputs" placeholder="" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputNs1" class="control-label">{$MODLANG.trustoceanssl.enroll.organization.registerted_no}</label>
                                        <input type="text" name="registerted_no" autocomplete="new-password" class="form-control domnsinputs" placeholder="" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputNs1" class="control-label">{$MODLANG.trustoceanssl.enroll.organization.country}</label>
                                        <input type="text" name="country" autocomplete="new-password" class="form-control domnsinputs" placeholder="CN" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputNs1" class="control-label">{$MODLANG.trustoceanssl.enroll.organization.state}</label>
                                        <input type="text" name="state" autocomplete="new-password" class="form-control domnsinputs" placeholder="" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputNs1" class="control-label">{$MODLANG.trustoceanssl.enroll.organization.city}</label>
                                        <input type="text" name="city" autocomplete="new-password" class="form-control domnsinputs" placeholder="" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputNs1" class="control-label">{$MODLANG.trustoceanssl.enroll.organization.postal_code}</label>
                                        <input type="text" name="postal_code" autocomplete="new-password" class="form-control domnsinputs" placeholder="" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputNs1" class="control-label">{$MODLANG.trustoceanssl.enroll.organization.organization_phone}</label>
                                        <input type="text" name="organization_phone" autocomplete="new-password" class="form-control domnsinputs" placeholder="" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputNs1" class="control-label">{$MODLANG.trustoceanssl.enroll.organization.date_of_incorporation}</label>
                                        <input type="text" name="date_of_incorporation" autocomplete="new-password" class="form-control domnsinputs" placeholder="2016-03-18" value="">
                                    </div>
                                    <hr>
                                    <div class="text-info">{$MODLANG.trustoceanssl.enroll.organization.contactnotice}</div>
                                    <div class="form-group">
                                        <label for="inputNs1" class="control-label">{$MODLANG.trustoceanssl.enroll.organization.contact_name}</label>
                                        <input type="text" name="contact_name" autocomplete="new-password" class="form-control domnsinputs" placeholder="" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputNs1" class="control-label">{$MODLANG.trustoceanssl.enroll.organization.contact_title}</label>
                                        <input type="text" name="contact_title" autocomplete="new-password" class="form-control domnsinputs" placeholder="" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputNs1" class="control-label">{$MODLANG.trustoceanssl.enroll.organization.contact_phone}</label>
                                        <input type="text" name="contact_phone" autocomplete="new-password" class="form-control domnsinputs" placeholder="" value="">
                                    </div>
                                {/if}
                                <div class="form-group">
                                    <label for="inputNs1" class="control-label">{$MODLANG.trustoceanssl.enroll.setupone.emailaddress}</label>
                                    <input type="text" name="email" autocomplete="new-password" class="form-control domnsinputs" placeholder="{$MODLANG.trustoceanssl.enroll.setupone.emailaddressplaceholder}" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script type="application/javascript">

                    $(document).ready(function() {
                        csrchecker2('{$vars.ismultidomain}');
                    });

                   function csrchecker2(multidoman){
                     if($('select[name=csroption]').val() ==='upload'){
                                $('div[data-input-csr]').show();
                                if(multidoman !== "on" && $('div[data-sinledomaininput]').length >0){
                                    $('div[data-sinledomaininput]').hide();
                                }
                                }else{
                                    $('div[data-input-csr]').hide();
                                    if(multidoman !== "on" && $('div[data-sinledomaininput]').length >0){
                                        $('div[data-sinledomaininput]').show();
                                    }
                                }
                            }

                </script>
            </div>
        </form>
            <div class="form-actions">
                <button class="btn btn-primary"  onclick="tryAddCertificateInformation(this)" data-serviceid="{$serviceid}" data-loading-text="Processing...">{$MODLANG.trustoceanssl.enroll.setupone.nextsetup}</button>
            </div>
    </div>
</div>