<script type="application/javascript">
    {literal}
$(document).ready(function(){
    $('*[api-form]').append(
        '<div class="loader loader-panel" style="z-index: 100; opacity: 0.9; background-color: #f6f7f8;"><div class="spinner "> <div class="rect1"></div> <div class="rect2"></div> <div class="rect3"></div> <div class="rect4"></div> <div class="rect5"></div> </div>  <div style="display: contents;padding-left: 15px;"><span api-text></span> 可能需要一些时间</div></div>'
    );
    $('form[api-form] input[type=submit]').after(
        '<p style="padding-left: 10px;padding-top: 20px;" api-btn-result></p>'
    );

    $('*[api-form]').submit(function(){
        apiClient( $(this).attr('action'), $(this).serialize(), $(this).attr('api-loader'), $(this).attr('api-success'), $(this).attr('api-error'), this);
        return false;
    });
    /**
     * DCV INFO 选择项
     */
    // $('tr[dcv-tds]').find('td[dcv-tab]').hide();
    // $('select[dcv-domain]').find('option:selected').each(function(){
    //     console.log($(this).val());
    //     $('tr[dcv-tds='+$(this).attr('dcv-domain')+']').find('td[dcv-tab='+$(this).val()+']').show();
    // });

    $('select[dcv-domain]').change(function(){
        $('tr[dcv-tds='+$(this).attr('dcv-domain')+']').find('td').hide();
        //$('tr[dcv-tds='+$(this).attr('dcv-domain')+']').find('td[dcv-change]').show();
        var that = this;
        // $.post('/clientarea.php?action=productdetails&id=2&modop=custom&a=enroll',{id:$(that).attr('dcv-cert-id'), toca:'changedcv',domain:$(that).attr('dcv-domain-name'), method:$(that).val()}, function(resp){
        //     $('tr[dcv-tds='+$(that).attr('dcv-domain')+']').find('td[dcv-change]').hide();
        //
        // })
        if($(that).val() === "dns" || $(that).val() === "http" || $(that).val() === "https"){
            $('tr[dcv-tds='+$(that).attr('dcv-domain')+']').find('td[dcv-tab='+$(that).val()+']').show();
        }
    });
    $('select[ca-dcv-domain]').on('focus', function(){
        previous = this.value;
    }).change(function(){
        var that = this;
        // 当前验证方式
        var currentDCV = $('tr[dcv-tds='+$(that).attr('ca-dcv-domain')+']').find('td:visible');
        // DCV changing
        $('tr[dcv-tds='+$(this).attr('ca-dcv-domain')+']').find('td').hide();
        $('tr[dcv-tds='+$(this).attr('ca-dcv-domain')+']').find('td[dcv-change]').show();
        $.post('/clientarea.php?action=productdetails&id='+$(that).attr('dcv-cert-id')+'&modop=custom&a=clientAreaChangeDCVMethod',{id:$(that).attr('dcv-cert-id'), domain:$(that).attr('dcv-domain-name'), method:$(that).val()}, function(resp){
            $('tr[dcv-tds='+$(that).attr('ca-dcv-domain')+']').find('td[dcv-change]').hide();
            if(resp.status === "success"){
                if($(that).val() === "dns" || $(that).val() === "http" || $(that).val() === "https"){
                    $('tr[dcv-tds='+$(that).attr('ca-dcv-domain')+']').find('td[dcv-tab='+$(that).val()+']').show();
                }
            }else{
                $(currentDCV).show();
                $(that).val(previous);
                tonotify($(that).attr('ca-dcv-domain'),'error', resp.message);
            }
        });
    });
    // DCV操作
    $('a[data-redo-dcv]').click(function(){
        $(this).button('loading');
        $(this).html('<i class="oi fa fa-spin" data-glyph="aperture" style="margin-left: -18px;margin-top: 2px; position: absolute; padding-bottom: 2px;"></i>');
        var that = this;
        $.post('/clientarea.php?action=productdetails&id='+$(that).attr('data-redo-dcv')+'&modop=custom&a=clientarearesenddcvemail',{id:$(that).attr('data-redo-dcv')},function(resp){
            if(resp.status === "success"){
                tonotify('mainorderdcv' ,'success', MODLANG.trustoceanssl.dcvfetchsuccess);
            }else{
                tonotify('mainorderdcv', 'error', resp.message);
            }
            $(that).button('reset');
        });
    });

    // 移除为验证通过的域名-操作
    $('button[data-remove-dcv-domain]').click(function(){
        $(this).button('loading');
        $(this).html('<i class="oi fa fa-spin" data-glyph="aperture" style="margin-left: -18px;margin-top: 2px; position: absolute; padding-bottom: 2px;"></i>');
        var that = this;
        $.post('/clientarea.php?action=productdetails&id='+$(that).attr('data-redo-dcv')+'&modop=custom&a=clientarearesenddcvemail',{id:$(that).attr('data-redo-dcv')},function(resp){
            if(resp.status === "success"){
                tonotify('mainorderdcv' ,'success', MODLANG.trustoceanssl.dcvfetchsuccess);
            }else{
                tonotify('mainorderdcv', 'danger', resp.message);
            }
            $(that).button('reset');
        });
    });

    // 移除已经签发证书内的域名
    // $('span[data-remove-domain]').on('click', function(){
    //     removeMDCDomain(this);
    // });
     $('div[data-san-domainnames]').delegate('div>span[data-remove-domain]','click', function(){
        removeMDCDomain(this);
    });
});

