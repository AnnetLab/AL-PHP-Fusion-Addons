<?php
require_once "../../maincore.php";
require_once "infusion_db.php";

if (isset($_POST['action']) && $_POST['action'] == "get_info") {

switch ($_POST['provider']) {
case 1:

break;
case 2:

$url = "http://api.own3d.tv/liveCheck.php?live_id=".$_POST['provider_id'];
$xml = simplexml_load_file($url);
$is_live = $xml->liveEvent->isLive;

if ($is_live == "true") {
$l = 1;
$img = "http://img.own3d.tv/live/live_tn_".$_POST['provider_id']."_.jpg?".time();
$v = $xml->liveEvent->liveViewers;
$view = (($v+1)*10)/10;
} else {
$l = 0;
$img = $settings['siteurl']."infusions/al_streams/offline.jpg";
$view = 0;
}
$responce = array("id"=>"div#a".$_POST['id'],"sid"=>$_POST['id'],"img"=>$img,"is_live"=>$l,"view"=>$view);

break;
case 3:

$json_file = @file_get_contents("http://api.justin.tv/api/stream/list.json?channel=".$_POST['provider_id'], 0, null, null);
$json_array = json_decode($json_file , true);
if (!empty($json_array)) {
//if ($json_array[0]['name'] == "live_user_".$_POST['provider_id']) {
$l = 1;
$view = $json_array[0]['stream_count'];
$img = $json_array[0]['channel']['screen_cap_url_huge'];
} else {
$l = 0;
$img = $settings['siteurl']."infusions/al_streams/offline.jpg";
$view = 0;
}
$responce = array("id"=>"div#a".$_POST['id'],"sid"=>$_POST['id'],"img"=>$img,"is_live"=>$l,"view"=>$view);
break;
case 4:

$json_file = @file_get_contents("http://api.cybergame.tv/w/streams2.php?channel=".$_POST['provider_id'], 0, null, null);
$json_array = json_decode($json_file , true);
if (!empty($json_array)) {
//if ($json_array[0]['name'] == "live_user_".$_POST['provider_id']) {
$l = $json_array['online'];
$view = $json_array['viewers'];
$img = $json_array['thumbnail'];
} else {
$l = 0;
$img = $settings['siteurl']."infusions/al_streams/offline.jpg";
$view = 0;
}

$responce = array("id"=>"div#a".$_POST['id'],"sid"=>$_POST['id'],"img"=>$img,"is_live"=>$l,"view"=>$view);
break;
}
$online_count = dbcount("(co_id)",DB_SS_CHAT_ONLINE,"co_channel='".$_POST['id']."'");
$user = dbarray(dbquery("SELECT st.st_user, u.user_name FROM ".DB_SS_STREAMS." st LEFT JOIN ".DB_USERS." u ON u.user_id=st.st_user WHERE st_id='".$_POST['id']."'"));
$responce['online_chat'] = $online_count;
$responce['user_id'] = $user['st_user'];
$responce['user_name'] = $user['user_name'];
$r = json_encode($responce);
print $r;
}

?>
