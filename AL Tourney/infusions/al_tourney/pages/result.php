<?php
if (!defined("IN_FUSION")) die("access denied");

if (!isset($_GET['id']) && !isnum($_GET['id'])) {
redirect(START_PAGE);
}

if (isset($_POST['admin_set'])) {
$md = mktime($_POST['mhour'], $_POST['mmin'], 0, $_POST['mmon'], $_POST['mday'], $_POST['myear']);
$now = time()+(($settings['timeoffset']+$settings['serveroffset'])*3600);
if ($now < $md) {
$update = dbquery("UPDATE ".DB_T_MATCHES." SET match_date='".$md."' WHERE match_id='".$_POST['mid']."'");
setNoti($_POST['mid'], 1, 3); 
}
redirect(BASEDIR."tourney.php?p=result&id=".$_POST['mid']);
}

if (isset($_POST['pl_agree'])) {
 $now = time()+(($settings['timeoffset']+$settings['serveroffset'])*3600);
if ($_POST['mdate'] > $now) {
$udate = dbquery("UPDATE ".DB_T_MATCHES." SET match_date='".$_POST['mdate']."' WHERE match_id='".$_POST['mid']."'");
// 1 ust nov data
setNoti($_POST['mid'], 1, 3);
} 
redirect(BASEDIR."tourney.php?p=result&id=".$_POST['mid']); 
}

 if (isset($_POST['pl_set'])) {
$md = mktime($_POST['mhour'], $_POST['mmin'], 0, $_POST['mmon'], $_POST['mday'], $_POST['myear']);
$now = time()+(($settings['timeoffset']+$settings['serveroffset'])*3600);
if ($now < $md) {
if ($_POST['m_pl'] == "1") {
$update = dbquery("UPDATE ".DB_T_MATCHES." SET match_date1='".$md."' WHERE match_id='".$_POST['mid']."'");
// 2 - prot predl vremya
setNoti($_POST['mid'], 2, 2); 
} else {
$update = dbquery("UPDATE ".DB_T_MATCHES." SET match_date2='".$md."' WHERE match_id='".$_POST['mid']."'");
setNoti($_POST['mid'], 2, 1); 
}
}
redirect(BASEDIR."tourney.php?p=result&id=".$_POST['mid']);
} 

if (isset($_POST['add_result'])) {
$data = dbarray(dbquery("SELECT * FROM ".DB_T_MATCHES." WHERE match_id='".$_POST['mid']."'")); 
if (isset($_POST['score1']) && isnum($_POST['score1']) && isset($_POST['score2']) && isnum($_POST['score2']) && $_POST['score1'] != $_POST['score2']) {
if (checkrights("T")) {
EnterResult($data['match_tour'], $data['match_round'], $data['match_match'], $_POST['score1'], $_POST['score2']);
clearByes($data['match_tour']);
redirect(BASEDIR."tourney.php?p=viewbracket&id=".$data['match_tour']);
} elseif ($data['match_pl1'] == $userdata['user_id'] && $_POST['score1'] < $_POST['score2']) {
EnterResult($data['match_tour'], $data['match_round'], $data['match_match'], $_POST['score1'], $_POST['score2']);
clearByes($data['match_tour']);
redirect(BASEDIR."tourney.php?p=viewbracket&id=".$data['match_tour']); 
} elseif ($data['match_pl2'] == $userdata['user_id'] && $_POST['score1'] > $_POST['score2']) {
EnterResult($data['match_tour'], $data['match_round'], $data['match_match'], $_POST['score1'], $_POST['score2']);
clearByes($data['match_tour']);
redirect(BASEDIR."tourney.php?p=viewbracket&id=".$data['match_tour']); 
} else {
redirect(FUSION_SELF."?p=result&id=".$_GET['id']); 
}
} else {
redirect(FUSION_SELF."?p=result&id=".$_GET['id']); 
}
}

$result = dbquery("SELECT tm.*, tt.* FROM ".DB_T_MATCHES." tm LEFT JOIN ".DB_T_TOURS." tt ON tt.tour_id=tm.match_tour WHERE match_id='".$_GET['id']."'");

