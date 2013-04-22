<?php
if (!defined("IN_FUSION")) die("access denied!");
require_once INCLUDES."bbcode_include.php";

if (!isset($_GET['id']) && !isnum($_GET['id'])) {
redirect(BASEDIR."tourney.php?p=tours");
}

if (isset($_GET['checkin']) && isnum($_GET['checkin']) && isset($_GET['pid']) && isnum($_GET['pid'])) {
if (checkrights("T")) {
$checkh = dbquery("UPDATE ".DB_T_PLAYERS." SET player_checkin='".$_GET['checkin']."' WHERE player_id='".$_GET['pid']."'");
}
redirect(BASEDIR."tourney.php?p=viewtour&id=".$_GET['id']);
}

if (isset($_GET['delete']) && isnum($_GET['delete'])) {
if (checkrights("T")) {
$checkh = dbquery("DELETE FROM ".DB_T_PLAYERS." WHERE player_id='".$_GET['delete']."'");
}
redirect(BASEDIR."tourney.php?p=viewtour&id=".$_GET['id']);
} 

if (isset($_POST['t_reg'])) {
$numpl = dbcount("(player_id)", DB_T_PLAYERS, "player_tour='".$_GET['id']."' AND player_checkin='1'");
$maxpl = dbarray(dbquery("SELECT * FROM ".DB_T_TOURS." WHERE tour_id='".$_GET['id']."'"));
//echo $numpl.$maxpl['tour_maxpl'];
$info = dbquery("SELECT COUNT(tpl.player_id) AS numpl, tt.tour_maxpl FROM ".DB_T_PLAYERS." tpl LEFT JOIN ".DB_T_TOURS." tt ON tt.tour_id='".$_POST['tid']."' WHERE player_tour='".$_POST['tid']."'");
if ($_POST['tkod'] == "1") {
$reg = dbquery("INSERT INTO ".DB_T_PLAYERS." (player_user, player_tour, player_checkin) VALUES ('".$_POST['uid']."', '".$_POST['tid']."', '0')");
redirect(FUSION_REQUEST);
}
if ($_POST['tkod'] == "2") {
if ($numpl < $maxpl['tour_maxpl']) { 
$reg = dbquery("INSERT INTO ".DB_T_PLAYERS." (player_user, player_tour, player_checkin) VALUES ('".$_POST['uid']."', '".$_POST['tid']."', '1')");
redirect(FUSION_REQUEST);
 } else {
redirect(FUSION_REQUEST."&m=1"); 
} 
} 
 if ($_POST['tkod'] == "3") {
if ($numpl < $maxpl['tour_maxpl']) {
$reg = dbquery("UPDATE ".DB_T_PLAYERS." SET player_checkin='1' WHERE player_user='".$_POST['uid']."' AND player_tour='".$_POST['tid']."'");
redirect(FUSION_REQUEST);
} else {
redirect(FUSION_REQUEST."&m=1"); 
}
} 
}

if (isset($_GET['m']) && $_GET['m'] == "1") {
echo "<div class='admin-message'>Too much players...</div>";
}

/*$date_now = time()+($settings['timeoffset']*3600);
echo date("G:i", $date_now);
echo date("G:i", time());*/
$result = dbquery("SELECT * FROM ".DB_T_TOURS." WHERE tour_id='".$_GET['id']."'");
$bye = bye();

