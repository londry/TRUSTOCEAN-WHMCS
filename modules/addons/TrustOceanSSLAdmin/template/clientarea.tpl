{*<link rel="stylesheet" type="text/css" href="/modules/addons/TrustOceanSSLAdmin/assets/ThirdParty/DataTables/datatables.min.css"/>*}
{*<script type="text/javascript" src="/modules/addons/TrustOceanSSLAdmin/assets/ThirdParty/DataTables/datatables.min.js"></script>*}
<link href="/modules/addons/TrustOceanSSLAdmin/assets/css/clientarea.css" rel="stylesheet">
<table id="CertificateTable" class="to-ssl-management-table">
    <thead>
        <tr>
            <th>证书ID</th>
            <th>基本信息</th>
            <th>状态</th>
            <th>验证类型</th>
            <th>创建日期</th>
            <th>过期日期</th>
            <th>域名列表</th>
            <th>管理</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<link rel="stylesheet" href="{$BASE_PATH_CSS}/dataTables.responsive.css">
<script type="text/javascript" charset="utf8" src="{$BASE_PATH_JS}/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="{$BASE_PATH_JS}/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" charset="utf8" src="{$BASE_PATH_JS}/dataTables.responsive.min.js"></script>

{if isset($filterColumn) && $filterColumn}
    <script type="text/javascript">
        if (typeof(buildFilterRegex) !== "function") {
            function buildFilterRegex(filterValue) {
                if (filterValue.indexOf('&') === -1) {
                    return '[~>]\\s*' + jQuery.fn.dataTable.util.escapeRegex(filterValue) + '\\s*[<~]';
                } else {
                    var tempDiv = document.createElement('div');
                    tempDiv.innerHTML = filterValue;
                    return '\\s*' + jQuery.fn.dataTable.util.escapeRegex(tempDiv.innerText) + '\\s*';
                }
            }
        }
    </script>
{/if}

<script type="text/javascript">

    function checkAll(){
        var checkAll = $('#selectAll');
        var checkboxes = $('.domids').not(':disabled');
    }

    //var alreadyReady = false; // The ready function is being called twice on page load.
    jQuery(document).ready( function () {ldelim}
        //asdfasd
        var table = jQuery("#CertificateTable").DataTable({ldelim}
            "processing": true,//刷新的那个对话框
            "serverSide": true,//服务器端获取数据
             "searching": true, // 启动搜索框
            //"paging": true,//开启分页
            "responsive": true,
            "drawCallback": function( settings ) {
                jQuery('.table-container').removeClass('loading');
                jQuery('#tableLoading').addClass('hidden');
            },
            "dom": '<"listtable"fit>pl',
            "columns": [
                { "data": "serviceid" },
                { "data": "name" },
                { "data": "status" },
                { "data": "class" },
                { "data": "created_at" },
                { "data": "expire_at" },
                { "data": "domain_string" },
                { "data": "manage" }
            ],
            ordering:false,
            //"info": false,{if isset($noSearch) && $noSearch}
            //"filter": false,{/if}
            //"bProcessing": true,
            "ajax":{
                'url':"/index.php?m=TrustOceanSSLAdmin&action=getMyCertificateList&status={$smarty.get.cat}",
                'type':'POST'
            },
            "oLanguage": {ldelim}
                "sEmptyTable":     "{$LANG.norecordsfound}",
                "sInfo":           "{$LANG.tableshowing}",
                "sInfoEmpty":      "{$LANG.tableempty}",
                "sInfoFiltered":   "{$LANG.tablefiltered}",
                "sInfoPostFix":    "",
                "sInfoThousands":  ",",
                "sLengthMenu":     "{$LANG.tablelength}",
                "sLoadingRecords": "{$LANG.tableloading}",
                "sProcessing":     "{$LANG.tableprocessing}",
                "sSearch":         "",
                "sZeroRecords":    "{$LANG.norecordsfound}",
                "oPaginate": {ldelim}
                    "sFirst":    "{$LANG.tablepagesfirst}",
                    "sLast":     "{$LANG.tablepageslast}",
                    "sNext":     "{$LANG.tablepagesnext}",
                    "sPrevious": "{$LANG.tablepagesprevious}"
                    {rdelim}
                {rdelim},
            "pageLength": 10,
            {*"order": [*}
            {*[ {if isset($startOrderCol) && $startOrderCol}{$startOrderCol}{else}0{/if}, "asc" ]*}
            {*],*}
            "lengthMenu": [
                [10, 25, 50],
                [10, 25, 50]
            ],
            "aoColumnDefs": [
                {ldelim}
                    "bSortable": false,
                    "aTargets": [ {if isset($noSortColumns) && $noSortColumns !== ''}{$noSortColumns}{/if} ]
                    {rdelim},
                {ldelim}
                    "sType": "string",
                    "aTargets": [ {if isset($filterColumn) && $filterColumn}{$filterColumn}{/if} ]
                    {rdelim}
            ],
            "stateSave": false,
            "autoWidth": false,
            "initComplete": function(settings, json) {
                jQuery('.table-container').removeClass('loading');
                jQuery('#tableLoading').addClass('hidden');
            }
            {rdelim});

        {if isset($filterColumn) && $filterColumn}
        // highlight remembered filter on page re-load
        var rememberedFilterTerm = table.state().columns[{$filterColumn}].search.search;
        if (rememberedFilterTerm && !alreadyReady) {

            // This should only run on the first "ready" event.
            jQuery(".view-filter-btns a span").each(function(index) {
                if (buildFilterRegex(jQuery(this).text().trim()) == rememberedFilterTerm) {
                    $(this).closest('li').addClass('active');
                    $(this).closest('.view-filter-btns').find('.dropdown-toggle span').text(jQuery(this).text());
                }
            });
        }
        {/if}
        //alreadyReady = true;

        $('#certsearch').click(function(){
            jQuery('.table-container').addClass('loading');
            jQuery('#tableLoading').removeClass('hidden');
            var searchVal = $('#searchwords').val()+"|"+$('select[name=stype]').val();
            table.search(searchVal, true).draw();
        });

//            if ($('#table-search').length > 0 && $('.dataTables_filter').length > 0){
//                var searchTableVal = $('.dataTables_filter input').val();
//                $('#table-search').val(searchTableVal);
//                var searchVal = $('#table-search').val();
//                table.search(searchVal, true).draw();
//            }
//
//            $('#table-search').on('keyup', function () {
//                table.search(this.value, true).draw();
//            });

        table.on('draw.dt', function () {
            checkAll();

        });


        {rdelim});
</script>