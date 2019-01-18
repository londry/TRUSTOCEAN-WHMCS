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
        <h3 style="padding-left: 22px;"><i class="oi fa fa-spin" data-glyph="aperture" style="margin-left: -23px;margin-top: 2px; position: absolute; padding-bottom: 3px;"></i> 正在对您提交的企业信息进行预验证</h3>
        <p>1.为协助您顺利进行企业证书认证，我们将会对您提交的以下企业信息进行预验证，预验证通过后由COMODO CA进行复核签发证书。<br>2.预审核过程中我们可能会通过电话 029-88188289 联系您，过程中若存疑问您可通过工单，在线客服，或通过热线联系我们。</p>
        {if $vars.error}
            {include file="$template/includes/alert.tpl" type="error" msg=$vars.error textcenter=false idname="alertModuleCustomButtonFailed"}
        {/if}
        {if $vars.info}
            {include file="$template/includes/alert.tpl" type="success" msg=$vars.info textcenter=false idname="alertModuleCustomButtonFailed"}
        {/if}
        <div class="table-container clearfix">
        <table>
            <tbody>
                <tr role="row">
                    <td>组织名称</td>
                    <td>{$vars.orginfo.organization_name}</td>
                </tr>
                <tr role="row">
                    <td>组织编号</td>
                    <td>{$vars.orginfo.registered_no}</td>
                </tr>
                <tr role="row">
                    <td>登记日期</td>
                    <td>{$vars.orginfo.date_of_incorporation}</td>
                </tr>
                <tr role="row">
                    <td>地理信息</td>
                    <td>{$vars.orginfo.country} - {$vars.orginfo.state} - {$vars.orginfo.city} - {$vars.orginfo.postal_code}</td>
                </tr>
                <tr role="row">
                    <td>执照地址</td>
                    <td>{$vars.orginfo.registered_address_line1}</td>
                </tr>
                <tr role="row">
                    <td>联系电话</td>
                    <td>{$vars.orginfo.organization_phone}<p style="color: #2196F3 !important;">
                            请您将企业名称、地址和联系电话（{$vars.orginfo.organization_phone}）登记到下列公开数据库中的任意一个, 并确保生效可查询
                            <br>
                            <ul style="color: #2196F3 !important;">
                                <li>百度地图 - map.baidu.com （登记免费, 1-7工作日可完成可查）</li>
                                <li>邓白氏DUNS - www.upik.de（付费, 7-30工作日可完成可查）</li>
                                <li>黄页(YellowPages) - www.yellowpages.com （适用于港台、海外企业, 1-15工作日可完成可查）</li>
                                <li>谷歌商家(GoogleBusiness) - www.google.com （适用于港台、海外企业, 1-15工作日可完成可查）</li>
                            </ul>
                        </p>
                    </td>
                </tr>
                <tr role="row">
                    <td>申请联系人</td>
                    <td>{$vars.orginfo.contact_name}（{$vars.orginfo.contact_title}）<br>{$vars.orginfo.contact_email}<br>{$vars.orginfo.contact_phone}</td>
                </tr>
            </tbody>
        </table>
        </div>
        <p style="margin-top: 20px;">
            <a href="/submitticket.php?step=2&deptid=1&subject=[组织信息验证][SSLOrder-{$serviceid}]请帮助我进行SSL证书审核&serviceid=S{$serviceid}" target="_blank" class="btn btn-info btn-checkout"><i class="fas fa-ticket ls ls-new-window"></i> 提交工单</a>
        </p>
    </div>
</div>