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
        <h3>{$LANG.trustoceanssl.enroll.setup2.title}</h3>
        <p>
            {lang key="trustoceanssl.enroll.setup2.desc" domainCount=$vars.domaintotal}
        </p>


        {if $vars.error}
            {include file="$template/includes/alert.tpl" type="error" msg=$vars.error textcenter=false idname="alertModuleCustomButtonFailed"}
        {/if}
    </div>
    <div class="section-body">

        <form role="form" method="post" action="{if $smarty.session.uid === 1}/user/certificate/{$vars.serviceid}/details{else}{$smarty.server.PHP_SELF}?action=productdetails&id={$vars.serviceid}&modop=custom&a=enroll{/if}">
            <input type="hidden" name="id" value="{$vars.serviceid}">
            <div class="panel panel-default panel-form" data-inputs-container="">
                <div class="panel-body">
					<div class="form-group">
						<label for="domains">{lang key="trustoceanssl.enroll.setup2.inputdomain" domainCount=$vars.domaintotal}</span>)</label>
						<textarea name="domains" id="domains" class="form-control" rows="6">{if $vars.domains}{$vars.domains}{/if}</textarea>
					</div>
				</div>
            </div>
            <div class="form-actions">
                <input type="submit" class="btn btn-primary" value="{lang key="trustoceanssl.enroll.setup2.next2"}">
            </div>
        </form>
    </div>
</div>