{* jsApplicationLibary *}
<script src="/modules/addons/TRUSTOCEANSSL_RA/static/js/admin.js" type='application/javascript'></script>
<ul class="nav nav-tabs admin-tabs" role="tablist" style="background-color: #000000; border-radius: 3px;">
    <li><a style="border-radius: 0px; background-color: black; border: none; color: #ffffff;" class="tab-top" href="#tabDCV" role="tab" data-toggle="tab" id="tabLink1" data-tab-id="1" aria-expanded="true">DCV Status</a></li>
    <li>
        <a style="border-radius: 0px; background-color: black; border: none; color: #ffffff;" class="tab-top" href="#tabCert" role="tab" data-toggle="tab" id="tabLink1" data-tab-id="1" aria-expanded="true">证书内容</a>
    </li>
</ul>
<div class="tab-content admin-tabs">
    <div class="tab-pane" id="tabCert">
        <pre>{$certCode}</pre>
    </div>
    <div class="tab-pane" id="tabDCV">
        <table class="datatable" width="100%">
            <thead>
            <tr>
                <th style="text-align: left;padding: 10px;">域名</th>
                <th style="text-align: left;padding: 10px;">验证方式</th>
                <th style="text-align: left;padding: 10px;">验证状态</th>
                <th style="text-align: left;padding: 10px;">操作</th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$dcvInformation key=domainName item=info}
                <tr>
                    <td style="padding: 10px;">{$domainName}</td>
                    <td style="padding: 10px;">{$info.method|upper}</td>
                    <td style="padding: 10px;">{$info.status}</td>
                    <td style="padding: 10px;">
                        <button type="button" class="btn btn-xs btn-danger">删除</button>
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
</div>