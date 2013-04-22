<?php
if (!defined("IN_FUSION")) die("fu");

if (isset($_POST['app_id']) && isnum($_POST['app_id'])) {
$info = dbarray(dbquery("SELECT * FROM ".DB_RM_APPS." WHERE app_id='".$_POST['app_id']."'"));
$voted = $info['app_voted'] != "" ? $info['app_voted'].".".$userdata['user_id'] : $userdata['user_id'];
if ($info['app_status'] == "0") {
    if (isset($_POST['c_apply']) && check_group(3)) {
        $upd = dbquery("UPDATE ".DB_RM_APPS." SET app_status='1', app_voted='".$userdata['user_id']."' WHERE app_id='".$_POST['app_id']."'");
        if ($info['app_user'] != "0") {
// add group
$uinfo = dbarray(dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$info['app_user']."'"));
$groups = $uinfo['user_groups'] == "" ? ".2" : $uinfo['user_groups'].".2";
$updg = dbquery("UPDATE ".DB_USERS." SET user_groups='".$groups."' WHERE user_id='".$info['app_user']."'");
// sendmail, group acept

 require_once INCLUDES."sendmail_include.php";
//$msg = sprintf($locale['eml3'],$code);
$msg = $locale['eml4'];
sendemail($uinfo['user_name'], $uinfo['user_email'], $settings['siteusername'], $settings['siteemail'], $locale['eml1'], $msg,"html"); 

} else if ($info['app_rm_user'] != "0") {
$uinfo = dbarray(dbquery("SELECT * FROM ".DB_RM_USERS." WHERE rmuser_id='".$info['app_rm_user']."'"));
if ($uinfo['rmuser_verified'] == "1") {
// insert new user, del rmuser, add group

 $result = dbquery("INSERT INTO ".DB_USERS." (user_name, user_password, user_admin_password, user_email, user_hide_email, user_avatar, user_posts, user_threads, user_joined, user_lastvisit, user_ip, user_rights, user_groups, user_level, user_status, user_sig, user_salt, user_algo) VALUES('".$uinfo['rmuser_username']."', '".$uinfo['rmuser_password']."', '', '".$uinfo['rmuser_useremail']."', '1', '', '0', '0', '".time()."', '0', '".USER_IP."', '', '.2', '101', '0', '', '".$uinfo['rmuser_salt']."', '".$uinfo['rmuser_algo']."')"); 
$new_user = mysql_insert_id();
$upd_Pp = dbquery("UPDATE ".DB_RM_APPS." SET app_user='".$new_user."' WHERE app_id='".$info['app_id']."'");
$delrm = dbquery("DELETE FROM ".DB_RM_USERS." WHERE rmuser_id='".$info['app_rm_user']."'");
// sendmail, user created, group acepted

require_once INCLUDES."sendmail_include.php";
//$msg = sprintf($locale['eml3'],$code);
$msg = $locale['eml5'];
sendemail($uinfo['rmuser_username'], $uinfo['rmuser_useremail'], $settings['siteusername'], $settings['siteemail'], $locale['eml1'], $msg,"html"); 

} else {
$updu = dbquery("UPDATE ".DB_RM_USERS." SET rmuser_approved='1' WHERE rmuser_id='".$info['app_rm_user']."'");
// sendmail - app aproved, need confirm acc

require_once INCLUDES."sendmail_include.php";
$msg = sprintf($locale['eml6'],$uinfo['rmuser_code']);
//$msg = $locale['eml6'];
sendemail($uinfo['rmuser_username'], $uinfo['rmuser_useremail'], $settings['siteusername'], $settings['siteemail'], $locale['eml1'], $msg,"html"); 

}
}
    } elseif (isset($_POST['c_decline']) && check_group(3)) {
        $upd = dbquery("UPDATE ".DB_RM_APPS." SET app_status='2', app_voted='".$userdata['user_id']."' WHERE app_id='".$_POST['app_id']."'");
if ($info['app_rm_user'] != "0") {
$del = dbquery("DELETE FROM ".DB_RM_USERS." WHERE rmuser_id='".$info['app_rm_user']."'");
// sendmail, app declined, acc do not created

require_once INCLUDES."sendmail_include.php";
$uinfo = dbarray(dbquery("SELECT * FROM ".DB_RM_USERS." WHERE rmuser_id='".$info['app_rm_user']."'"));
$msg = $locale['eml7'];
//$msg = $locale['eml6'];
sendemail($uinfo['rmuser_username'], $uinfo['rmuser_useremail'], $settings['siteusername'], $settings['siteemail'], $locale['eml1'], $msg,"html"); 

} else {
// sendmail, app declined
 require_once INCLUDES."sendmail_include.php";
$uinfo = dbarray(dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$info['app_user']."'"));
$msg = $locale['eml7'];
//$msg = $locale['eml6'];
sendemail($uinfo['user_name'], $uinfo['user_email'], $settings['siteusername'], $settings['siteemail'], $locale['eml1'], $msg,"html"); 
}
    }
    
    
