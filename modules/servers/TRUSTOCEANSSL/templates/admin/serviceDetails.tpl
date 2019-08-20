{* jsApplicationLibary *}
<script src="/modules/addons/TRUSTOCEANSSL_RA/static/js/admin.js" type='application/javascript'></script>

{* 基本域名验证信息 *}
<table class="table">
    <thead>
    <tr>
        <th>域名</th>
        <th>验证方式</th>
        <th>验证状态</th>
        <th>发送CAA记录</th>
        <th>其他操作</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>$domain</td>
        <td>".strtoupper($dcvinfo[$domain]['method'])."</td>
        <td>".$dcvinfo[$domain]['status']."</td>
        <td>
            <button type=\"button\" class=\"btn btn-xs btn-info\" onclick=\"runTrustOceanCommand('sendcaarecord',{domain:'".$domain."'}, ".$service->uid.", ".$service->serviceid.")\" id=\"btncaa".md5($domain)."\">发送CAA</button>
            <button type=\"button\" class=\"btn btn-xs btn-danger\" onclick=\"runTrustOceanCommand('adminremovedomain',{domain:'".$domain."'}, ".$service->uid.", ".$service->serviceid.")\" id=\"btnremove".md5($domain)."\">删除域名</button>
        </td>
        <td> $dcvstring </td>
    </tr>
    </tbody>
</table>

{* 企业订单信息*}
<div id=\"orderorginfo\" style=\"padding: 20px;\">
组织名称：<input type=\"text\" name=\"organization_name\" value=\"".$orginfo['organization_name']."\" size=\"25\" class=\"form-control input-300\" ".$isDisabled.">
部门名称：<input type=\"text\" name=\"organizationalUnitName\" value=\"".$orginfo['organizationalUnitName']."\" size=\"25\" class=\"form-control input-300\" ".$isDisabled.">
组织编号：<input type=\"text\" name=\"registered_no\" value=\"".$orginfo['registered_no']."\" size=\"25\" class=\"form-control input-300\" ".$isDisabled.">
注册日期：<input type=\"text\" name=\"date_of_incorporation\" value=\"".$orginfo['date_of_incorporation']."\" size=\"25\" class=\"form-control input-200\" ".$isDisabled.">
注册地址：<input type=\"text\" name=\"registered_address_line1\" value=\"".$orginfo['registered_address_line1']."\" size=\"25\" class=\"form-control input-500\" ".$isDisabled.">
国家：<input type=\"text\" name=\"country\" value=\"".$orginfo['country']."\" size=\"25\" class=\"form-control input-100\" ".$isDisabled.">
省份：<input type=\"text\" name=\"state\" value=\"".$orginfo['state']."\" size=\"25\" class=\"form-control input-100\" ".$isDisabled.">
城市：<input type=\"text\" name=\"city\" value=\"".$orginfo['city']."\" size=\"25\" class=\"form-control input-100\" ".$isDisabled.">
邮政编码：<input type=\"text\" name=\"postal_code\" value=\"".$orginfo['postal_code']."\" size=\"25\" class=\"form-control input-200\" ".$isDisabled.">
企业联系电话(CallBack)：<input type=\"text\" name=\"organization_phone\" value=\"".$orginfo['organization_phone']."\" size=\"25\" class=\"form-control input-300\" ".$isDisabled.">
<hr />
认证联系人_姓名：<input type=\"text\" name=\"contact_name\" value=\"".$orginfo['contact_name']."\" size=\"25\" class=\"form-control input-200\" ".$isDisabled.">
认证联系人_职位：<input type=\"text\" name=\"contact_title\" value=\"".$orginfo['contact_title']."\" size=\"25\" class=\"form-control input-200\" ".$isDisabled.">
认证联系人_电话：<input type=\"text\" name=\"contact_phone\" value=\"".$orginfo['contact_phone']."\" size=\"25\" class=\"form-control input-300\" ".$isDisabled.">
认证联系人_电子邮箱：<input type=\"text\" name=\"contact_email\" value=\"".$orginfo['contact_email']."\" size=\"25\" class=\"form-control input-300\" ".$isDisabled.">
<hr/>
DUNS号码：<input type=\"text\" name=\"dunsNumber\" value=\"".$orginfo['dunsNumber']."\" size=\"25\" class=\"form-control input-300\" ".$isDisabled.">
DBA名称：<input type=\"text\" name=\"assumedName\" value=\"".$orginfo['assumedName']."\" size=\"25\" class=\"form-control input-300\" ".$isDisabled.">
<hr/>
企业类型(".$orginfo['businessCategory'].")：<select name=\"businessCategory\" class=\"form-control input-300\" ".$isDisabled."> <option value=\"b\">b - Private Organization</option> <option value=\"c\">c - Government Entity</option> <option value=\"d\">d - Business Entity</option> </select>
CallBack方式(".$orginfo['callbackMethod'].")：<select name=\"callbackMethod\" class=\"form-control input-300\" ".$isDisabled."> <option value=\"T\">Telephone Callback</option> <option value=\"L\">Law Letter</option> </select>
申请人已验证(".$orginfo['isAppRepValidated'].")：<select name=\"isAppRepValidated\" class=\"form-control input-300\" ".$isDisabled."> <option value=\"Y\">是-已经验证申请人权限</option> <option value=\"N\">否-COMODO会二次验证</option> </select>
已完成CallBack验证(".$orginfo['isCallbackCompleted'].")：<select name=\"isCallbackCompleted\" class=\"form-control input-300\" ".$isDisabled."> <option value=\"Y\">是-TRUSTOCEAN已经完成CALLBACK</option> <option value=\"N\">否-COMODO会执行CALLBACK</option> </select>
是否让COMODO验证OV(".$orginfo['doAutoOV'].")：<select name=\"doAutoOV\" class=\"form-control input-300\" ".$isDisabled."> <option value=\"Y\">是</option> <option value=\"N\">否</option> </select>
<br>
<button type=\"button\" class=\"btn btn-sm btn-info\" onclick=\"runTrustOceanCommand('updateorginfo',
{organization_name:$('input[name=organization_name]').val(),
registered_no:$('input[name=registered_no]').val(),
date_of_incorporation:$('input[name=date_of_incorporation]').val(),
registered_address_line1:$('input[name=registered_address_line1]').val(),
country:$('input[name=country]').val(),
state:$('input[name=state]').val(),
city:$('input[name=city]').val(),
contact_name:$('input[name=contact_name]').val(),
contact_title:$('input[name=contact_title]').val(),
contact_phone:$('input[name=contact_phone]').val(),
contact_email:$('input[name=contact_email]').val(),
postal_code:$('input[name=postal_code]').val(),
dunsNumber:$('input[name=dunsNumber]').val(),
assumedName:$('input[name=assumedName]').val(),
organizationalUnitName:$('input[name=organizationalUnitName]').val(),
businessCategory:$('select[name=businessCategory]').val(),
callbackMethod:$('select[name=callbackMethod]').val(),
isAppRepValidated:$('select[name=isAppRepValidated]').val(),
isCallbackCompleted:$('select[name=isCallbackCompleted]').val(),
doAutoOV:$('select[name=doAutoOV]').val(),
organization_phone:$('input[name=organization_phone]').val()}, ".$service->uid.", ".$service->serviceid.")\" id=\"btnorginfo".md5($domain)."\"  ".$isDisabled.">更新企业信息</button>
</div>