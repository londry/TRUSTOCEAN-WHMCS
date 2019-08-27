{* jsApplicationLibary *}
<script src="/modules/servers/TRUSTOCEANSSL/assets/js/adminarea.js" type='application/javascript'></script>
{* admin area style sheets*}
<link rel="stylesheet" href="/modules/servers/TRUSTOCEANSSL/assets/css/adminarea.css">
<style>
    #contentarea>div>h1{
        display: none;
    }
</style>
<ul class="nav nav-tabs admin-tabs" role="tablist" style="padding: 15px 10px;background-color: #000000; border-radius: 3px;">
    <li>
        <img src="/modules/servers/TRUSTOCEANSSL/assets/img/trustocean-seal.svg" height="20px;">
    </li>
    <li>
        <a style="border-radius: 0px; background-color: black; border: none; color: #ffffff;" class="tab-top active" href="#tabOrders" role="tab" data-toggle="tab" id="tabLink2" data-tab-id="2" aria-expanded="true">证书管理</a>
    </li>
    <li>
        <a style="border-radius: 0px; background-color: black; border: none; color: #ffffff;" class="tab-top" href="#tabSystem" role="tab" data-toggle="tab" id="tabLink1" data-tab-id="1" aria-expanded="true">系统信息</a>
    </li>
    <li>
        <a style="border-radius: 0px; background-color: black; border: none; color: #ffffff;" class="tab-top" href="#tabApis" role="tab" data-toggle="tab" id="tabLink3" data-tab-id="3" aria-expanded="true">API 设置</a>
    </li>
</ul>
<div class="tab-content admin-tabs" style="background-color: #ffffff;">
    <div class="tab-pane" id="tabSystem">
        <table class="datatable to-table" width="100%">
            <tbody>
                <tr>
                    <td width="150px;">系统版本</td>
                    <td>1.1.0 Beta</td>
                </tr>
                <tr>
                    <td width="150px;">链接状态</td>
                    <td>正常</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="tab-pane active" id="tabOrders">
        {include file="./includes/adminOrderList.tpl"}
    </div>
    <div class="tab-pane" id="tabApis">
        asdfsad
    </div>
</div>