var TRUSTOCEANSSLModelApp = {
    userId: undefined,
    serviceId: undefined,
    domainNameForRemoval: "",
    removeDomainModel: function(domainName){
        this.domainNameForRemoval = domainName;
        jQuery('#modalModuleTrustOceanRemoveDomain').modal('show');
    },
    revokeSSL: function(){
        this.runModuleCommand('revokeSSLWithReason', {revocationReason: jQuery('select[name=revocationReason]').val()});
    },
    removeDomain: function(){
        this.runModuleCommand("removeDomain", {domain:this.domainNameForRemoval});
    },
    runModuleCommand:function(cmd,params)
    {
        $('#growls').fadeOut('fast').remove();
        $('.successbox,.errorbox').slideUp('fast').remove();
        // Hide the modal that was activated.
        jQuery("[id^=modalModule]").modal("hide");
        var commandButtons = jQuery('#modcmdbtns'),
            commandWorkingTrustOceanSSL = jQuery('#TrustOceanSSLmodelworking');
            commandWorking = jQuery("[id^=modcmdworking]");

        commandButtons.css("filter", "alpha(opacity=20)");
        commandButtons.css("-moz-opacity", "0.2");
        commandButtons.css("-khtml-opacity", "0.2");
        commandButtons.css("opacity", "0.2");
        var position = commandButtons.position();

        commandWorking.css("position", "absolute");
        commandWorking.css("top", position.top);
        commandWorking.css("left", position.left);
        commandWorking.css("padding", "9px 50px 0");
        commandWorking.fadeIn();
        commandWorkingTrustOceanSSL.fadeIn();

        var reqstr = "userid="+this.userId+"&id="+this.serviceId+"&modop=custom&ac=" + cmd + "&ajax=1&token="+csrfToken;
        if (params) {
            var requestString = "";
            Object.keys(params).map(function(key){
               requestString = requestString + encodeURIComponent(key)+'='+encodeURIComponent(params[key])+'&';
            });
            reqstr += "&" + requestString;
        }

        WHMCS.http.jqClient.post("clientsservices.php", reqstr,
            function (data) {
                if (data.success && data.redirect) {
                    data = data.redirect;
                }
                $("#servicecontent").html(data);
                    $('html, body').animate({
                        scrollTop: $('.client-tabs').offset().top - 10
                    }, 500);
                });
    }
};