if (isset($_POST['vote_yes'])) {
if ($info['app_votes_yes'] < 2) {
 $upd = dbquery("UPDATE ".DB_RM_APPS." SET app_votes_yes=app_votes_yes+1, app_voted='".$voted."' WHERE app_id='".$_POST['app_id']."'"); 
} else if ($info['app_votes_yes'] >= 2) {
 $upd = dbquery("UPDATE ".DB_RM_APPS." SET app_status='1', app_votes_yes='3', app_voted='".$voted."' WHERE app_id='".$_POST['app_id']."'"); 
if ($info['app_user'] != "0") {
// add group
$uinfo = dbarray(dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$info['app_user']."'"));
$groups = $uinfo['user_groups'] == "" ? ".2" : $uinfo['user_groups'].".2";
$updg = dbquery("UPDATE ".DB_USERS." SET user_groups='".$groups."' WHERE user_id='".$info['app_user']."'");
// sendmail, group acept

 require_once INCLUDES."sendmail_include.php";
//$msg = sprintf($locale['eml3'],$code);
$msg = $locale['eml4'];
sendemail($uinfo['user_name'], $uinfo['user_email'], $settings['siteusername'], $settings['siteemail'], $locale['eml1'], $msg,"html"); 

} else if ($info['app_rm_user'] != "0") {
$uinfo = dbarray(dbquery("SELECT * FROM ".DB_RM_USERS." WHERE rmuser_id='".$info['app_rm_user']."'"));
if ($uinfo['rmuser_verified'] == "1") {
// insert new user, del rmuser, add group

 $result = dbquery("INSERT INTO ".DB_USERS." (user_name, user_password, user_admin_password, user_email, user_hide_email, user_avatar, user_posts, user_threads, user_joined, user_lastvisit, user_ip, user_rights, user_groups, user_level, user_status, user_sig, user_salt, user_algo) VALUES('".$uinfo['rmuser_username']."', '".$uinfo['rmuser_password']."', '', '".$uinfo['rmuser_useremail']."', '1', '', '0', '0', '".time()."', '0', '".USER_IP."', '', '.2', '101', '0', '', '".$uinfo['rmuser_salt']."', '".$uinfo['rmuser_algo']."')"); 
$new_user = mysql_insert_id();
$upd_Pp = dbquery("UPDATE ".DB_RM_APPS." SET app_user='".$new_user."' WHERE app_id='".$info['app_id']."'");
$delrm = dbquery("DELETE FROM ".DB_RM_USERS." WHERE rmuser_id='".$info['app_rm_user']."'");
// sendmail, user created, group acepted

require_once INCLUDES."sendmail_include.php";
//$msg = sprintf($locale['eml3'],$code);
$msg = $locale['eml5'];
sendemail($uinfo['rmuser_username'], $uinfo['rmuser_useremail'], $settings['siteusername'], $settings['siteemail'], $locale['eml1'], $msg,"html"); 

} else {
$updu = dbquery("UPDATE ".DB_RM_USERS." SET rmuser_approved='1' WHERE rmuser_id='".$info['app_rm_user']."'");
// sendmail - app aproved, need confirm acc

require_once INCLUDES."sendmail_include.php";
$msg = sprintf($locale['eml6'],$uinfo['rmuser_code']);
//$msg = $locale['eml6'];
sendemail($uinfo['rmuser_username'], $uinfo['rmuser_useremail'], $settings['siteusername'], $settings['siteemail'], $locale['eml1'], $msg,"html"); 

}
}
}
} else if (isset($_POST['vote_no'])) {
if ($info['app_votes_no'] < 2) {
 $upd = dbquery("UPDATE ".DB_RM_APPS." SET app_votes_no=app_votes_no+1, app_voted='".$voted."' WHERE app_id='".$_POST['app_id']."'"); 
} elseif ($info['app_votes_no'] >= 2) {
$upd = dbquery("UPDATE ".DB_RM_APPS." SET app_status='2', app_votes_no='3', app_voted='".$voted."' WHERE app_id='".$_POST['app_id']."'");
if ($info['app_rm_user'] != "0") {
$del = dbquery("DELETE FROM ".DB_RM_USERS." WHERE rmuser_id='".$info['app_rm_user']."'");
// sendmail, app declined, acc do not created

require_once INCLUDES."sendmail_include.php";
$uinfo = dbarray(dbquery("SELECT * FROM ".DB_RM_USERS." WHERE rmuser_id='".$info['app_rm_user']."'"));
$msg = $locale['eml7'];
//$msg = $locale['eml6'];
sendemail($uinfo['rmuser_username'], $uinfo['rmuser_useremail'], $settings['siteusername'], $settings['siteemail'], $locale['eml1'], $msg,"html"); 

} else {
// sendmail, app declined
 require_once INCLUDES."sendmail_include.php";
$uinfo = dbarray(dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$info['app_user']."'"));
$msg = $locale['eml7'];
//$msg = $locale['eml6'];
sendemail($uinfo['user_name'], $uinfo['user_email'], $settings['siteusername'], $settings['siteemail'], $locale['eml1'], $msg,"html"); 
}
}
}
}
redirect(INFUSIONS."al_register_mod/admin/index.php?p=view_app&id=".$_POST['app_id']);
}

