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
        $('tr[dcv-tds='+$(this).attr('dcv-domain')+']').find('td[dcv-change]').show();
        var that = this;
        $.post('/clientarea.php?action=productdetails&id=2&modop=custom&a=enroll',{id:$(that).attr('dcv-cert-id'), toca:'changedcv',domain:$(that).attr('dcv-domain-name'), method:$(that).val()}, function(resp){
            $('tr[dcv-tds='+$(that).attr('dcv-domain')+']').find('td[dcv-change]').hide();
            if($(that).val() === "dns" || $(that).val() === "http" || $(that).val() === "https"){
                $('tr[dcv-tds='+$(that).attr('dcv-domain')+']').find('td[dcv-tab='+$(that).val()+']').show();
            }
        })
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
                tonotify('mainorderdcv' ,'success', 'DCV操作执行成功！我们可能需要5-15分钟才能检查完成您所有的域名。');
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
                tonotify('mainorderdcv' ,'success', 'DCV操作执行成功！我们可能需要5-15分钟才能检查完成您所有的域名。');
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
    $.post('/clientarea.php?action=productdetails&id='+$(that).attr('data-redo-dcv')+'&modop=custom&a=clientarearesenddcvemail',{id:$(that).attr('data-redo-dcv')},function(resp){
        if(resp.status === "success"){
            tonotify('mainorderdcv' ,'success', 'DCV操作执行成功！我们可能需要5-15分钟才能检查完成您所有的域名。');
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
            tonotify('mainsubmittoca' ,'success', '您的订单提交已经成功了! 请您刷新页面进行接下来的操作.');
        }else{
            tonotify('mainsubmittoca', 'error', resp.message);
        }
        $(that).button('reset');
    });
}


function syncGetCertStatus(obj){
    $(obj).button('loading');
    var that = obj;
    $.post('/clientarea.php?action=productdetails&id='+$(that).attr('data-serviceid')+'&modop=custom&a=clientSynccertorderdata',{id:$(that).attr('data-serviceid')}, function(resp){
        $(that).button('reset');
        if(resp.status ==='success'){
            tonotify('mainFetchCertDomain', 'success', "您的证书已经签发！请您刷新页面查看并下载证书文件。");
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
            tonotify('mainreCertificateInfo', 'success', "证书重签提交成功, 即将刷新页面进行下一步操作");
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

            $newDomainHtml = '<div data-loading-text="删除中..." data-remove-san="'+resp.md5hash+'" style="height: 30px; vertical-align: middle; line-height: 35px;">\n' +
'                                    <span class="oi text-danger" data-loading-text="删除中..." data-serviceid="'+serviceid+'" data-remove-san="'+resp.md5hash+'" data-remove-domain="'+resp.domain+'" data-glyph="circle-x" style="color: #0c70de !important; top: 4px; position: relative; padding-right: 5px;"></span> '+resp.domain+'\n' +
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
            tonotify('mainCSRCertDomain', 'success', "CSR信息保存成功!");
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

function tryAddCertificateInformation(obj){
    $(obj).button('loading');
    var that = obj;
    $.post('/clientarea.php?action=productdetails&id='+$(that).attr('data-serviceid')+'&modop=custom&a=ajaxUploadCertInfo',$('form[data-certificate-baseinfo]').serialize(), function(resp){
        $(that).button('reset');
        if(resp.status ==='success'){
            tonotify('mainBaseCertificateInfo', 'success', "证书信息保存成功, 即将刷新页面进行下一步操作");
        }else{
            tonotify('mainBaseCertificateInfo', 'danger', resp.message);
        }
    });
}

function tonotify(windowid, type, message){
    // 创建通知窗口
    $('body').append('<div data-notify-window="'+windowid+'" class="univer-notify">\n' +
        '            <span style="position: absolute; top: -21px; color: #fff;">系统通知</span>\n' +
        '            <span onclick="$(this).parent().remove();" style="position: absolute; right: 5px; top: -20px; color: #ffffff;"><span class="oi" data-glyph="circle-x"></span></span>\n' +
        '            <div data-body style="margin-bottom: 10px;word-break: break-all;">Notification Content Here</div>\n' +
        '            <button class="btn btn-xs btn-default" style="float: right;" onclick="$(this).parent().remove();">知道了</button>\n' +
        '        </div>');
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