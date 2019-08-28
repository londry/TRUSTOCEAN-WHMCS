<div class="panel panel-sidebar panel-ticket-information" style="border: none;"><div class="list-group">
<div menuitemname="Department" class="list-group-item ticket-details-children\" id="Primary_Sidebar-Ticket_Information-Department">
<span class="title">{$MODLANG.trustoceanssl.enroll.sider.type}</span><br> {$smarty.session.ssl_name}
</div>
<div menuitemname="Date Opened" class="list-group-item ticket-details-children" id="Primary_Sidebar-Ticket_Information-Date_Opened">
<span class="title">{$MODLANG.trustoceanssl.enroll.sider.class}</span><br> {$smarty.session.ssl_class}
</div>
<div menuitemname="Last Updated" class="list-group-item ticket-details-children" id="Primary_Sidebar-Ticket_Information-Last_Updated">
<span class="title">{$MODLANG.trustoceanssl.enroll.sider.valid}</span><br> {$smarty.session.ssl_created_at}
</div>
<div menuitemname="Priority" class="list-group-item ticket-details-children" id="Primary_Sidebar-Ticket_Information-Priority">
<span class="title">{$MODLANG.trustoceanssl.enroll.sider.trustoceanno}</span><br># {$smarty.session.service_id}
</div>
<div menuitemname="Priority" class="list-group-item ticket-details-children" id="Primary_Sidebar-Ticket_Information-Priority">
<span class="title">{$MODLANG.trustoceanssl.enroll.sider.comodono}</span><br># {$smarty.session.ssl_vendor_id}
</div>
</div>
<div class="panel-footer clearfix">
<div class="col-xs-6 col-button-left">
<a class="btn btn-success btn-md btn-block" href="clientarea.php?action=productdetails&id={$smarty.session.service_id}">
<span class="fa fa-book" style='margin-right: 5px;'></span> {$MODLANG.trustoceanssl.enroll.sider.certinfo}
</a>
</div>
<div class="col-xs-6 col-button-right">
<a class="btn btn-info btn-md btn-block" href="index.php?m=TrustOceanSSLAdmin">
<span class="fa fa-reply" style='margin-right: 5px;'></span> {$MODLANG.trustoceanssl.enroll.sider.certlist}
</a>
</div>

</div>
</div>