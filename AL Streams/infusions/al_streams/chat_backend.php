<?php
require_once "../../maincore.php";
require_once "infusion_db.php";


if (isset($_POST['action']) && $_POST['action'] == "refresh") {
    
    $user_id = $_POST['user_id'];
    $stream_id = $_POST['channel_id'];
    
    $result = dbquery("SELECT cm.*, u.user_name FROM ".DB_SS_CHAT_MESSAGES." cm LEFT JOIN ".DB_USERS." u ON u.user_id=cm.cm_user_id WHERE cm_channel_id='".$stream_id."' ORDER BY cm_timestamp DESC LIMIT 0,30");
    while ($data = dbarray($result)) {
        $msgs[] = array('user_id'=>$data['cm_user_id'],'user_name'=>$data['user_name'],'date'=>showdate("forumdate",$data['cm_timestamp']),'message'=>$data['cm_message']);
    }
    $result = dbquery("DELETE FROM ".DB_SS_CHAT_ONLINE." WHERE co_timestamp>'".(time()+900)."'");
    $online_count = dbcount("(co_id)",DB_SS_CHAT_ONLINE,"co_channel='".$stream_id."'");
    //$online = dbquery("SELECT co.*, u.user_name FROM ".DB_SS_CHAT_ONLINE." co LEFT JOIN ".DB_USERS." u ON u.user_id=co.co_user_id WHERE co_channel='".$stream_id."'");
    //if (dbrows($online)) {
        //$online_res = $online_count;
        //while ($data = dbarray($online)) {
            //$online_res .= "<a href=\'".$settings['siteurl']."profile\.php&amp;lookup=".$data['co_user_id']."\'>".$data['user_name']."</a>";
        //} 
    //} else {
        //$online_res = "В чате\: 0 человек\.";
    //}
    
    print json_encode(array('msgs'=>$msgs,'count'=>count($msgs),'online'=>$online_count));
    
}

if (isset($_POST['action']) && $_POST['action'] == "shout") {

    $user_id = $_POST['user_id'];
    $stream_id = $_POST['channel_id'];
    $message = stripinput(trim($_POST['message']));
    $insert = dbquery("INSERT INTO ".DB_SS_CHAT_MESSAGES." (cm_user_id,cm_channel_id,cm_timestamp,cm_message) VALUES ('".$user_id."','".$stream_id."','".(time())."','".$message."')");
    
    $check = dbquery("SELECT * FROM ".DB_SS_CHAT_ONLINE." WHERE co_channel='".$stream_id."' AND co_user_id='".$user_id."'");
    if (dbrows($check)) {
        $update = dbquery("UPDATE ".DB_SS_CHAT_ONLINE." SET co_timestamp='".(time())."' WHERE co_channel='".$stream_id."' AND co_user_id='".$user_id."'");
    } else {
        $insert = dbquery("INSERT INTO ".DB_SS_CHAT_ONLINE." (co_user_id,co_timestamp,co_channel) VALUES ('".$user_id."','".(time())."','".$stream_id."')");
    }
    print json_encode("ok");
}

?>