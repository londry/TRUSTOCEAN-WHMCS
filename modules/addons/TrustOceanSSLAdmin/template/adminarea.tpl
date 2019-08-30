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
    <li>
        <a style="border-radius: 0px; background-color: black; border: none; color: #ffffff;" class="tab-top" href="#tabInterface" role="tab" data-toggle="tab" id="tabLink4" data-tab-id="4" aria-expanded="true">功能设置</a>
    </li>
</ul>
<div class="tab-content admin-tabs" style="background-color: #ffffff;">
    <div class="tab-pane" id="tabSystem">
        请稍等, 系统信息加载中....
    </div>
    <div class="tab-pane" id="tabInterface">
        <table class="datatable to-table" width="100%">
            <form action="addonmodules.php?module=TrustOceanSSLAdmin&action=updateInterfaceConfig" method="post">
            <tbody>
                <tr>
                    <td width="150px;">网站签章</td>
                    <td>
                        <select name="siteseal" class="form-control select-inline">
                            <option value="show" {if $moduleSetting.siteseal eq "show"}selected="selected"{/if}>开启</option>
                            <option value="hidden" {if $moduleSetting.siteseal eq "hidden"}selected="selected"{/if}>关闭</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                        <button type="submit" class="btn btn-success btn-sm">保 存</button>
                    </td>
                </tr>
            </tbody>
            </form>
        </table>
    </div>
    <div class="tab-pane active" id="tabOrders">
        {include file="./includes/adminOrderList.tpl"}
    </div>
    <div class="tab-pane" id="tabApis">
        <form action="addonmodules.php?module=TrustOceanSSLAdmin&action=updateApiConfig" method="post">
            <table class="datatable to-table" style="width: 100%;">
                <tbody>
                    <tr>
                        <td style="width: 150px;">
                            API 账户
                        </td>
                        <td>
                            <input name="apiusername" value="{$moduleSetting.username}" class="form-control input-300" placeholder="someone.api@trustocean.com">
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 150px;">
                            API Token
                        </td>
                        <td>
                            <input name="apipassword" value="{$moduleSetting.password}" class="form-control input-500" placeholder="">
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 150px;">
                            API Salt
                        </td>
                        <td>
                            <input name="apiunicodesalt" value="{$moduleSetting.salt}" class="form-control input-300" placeholder="">
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 150px;">
                            API 接入点
                        </td>
                        <td>
                            <select name="apiservertype" class="form-control select-inline">
                                <option value="CN-Beijing" {if $moduleSetting.servertype eq "CN-Beijing"}selected="selected"{/if}>CN-Beijing</option>
                                <option value="UK-London" {if $moduleSetting.servertype eq "UK-London"}selected="selected"{/if}>UK-London</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 150px;">
                            API PUSH 私钥
                        </td>
                        <td>
                            <textarea name="privatekey" class="form-control input-500" style="min-height: 250px;" placeholder="">{$moduleSetting.privateKey}</textarea>
                            <p>提示: 用于验证 PUSH 通知的 1024位 RSA Private Key, 要想使用PUSH加密功能, 您还需要同时在 <a target="_blank" href="https://console.trustocean.com/partner/api-setting">API配置页面</a> 填写此私钥对应的RSA公钥。</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-success btn-sm">保 存</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p>
                提示: 请您访问 经销商账户的 <a target="_blank" href="https://console.trustocean.com/partner/api-setting">API设置页面</a> 查询相关配置信息.
            </p>
        </form>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function(){
        // 异步加载本地和远端的系统版本信息
        jQuery.post('addonmodules.php?module=TrustOceanSSLAdmin&action=getSystemStatus', {}, function(resp){
            jQuery("#tabSystem").html(resp);
        })
    });
</script>