if (dbrows($result)) {
$bye = bye();
opentable("Enter result");
$data = dbarray($result);

// table with match info
 // pl1
if ($data['match_pl1'] == "0") {
$pl1 = "TBA";
$av1 = "<img src='".IMAGES."avatars/noavatar50.png' border='0' />"; 
} elseif ($data['match_pl1'] == $bye) {
$pl1 = "freeslot";
$av1 = "<img src='".IMAGES."avatars/noavatar50.png' border='0' />"; 
} else {
$u1 = dbarray(dbquery("SELECT user_name, user_avatar FROM ".DB_USERS." WHERE user_id='".$data['match_pl1']."'"));
$pl1 = "<a href='".BASEDIR."profile.php?lookup=".$data['match_pl1']."'>".($data['match_winner'] == $data['match_pl1'] ? "<strong>".$u1['user_name']."</strong>" : $u1['user_name'])."</a>";
$av1 = "<img src='".IMAGES."avatars/".($u1['user_avatar'] != "" ? $u1['user_avatar'] : "noavatar50.png" )."' border='0' />";
}

// pl2
if ($data['match_pl2'] == "0") {
$pl2 = "TBA";
$av2 = "<img src='".IMAGES."avatars/noavatar50.png' border='0' />"; 
} elseif ($data['match_pl2'] == $bye) {
$pl2 = "freeslot";
$av2 = "<img src='".IMAGES."avatars/noavatar50.png' border='0' />"; 
} else {
$u2 = dbarray(dbquery("SELECT user_name, user_avatar FROM ".DB_USERS." WHERE user_id='".$data['match_pl2']."'"));
$pl2 = "<a href='".BASEDIR."profile.php?lookup=".$data['match_pl2']."'>".($data['match_winner'] == $data['match_pl2'] ? "<strong>".$u2['user_name']."</strong>" : $u2['user_name'])."</a>";
$av2 = "<img src='".IMAGES."avatars/".($u2['user_avatar'] != "" ? $u2['user_avatar'] : "noavatar50.png" )."' border='0' />"; 
} 

// vs 
if ($data['match_played'] == "1") {
$vs = $data['match_score1'].":".$data['match_score2'];
} else {
$vs = "vs";
}

$pl = dbcount("(player_id)", DB_T_PLAYERS, "player_tour='".$data['tour_id']."' AND player_checkin='1'");
$max = getMaxPl($pl);
$rounds = log($max)/log(2); 
$y = pow(2, ($rounds-$data['match_round']+1));

echo "<table width='90%' class='center'><tr><td class='tbl2' colspan='3'><a href='".BASEDIR."tourney.php?p=viewtour&id=".$data['tour_id']."'>".$data['tour_name']."</a> @ 1/".$y." - <a href='".BASEDIR."tourney.php?p=viewbracket&id=".$data['tour_id']."'>brackets</a></td></tr>";
$date_now = time()+(($settings['timeoffset']+$settings['serveroffset'])*3600);
if ($data['match_date'] != "0") {
echo "<tr><td class='tbl2' colspan='3'>Date: ".date("G:i j-n-Y", $data['match_date']).". Current time (check your offset): ".date("G:i j-n-Y", $date_now)."</td></tr>";
}
echo "<tr><td class='tbl2' align='center' width='40%'>".$av1."</td><td class='tbl1' align='center' valign='middle' width='20%'></td><td class='tbl2' align='center' width='40%'>".$av2."</td></tr><tr><td class='tbl2' align='center'>".$pl1."</td><td class='tbl2' align='center'>".$vs."</td><td class='tbl2' align='center'>".$pl2."</td></tr>";

if (checkrights("T") || $userdata['user_id'] == $data['match_pl1'] || $userdata['user_id'] == $data['match_pl2']) {
if ($data['match_pl1'] != $bye && $data['match_pl2'] != $bye && $data['match_pl1'] != "0" && $data['match_pl2'] != "0") {
if ($data['match_played'] == "0" || ($data['match_played'] == "1" && checkrights("T"))) {

echo "<tr><td class='tbl2' align='right'><form name='gfghgd' method='post'><input type='text' name='score1' class='textbox' value='".$data['match_score1']."' style='width:30px;text-align:center;' maxlength='3' /></td><td class='tbl2' align='center'>:</td><td class='tbl2'><input type='text' name='score2' class='textbox' style='width:30px;text-align:center;' maxlength='3' value='".$data['match_score2']."' /><input type='hidden' name='mid' value='".$_GET['id']."' /></td></tr><tr><td class='tbl2' colspan='3' align='center'>".(!checkrights("T") ? "You can report about lose only!<br />" : "")."<input type='submit' class='button' name='add_result' value='Report' /></form></td></tr>";
}
}
}

if ($data['match_date'] == "0" && $data['match_played'] == "0" && $data['match_pl1'] != "0" && $data['match_pl2'] != "0") {
if ($data['match_pl1'] == $userdata['user_id'] || $data['match_pl2'] == $userdata['user_id']) {
// pl set
if ($userdata['user_id'] == $data['match_pl1']) {
$left = true;
if ($data['match_date1'] != "0") {
$h = date("G", $data['match_date1']);
$mi = date("i", $data['match_date1']);
$d = date("j", $data['match_date1']); 
$m = date("n", $data['match_date1']);
$y = date("Y", $data['match_date1']); 
} else {
$h = date("G", $date_now);
$mi = date("i", $date_now);
$d = date("j", $date_now);
$m = date("n", $date_now);
$y = date("Y", $date_now);
}
$opp = $data['match_date2'] != "0" ? $data['match_date2'] : 0; 
} else {
$left = false;
if ($data['match_date2'] != "0") {
$h = date("G", $data['match_date2']);
$mi = date("i", $data['match_date2']);
$d = date("j", $data['match_date2']); 
$m = date("n", $data['match_date2']);
$y = date("Y", $data['match_date2']); 
} else {
$h = date("G", $date_now);
$mi = date("i", $date_now);
$d = date("j", $date_now); 
$m = date("n", $date_now);
$y = date("Y", $date_now); 
}
$opp = $data['match_date1'] != "0" ? $data['match_date1'] : 0;
}
// form for user set
echo "<tr><td class='tbl2' align='center'>".($opp != 0 ? "Your opponent has proposed the following match time:<br />".date("G:i d-m-Y", $opp)."<br /><form name='gydgh' method='post'><input type='hidden' name='mid' value='".$_GET['id']."' /><input type='hidden' name='mdate' value='".$opp."' /><input type='submit' name='pl_agree' class='button' value='Agree' /></form>" : "Your opponent hasn't proposed any time.")."</td><td class='tbl1'></td><td class='tbl2'class='center'>You can propose your match time:<br />";
echo "<form method='post' name='ghdfvnbb'><input type='hidden' name='mid' value='".$_GET['id']."' /><input type='hidden' name='m_pl' value='".($userdata['user_id'] == $data['match_pl1'] ? 1 : 2)."' /><select name='mhour'>";
for ($i=0;$i<=23;$i++) {
echo "<option value='".$i."'".($h == $i ? " selected='selected'" : "").">".$i."</option>";
}
echo "</select> : <select name='mmin'>";
for ($i = 0;$i<=9;$i++) {
$x = "0".$i;
echo "<option value='".$x."'".($x == $mi ? " selected='selected'" : "").">".$x."</option>";
}
for ($i=10;$i<=59;$i++) {
echo "<option value='".$i."'".($i == $mi ? " selected='selected'" : "").">".$i."</option>"; 
}
echo "</select> <select name='mday'>";
for ($i=1;$i<=31;$i++) {
echo "<option value='".$i."'".($i == $d ? " selected='selected'" : "").">".$i."</option>";
}
echo "</select> - <select name='mmon'>";
for ($i=1;$i<=12;$i++) {
echo "<option value='".$i."'".($i == $m ? " selected='selected'" : "").">".$i."</option>";
} 
echo "</select> - <select name='myear'>";
for ($i=2012;$i<=2020;$i++) {
echo "<option value='".$i."'".($i == $y ? " selected='selected'" : "").">".$i."</option>";
} 
echo "</select><input type='submit' class='button' name='pl_set' value='Propose' /></form>"; 
echo "</td></tr>";

} elseif (checkrights("T")) {
// admin set
$h = date("G", $date_now);
$mi = date("i", $date_now);
$d = date("j", $date_now);
$m = date("n", $date_now);
$y = date("Y", $date_now); 
echo "<tr><td class='tbl2' colspan='3' align='center'>Set match date:<br /><form name='ghuhgff' method='post'><input type='hidden' name='mid' value='".$_GET['id']."' /><select name='mhour'>";
for ($i=0;$i<=23;$i++) {
echo "<option value='".$i."'".($h == $i ? " selected='selected'" : "").">".$i."</option>";
}
echo "</select> : <select name='mmin'>";
for ($i = 0;$i<=9;$i++) {
$x = "0".$i;
echo "<option value='".$x."'".($x == $mi ? " selected='selected'" : "").">".$x."</option>";
}
for ($i=10;$i<=59;$i++) {
echo "<option value='".$i."'".($i == $mi ? " selected='selected'" : "").">".$i."</option>"; 
}
echo "</select> <select name='mday'>";
for ($i=1;$i<=31;$i++) {
echo "<option value='".$i."'".($i == $d ? " selected='selected'" : "").">".$i."</option>";
}
echo "</select> - <select name='mmon'>";
for ($i=1;$i<=12;$i++) {
echo "<option value='".$i."'".($i == $m ? " selected='selected'" : "").">".$i."</option>";
} 
echo "</select> - <select name='myear'>";
for ($i=2012;$i<=2020;$i++) {
echo "<option value='".$i."'".($i == $y ? " selected='selected'" : "").">".$i."</option>";
} 
echo "</select><input type='submit' class='button' name='admin_set' value='Set' /></form></td></tr>";

}
} else {
if (checkrights("T")) {
// admin change
$h = date("G", $data['match_date']);
$mi = date("i", $data['match_date']);
$d = date("j", $data['match_date']);
$m = date("n", $data['match_date']);
$y = date("Y", $data['match_date']); 
echo "<tr><td class='tbl2' colspan='3' align='center'>Change match date:<br /><form name='ghuhgff' method='post'><input type='hidden' name='mid' value='".$_GET['id']."' /><select name='mhour'>";
for ($i=0;$i<=23;$i++) {
echo "<option value='".$i."'".($h == $i ? " selected='selected'" : "").">".$i."</option>";
}
echo "</select> : <select name='mmin'>";
for ($i = 0;$i<=9;$i++) {
$x = "0".$i;
echo "<option value='".$x."'".($x == $mi ? " selected='selected'" : "").">".$x."</option>";
}
for ($i=10;$i<=59;$i++) {
echo "<option value='".$i."'".($i == $mi ? " selected='selected'" : "").">".$i."</option>"; 
}
echo "</select> <select name='mday'>";
for ($i=1;$i<=31;$i++) {
echo "<option value='".$i."'".($i == $d ? " selected='selected'" : "").">".$i."</option>";
}
echo "</select> - <select name='mmon'>";
for ($i=1;$i<=12;$i++) {
echo "<option value='".$i."'".($i == $m ? " selected='selected'" : "").">".$i."</option>";
} 
echo "</select> - <select name='myear'>";
for ($i=2012;$i<=2020;$i++) {
echo "<option value='".$i."'".($i == $y ? " selected='selected'" : "").">".$i."</option>";
} 
echo "</select><input type='submit' class='button' name='admin_set' value='Change' /></form></td></tr>"; 

}
}


echo "</table>";
closetable();

require_once INCLUDES."comments_include.php";
showcomments("TM", DB_T_MATCHES, "match_id", $_GET['id'], BASEDIR."tourney.php?p=result&id=".$_GET['id']);

} else {
echo "invalid id";
}

?>
