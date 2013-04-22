<?php
if (!defined("IN_FUSION")) die("access denied!");
require_once INCLUDES."bbcode_include.php";

if (isset($_GET['a']) && $_GET['a'] == "delete" && isnum($_GET['id'])) {
$delete = dbquery("DELETE FROM ".DB_T_TOURS." WHERE tour_id='".$_GET['id']."'");
redirect(FUSION_SELF.$aidlink."&p=newtour&m=success"); 
}

if (isset($_POST['add']) || isset($_POST['save'])) {
$ttname = trim(stripinput($_POST['tname']));
$ttgame = trim(stripinput($_POST['tgame']));
$ttdesc = $_POST['tdesc']; 
$ttdate = mktime($_POST['thour'], $_POST['tmin'],0,$_POST['tmon'],$_POST['tday'],$_POST['tyear']);
$ttmaxpl = $_POST['tmaxpl'];
if ($ttdate < time()) {
$err = 3;
} elseif ($ttname == "") {
$err = 2;
} else {
if (isset($_POST['add'])) {
$result = dbquery("INSERT INTO ".DB_T_TOURS." (tour_name, tour_desc, tour_game, tour_date, tour_grid, tour_finished, tour_maxpl, tour_w1, tour_w2, tour_w3) VALUES ('".$ttname."', '".$ttdesc."', '".$ttgame."', '".$ttdate."', '0', '0', '".$ttmaxpl."', '0', '0', '0')");
}
if (isset($_POST['save'])) {
$result = dbquery("UPDATE ".DB_T_TOURS." SET tour_name='".$ttname."', tour_desc='".$ttdesc."', tour_game='".$ttgame."', tour_date='".$ttdate."', tour_maxpl='".$ttmaxpl."' WHERE tour_id='".$_POST['tid']."'");
}
if ($result) {
redirect(FUSION_SELF.$aidlink."&p=newtour&m=success");
} else {
$err = 4;
}
}
}

opentable("add tourney");
showAdminNav();
if (isset($_GET['a']) && $_GET['a'] == "edit" && isset($_GET['id']) && isnum($_GET['id'])) {
$result = dbquery("SELECT * FROM ".DB_T_TOURS." WHERE tour_id='".$_GET['id']."'");
if (dbrows($result)) {
$data = dbarray($result);
$tname = $data['tour_name'];
$tgame = $data['tour_game'];
$tdesc = $data['tour_desc']; 
$tday = date("j", $data['tour_date']);
$tmon = date("n", $data['tour_date']);
$tyear = date("Y", $data['tour_date']);
$tmin = date("i", $data['tour_date']);
$thour = date("G", $data['tour_date']);
$tmaxpl = $data['tour_maxpl'];
$tedit = true;
} else {
$err = 1;
$tname = "";
$tgame = "";
$tdesc = ""; 
$tday = date("j");
$tmon = date("n");
$tyear = date("Y");
$tmin = 00;
$thour = 0; 
$tmaxpl = 8;
}
} else {
if (isset($err) && ($err == 2 || $err == 3)) {
$tname = trim(stripinput($_POST['tname']));
$tgame = trim(stripinput($_POST['tgame']));
$tdesc = $_POST['tdesc']; 
$tday = $_POST['tday']; 
$tmon = $_POST['tmon']; 
$tyear = $_POST['tyear']; 
$tmin = $_POST['tmin']; 
$thour = $_POST['thour']; 
$tmaxpl = $_POST['tmaxpl'];
} else {
$tname = "";
$tgame = "";
$tdesc = ""; 
$tday = date("j");
$tmon = date("n");
$tyear = date("Y");
$tmin = 0;
$thour = 0; 
$tmaxpl = 8;
}
}
if (isset($err) || isset($_GET['m'])) {
echo "<div class='admin-message'>";
if ($err == 1) {
echo "Invalid tour id.";
}
if ($err == 2) {
echo "Tour name must be filled..";
} 
if ($err == 3) {
echo "Tour date must be in the future..";
} 
if ($err == 4) {
echo "Database error.";
} 
if ($_GET['m'] == "success") {
echo "Tour was added/updated successfuly.";
} 
echo "</div>";
}

echo "<form name='inputform' method='post'>";
echo "<table width='100%'>";
echo "<tr><td class='tbl2' width='250'>Tour name:</td><td class='tbl1'><input type='text' name='tname' value='".$tname."' class='textbox' style='width:250px;' /></td></tr>";
 echo "<tr><td class='tbl2' width='250'>Tour game:</td><td class='tbl1'><input type='text' name='tgame' value='".$tgame."' class='textbox' style='width:250px;' /></td></tr>";
 echo "<tr><td class='tbl2' width='250'>Tour desc:</td><td class='tbl1'><textarea class='textbox' name='tdesc' rows='3' cols='40'>".$tdesc."</textarea>";
 echo display_bbcodes("240px;", "tdesc", "inputform"); 
echo "</td></tr>";
echo "<tr><td class='tbl2'>Tour max players:</td><td class='tbl1'><select name='tmaxpl'><option value='8'".($tmaxpl == 8 ? " selected='selected'" : "").">8</option><option value='16'".($tmaxpl == 16 ? " selected='selected'" : "").">16</option><option value='32'".($tmaxpl == 32 ? " selected='selected'" : "").">32</option></select></td></tr>";
 echo "<tr><td class='tbl2' width='250'>Tour date:</td><td class='tbl1'><select name='thour'>";
for ($i=0;$i<=23;$i++) {
echo "<option value='".$i."'".($thour == $i ? " selected='selected'" : "").">".$i."</option>";
}
echo "</select> : <select name='tmin'>";
for ($i = 0;$i<=9;$i++) {
$x = "0".$i;
echo "<option value='".$x."'".($x == $tmin ? " selected='selected'" : "").">".$x."</option>";
}
for ($i=10;$i<=59;$i++) {
echo "<option value='".$i."'".($i == $tmin ? " selected='selected'" : "").">".$i."</option>"; 
}
echo "</select> <select name='tday'>";
for ($i=1;$i<=31;$i++) {
echo "<option value='".$i."'".($i == $tday ? " selected='selected'" : "").">".$i."</option>";
}
echo "</select> - <select name='tmon'>";
for ($i=1;$i<=12;$i++) {
echo "<option value='".$i."'".($i == $tmon ? " selected='selected'" : "").">".$i."</option>";
} 
echo "</select> - <select name='tyear'>";
for ($i=2012;$i<=2020;$i++) {
echo "<option value='".$i."'".($i == $tyear ? " selected='selected'" : "").">".$i."</option>";
} 
echo "</select></td></tr>";
 echo "<tr><td class='tbl1' colspan='2'>".(isset($tedit) ? "<input type='hidden' name='tid' value='".$_GET['id']."' />" : "")."<input type='submit' class='button' value='save' name='".(isset($tedit) ? "save" : "add")."' /></td></tr>"; 
echo "</table></form>";
closetable();

?>
