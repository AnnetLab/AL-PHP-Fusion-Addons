<?php
if (!defined("IN_FUSION")) die("fu");

if (isset($_POST['search'])) {

opentable($locale['ar33']);
if ($_POST['sort'] == "1") {
$id = trim(stripinput($_POST['query']));
$result = dbquery("SELECT * FROM ".DB_RM_APPS." WHERE app_id='".$id."'");
} elseif ($_POST['sort'] == "2") {
$name = trim(stripinput($_POST['query']));
$qwe = dbquery("SELECT * FROM ".DB_USERS." WHERE user_name='".$name."'");
if (dbrows($qwe)) {
$qwe = dbarray($qwe);
$result = dbquery("SELECT * FROM ".DB_RM_APPS." WHERE app_user='".$qwe['user_id']."'");
} else {
$asd = dbquery("SELECT * FROM ".DB_RM_USERS." WHERE rmuser_username='".$name."'"); 
if (dbrows($asd)) {
$asd = dbarray($asd);
$result = dbquery("SELECT * FROM ".DB_RM_APPS." WHERE app_rm_user='".$asd['rmuser_id']."'"); 
} else {
$result = false;
}
}
} elseif ($_POST['sort'] == "3") {
 $email = trim(stripinput($_POST['query']));
$qwe = dbquery("SELECT * FROM ".DB_USERS." WHERE user_email='".$email."'");
if (dbrows($qwe)) {
$qwe = dbarray($qwe);
$result = dbquery("SELECT * FROM ".DB_RM_APPS." WHERE app_user='".$qwe['user_id']."'");
} else {
$asd = dbquery("SELECT * FROM ".DB_RM_USERS." WHERE rmuser_useremail='".$email."'"); 
if (dbrows($asd)) {
$asd = dbarray($asd);
$result = dbquery("SELECT * FROM ".DB_RM_APPS." WHERE app_rm_user='".$asd['rmuser_id']."'"); 
} else {
$result = false;
}
}
} elseif (strlen($_POST['sort']) > 1) {
    $query = trim(stripinput($_POST['query']));
    $qwe = dbquery("SELECT * FROM ".DB_RM_FORM_APPS." WHERE fa_".$_POST['sort']."='".$query."'");
    if (dbrows($qwe)) {
        $qwe = dbarray($qwe);
        $result = dbquery("SELECT * FROM ".DB_RM_APPS." WHERE app_form='".$qwe['fa_id']."'");
    } else {
        $result = false;
    }
}
if (dbrows($result)) {
 echo "<table width='100%'>";
echo "<tr><td class='tbl2' width='1%' align='center'>ID</td><td class='tbl2' width='30%'>".$locale['ar23']."</td><td class='tbl2' width='25%'>".$locale['ar26']."</td><td class='tbl2' width='20%'>".$locale['ar24']."</td><td class='tbl2' width='20%' align='center'>".$locale['ar25']."</td><td class='tbl2' align='center'></td></tr>"; 
$i=0;
while ($data = dbarray($result)) {
 if ($data['app_user'] != "0") {
$uinfo = dbarray(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$data['app_user']."'"));
$ulink = "<a href='".BASEDIR."profile.php?lookup=".$data['app_user']."'><span style='color:green'>".(!empty($uinfo['user_name']) ? $uinfo['user_name'] : "deleted")."</span></a>";
} else if ($data['app_rm_user'] != "0") {
$uinfo = dbarray(dbquery("SELECT rmuser_username, rmuser_verified FROM ".DB_RM_USERS." WHERE rmuser_id='".$data['app_rm_user']."'"));
$ulink = "<span style='color:".($uinfo['rmuser_verified'] == "1" ? "green" : "red")."'>".(!empty($uinfo['rmuser_username']) ? $uinfo['rmuser_username'] : $data['app_username'])."</span>"; 
}

if ($data['app_status'] == "0") {
$status = "not decided";
} elseif ($data['app_status'] == "1") {
$status = "<span style='color:green;'>acepted</span>";
} else {
 $status = "<span style='color:red;'>declined</span>"; 
}
$cl = $i%2==0 ? "tbl1" : "tbl2";
echo "<tr><td class='".$cl."' align='center'>".$data['app_id']."</td><td class='".$cl."'>".$ulink."</td><td class='".$cl."'>".$status."</td><td class='".$cl."'>".showdate("shortdate",$data['app_date'])."</td><td class='".$cl."' align='center'><span style='color:green;'>".$data['app_votes_yes']."</span>/<span style='color:red;'>".$data['app_votes_no']."</span> </td><td class='".$cl."'><a href='".INFUSIONS."al_register_mod/admin/index.php?p=view_app&id=".$data['app_id']."'>".$locale['ar27']."</a></td></tr>";
$i++; 

}
echo "</table>";
} else {
echo "No results...";
}