function tryToRecheckDCV(obj){
    $(obj).button('loading');
    var that = obj;
    $.post('/clientarea.php?action=productdetails&id='+$(that).attr('data-redo-dcv')+'&modop=custom&a=clientarearesenddcvemail',{responsetype:'json',id:$(that).attr('data-redo-dcv')},function(resp){
        if(resp.status === "success"){
            tonotify('mainorderdcv' ,'success', MODLANG.trustoceanssl.dcvfetchsuccess);
        }else{
            tonotify('mainorderdcv', 'error', resp.message);
        }
        $(that).button('reset');
    });
}

function trySubmitToCA(obj){
    $(obj).button('loading');
    var that = obj;
    $.post('/clientarea.php?action=productdetails&id='+$(that).attr('data-serviceid')+'&modop=custom&a=ajaxTrySubmittoca',{id:$(that).attr('data-serviceid'),responsetype:'json'},function(resp){
        if(resp.status === "success"){
            tonotify('mainsubmittoca' ,'success', MODLANG.trustoceanssl.submitsuccess);
            finisheAjax($(that).attr('data-serviceid'));
        }else{
            tonotify('mainsubmittoca', 'error', resp.message);
        }
        $(that).button('reset');
    });
}

function reissueCsrFunction(multidomain){
    var csrOpt = $('select[name=csroption]').val();

    if(csrOpt ==='upload'){
            $('div[data-input-csr]').show();
            if(multidomain === "on"){
                $('div[data-domainNamesInput]').show();
                $('div[data-domainNameInput]').hide();
            }else{
                $('div[data-domainNamesInput]').hide();
                $('div[data-domainNameInput]').hide();
            }
        }
    if(csrOpt === 'seamcsr'){
        $('div[data-input-csr]').hide();
        if(multidomain === "on"){
            $('div[data-domainNamesInput]').show();
            $('div[data-domainNameInput]').hide();
        }else{
            $('div[data-domainNamesInput]').hide();
            $('div[data-domainNameInput]').hide();
        }
    }
    if(csrOpt === 'generate'){
        $('div[data-input-csr]').hide();
        if(multidomain === "on"){
                $('div[data-domainNamesInput]').show();
                $('div[data-domainNameInput]').hide();
        }else{
            $('div[data-domainNamesInput]').hide();
            $('div[data-domainNameInput]').show();
        }
    }
}

