<?php

function checkDelete($type, $id, $group="") {
if ($type == "u") {
$check = dbquery("SELECT * FROM ".DB_GS_VOTES_USERS." WHERE vuser_user='".$id."' AND vuser_group='".$group."'");
} elseif ($type == "n") {
$check = dbquery("SELECT * FROM ".DB_GS_VOTES_NEWS." WHERE vnews_news='".$id."'");
}
if (dbrows($check)) {
return true;
} else {
return false;
}
}

function inLine($value, $str) {
$str_arr = explode(".",$str);
if (in_array($value, $str_arr)) {
return true;
} else {
return false;
}
}

function checkJoin($id) {
global $userdata;
if (iMEMBER) {
$check = dbquery("SELECT * FROM ".DB_GS_VOTES_USERS." WHERE vuser_user='".$userdata['user_id']."' AND vuser_group='".$id."'");
if (dbrows($check)) {
return true;
} else {
return false;
}
} else {
return false;
}

}

function inGroup($id) {
global $userdata;
if (iMEMBER) {
$check =dbquery("SELECT * FROM ".DB_GS_GROUP_USERS." WHERE guser_user='".$userdata['user_id']."' AND guser_group='".$id."'");
if (dbrows($check)) {
return true;
} else {
return false;
}
} else {
 return false;
} 

}

function showNav() {
    global $locale, $aidlink;
    echo "<button class='button'><a href='".INFUSIONS."al_groups/admin/index.php".$aidlink."'>".$locale['gs8']."</a></button> ";
    echo "<button class='button'><a href='".INFUSIONS."al_groups/admin/index.php".$aidlink."&p=cats'>".$locale['gs9']."</a></button> ";
    /*echo "<button class='button'><a href='".INFUSIONS."al_stats/admin/index.php".$aidlink."&p=games'>".$locale['st6']."</a></button> ";
*/
}

?>
