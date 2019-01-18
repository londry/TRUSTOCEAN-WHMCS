<style type="text/css">
    .tohide{
        display: none;
    }
    .tohide2{
        display: none;
    }
</style>
<div class="panel panel-default panel-product-details ssl-container">
    <div class="panel-body">
        <div class="section">
            <div class="section-header" style="text-align: left;">
                <h3 class="text-info"><span class="fa fa-cog fa-spin" style="margin-right: 5px;"></span> {$MODLANG.trustoceanssl.enroll.caprocessing.title}</h3>
                <p style="margin-bottom: 0px;">{$MODLANG.trustoceanssl.enroll.caprocessing.desc}</p>
                {if $vars.error}
                    {include file="$template/includes/alert.tpl" type="error" msg=$vars.error textcenter=false idname="alertModuleCustomButtonFailed"}
                {/if}
                {if $vars.info}
                    {include file="$template/includes/alert.tpl" type="success" msg=$vars.info textcenter=false idname="alertModuleCustomButtonFailed"}
                {/if}
                <p>{$MODLANG.trustoceanssl.enroll.caprocessing.desc2}</p>
            </div>
        </div>
    </div>
</div>
{include file="./enroll_dcv_async.tpl"}