function syncGetCertStatus(obj){
    $(obj).button('loading');
    var that = obj;
    $.post('/clientarea.php?action=productdetails&id='+$(that).attr('data-serviceid')+'&modop=custom&a=clientSynccertorderdata',{id:$(that).attr('data-serviceid')}, function(resp){
        $(that).button('reset');
        if(resp.status ==='success' && resp.cert_status ==='issued_active'){ 
            tonotify('mainFetchCertDomain', 'success', MODLANG.trustoceanssl.certissued);
            finisheAjax($(that).attr('data-serviceid'));
        }else if(resp.status ==='danger'){
            tonotify('mainFetchCertDomain', 'danger', resp.message);
        }else{
            tonotify('mainFetchCertDomain', 'error', resp.message);
        }
    });
}

function tryReissueCertificate(obj){
    $(obj).button('loading');
    var that = obj;
    $.post('/clientarea.php?action=productdetails&id='+$(that).attr('data-serviceid')+'&modop=custom&a=ajaxTryToReissueSSL',$('form[data-certificate-reissueinfo]').serialize(), function(resp){
        $(that).button('reset');
        if(resp.status ==='success'){
            tonotify('mainreCertificateInfo', 'success', MODLANG.trustoceanssl.submitreissue);
            finisheAjax($(that).attr('data-serviceid'));
        }else{
            tonotify('mainreCertificateInfo', 'danger', resp.message);
        }
    });
}

function removeMDCDomain(obj){
    $(obj).button('loading');
    var that = obj;
    var domain = $(obj).attr('data-remove-domain');
    $.post('/clientarea.php?action=productdetails&id='+$(that).attr('data-serviceid')+'&modop=custom&a=syncRemoveSANDomain',{id:$(that).attr('data-serviceid'), domain:domain}, function(resp){
        $(that).button('reset');
        if(resp.status ==='success'){
            $('div[data-remove-san='+resp.md5hash+']').remove();
            //tonotify('mainAddCertDomain', 'success', domain+" 删除成功!");
        }else if(resp.status ==='danger'){
            tonotify('mainAddCertDomain', 'danger', resp.message);
        }else{
            tonotify('mainAddCertDomain', 'error', resp.message);
        }
    });
}

function addMDCDomain(obj){
    $(obj).button('loading');
    var that = obj;
    var domain = $('input[data-new-san]').val();
    var serviceid = $(that).attr('data-serviceid');
    $.post('/clientarea.php?action=productdetails&id='+$(that).attr('data-serviceid')+'&modop=custom&a=clientareaAddSanDomain',{id:serviceid, domain:domain}, function(resp){
        $(that).button('reset');
        if(resp.status ==='success'){
            $('input[data-new-san]').val("");

            $newDomainHtml = '<div data-loading-text="'+MODLANG.trustoceanssl.removing+'..." data-remove-san="'+resp.md5hash+'" style="height: 30px; vertical-align: middle; line-height: 35px;">\n' +
'                                    <span class="oi text-danger" data-loading-text="'+MODLANG.trustoceanssl.removing+'..." data-serviceid="'+serviceid+'" data-remove-san="'+resp.md5hash+'" data-remove-domain="'+resp.domain+'" data-glyph="circle-x" style="color: #0c70de !important; top: 4px; position: relative; padding-right: 5px;"></span> '+resp.domain+'\n' +
'                                </div>';
            $('div[data-san-domainnames]').prepend($newDomainHtml);
            //tonotify('mainAddCertDomain', 'success', domain+" 添加成功!");
        }else if(resp.status ==='danger'){
            tonotify('mainAddCertDomain', 'danger', resp.message);
        }else{
            tonotify('mainAddCertDomain', 'error', resp.message);
        }
    });
}

