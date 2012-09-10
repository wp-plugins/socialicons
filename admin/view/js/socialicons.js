(function($){
   
    var SocialIcons = {
        init:function(){
            
            SocialIcons.reloadMyIcons();
            
            $('.socialicons_wrap').delegate('#findiconfinderbutton','click',SocialIcons.findOnIconfinderDialog);
            $('.socialicons_wrap').delegate('#addedit_socialicons','submit',SocialIcons.addNewSocialIcon);
            $('.socialicons_wrap').delegate('#settings_socialicons','submit',SocialIcons.saveSettings);
            $('.socialicons_wrap').delegate('.remove_socialicon','click',SocialIcons.removeSelectedIcon);
            $('.socialicons_wrap').delegate('.changestatus','click',SocialIcons.changeIconStatus);
            
            
            $('#socialicons-modal-iconfinder').delegate('#find-socialicons-iconfinder-ok','click',SocialIcons.findOnIconfinder);
            $('#socialicons-modal-iconfinder').delegate('.downloaditem','click',SocialIcons.downloadIconFromIconfinder);
            
        },
        changeIconStatus:function(e){
            e.preventDefault(); 
            
            var changestatuslnk = $(this);
            var linkbox = changestatuslnk.parents('li');
            var id = linkbox.find('.id span').text();
            var status = linkbox.find('.status span').text().toLowerCase();
            
            $.ajax({
                'url':socialicons_js.admin_ajax_url,
                'data':{
                    'action':'sociallinks_changestatus_link',
                    'id':id,
                    'status':status,
                    '_ajax_wpnonce':socialicons_js.wpnonce_search_iconfinder
                },
                'beforeSend':function(){
                    changestatuslnk.text(socialicons_js.trl_changing)
                },
                'success':function(r){
                    SocialIcons.reloadMyIcons();
                }
            });
        },
        removeSelectedIcon:function(e){
            e.preventDefault();
            
            var removelnk = $(this);
            var linkbox = removelnk.parents('li');
            var id = linkbox.find('.id span').text();

            $.ajax({
                'url':socialicons_js.admin_ajax_url,
                'dataType':'json',
                'data':{
                    'action':'sociallinks_remove_link',
                    'id':id,
                    '_ajax_wpnonce':socialicons_js.wpnonce_search_iconfinder
                },
                'beforeSend':function(){
                    linkbox.fadeOut('slow');
                },
                'success':function(r){
                }
            });
        },
        saveSettings:function(e){
            e.preventDefault();
           
            var form = $(this);
            var formdata = form.serialize();
            var savebutton = form.find('#socialicon-savesettings');
            var savebutton_default_val = savebutton.val();
           
            $.ajax({
                'url':socialicons_js.admin_ajax_url,
                'data':formdata,
                'beforeSend':function(){
                    savebutton.val(socialicons_js.trl_saving).attr('disabled',true);
                },
                'success':function(r){
                    alert(r); 
                },
                'complete':function(){
                    savebutton.val(savebutton_default_val).removeAttr('disabled')
                }
            });
           
        },
        reloadMyIcons:function(){
            var list = $('.socialicons_list_of_links');
            $.ajax({
                'url':socialicons_js.admin_ajax_url,
                'data':{
                    'action':'sociallinks_load_socialicons',
                    '_ajax_wpnonce':socialicons_js.wpnonce_search_iconfinder
                },
                'beforeSend':function(){
                    list.html('<div class="loading_socialicons">'+socialicons_js.trl_loading+'</div>');
                },
                'success':function(r){
                    list.html();
                    var listcontent = [];
                    if(typeof r.icons != 'undefined'){
                        
                        $.each(r.icons,function(i){
                            var icon = r.icons[i];
                            
                            var file = icon.icon_file;
                            var id = icon.id;
                            var name = icon.name;
                            var url = icon.url;
                            var status = icon.status;
                            var statusclass= '';
                            
                            if(status == 1){
                                status = socialicons_js.trl_enabled;
                                statusclass = 'statusenabled';
                            }else{
                                status = socialicons_js.trl_disabled;
                                statusclass = 'statusdisabled';
                            }
                           
                            
                            listcontent.push('<li>');
                            listcontent.push('<div class="iconimage"><img src="'+file+'" /></div>');
                            listcontent.push('<div class="iconinfo">');
                            listcontent.push('<div class="id">'+socialicons_js.trl_id+': <span>'+id+'</span></div>');
                            listcontent.push('<div class="name">'+socialicons_js.trl_name+': '+name+'</div>');
                            listcontent.push('<div class="status">'+socialicons_js.trl_status+': <span class="'+statusclass+'">'+status+'</span></div>');
                            listcontent.push('<div class="url">'+socialicons_js.trl_url+': '+url+'</div>');
                            listcontent.push('<div class="iconoptions"><a href="#" class="remove_socialicon">'+socialicons_js.trl_remove+'</a> | <a href="#" class="changestatus">'+socialicons_js.trl_changestatus+'</a></div>');
                            listcontent.push('</div><div style="clear:both;"></div>');
                            listcontent.push('</li>');
                        });
                        
                    }
                    list.html(listcontent.join(''));
                }
            });
        },
        addNewSocialIcon:function(e){
            e.preventDefault();
            
            var form = $(this);
            var formdata = form.serialize();
            var savebutton = form.find('#add_new_socialicon');
            var savebutton_default_val = savebutton.val();
            
            $.ajax({
                'url':socialicons_js.admin_ajax_url,
                'data':formdata,
                'dataType':'json',
                'beforeSend':function(){
                    savebutton.val(socialicons_js.trl_saving).attr('disabled',true);
                },
                'success':function(r){
                    if(typeof r.errors != 'undefined'){
                        if(r.errors != null){
                            alert(r.errors.error_add_socialicon[0]);
                        }
                    }
                    if(typeof r.code != 'undefined'){
                        if(r.code == 200){
                            $.each(form.find(':input'),function(){
                                if(this.type == 'text'){
                                    $(this).val('');
                                }
                            });
                        }
                        alert(r.message);
                        SocialIcons.reloadMyIcons();
                    }
                },
                'complete':function(){
                    savebutton.val(savebutton_default_val).removeAttr('disabled')
                }
            });
            
            
        },
        downloadIconFromIconfinder:function(e){
            e.preventDefault();
            var downloadlink = $(this);
            var item = downloadlink.parents('li').find('img');
            var default_downloadlink_text = downloadlink.text();
            
            if(item.length>0){
                var image = item.attr('src');
            }
            
            $.ajax({
                'url':socialicons_js.admin_ajax_url,
                'data':{
                    'action':'downloadicon_iconfinder',
                    'icon':image,
                    '_ajax_wpnonce':socialicons_js.wpnonce_search_iconfinder
                },
                'beforeSend':function(){
                    downloadlink.text(socialicons_js.trl_downloading).removeClass('downloaditem');
                },
                'success':function(r){
                    if(typeof r.errors != 'undefined'){
                        alert(r.errors.download_iconfinder_error[0]);
                    }else{
                        $( "#socialicons-modal-iconfinder" ).dialog('close');
                        $("#socialicons_iconurl").val(r.upload.url);
                        alert(socialicons_js.trl_download_success);
                    }
                },
                'complete':function(){
                    downloadlink.text(default_downloadlink_text).addClass('downloaditem');
                }
            });
            
        },
        findOnIconfinder:function(e){
            e.preventDefault();
            var q=$.trim($("#socialicons_iconfinder_q").val());
            var button=$(this);
            var button_default_value=button.val();
            var target_response = $("#iconsresult");
            var socialicons_dimension = $("#socialicons_dimension option:selected").val();
            
            $.ajax({
                'url': socialicons_js.admin_ajax_url,
                'type':'get',
                'dataType':'json',
                'data':{
                    'action':'search_icons_iconfinder',
                    'q':q,
                    'dimension':socialicons_dimension,
                    '_ajax_wpnonce':socialicons_js.wpnonce_search_iconfinder
                },
                'beforeSend':function(){
                    button.val(socialicons_js.trl_searching).attr('disabled',true);
                    target_response.html('');
                },
                'success':function(r){
                    var results = r.searchresults,listicons = [];
                    if(typeof results == 'undefined'){
                        return;
                    }
                    if(typeof results.icons == 'undefined' || results.icons == null){
                        return;
                    }

                    if(results.icons.length > 0){
                        listicons.push('<ul>');
                        $.each(results.icons,function(i){

                            var icon_item = results.icons[i];
                            var image = icon_item.image;
                            var size = icon_item.size;
                           
                            
                            listicons.push('<li>');
                            listicons.push('<div class="iconimage"><img src="'+image+'"/></div>');
                            listicons.push('<div class="icondownload"><a href="javascript:;" class="downloaditem">'+socialicons_js.trl_usethis+'</a></div>');
                            listicons.push('<div class="icondimension"><strong>'+size+'x'+size+'px</strong></div>');
                            listicons.push('</li>');
                            
                        });
                        listicons.push('</ul>');
                        target_response.html(listicons.join(''));
                    }
                },
                'error':function(){
                    target_response.html('');
                },
                'complete':function(){
                    button.removeAttr('disabled').val(button_default_value);
                }
            });
        },
        findOnIconfinderDialog:function(e){
            e.preventDefault();
            var dialog_properties = {
                height: 400,
                width:650,
                modal: true,
                draggable:false,
                position:'center'
            };
            
            $( "#socialicons-modal-iconfinder" ).dialog(dialog_properties);
        }
       
       
    };
   
   
    SocialIcons.init();
   
   
})(jQuery);