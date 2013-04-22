<?php
 /*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| AL jQ comments 2.1
| Filename: comments_include.php
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
if (!defined("IN_FUSION")) { die("Access Denied"); }



function showcomments($ctype, $cdb, $ccol, $cid, $clink="") {
    global $settings, $userdata, $locale;
    include LOCALE.LOCALESET."comments.php";
	 require_once INCLUDES."bbcode_include.php"; 
    add_to_head("<script type='text/javascript' src='".INCLUDES."comments_include/comments.js'></script>");
    add_to_head("<script type='text/javascript'>
            var ctype = '".$ctype."';
            if (ctype == 'MG') {
                var cid = window.cidas;
            } else {
                var cid = '".$cid."';
            }
            var basedir = '".BASEDIR."';
			var locale_noc = '".$locale['c101']."';
			var locale_edit = '".$locale['c108']."';
			var locale_del = '".$locale['c109']."';
    </script>");
    
    echo "<br /><br />";
    opentable($locale['c100']);
    echo "<div id='comments_load' style='display:none;text-align:center;'><img src='".INCLUDES."comments_include/load.gif' alt='' style='border:0;' /></div><div id='comments'></div>";
    closetable();
    echo "<br /><br />";
    opentable($locale['c102']);
    if (iMEMBER) {
	echo "<div align='center'>";
	echo "<a id='edit_comment' name='edit_comment'></a>";
	echo "<form name='comment_form' method='post' action='#'>";
		echo "<input type='hidden' name='comment_name' value='".$userdata['user_id']."' />";
		echo "<input type='hidden' name='cid' value='".$cid."' />";
        echo "<input type='hidden' name='ctype' value='".$ctype."' />";
		echo "<input type='hidden' name='edited_id' value='' />";
		
	echo "<input type='hidden' name='edited_id' value='' />";
	echo "<textarea name='comment_message' cols='70' rows='6' class='textbox' style='width:360px'></textarea><br />";
	echo "<div align='center'>".display_bbcodes("360px", "comment_message", "comment_form")."</div>";
	echo "<input type='submit' name='post_comment' value='".$locale['c102']."' class='button' /> ";
	echo "<input type='submit' name='edit_comment' value='".$locale['c103']."' class='button' /> ";
	echo "</form>";
	echo "<div id='comment_load' style='display:none;text-align:center;height:140px;'><img src='".INCLUDES."comments_include/load.gif' alt='' style='border:0;margin-top:50px;' /></div>";
	echo "<br />";
	echo "</div>";
	
    } else {
	echo "<div style='text-align:center'>".$locale['c105']."</div><br />";
    }

    closetable();
    echo "<div style='text-align:center;width:100%;'>AL jQ comments <a href='http://fusion.annetlab.tk'>Fusion @ AnnetLab</a> &copy; 2011-2012</div>"; 
    
    
}

?>
