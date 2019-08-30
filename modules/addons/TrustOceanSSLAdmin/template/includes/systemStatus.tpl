<table class="datatable to-table" width="100%">
    <tbody>
    <tr>
        <td width="150px;">已安装版本</td>
        <td>{$moduleSetting.modVersion}  {if $moduleSetting.modVersion >= $remoteMSE.current_version}<span class="versionTagSafe">最新版本</span>{else}<span class="versionTagunSafe">不是最新版本</span>{/if}</td>
    </tr>
    {if $moduleSetting.modVersion < $remoteMSE.current_version}
        <tr>
            <td width="150px;">最新版本</td>
            <td>{$remoteMSE.current_version}</td>
        </tr>
        <tr>
            <td width="150px;">最新版发布日期</td>
            <td>{$remoteMSE.publish_date}</td>
        </tr>
        <tr>
            <td width="150px;">模块更新</td>
            <td>
                <a class="btn btn-success btn-xs" href="{$remoteMSE.download_url}?utm_click_source=WHMCSModuleAdmin" target="_blank">查看和下载最新模块</a>
                <div>最新的模块可适用于 {foreach from=$remoteMSE.whmcs_version item=$version}WHMCS{$version}   {/foreach}版本, 同时包含多项安全更新和功能升级</div>
            </td>
        </tr>
    {/if}
    <tr>
        <td width="150px;">API 链接状态</td>
        <td>
            {if $moduleSetting.connected}
                <span class="text-success">连接正常</span>
            {else}
                <span class="text-danger">连接失败, 请您检查API配置或服务器网络连接状况</span>
            {/if}
        </td>
    </tr>
    </tbody>
</table>
<div class="mse-message">
    <h3>更新和通知:</h3>
    {foreach from=$remoteMSE.recent_news item=$message}
        <div class="to-module-mse">
            <h3>{$message.title}</h3>
            <div class="msebody">{$message.body}</div>
        </div>
    {/foreach}
</div>