$app = dbarray(dbquery("SELECT * FROM ".DB_RM_APPS." WHERE app_id='".$_GET['id']."'"));
// userinfo
if ($app['app_user'] != "0") {
$app_user = dbarray(dbquery("SELECT user_name, user_email FROM ".DB_USERS." WHERE user_id='".$app['app_user']."'"));
$confirm = "yes";
$name = $app_user['user_name'];
$email = $app_user['user_email'];
} elseif ($app['app_rm_user'] != "0") {
$app_user = dbarray(dbquery("SELECT * FROM ".DB_RM_USERS." WHERE rmuser_id='".$app['app_rm_user']."'"));
$confirm = $app_user['rmuser_verified'] == "1" ? "yes" : "no";
$name = $app['app_username'];
$name = !empty($name) ? $name : "deleted";
$email = $app['app_useremail'];
$email = !empty($email) ? $email : "deleted";
}
opentable($locale['ar34']);
echo "<table width='100%'>";
echo "<tr><td class='tbl2' width='150'>".$locale['ar36']."</td><td class='tbl2'>".($app['app_user'] != "0" ? $locale['ar38'] : $locale['ar37'])."</td></tr>";
 echo "<tr><td class='tbl2' width='150'>".$locale['ar39']."</td><td class='tbl1'>";
if ($app['app_status'] == "0") {
echo "not decided";
} else if ($app['app_status'] == "1") {
echo "approved";
} else if ($app['app_status'] == "2") {
echo "declined";
} 
echo "</td></tr>"; 
 echo "<tr><td class='tbl2' width='150'>".$locale['ar40']."</td><td class='tbl1'>".$name."</td></tr>";
 echo "<tr><td class='tbl2' width='150'>".$locale['ar41']."</td><td class='tbl1'>".$email."</td></tr>";
 echo "<tr><td class='tbl2' width='150'>".$locale['ar42']."</td><td class='tbl1'>".showdate("longdate",$app['app_date'])."</td></tr>";
 echo "<tr><td class='tbl2' width='150'>".$locale['ar43']."</td><td class='tbl1'>".$confirm."</td></tr>"; 
 echo "<tr><td class='tbl2' width='150'>".$locale['ar44']."</td><td class='tbl1'><span style='color:green;'>".$app['app_votes_yes']."</span>/<span style='color:red;'>".$app['app_votes_no']."</span> ".$locale['ar45']."";
if ($app['app_voted'] != "") {
$voted = explode(".",$app['app_voted']);
$me_voted = in_array($userdata['user_id'], $voted);
$i=1;
foreach ($voted as $uid) {
$vname = dbarray(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$uid."'"));
echo ($i > 1 ? ", " : "");
echo $vname['user_name'];
$i++;
}
} else {
$voted = "";
$me_voted = false;
echo "-";
}
echo "<br />";
if (!$me_voted && $app['app_status'] == "0" && (check_group(2) || check_group(3))) {
echo "<form method='post' name='tgcgh'><input type='hidden' name='app_id' value='".$_GET['id']."' /><input type='submit' name='vote_yes' class='button' value='".$locale['ar46']."' /><input type='submit' class='button' name='vote_no' value='".$locale['ar47']."' />";
if ($app['app_status'] == "0" && check_group(3)) {
    echo "<br /><input type='submit' class='button' name='c_apply' value='".$locale['ar461']."' /><input type='submit' class='button' name='c_decline' value='".$locale['ar471']."' />";
} 
echo "</form>";
}
echo "</td></tr>"; 

echo "</table>";
closetable();

// app
$app_form = dbquery("SELECT * FROM ".DB_RM_FORM_FIELDS." ORDER BY ff_order ASC");
$ff_info = dbarray(dbquery("SELECT * FROM ".DB_RM_FORM_APPS." WHERE fa_id='".$app['app_form']."'"));
opentable($locale['ar35']);
echo "<table width='100%'>";
while ($ff = dbarray($app_form)) {
    if ($ff['ff_type'] == "1") {
        echo "<tr><td class='tbl2' width='250'>".$ff['ff_title']."</td><td class='tbl2'>".$ff_info['fa_'.$ff['ff_name']]."</td></tr>";
    } elseif ($ff['ff_type'] == "2") {
        echo "<tr><td class='tbl2' width='250'>".$ff['ff_title']."</td><td class='tbl2'>".nl2br(trim(stripinput($ff_info['fa_'.$ff['ff_name']])))."</td></tr>";
    } elseif ($ff['ff_type'] == "3") {
        echo "<tr><td class='tbl2' width='250'>".$ff['ff_title']."</td><td class='tbl2'>".$ff_info['fa_'.$ff['ff_name']]."</td></tr>";
    }
}
echo "</table>";
closetable();

 
require_once INCLUDES."comments_include.php";
showcomments("RM", DB_RM_APPS, "app_id", $_GET['id'], INFUSIONS."al_register_mod/admin/index.php?p=view_app&id=".$_GET['id']); 
 
?>