closetable();

} else {

opentable($locale['ar28']);
echo "<form name='inputform' method='post'>";
echo "<div style='width:100%;text-align:center;'><input type='text' class='textbox' name='query' /> <select name='sort'><option value='1'>".$locale['ar29']."</option><option value='2'>".$locale['ar30']."</option><option value='3'>".$locale['ar31']."</option>";
$ffs = dbquery("SELECT * FROM ".DB_RM_FORM_FIELDS." WHERE ff_type='1'");
while ($ff=dbarray($ffs)) {
    echo "<option value='".$ff['ff_name']."'>".$ff['ff_title']."</option>";
}
echo "</select> <input type='submit' class='button' name='search' value='".$locale['ar28']."' /></div>";
echo "</form>";
closetable();

$result = dbquery("SELECT * FROM ".DB_RM_APPS." WHERE app_status='0' ORDER BY app_id DESC");
openside($locale['ar20'], true, "on");
if (dbrows($result)) {
echo "<table width='100%'>";
echo "<tr><td class='tbl2' width='1%' align='center'>ID</td><td class='tbl2' width='30%'>".$locale['ar23']."</td><td class='tbl2' width='25%'>".$locale['ar26']."</td><td class='tbl2' width='20%'>".$locale['ar24']."</td><td class='tbl2' width='20%' align='center'>".$locale['ar25']."</td><td class='tbl2' align='center'></td></tr>";
$i=0;
while ($data = dbarray($result)) {


if ($data['app_user'] != "0") {
$uinfo = dbarray(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$data['app_user']."'"));
$ulink = "<a href='".BASEDIR."profile.php?lookup=".$data['app_user']."'><span style='color:green'>".(!empty($uinfo['user_name']) ? $uinfo['user_name'] : "deleted")."</span></a>";
} else if ($data['app_rm_user'] != "0") {
$uinfo = dbarray(dbquery("SELECT rmuser_username, rmuser_verified FROM ".DB_RM_USERS." WHERE rmuser_id='".$data['app_rm_user']."'"));
$ulink = "<span style='color:".($uinfo['rmuser_verified'] == "1" ? "green" : "red")."'>".(!empty($uinfo['rmuser_username']) ? $uinfo['rmuser_username'] : $data['app_username'])."</span>"; 
}

if ($data['app_status'] == "0") {
$status = "not decided";
} elseif ($data['app_status'] == "1") {
$status = "<span style='color:green;'>acepted</span>";
} else {
 $status = "<span style='color:red;'>declined</span>"; 
}
$cl = $i%2==0 ? "tbl1" : "tbl2";
echo "<tr><td class='".$cl."' align='center'>".$data['app_id']."</td><td class='".$cl."'>".$ulink."</td><td class='".$cl."'>".$status."</td><td class='".$cl."'>".showdate("shortdate",$data['app_date'])."</td><td class='".$cl."' align='center'><span style='color:green;'>".$data['app_votes_yes']."</span>/<span style='color:red;'>".$data['app_votes_no']."</span> </td><td class='".$cl."'><a href='".INFUSIONS."al_register_mod/admin/index.php?p=view_app&id=".$data['app_id']."'>".$locale['ar27']."</a></td></tr>";
$i++;
}
echo "</table>";
} else {
echo $locale['ar22'];
}
closeside();

if (!isset($_GET['rowstart'])) {
$rowstart = 0;
$_GET['rowstart'] = 0;
} else {
$rowstart = $_GET['rowstart'];
}

$tot = dbcount("(app_id)",DB_RM_APPS);
 $result = dbquery("SELECT * FROM ".DB_RM_APPS." ORDER BY app_id DESC LIMIT ".$rowstart.",15"); 
opentable($locale['ar21']);
 if (dbrows($result)) {
echo "<table width='100%'>";
echo "<tr><td class='tbl2' width='1%' align='center'>ID</td><td class='tbl2' width='30%'>".$locale['ar23']."</td><td class='tbl2' width='25%'>".$locale['ar26']."</td><td class='tbl2' width='20%'>".$locale['ar24']."</td><td class='tbl2' width='20%' align='center'>".$locale['ar25']."</td><td class='tbl2' align='center'></td></tr>";
$i=0;
while ($data = dbarray($result)) {


if ($data['app_user'] != "0") {
$uinfo = dbarray(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$data['app_user']."'"));
$ulink = "<a href='".BASEDIR."profile.php?lookup=".$data['app_user']."'><span style='color:green'>".(!empty($uinfo['user_name']) ? $uinfo['user_name'] : "deleted")."</span></a>";
} else if ($data['app_rm_user'] != "0") {
$uinfo = dbarray(dbquery("SELECT rmuser_username, rmuser_verified FROM ".DB_RM_USERS." WHERE rmuser_id='".$data['app_rm_user']."'"));
$ulink = "<span style='color:".($uinfo['rmuser_verified'] == "1" ? "green" : "red")."'>".(!empty($uinfo['rmuser_username']) ? $uinfo['rmuser_username'] : $data['app_username'])."</span>"; 
}

if ($data['app_status'] == "0") {
$status = "not decided";
} elseif ($data['app_status'] == "1") {
$status = "<span style='color:green;'>acepted</span>";
} else {
 $status = "<span style='color:red;'>declined</span>"; 
}
$cl = $i%2==0 ? "tbl1" : "tbl2";
echo "<tr><td class='".$cl."' align='center'>".$data['app_id']."</td><td class='".$cl."'>".$ulink."</td><td class='".$cl."'>".$status."</td><td class='".$cl."'>".showdate("shortdate",$data['app_date'])."</td><td class='".$cl."' align='center'><span style='color:green;'>".$data['app_votes_yes']."</span>/<span style='color:red;'>".$data['app_votes_no']."</span> </td><td class='".$cl."'><a href='".INFUSIONS."al_register_mod/admin/index.php?p=view_app&id=".$data['app_id']."'>".$locale['ar27']."</a></td></tr>";
$i++;
}
echo "</table>";
 echo makepagenav($_GET['rowstart'], 15, $tot, 3, INFUSIONS."al_register_mod/admin/index.php?p=apps&"); 
} else {
echo $locale['ar22'];
} 
closetable();


}
?>