if (!dbrows($result)) {
echo "invalid id";
} else {
$data = dbarray($result);
$r_tot = dbcount("(player_id)", DB_T_PLAYERS, "player_tour='".$data['tour_id']."' AND player_user<>'".$bye."'");
$r_ch = dbcount("(player_id)", DB_T_PLAYERS, "player_tour='".$data['tour_id']."' AND player_checkin='1' AND player_user<>'".$bye."'"); 
opentable("Tourney info");
echo "<table width='100%'>";
echo "<tr><td width='1%' class='tbl2'>Name:</td><td class='tbl1'>".$data['tour_name']."</td><td class='tbl2' rowspan='7' align='center'>";
if (iMEMBER) {
if ($data['tour_finished'] == "1") {
// winners
//echo "<div class='center'>";
echo "Winners:<br />";
for ($z=1;$z<=4;$z++) {
$ui = dbarray(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$data['tour_w'.$z]."'"));
echo $z.". <a href='".BASEDIR."profile.php?lookup=".$data['tour_w'.$z]."'><strong>".$ui['user_name']."</strong></a><br />";
}
//echo "</div>";

} elseif ($data['tour_grid'] == "1" && $data['tour_finished'] == "0") {
// matches
$check_r = dbquery("SELECT * FROM ".DB_T_PLAYERS." WHERE player_tour='".$data['tour_id']."' AND player_user='".$userdata['user_id']."' AND player_checkin='1'");
if (dbrows($check_r)) {
$check_m = dbquery("SELECT * FROM ".DB_T_MATCHES." WHERE match_tour='".$data['tour_id']."' AND match_played='0' AND (match_pl1='".$userdata['user_id']."' OR match_pl2='".$userdata['user_id']."')");
if (dbrows($check_m)) {
$nm = dbarray($check_m);
if ($nm['match_pl1'] == $userdata['user_id']) {
if ($nm['match_pl2'] != "0") {
$opp = dbarray(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$nm['match_pl2']."'"));
$opp_link = "vs <a href='".BASEDIR."profile.php?lookup=".$nm['match_pl2']."'>".$opp['user_name']."</a>";
} else {
$opp_link = "wating for an opponent...";
}
} else {
if ($nm['match_pl1'] != "0") {
$opp = dbarray(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$nm['match_pl1']."'"));
$opp_link = "vs <a href='".BASEDIR."profile.php?lookup=".$nm['match_pl1']."'>".$opp['user_name']."</a>"; 
} else {
$opp_link = "wating for an opponent...";
}
}
echo "Your next match:<br />
".$opp_link."<br /><a href='".BASEDIR."tourney.php?p=result&id=".$nm['match_id']."'>Go to match</a>";

} else {
echo "You have no more matches to play in this tourney";
}

} else {
echo "You don't take part in this tourney.";
}



} else {
$check = dbquery("SELECT * FROM ".DB_T_PLAYERS." WHERE player_user='".$userdata['user_id']."' AND player_tour='".$data['tour_id']."'"); 
if (dbrows($check)) {
echo "You have already registered at this event.<br />";
$check2 = dbarray($check);
if ($check2['player_checkin'] == "1") {
echo "You have already confirmed your registration.";
} else {
$ch_time = $data['tour_date'] - 1800;
$now = time()+(($settings['timeoffset']+$settings['serveroffset'])*3600);
if ($ch_time > $now) {
echo "Don't forget to confirm your registration!";
} else {
if ($now < $data['tour_date']) {
// confirm form
echo "Push a button to confirm your registration.<br /><br />";
 echo "<form name='ty64477' method='post'><input type='hidden' name='uid' value='".$userdata['user_id']."' /><input type='hidden' name='tid' value='".$data['tour_id']."' /><input type='hidden' name='tkod' value='3' /><input type='submit' class='button' name='t_reg' value='Confirm' /></form>"; 
} else {
echo "You are late, sorry...";
}
}

}
} else {
// not registered
$now = time()+(($settings['timeoffset']+$settings['serveroffset'])*3600); 
$ch_time = $data['tour_date'] - 1800; 
if ($now < $data['tour_date']) {
if ($now > $ch_time) {
// reg + checkin
echo "Push a button for registration.<br /><br />";
echo "<form name='ty64477' method='post'><input type='hidden' name='uid' value='".$userdata['user_id']."' /><input type='hidden' name='tid' value='".$data['tour_id']."' /><input type='hidden' name='tkod' value='2' /><input type='submit' class='button' name='t_reg' value='Register and confirm' /></form>"; 
} else {
// just reg
echo "Just push a button for registration.<br /><br />";
echo "<form name='ty64477' method='post'><input type='hidden' name='uid' value='".$userdata['user_id']."' /><input type='hidden' name='tid' value='".$data['tour_id']."' /><input type='hidden' name='tkod' value='1' /><input type='submit' class='button' name='t_reg' value='Register' /></form>";
}

} else {
echo "This tourney has been started.";
}

}
}
} else {
if ($data['tour_finished'] == "0") {
echo "<span class='center'>Please, login for<br />register to tourney.</span>";
} else {
 echo "Winners:<br />";
for ($z=1;$z<=4;$z++) {
$ui = dbarray(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$data['tour_w'.$z]."'"));
echo $z.". <a href='".BASEDIR."profile.php?lookup=".$data['tour_w'.$z]."'><strong>".$ui['user_name']."</strong></a><br />";
} 
}
}
echo "</td></tr>";
echo "<tr><td class='tbl2'>Status:</td><td class='tbl1'>".showStatus($data['tour_id'])."</td></tr>"; 
echo "<tr><td class='tbl2'>Game:</td><td class='tbl1'>".$data['tour_game']."</td></tr>";
echo "<tr><td class='tbl2'>Players:<br /><i class='small'>(registered/confirmed/max)</i></td><td class='tbl1'>".$r_ch."/".$r_tot."/".$data['tour_maxpl']."</td></tr>"; 
echo "<tr><td class='tbl2'>Registration ends:</td><td class='tbl1'>".date("G:i j-n-Y", $data['tour_date'])."</td></tr>";
echo "<tr><td class='tbl2'>Check-in start:</td><td class='tbl1'>".date("G:i j-n-Y", ($data['tour_date']-1800))."</td></tr>"; 
$asd = time()+(($settings['timeoffset']+$settings['serveroffset'])*3600); 
echo "<tr><td class='tbl2'>Current server time:<br /><i class='small'>(check your offset)</i></td><td class='tbl1'>".date("G:i j-n-Y", $asd)."</td></tr>"; 
if (checkrights("T")) {
echo "<tr><td class='tbl2' colspan='3'>Options: <a href='".INFUSIONS."al_tourney/admin/index.php".$aidlink."&p=newtour&a=edit&id=".$data['tour_id']."''><img src='".IMAGES."edit.png' alt='edit tourney' /></a> <a href='".INFUSIONS."al_tourney/admin/index.php".$aidlink."&p=bracket&id=".$data['tour_id']."''><img src='".IMAGES."arrow.png' alt='edit bracket' /></a> <a href='".INFUSIONS."al_tourney/admin/index.php".$aidlink."&p=newtour&a=delete&id=".$data['tour_id']."''><img src='".IMAGES."no.png' alt='delete tourney' /></a></td></tr>"; 
}
if ($data['tour_grid'] == "1") {
echo "<tr><td class='tbl2' colspan='3' align='center'><a href='".BASEDIR."tourney.php?p=viewbracket&id=".$data['tour_id']."'>Go to brackets</a></td></tr>"; 
}
echo "<tr><td class='tbl2' colspan='3'>Description:</td></tr>";
echo "<tr><td class='tbl1'>".nl2br(parseubb($data['tour_desc']))."</td></tr>";
echo "</table>";
closetable();
openside("Players", true, "off");
$result2 = dbquery("SELECT tpl.*, us.user_name FROM ".DB_T_PLAYERS." tpl LEFT JOIN ".DB_USERS." us ON tpl.player_user=us.user_id WHERE player_tour='".$data['tour_id']."' AND player_user<>'".$bye."'");

