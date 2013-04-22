/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| AL jQ comments 2.1
| Filename: comments.js
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
$(document).ready(function(){
        $("input[name='edit_comment']").hide();
        $('#comments_load').show();
        getComments(cid, ctype);
        
        $("input[name='post_comment']").click( function() {
		if($("input[name='comment_name']").val() == "") { alert('Error!'); return false; }
		if($("textarea[name='comment_message']").val() == "") { alert('Enter ur message'); return false; }
				$("form[name='comment_form']").fadeOut("fast", function() {
					$("div#comment_load").fadeIn("fast", function() {
						$.post(basedir+"includes/comments_include/backend.php", {
							action: "add",
							name: $("input[name='comment_name']").val(), 
							message: $("textarea[name='comment_message']").val(),
							cid: $("input[name='cid']").val(),
							ctype: $("input[name='ctype']").val()
						}, function(cdata) {
							$("div#comment_load").fadeOut("slow", function() {
								$("textarea[name='comment_message']").val("");
								$("form[name='comment_form']").fadeIn("slow", function() {
									//$("div#comments").empty();
									getComments(cdata.cid, cdata.ctype);
                                    delete cdata;
								});
							});
						}, "json");
					});
				});
			
	return false;
	});
    
    $("input[name='edit_comment']").click(function() { 
		if($("textarea[name='comment_message']").val() == "") { alert('No messege'); return false; }
		$("form[name='comment_form']").fadeOut("fast", function() {
			$("div#comment_load").fadeIn("fast", function() {
				$.post(basedir+"includes/comments_include/backend.php", {
					action: "save_edit",
					commentid: $("input[name='edited_id']").val(),
					message: $("textarea[name='comment_message']").val(),
					cid: $("input[name='cid']").val(),
					ctype: $("input[name='ctype']").val()
				}, function(cdata) {
					$("div#comment_load").fadeOut("slow", function() {
						$("textarea[name='comment_message']").val("");
						$("input[name='post_comment']").css("display", "inline");
						$("input[name='edit_comment']").css("display", "none");
						$("form[name='comment_form']").fadeIn("slow", function() {
							//$("div#comment_messages").empty();
							getComments(cdata.cid, cdata.ctype);
							delete cdata;
						});
					});			
				}, "json");
			});
		});
	return false;
	});
        
        
});

    function getComments(cid, ctype) {
        $.post(basedir+"includes/comments_include/backend.php", {
            action: 'get_comments',
            cid: cid,
            ctype: ctype
            }, function(cdata) {
                if (cdata.type == 'success') {
                    showComments(cdata);
                    parseSmileys();
                    delete(cdata);
            } else if (cdata.type == 'noone') {
                $('div#comments').html(locale_noc);
                $('#comments_load').hide();
            } else {
                alert("error!");
            }
        }, "json");
    }


    function showComments(cdata) {
        var i = 1;
        var all_comments = "";
        $(cdata.comments).each(function() {
        if($(this).attr('access') == 1) {
			var actions = "<a href='javascript:edit_comment("+$(this).attr('comment_id')+");' class='side'>"+locale_edit+"</a> | <a href='javascript:delete_comment("+$(this).attr('comment_id')+");' class='side'>"+locale_del+"</a>";
		} else {
			var actions = "";
		}
		if($(this).attr('user_avatar') == "") {
		var avatar = "<img src='"+basedir+"images/avatars/noavatar50.png' border='0' width='50' height='50'>";
		} else {
		var avatar = "<img src='"+basedir+"images/avatars/"+$(this).attr('user_avatar')+"' border='0' width='50' height='50'>";
		}
        
		all_comments += "<div id='comment_"+$(this).attr('comment_id')+"'><table cellspacing='0' cellpadding='0' border='0' width='100%' height='60' class='tbl2'><tr><td width='60' valign='top' align='center'>"+avatar+"</td>"
		+"<td style='padding: 5px;'><div style='float:left;'>#"+i+". <a href='"+basedir+"profile.php?lookup="+$(this).attr('user_id')+"'><b>"+$(this).attr('user_name')+"</b></a>&nbsp;&nbsp;&nbsp;<i class='small'>"+$(this).attr('comment_date')+"</i></div>"
		+"<div style='float:right;'>"+actions+"</div>"
		+"<br><div align='left'>"+$(this).attr('comment_message')+"<br><br></div></td>"
		+"</tr></table><br>"
		+"</div>";
		i++;
	   });
       $("div#comments").html(all_comments);
	   delete cdata;
        //$('#comments').slideDown();
        $('#comments_load').hide();
    }
    
    function parseSmileys() {
	var smileys = $("img.smiley");
	$("img.smiley").each(function(i) {
		var img = $(smileys[i]).attr("alt");
		$(smileys[i]).attr({ "src": basedir+"images/smiley/"+img });
		$(smileys[i]).removeClass("smiley");
	});
	delete smileys;
    }
    
    function delete_comment(comment_id) {
	$("div#comment_"+comment_id).fadeTo("fast", 0.3, function() {
		$.post(basedir+"includes/comments_include/backend.php", {
			action: "delete", 
			commentid: comment_id
		}, function(cdata) {
			$("div#comment_"+comment_id).slideUp("slow", function() {
				//$("#comments").slideUp("normal");
                //$("#comments").empty();
				getComments(cdata.cid, cdata.ctype);
				//parseSmileys();
			});
		}, "json");
	});
    }
    
    
    function edit_comment(comment_id) {
	$("form[name='comment_form']").fadeOut("fast", function() {
		$("div#comment_load").fadeIn("fast", function() {
			$.post(basedir+"includes/comments_include/backend.php", {
				action: "get_edit",
				commentid: comment_id
			}, function(cdata) {
				$("input[name='edited_id']").val(cdata.comment_id);
				$("div#comment_load").fadeOut("slow", function() {
					$("textarea[name='comment_message']").val(cdata.comment_message);
					$("input[name='post_comment']").css("display", "none");
					$("input[name='edit_comment']").css("display", "inline");
					$("form[name='comment_form']").fadeIn("slow");
					$("textarea[name='comment_message']").focus();
				});
			}, "json");
		});
	});
    }
    
    
    
    
    
    
    
    
    
    
