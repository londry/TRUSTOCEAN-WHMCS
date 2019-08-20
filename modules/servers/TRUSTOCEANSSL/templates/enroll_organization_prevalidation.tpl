<style type="text/css">
    .tohide{
        display: none;
    }
    .tohide2{
        display: none;
    }
</style>
<div class="section" style="text-align: left !important;">
    <div class="section-header">
        <h3>{$MODLANG['trustoceanssl']['certview']['orgpre']['title']}</h3>
        <p>{$MODLANG['trustoceanssl']['certview']['orgpre']['desc1']}<br>{$MODLANG['trustoceanssl']['certview']['orgpre']['desc2']}</p>
        {if $vars.error}
            {include file="$template/includes/alert.tpl" type="error" msg=$vars.error textcenter=false idname="alertModuleCustomButtonFailed"}
        {/if}
        {if $vars.info}
            {include file="$template/includes/alert.tpl" type="success" msg=$vars.info textcenter=false idname="alertModuleCustomButtonFailed"}
        {/if}
        <div class="table-container clearfix">
        <table class="infotable">
            <tbody>
                <tr role="row">
                    <td>{$MODLANG['trustoceanssl']['certview']['orgpre']['orgName']}</td>
                    <td>{$vars.orginfo.organization_name}</td>
                </tr>
                <tr role="row">
                    <td>{$MODLANG['trustoceanssl']['certview']['orgpre']['orgNo']}</td>
                    <td>{$vars.orginfo.registered_no}</td>
                </tr>
                <tr role="row">
                    <td>{$MODLANG['trustoceanssl']['certview']['orgpre']['orgRegistertedAt']}</td>
                    <td>{$vars.orginfo.date_of_incorporation}</td>
                </tr>
                <tr role="row">
                    <td>{$MODLANG['trustoceanssl']['certview']['orgpre']['orgLocation']}</td>
                    <td>{$vars.orginfo.country} - {$vars.orginfo.state} - {$vars.orginfo.city} - {$vars.orginfo.postal_code}</td>
                </tr>
                <tr role="row">
                    <td>{$MODLANG['trustoceanssl']['certview']['orgpre']['orgLocationedAt']}</td>
                    <td>{$vars.orginfo.registered_address_line1}</td>
                </tr>
                <tr role="row">
                    <td>{$MODLANG['trustoceanssl']['certview']['orgpre']['orgContactPhone']}</td>
                    <td>{$vars.orginfo.organization_phone}
                        <p style="font-size: 13px;">{$MODLANG['trustoceanssl']['certview']['orgpre']['trustedDatabaseDesc']}</p>
                        <div class="infoMessage">{$MODLANG['trustoceanssl']['certview']['orgpre']['trustedDatabaseList']}</div>
                    </td>
                </tr>
                <tr role="row">
                    <td>{$MODLANG['trustoceanssl']['certview']['orgpre']['orgContact']}</td>
                    <td>{$vars.orginfo.contact_name}（{$vars.orginfo.contact_title}）<br>{$vars.orginfo.contact_email}<br>{$vars.orginfo.contact_phone}</td>
                </tr>
            </tbody>
        </table>
        </div>
        <p style="margin-top: 20px;">
            <a href="/submitticket.php" target="_blank" class="btn btn-info btn-checkout"><i class="fas fa-ticket ls ls-new-window"></i> {$MODLANG['trustoceanssl']['enroll']['caprocessing']['submitticket']}</a>
        </p>
    </div>
</div>