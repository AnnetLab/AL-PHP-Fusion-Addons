<?php
 /*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| AL jQ comments 2.1
| Filename: backend.php
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
require_once "../../maincore.php";
if (file_exists(INFUSIONS."mg/infusion_db.php")) {
require_once INFUSIONS."mg/infusion_db.php";
}

function ajax_parsesmileys($message) {
	global $smiley_cache;
		if (!$smiley_cache) { cache_smileys(); }
		if (is_array($smiley_cache) && count($smiley_cache)) {
			foreach ($smiley_cache as $smiley) {
				$smiley_code = preg_quote($smiley['smiley_code']);
				$smiley_image = "<img src='".$smiley['smiley_image']."' alt='".$smiley['smiley_image']."' style='vertical-align:middle;' class='smiley' />";
				$message = preg_replace("#{$smiley_code}#si", $smiley_image, $message);
			}
		}
	return $message;
}

if (isset($_POST['action']) && $_POST['action'] == "get_comments") {
    $cid = $_POST['cid'];
    $ctype = $_POST['ctype'];
    $result = dbquery(
		"SELECT tcm.*,user_name,user_avatar FROM ".DB_COMMENTS." tcm
		LEFT JOIN ".DB_USERS." tcu ON tcm.comment_name=tcu.user_id
		WHERE comment_item_id='".$cid."' AND comment_type='".$ctype."'
		ORDER BY comment_datestamp ASC"
	);
    $comments = array();
    if (dbrows($result)) {
        while ($data=dbarray($result)) {
            $access = (iADMIN && checkrights("C")) || (iMEMBER && $data['comment_name'] == $userdata['user_id'] && isset($data['user_name'])) ? 1 : 0;
            $comments[] = array('comment_id'=>$data['comment_id'],'user_name'=>iconv($locale['charset'],"UTF-8",$data['user_name']),'user_id'=>$data['comment_name'],'user_avatar'=>$data['user_avatar'],'comment_date'=>showdate("forumdate", $data['comment_datestamp']),'comment_message'=>nl2br(parseubb(ajax_parsesmileys(iconv($locale['charset'], "UTF-8",$data['comment_message'])))),'access'=>$access);
        }
        $type = "success";
    } else {
        $type = "noone";
    }
    $result = array('type'=>$type, 'comments'=>$comments);
    print json_encode($result);
}

if (isset($_POST['action']) && $_POST['action'] == "add") {
        $comment_message = trim(stripinput(censorwords(iconv("UTF-8", $locale['charset'], $_POST['message']))));
        $comment_name = trim(stripinput(censorwords($_POST['name'])));
		$comment_itemid = $_POST['cid'];
		$comment_type = $_POST['ctype'];
		$result = dbquery("INSERT INTO ".DB_COMMENTS." (comment_name, comment_message, comment_datestamp, comment_item_id, comment_type) VALUES ('".$comment_name."', '".$comment_message."', '".time()."', '".$comment_itemid."', '".$comment_type."')");
    $result = array('cid'=>$comment_itemid,'ctype'=>$comment_type);
    print json_encode($result);
}

if (isset($_POST['action']) && $_POST['action'] == "delete") {
    if ((iADMIN && checkrights("C")) || (iMEMBER && dbcount("(comment_id)", DB_COMMENTS, "comment_id='".$_POST['commentid']."' AND comment_name='".$userdata['user_id']."'"))) {
		$info = dbarray(dbquery("SELECT comment_item_id, comment_type FROM ".DB_COMMENTS." WHERE comment_id='".$_POST['commentid']."'"));
        $result = dbquery("DELETE FROM ".DB_COMMENTS." WHERE comment_id='".$_POST['commentid']."'");
	   $result = array('cid'=>$info['comment_item_id'],'ctype'=>$info['comment_type']);
       print json_encode($result);
    }
}

if (isset($_POST['action']) && $_POST['action'] == "get_edit" && isset($_POST['commentid']) && isnum($_POST['commentid'])) {
	$data = dbarray(dbquery("SELECT comment_id, comment_message FROM ".DB_COMMENTS." WHERE comment_id='".$_POST['commentid']."'"));
    $result = array('comment_id'=>$data['comment_id'],'comment_message'=>iconv($locale['charset'], "UTF-8",$data['comment_message']));
    print json_encode($result);
}
if (isset($_POST['action']) && $_POST['action'] == "save_edit" && isset($_POST['commentid']) && isnum($_POST['commentid'])) {
    $comment_message = trim(stripinput(censorwords(iconv("UTF-8", $locale['charset'], $_POST['message']))));
    $comment_itemid = $_POST['cid'];
    $comment_type = $_POST['ctype'];
    $update = dbquery("UPDATE ".DB_COMMENTS." SET comment_message='".$comment_message."' WHERE comment_id='".$_POST['commentid']."'");
    $result = array('cid'=>$comment_itemid,'ctype'=>$comment_type);
    print json_encode($result);
}

?>