function changeCSRCode(obj){
    $(obj).button('loading');
    var that = obj;
    $.post('/clientarea.php?action=productdetails&id='+$(that).attr('data-serviceid')+'&modop=custom&a=clientareaChangeCSRCode',{id:$(that).attr('data-serviceid'), csr:$('textarea[data-new-csr]').val()}, function(resp){
        $(that).button('reset');
        if(resp.status ==='success'){
            $('div[data-csr-details]').html("");
            $.each(resp.csrobj, function(title, value){
                $('div[data-csr-details]').append('<span><span style="color: #8e8f90;">'+title+':</span> '+value+'</span><br>');
            });
            tonotify('mainCSRCertDomain', 'success', MODLANG.trustoceanssl.csrsaved);
        }else if(resp.status ==='danger'){
            tonotify('mainCSRCertDomain', 'danger', resp.message);
        }else{
            tonotify('mainCSRCertDomain', 'error', resp.message);
        }
    });
}

function tryRemoveDomain(obj, domainName, divdata){
    $(obj).button('loading');
    var that = obj;
    $.post('/clientarea.php?action=productdetails&id='+$(that).attr('data-serviceid')+'&modop=custom&a=clientAreaRemoveDomain',{id:$(that).attr('data-serviceid'), domain:domainName}, function(resp){
        $(that).button('reset');
        if(resp.status ==='success'){
            $('tr[data-ramoval-div='+divdata+']').remove();
        }else{
            tonotify('mainRemoveDomain', 'error', resp.message);
        }
    });
}

function finisheAjax(serviceid){
    $('div.module-client-area').children().hide();
    $('div.module-client-area').append('<span class="fa fa-cog fa-spin" style="margin-right: 5px;"></span>'+" Loading..., Please hold on , the page will refresh now!");
    window.location = '/clientarea.php?action=productdetails&id='+serviceid;
}

function tryAddCertificateInformation(obj){
    $(obj).button('loading');
    var that = obj;
    $.post('/clientarea.php?action=productdetails&id='+$(that).attr('data-serviceid')+'&modop=custom&a=ajaxUploadCertInfo',$('form[data-certificate-baseinfo]').serialize(), function(resp){
        $(that).button('reset');
        if(resp.status ==='success'){
            tonotify('mainBaseCertificateInfo', 'success', MODLANG.trustoceanssl.certinfosaved);
            finisheAjax($(that).attr('data-serviceid'));
        }else{
            tonotify('mainBaseCertificateInfo', 'danger', resp.message);
        }
    });
}