if (dbrows($result2)) {
echo "<table width='100%'>";
echo "<tr><td width='1%' class='tbl2'>#</td><td class='tbl2'>Name</td><td width='1%' class='tbl2'>Confirm</td><td class='tbl2' width='1%'></td></tr>";
$i = 1;
while ($data2=dbarray($result2)) {
echo "<tr><td width='1%' class='".($i%2==0 ? "tbl2" : "tbl1")."'>".$i."</td><td class='".($i%2==0 ? "tbl2" : "tbl1")."'><a href='".BASEDIR."profile.php?lookup=".$data2['player_user']."'>".$data2['user_name']."</a></td><td width='1%' class='".($i%2==0 ? "tbl2" : "tbl1")."' align='center'>".($data2['player_checkin'] == 1 ? "<span style='color:green;'>confirmed</span>" : "<span style='color:red;'>not confirmed</span>")."</td><td class='".($i%2==0 ? "tbl2" : "tbl1")."' width='1%'>";
if (checkrights("T")) {
echo "<a href='".FUSION_REQUEST."&checkin=".($data2['player_checkin'] == "1" ? "0" : "1")."&pid=".$data2['player_id']."'><img src='".IMAGES."".($data2['player_checkin'] == "1" ? "no" : "yes").".png' alt='change confirmation' /></a> <a href='".FUSION_REQUEST."&delete=".$data2['player_id']."'><img src='".IMAGES."no.png' alt='delete player' /></a>";
} 
echo "</td></tr>"; 
$i++;
}
echo "</table>";
} else {
echo "No registered players.";
}
closeside();
}

require_once INCLUDES."comments_include.php";
showcomments("TT", DB_T_TOURS, "tour_id", $_GET['id'], BASEDIR."tourney.php?p=viewtour&id=".$_GET['id']); 

?>
