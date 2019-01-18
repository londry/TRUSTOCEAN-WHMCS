{if $rlt}
    {if $rlt.status == "success"}
        {include file="$template/includes/alert.tpl" type="success" msg=$rlt.message textcenter=true idname="alertModuleCustomButtonSuccess"}
    {else}
        {include file="$template/includes/alert.tpl" type="error" msg=$rlt.message textcenter=true idname="alertModuleCustomButtonFailed"}
    {/if}
{/if}
<div class="main-content">
        {if !$hasInvoice}
        <p>{$LANG.trustoceanssl.enroll.addsan.desc}</p>
        <form method="post" action="/clientarea.php?action=productdetails&id={$serviceid}&modop=custom&a=addSanToCertOrder">
        <input type="hidden" name="id" value="{$serviceid}">
                        <div class="upgrade-config-option">
            <h3>{lang key="trustoceanssl.enroll.addsan.desc2" total=$oldsan }</h3>
            <div class=" row row-eq-height m-b-neg-24">
                <div class="upgrade-new col-sm-6">
                    <div class="panel panel-default panel-form">
                        <div class="panel-body">
                            <h6>{$LANG.trustoceanssl.enroll.addsan.desc3}</h6>
                            <div class="input-group">
                                <input class="form-control" type="number" required name="newsan" value="" placeholder="{$LANG.trustoceanssl.enroll.addsan.desc4}">
                                <span class="input-group-addon">{lang key="trustoceanssl.enroll.addsan.after" price=$price }</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <input type="submit" value="{$LANG.trustoceanssl.enroll.addsan.btn2} >>" class="btn btn-primary">
        </div>
        </form>
    {else}
    <div class="message message-danger message-lg message-no-data">
        <div class="message-icon">
            <i class="lm lm-close"></i>
        </div>
        <h2 class="message-text">{lang key="trustoceanssl.enroll.addsan.erpage.title" invoiceid=$invoiceId}</h2>
        <h6 class="text-center text-light">{$LANG.trustoceanssl.enroll.addsan.erpage.desc}</h6>
        <p>
            <a href="/viewinvoice.php?id={$invoiceId}" class="btn btn-success" style="margin-right: 20px;">
            {$LANG.trustoceanssl.enroll.addsan.erpage.paybtn}
        </a>
        <a href="/submitticket.php" class="btn btn-default">
            {$LANG.trustoceanssl.enroll.addsan.erpage.submitticket}
        </a>
        </p>
    </div>
    {/if}
</div>