function syncMDCDomainStatus(){
    $('button[data-fetch-dcvstatus]').button('loading');
    {/literal}

    $.post('/clientarea.php?action=productdetails&id='+$('#tableDCVDomainList').attr('data-serviceid')+'&modop=custom&a=clientarteaSyncOrderStatus', {}, function(resp){
{literal}
        $('button[data-fetch-dcvstatus]').button('reset');
        if(resp.status === "error"){
            tonotify('mainFetchsyncDomain', 'error', resp.message);
        }else{
           var isMainDCV = true;
            $.each(resp.dcvinfo, function(index, info){
                $('select[ca-dcv-domain='+info.domainmd5hash+']').val(info.method);
                $('button[data-ca-remove-btn='+info.domainmd5hash+']').show();
                if(info.status === "verified"){
                    $('div[data-dcv-contain='+info.domainmd5hash+']').html(info.method);
                    $('div[data-dcv-contain='+info.domainmd5hash+']').css('display','flex');
                    $('select[ca-dcv-domain='+info.domainmd5hash+']').attr('disabled', true);
                    $('button[data-ca-remove-btn='+info.domainmd5hash+']').remove();
                    $('tr[data-ramoval-div='+info.domainmd5hash+']').attr('data-dcv-status-valid','true');
                    $('td[data-ca-status-td='+info.domainmd5hash+']').html('<span class="text-success" style="padding-left: 10px;"><i class="oi" data-glyph="circle-check" style="margin-left: -18px;margin-top: 2px; position: absolute; padding-bottom: 2px;"></i>'+MODLANG.enroll.status.verified+'</span>');
                }else{
                    var isexitDCVBtn = $('input[onclick="selectAllDcvMethod()"]').length > 0;
                    if(isexitDCVBtn === false && isMainDCV === true && resp.dcvinfo[index-1] !== undefined && resp.dcvinfo[index-1]['status'] === "verified"){
                        $('div[data-dcv-contain='+info.domainmd5hash+'] > select').removeAttr('data-sandcv');
                        $('div[data-dcv-contain='+info.domainmd5hash+'] > select').attr('data-maindcv', 'true');
                        $('div[data-dcv-contain='+info.domainmd5hash+']').append('<input type="button" onclick="selectAllDcvMethod()" data-toggle="tooltip" data-title="'+MODLANG.enroll.status.selectdcvmethodforall+'" data-original-title="" title="" class="btn btn-xs btn-default" value="'+MODLANG.enroll.status.selectall+'">');
                        isMainDCV = false;
                    } 
                    $('div[data-dcv-contain='+info.domainmd5hash+']').css('display','flex');
                    $('tr[data-ramoval-div='+info.domainmd5hash+']').attr('data-dcv-status-valid','false');
                    if(info.method === "dns" || info.method === "http" || info.method === "https"){
                        $('tr[dcv-tds='+info.domainmd5hash+']').find('td[dcv-tab='+info.method+']').show();
                    }else{
                        $('tr[dcv-tds='+info.domainmd5hash+']').find('td[dcv-tab]').hide();
                    }
                }
                if(info.status === "needverification"){
                    $('td[data-ca-status-td='+info.domainmd5hash+']').html('<span class="text-info" style="padding-left: 10px;"><i class="oi fa fa-spin" data-glyph="aperture" style="margin-left: -18px;margin-top: 2px; position: absolute; padding-bottom: 2px;"></i> '+MODLANG.enroll.status.processing+'</span>');
                }
            });
            tonotify('mainorderdcv' ,'success', MODLANG.trustoceanssl.enroll.ajax.dcvsuccess);
        }
    });
}

function tonotify(windowid, type, message){
    // 创建通知窗口
    {/literal}
    $('body').append('<div data-notify-window="'+windowid+'" class="univer-notify">\n' +
        '            <span style="position: absolute; top: -21px; color: #fff;">'+MODLANG.trustoceanssl.enroll.ajax.systemmessage+'</span>\n' +
        '            <span onclick="$(this).parent().remove();" style="position: absolute; right: 5px; top: -20px; color: #ffffff;"><span class="oi" data-glyph="circle-x"></span></span>\n' +
        '            <div data-body style="margin-bottom: 10px;word-break: break-all;">Notification Content Here</div>\n' +
        '            <button class="btn btn-xs btn-default" style="float: right;" onclick="$(this).parent().remove();">'+MODLANG.trustoceanssl.enroll.ajax.ok+'</button>\n' +
        '        </div>');
    {literal}
    var window = $('div[data-notify-window='+windowid+']');
    if(type === "danger"){
        window.removeClass('error');
        window.addClass('danger');
    }
    if(type === "error"){
        window.removeClass('danger');
        window.addClass('error');
    }
    if(type === "success"){
        window.removeClass('danger');
        window.removeClass('error');
    }
    window.children('div[data-body]').html(message);
    window.show();
}

function dcvDownloadFile(content, filename){
    var a = document.createElement("a");
    document.body.appendChild(a);
    a.style = "display: none";
    var json = content,
    blob = new Blob([json], {type: "octet/stream"}),
    url = window.URL.createObjectURL(blob);
    a.href = url;
    a.download = filename;
    a.click();
    window.URL.revokeObjectURL(url);
}

var clipboard = new ClipboardJS('button[data-clipbutton]');
    clipboard.on('success', function(e) {
        $(e.trigger).text('Success');

        e.clearSelection();
    });

    clipboard.on('error', function(e) {
       $(e.trigger).text('Faild');
    });
    {/literal}
</script>