/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| AL jQ gallery 1.7
| Filename: gallery.js
| Author: Rush
| http://fusion.annetlab.tk
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/ 
    $(document).ready(function() {
        
        $('#mg_load').hide();
        $("input[name='cid']").val(cur_hash);
        $("input[name='ctype']").val("MG");
        $.post(basedir+"infusions/mg/backend.php", {
            action: 'get_photo',
            id: cur_hash,
            album: album
            }, function(data) {
                if (data.type == 'success') {
                    var photo = "<a href=''><img src='"+basedir+"images/mg_photos/"+data.photo.t1+"' border='0' /></a>";
                    $('#mg_photo').html(photo);
                    updateButtons(data);
                    delete(data);
            } else {
                alert("error!");
            }
        }, "json");
        
        
   
    });

    function showFoto(id) {
        //alert(id);
        window.location.hash = id;
        $('#mg_photo').css({"opacity": "0.5"});
            $('#mg_load').show();
                if (id != "") {
                    $.post(basedir+"infusions/mg/backend.php", {
                        action: 'get_photo',
                        id: id,
                        album: album
                    }, function(data) {
                        if (data.type == 'success') {
                            var photo = "<a href=''><img src='"+basedir+"images/mg_photos/"+data.photo.t1+"' border='0' /></a>";
                            $('#mg_photo').css({"opacity": "1"});
                            $('#mg_photo').html(photo);
                            $('#mg_load').hide();
                            updateButtons(data);
                            var ctype = "MG";
                            var cid = id;
                            $("input[name='cid']").val(cid);
                            $("input[name='ctype']").val(ctype);
                            getComments(cid, ctype);
                            delete(data);
                                
                        } else {
                            alert("error!");
                        }
                    }, "json");
                }
            
    }




    function updateButtons(data) {
        var count = locale1+" "+data.buttons.cur+" "+locale2+" "+data.buttons.num;
        var info = "<strong>"+data.photo.title+"</strong><br />"+data.photo.desc;
        $('#mg_next a').attr({href: "javascript:showFoto("+data.buttons.next+");"});
        $('#mg_photo a').attr({href: "javascript:showFoto("+data.buttons.next+");"});
        $('#mg_prev a').attr({href: "javascript:showFoto("+data.buttons.prev+");"});
        $('#mg_count').html('').html(count);
        $('#mg_info').html('').html(info);
        if (data.photo.original != "") {
$('a#mg_zoom').attr({href: basedir+"images/mg_photos/"+data.photo.original});
$('div#mg_original').show();
		} else {
$('div#mg_original').hide();
		}
    }

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
