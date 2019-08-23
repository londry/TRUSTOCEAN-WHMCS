<style type="text/css">
    .tohide{
        display: none;
    }
    .tohide2{
        display: none;
    }
</style>
<div class="">
    <div class="panel-body">
        <div class="section">
            <div class="section-header" style="text-align: left;">
                <h3 class=""><span class="fa fa-cog fa-spin" style="margin-right: 5px;"></span> 验证域名</h3>
                {if $vars.error}
                    {include file="$template/includes/alert.tpl" type="error" msg=$vars.error textcenter=false idname="alertModuleCustomButtonFailed"}
                {/if}
                {if $vars.info}
                    {include file="$template/includes/alert.tpl" type="success" msg=$vars.info textcenter=false idname="alertModuleCustomButtonFailed"}
                {/if}
            </div>
        </div>
    </div>
</div>
{include file="./enroll_dcv_async.tpl"}