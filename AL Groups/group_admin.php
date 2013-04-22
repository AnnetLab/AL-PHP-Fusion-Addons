<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
 if (file_exists(INFUSIONS."al_group/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_groups/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_groups/locale/English.php";
}
require_once INFUSIONS."al_groups/infusion_db.php";

if (!iMEMBER) redirect(BASEDIR."groups.php");

if (isset($_POST['gsave'])) {
$gname = trim(stripinput($_POST['gname'])); 
$gid = $_POST['gid'];
$error = 0;
if ($gname == "") {
echo "<div class='admin-message'>Fill name</div>";
} else {
if ($_FILES['gimage']['name'] != "") {
 require_once INCLUDES."photo_functions_include.php"; 
$photo_dest = INFUSIONS."al_groups/images/";
$photo_types = array(".gif",".jpg",".jpeg",".png");
$photo_pic = $_FILES['gimage']['name'];
$photo_size = $_FILES['gimage']['size'];
$photo_temp = $_FILES['gimage']['tmp_name']; 
$photo_name = substr($photo_pic, 0, strrpos($photo_pic, "."));
$photo_ext = strtolower(strrchr($photo_pic,".")); 
$photo_name = $gid;


if ($photo_size > 1024*1024*5) {
$error = 1;
} elseif (!in_array($photo_ext, $photo_types)) {
$error = 2;
} elseif (!is_uploaded_file($photo_temp)) {
$error = 5;
} else {
move_uploaded_file($photo_temp, $photo_dest.$photo_name.$photo_ext);
$gimage = $photo_name.$photo_ext; 

$size = @getimagesize($photo_dest.$photo_name.$photo_ext);
if ($size[0] > 1800 || $size[1] > 1600) {
$error = 3;
unlink($photo_dest.$photo_name.$photo_ext);
} else {

if ($size[0] > 200 || $size[1] > 200) {
$photo_resized = $photo_name."_thumb".$photo_ext;
$gimage = $photo_resized; 
createthumbnail($size[2], $photo_dest.$photo_name.$photo_ext, $photo_dest.$photo_resized, 200, 200); 
} // resize
}
} 
// insert
if ($error > 0) {
echo "<div class='admin-message'>Error. wrong image</div>";
} else {
$update = dbquery("UPDATE ".DB_GS_GROUPS." SET group_name='".$gname."', group_cat='".$_POST['gcat']."', group_image='".$gimage."' WHERE group_id='".$gid."'");
redirect(BASEDIR."group.php?view=".$gid);
}
} else {
//no img uploaded
 $update = dbquery("UPDATE ".DB_GS_GROUPS." SET group_name='".$gname."', group_cat='".$_POST['gcat']."' WHERE group_id='".$gid."'");
redirect(BASEDIR."group.php?view=".$gid); 
}
}

}

if (isset($_POST['gadd'])) {
//print_r($_POST);
$gname = trim(stripinput($_POST['gname']));
require_once INCLUDES."photo_functions_include.php";
$maxid = dbarray(dbquery("SELECT * FROM ".DB_GS_GROUPS." ORDER BY group_id DESC LIMIT 1"));
$error = 0;
if ($_FILES['gimage']['name'] != "") {
$photo_dest = INFUSIONS."al_groups/images/";
$photo_types = array(".gif",".jpg",".jpeg",".png");
$photo_pic = $_FILES['gimage']['name'];
$photo_size = $_FILES['gimage']['size'];
$photo_temp = $_FILES['gimage']['tmp_name']; 
$photo_name = substr($photo_pic, 0, strrpos($photo_pic, "."));
$photo_ext = strtolower(strrchr($photo_pic,".")); 
$photo_name = $maxid['group_id']+1;


if ($photo_size > 1024*1024*5) {
$error = 1;
} elseif (!in_array($photo_ext, $photo_types)) {
$error = 2;
} elseif (!is_uploaded_file($photo_temp)) {
$error = 5;
} else {
move_uploaded_file($photo_temp, $photo_dest.$photo_name.$photo_ext);
$gimage = $photo_name.$photo_ext; 

$size = @getimagesize($photo_dest.$photo_name.$photo_ext);
if ($size[0] > 1800 || $size[1] > 1600) {
$error = 3;
unlink($photo_dest.$photo_name.$photo_ext);
} else {

if ($size[0] > 200 || $size[1] > 200) {
$photo_resized = $photo_name."_thumb".$photo_ext;
$gimage = $photo_resized; 
createthumbnail($size[2], $photo_dest.$photo_name.$photo_ext, $photo_dest.$photo_resized, 200, 200); 
} // resize
}
}
} else { // name != ""
$gimage = 0;
}

if ($gname == "") { $error = 10; }
if ($error > 0) {
redirect(BASEDIR."group_admin.php?action=create&error=".$error);
} else {
$create = dbquery("INSERT INTO ".DB_GS_GROUPS." (group_name, group_image, group_creator, group_cat, group_stat) VALUES ('".$gname."', '".$gimage."', '".$userdata['user_id']."', '".$_POST['gcat']."', '0')");
$gid = mysql_insert_id();
$insert2 = dbquery("INSERT INTO ".DB_GS_GROUP_USERS." (guser_user, guser_group) VALUES ('".$userdata['user_id']."', '".$gid."')");
redirect(BASEDIR."group.php?view=".$gid);
}
}

 if (isset($_GET['action']) && $_GET['action'] == "edit" && isset($_GET['id']) && isnum($_GET['id'])) { 
$group = dbquery("SELECT * FROM ".DB_GS_GROUPS." WHERE group_id='".$_GET['id']."'");
if (dbrows($group)) {
$group = dbarray($group);
if ($group['group_creator'] == $userdata['user_id'] || checkrights("GS")) {

opentable($locale['gs71']);
 echo "<form name='ghuhdh' method='post' enctype='multipart/form-data'>";
echo "<table width='100%'>";
echo "<tr><td class='tbl2' width='250'>".$locale['gs21']."</td><td class='tbl2'><input type='text' class='textbox' style='width:250px;' name='gname' value='".$group['group_name']."' /></td></tr>";
 echo "<tr><td class='tbl2' width='250'>".$locale['gs22']."</td><td class='tbl2'><select name='gcat'>";
$cats = dbquery("SELECT * FROM ".DB_GS_CATS."");
if (dbrows($cats)) {
while ($cat = dbarray($cats)) {
echo "<option value='".$cat['cat_id']."'".($group['group_cat'] == $cat['cat_id'] ? " selected='selected'" : "").">".$cat['cat_name']."</option>";
}
}
echo "</select></td></tr>";
 echo "<tr><td class='tbl2' width='250'>".$locale['gs23']."</td><td class='tbl2'>".$locale['gs72']."<br /><img src='".INFUSIONS."al_groups/images/".($group['group_image'] == "0" ? "no.jpg" : $group['group_image'])."' width='200' /><br /><input type='file' name='gimage' style='width:250px;' class='textbox' /></td></tr>"; 
 echo "<tr><td class='tbl2' colspan='2'><input type='hidden' name='gid' value='".$_GET['id']."' /><input type='submit' name='gsave' class='button' value='".$locale['gs24']."' /></td></tr>"; 

echo "</table></form>"; 
closetable();



} else {
redirect(BASEDIR."groups.php?cat=0"); 
}
} else {
redirect(BASEDIR."groups.php?cat=0");
}


} elseif (isset($_GET['action']) && $_GET['action'] == "create") {
opentable($locale['gs20']);
echo "<form name='ghuhdh' method='post' enctype='multipart/form-data'>";
echo "<table width='100%'>";
echo "<tr><td class='tbl2' width='250'>".$locale['gs21']."</td><td class='tbl2'><input type='text' class='textbox' style='width:250px;' name='gname' /></td></tr>";
 echo "<tr><td class='tbl2' width='250'>".$locale['gs22']."</td><td class='tbl2'><select name='gcat'>";
$cats = dbquery("SELECT * FROM ".DB_GS_CATS."");
if (dbrows($cats)) {
while ($cat = dbarray($cats)) {
echo "<option value='".$cat['cat_id']."'>".$cat['cat_name']."</option>";
}
}
echo "</select></td></tr>";
 echo "<tr><td class='tbl2' width='250'>".$locale['gs23']."</td><td class='tbl2'><input type='file' name='gimage' style='width:250px;' class='textbox' /></td></tr>"; 
 echo "<tr><td class='tbl2' colspan='2'><input type='submit' name='gadd' class='button' value='".$locale['gs24']."' /></td></tr>"; 

echo "</table></form>";

closetable();

} else {
redirect(BASEDIR."groups.php");
}

require_once THEMES."templates/footer.php";
?>
