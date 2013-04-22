<?php
 /*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: imagehost.php
| Author: Rush
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/ 
require_once "maincore.php";
require_once INFUSIONS."imagehost/infusion_db.php";
require_once THEMES."templates/header.php";
 require_once INCLUDES."photo_functions_include.php"; 

 // Locales
if (file_exists(INFUSIONS."imagehost/locale/".$settings['locale'].".php")) {
include INFUSIONS."imagehost/locale/".$settings['locale'].".php"; 
} else {
include INFUSIONS."imagehost/locale/English.php";
} 

// settings
$isettings = dbarray(dbquery("SELECT * FROM ".DB_IMH." WHERE id='1'"));
$can_post = $userdata['user_level'] >= $isettings['lvl'] ? TRUE : FALSE;
$can_admin = iADMIN && checkrights("IMH") ? TRUE : FALSE;

/*
echo "<pre>";
print_r($_POST);
print_r($_FILES);
echo "</pre>";
*/


if (isset($_POST['upload'])) {
 
$resize_w = array("2" => "320", "3" => "640", "4" => "800", "5" => "1024", "6" => "1280", "7" => "1600");
$resize_h = array("2" => "240", "3" => "480","4" => "600", "5" => "768", "6" => "1024", "7" => "1400"); 

$error = "";
$photo_dest = IMAGES."imagehost/";
$photo_types = array(".gif",".jpg",".jpeg",".png");
$photo_pic = $_FILES['image']['name'];
$photo_size = $_FILES['image']['size'];
$photo_temp = $_FILES['image']['tmp_name']; 
$photo_name = substr($photo_pic, 0, strrpos($photo_pic, "."));
$photo_ext = strtolower(strrchr($photo_pic,".")); 
if (!file_exists(IMAGES."imagehost/".md5($photo_name).$photo_ext)) {
$photo_name = md5($photo_name);
} else {
$photo_name = $photo_name.rand(123, 20000).rand(765, 18900).rand();
$photo_name = !file_exists(IMAGES."imagehost/".md5($photo_name).$photo_ext) ? md5($photo_name) : die("Impossible Error. wtf!?");
}


if ($photo_size > 1572864) {
$error = 1;
} elseif (!in_array($photo_ext, $photo_types)) {
$error = 2;
} elseif (!is_uploaded_file($photo_temp)) {
$error = 5;
} else {
move_uploaded_file($photo_temp, $photo_dest.$photo_name.$photo_ext);


$size = @getimagesize($photo_dest.$photo_name.$photo_ext);
if ($size[0] > 2400 || $size[1] > 2000) {
$error = 3;
unlink($photo_dest.$photo_name.$photo_ext);
} else {

//photo resized
if ($_POST['resize'] > 1) {
$r_w = $resize_w[$_POST['resize']];
$r_h = $resize_h[$_POST['resize']]; 
if ($size[0] > $r_w || $size[1] > $r_h) {
$photo_resized = $photo_name."_resized".$photo_ext;
createthumbnail($size[2], $photo_dest.$photo_name.$photo_ext, $photo_dest.$photo_resized, $r_w, $r_h);
} else {
$photo_resized = $photo_name.$photo_ext;
}
} else {
$photo_resized = "";
}

// tbig
if ($size[0] > $settings['photo_w'] || $size[1] > $settings['photo_h']) {
$photo_tbig = $photo_name."_tbig".$photo_ext;
createthumbnail($size[2], $photo_dest.$photo_name.$photo_ext, $photo_dest.$photo_tbig, $settings['photo_w'], $settings['photo_h']);
} else {
$photo_tbig = $photo_name.$photo_ext;
}

// tsmall
 if ($size[0] > 150 || $size[1] > 150) {
$photo_tsmall = $photo_name."_tsmall".$photo_ext;
createthumbnail($size[2], $photo_dest.$photo_name.$photo_ext, $photo_dest.$photo_tsmall, 150, 150);
} else {
$photo_tsmall = $photo_name.$photo_ext;
}

} // err 3

} // err 5

if ($error == "") {
// insert db, redir 2 img
$photo_orig = $photo_name.$photo_ext;
$photo_user = (iMEMBER ? $userdata['user_id'] : "0");

$result = dbquery("INSERT INTO ".DB_IMH_IMAGES." (image_user, image_size, image_type, image_resize, image_views, image_original, image_tsmall, image_tbig, image_resized) VALUES ('".$photo_user."', '".$photo_size."', '".$photo_ext."', '".$_POST['resize']."', '1', '".$photo_orig."', '".$photo_tsmall."', '".$photo_tbig."', '".$photo_resized."')");
if ($result) {
$id = mysql_insert_id();
redirect(BASEDIR."imagehost.php?p=image&id=".$id);
} else {
redirect(BASEDIR."imagehost.php?p=upload&error=4");
}

} else {
redirect(BASEDIR."hostimage.php?p=upload&error=".$error);
} 

}
if (isset($_GET['act']) && $_GET['act'] == "del" && isnum($_GET['id'])) {
$qwe = dbarray(dbquery("SELECT * FROM ".DB_IMH_IMAGES." WHERE image_id='".$_GET['id']."'"));
if (file_exists(IMAGES."imagehost/".$qwe['image_original'])) {
unlink(IMAGES."imagehost/".$qwe['image_original']);
}
 if (file_exists(IMAGES."imagehost/".$qwe['image_resized'])) {
unlink(IMAGES."imagehost/".$qwe['image_resized']);
} 
 if (file_exists(IMAGES."imagehost/".$qwe['image_tsmall'])) {
unlink(IMAGES."imagehost/".$qwe['image_tsmall']);
} 
 if (file_exists(IMAGES."imagehost/".$qwe['image_tbig'])) {
unlink(IMAGES."imagehost/".$qwe['image_tbig']);
$del = dbquery("DELETE FROM ".DB_IMH_IMAGES." WHERE image_id='".$_GET['id']."'");
redirect(BASEDIR."imagehost.php?p=images");
} 


}



if (isset($_GET['p'])) {

// view image @ image
if ($_GET['p'] == "image") {

if (isset($_GET['id']) && isnum($_GET['id'])) {
add_to_title(" - ".$locale['i27']);
 add_to_head("<script type='text/javascript' src='".INFUSIONS."imagehost/fancybox/jquery.easing-1.3.pack.js'></script>");
 add_to_head("<script type='text/javascript' src='".INFUSIONS."imagehost/fancybox/jquery.mousewheel-3.0.4.pack.js'></script>"); 
add_to_head("<script type='text/javascript' src='".INFUSIONS."imagehost/fancybox/jquery.fancybox-1.3.4.js'></script>");
add_to_head("<link rel='stylesheet' href='".INFUSIONS."imagehost/fancybox/jquery.fancybox-1.3.4.css' type='text/css' media='screen' />");
add_to_head("<style>
#tabs {
	margin: 0;
}
#tabs ul {
	width: 95%;
    margin: 0 auto -10px;
}
#tabs li {
	list-style: none;
    width:30%;
    float:left;
    text-align: center; 
}
* html #tabs li {
	display: inline;
}
#tabs div {
	clear: both;
    width:100%;
} 
</style>");
echo "<script type='text/javascript'>
            $(document).ready(function(){
                
 $('#tabs div').hide();
$('#tabs div:first').show();
$('#tabs ul li').addClass('tbl2');
$('#tabs ul li:first').removeClass('tbl2')
$('#tabs ul li:first').addClass('tbl1');
$('#tabs ul li a').click(function(){ 
$('#tabs ul li').removeClass('tbl1');
$('#tabs ul li').addClass('tbl2');
$(this).parent().removeClass('tbl2');
$(this).parent().addClass('tbl1'); 
var currentTab = $(this).attr('href'); 
$('#tabs div').hide();
$(currentTab).show();
return false;
}); 

 $('a#imh_zoom').fancybox({
                    padding:0,
                    centerOnScroll:true,
                    hideOnContentClick:true,
                    overlayOpacity:0.7,
                    overlayColor:'#000'
                });
               $('a#imh_res').fancybox({
                    padding:0,
                    centerOnScroll:true,
                    hideOnContentClick:true,
                    overlayOpacity:0.7,
                    overlayColor:'#000'
                }); 
            });
</script>"; 

$data = dbarray(dbquery("SELECT * FROM ".DB_IMH_IMAGES." WHERE image_id='".$_GET['id']."'"));
$views = $data['image_views'] + 1;
$update = dbquery("UPDATE ".DB_IMH_IMAGES." SET image_views='".$views."' WHERE image_id='".$data['image_id']."'");
if ($data['image_user'] != "0") {
$data2 = dbarray(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$data['image_user']."'"));
}
$author = $data['image_user'] != "0" ? "<a href='".BASEDIR."imagehost.php?p=user&id=".$data['image_user']."' alt='".$locale['i30']."'>".$data2['user_name']."</a>" : $locale['i29'];
$size = parsebytesize($data['image_size']);
$size2 = @getimagesize(IMAGES."imagehost/".$data['image_original']);
$res = array("2" => "320x240", "3" => "640x480", "4" => "800x600", "5" => "1024x768", "6" => "1280x1024", "7" => "1600x1200"); 

opentable($locale['i27']);
showNav(); 
echo "<table width='100%' class='tbl-border' cellpadding='0' cellspacing='1'>";
echo "<tr><td class='tbl2' width='33%' align='center' valign='middle' style='vertical-align:middle;'><img src='".IMAGES."dl_info.png' border='0' style='padding-right:5px;' width='16' />".$locale['i31'].$author.$locale['i28'].$data['image_views']."</td><td class='tbl2' width='33%' align='center' valign='middle'><img src='".IMAGES."arrow.png' border='0' style='padding-right:5px;' width='16' />".$locale['i32'].$size.", ".$size2[0]."x".$size2[1]."</td><td class='tbl2' align='center' valign='middle'><a id='imh_zoom' href='".IMAGES."imagehost/".$data['image_original']."'><img src='".INFUSIONS."imagehost/images/zoom.png' border='0' style='padding-right:5px;' />".$locale['i33']."</a>".($data['image_resize'] > 1 ? "<a id='imh_res' href='".IMAGES."imagehost/".$data['image_resized']."'><img src='".INFUSIONS."imagehost/images/zoom.png' border='0' style='padding-right:5px;' />".$res[$data['image_resize']]."</a>" : "").($can_admin ? "<a href='".BASEDIR."imagehost.php?act=del&id=".$data['image_id']."'><img src='".IMAGES."no.png' border='0' style='padding: auto 5px;' /></a>" : "")."</td></tr>";
echo "<tr><td class='tbl1' align='center' colspan='3'><img src='".IMAGES."imagehost/".$data['image_tbig']."' /></td></tr>";
echo "<tr><td colspan='3'>
<div id='tabs'>
<ul class='tbl-border'>
<li><a href='#bb'>BB-Code</a></li>
<li><a href='#html'>HTML</a></li>
<li><a href='#direct'>Direct</a></li>
</ul>
<div id='bb'>
<table width='100%' class='tbl2'>
<tr><td colspan='5' align='center'>".$locale['i37']."</td></tr>
<tr><td width='15%'><img src='".INFUSIONS."imagehost/fancybox/blank.gif' border='0' /></td><td width='120'>".$locale['i38']."</td><td><input type='text' class='textbox' name='bb1' value='[URL=".$settings['siteurl']."imagehost.php?p=image&id=".$data['image_id']."][IMG]".$settings['siteurl']."images/imagehost/".$data['image_original']."[IMG][/URL]' style='width:100%;' /></td><td width='1%'></td><td width='15%'><img src='".INFUSIONS."imagehost/fancybox/blank.gif' border='0' /></td></tr>
 <tr><td width='15%'><img src='".INFUSIONS."imagehost/fancybox/blank.gif' border='0' /></td><td width='120'>".$locale['i39']."</td><td><input type='text' class='textbox' name='bb2' value='[URL=".$settings['siteurl']."imagehost.php?p=image&id=".$data['image_id']."][IMG]".$settings['siteurl']."images/imagehost/".$data['image_tsmall']."[IMG][/URL]' style='width:100%;' /></td><td width='1%'></td><td width='15%'><img src='".INFUSIONS."imagehost/fancybox/blank.gif' border='0' /></td></tr>";
if ($data['image_resize'] > 1) {
echo "<tr><td width='15%'><img src='".INFUSIONS."imagehost/fancybox/blank.gif' border='0' /></td><td width='120'>".$locale['i40']."(".$res[$data['image_resize']]."):</td><td><input type='text' class='textbox' name='bb3' value='[URL=".$settings['siteurl']."imagehost.php?p=image&id=".$data['image_id']."][IMG]".$settings['siteurl']."images/imagehost/".$data['image_resized']."[IMG][/URL]' style='width:100%;' /></td><td width='1%'></td><td width='15%'><img src='".INFUSIONS."imagehost/fancybox/blank.gif' border='0' /></td></tr>";
}

echo "</table>
</div>
<div id='html'>
 <table width='100%' class='tbl2'>
<tr><td colspan='5' align='center'>".$locale['i41']."</td></tr>
<tr><td width='15%'><img src='".INFUSIONS."imagehost/fancybox/blank.gif' border='0' /></td><td width='120'>".$locale['i42']."</td><td><input type='text' class='textbox' name='html1' value='<a href=\"".$settings['siteurl']."imagehost.php?p=image&id=".$data['image_id']."\"><img src=\"".$settings['siteurl']."images/imagehost/".$data['image_original']."\" /></a>' style='width:100%;' /></td><td width='1%'></td><td width='15%'><img src='".INFUSIONS."imagehost/fancybox/blank.gif' border='0' /></td></tr>
 <tr><td width='15%'><img src='".INFUSIONS."imagehost/fancybox/blank.gif' border='0' /></td><td width='120'>".$locale['i43']."</td><td><input type='text' class='textbox' name='html2' value='<a href=\"".$settings['siteurl']."imagehost.php?p=image&id=".$data['image_id']."\"><img src=\"".$settings['siteurl']."images/imagehost/".$data['image_tsmall']."\" /></a>' style='width:100%;' /></td><td width='1%'></td><td width='15%'><img src='".INFUSIONS."imagehost/fancybox/blank.gif' border='0' /></td></tr>";
if ($data['image_resize'] > 1) {
echo "<tr><td width='15%'><img src='".INFUSIONS."imagehost/fancybox/blank.gif' border='0' /></td><td width='120'>".$locale['i44']."(".$res[$data['image_resize']]."):</td><td><input type='text' class='textbox' name='html3' value='<a href=\"".$settings['siteurl']."imagehost.php?p=image&id=".$data['image_id']."\"><img src=\"".$settings['siteurl']."images/imagehost/".$data['image_resized']."\" /></a>' style='width:100%;' /></td><td width='1%'></td><td width='15%'><img src='".INFUSIONS."imagehost/fancybox/blank.gif' border='0' /></td></tr>";
}

echo "</table>
</div> 
<div id='direct'>
 <table width='100%' class='tbl2'>
<tr><td colspan='5' align='center'>".$locale['i45']."</td></tr>
<tr><td width='15%'><img src='".INFUSIONS."imagehost/fancybox/blank.gif' border='0' /></td><td width='120'>".$locale['i46']."</td><td><input type='text' class='textbox' name='html1' value='".$settings['siteurl']."images/imagehost/".$data['image_original']."' style='width:100%;' /></td><td width='1%'></td><td width='15%'><img src='".INFUSIONS."imagehost/fancybox/blank.gif' border='0' /></td></tr>
 <tr><td width='15%'><img src='".INFUSIONS."imagehost/fancybox/blank.gif' border='0' /></td><td width='120'>".$locale['i47']."</td><td><input type='text' class='textbox' name='html2' value='".$settings['siteurl']."images/imagehost/".$data['image_tsmall']."' style='width:100%;' /></td><td width='1%'></td><td width='15%'><img src='".INFUSIONS."imagehost/fancybox/blank.gif' border='0' /></td></tr>";
if ($data['image_resize'] > 1) {
echo "<tr><td width='15%'><img src='".INFUSIONS."imagehost/fancybox/blank.gif' border='0' /></td><td width='120'>".$res[$data['image_resize']].":</td><td><input type='text' class='textbox' name='html3' value='".$settings['siteurl']."images/imagehost/".$data['image_resized']."' style='width:100%;' /></td><td width='1%'></td><td width='15%'><img src='".INFUSIONS."imagehost/fancybox/blank.gif' border='0' /></td></tr>";
}
echo "<tr><td width='15%'><img src='".INFUSIONS."imagehost/fancybox/blank.gif' border='0' /></td><td width='120'>".$locale['i48']."</td><td><input type='text' class='textbox' name='html2' value='".$settings['siteurl']."imagehost.php?p=image&id=".$data['image_id']."' style='width:100%;' /></td><td width='1%'></td><td width='15%'><img src='".INFUSIONS."imagehost/fancybox/blank.gif' border='0' /></td></tr> 

</table> 

</div> 
</div>
</td></tr>";
echo "</table>"; 
closetable();
} else { redirect(BASEDIR."imagehost.php?p=images"); }

// user images @ user
} elseif ($_GET['p'] == "user") { 
if (isset($_GET['id']) && isnum($_GET['id'])) {
if (isset($_GET['rs']) && isnum($_GET['rs'])) { $rs = $_GET['rs']; } else { $rs = 0; }
if ($_GET['id'] != 0) {
$res = dbarray(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$_GET['id']."'"));
$name = $res['user_name'];
} else {
$name = $locale['i29'];
}
add_to_title(" - ".$locale['i53'].$name);
opentable($locale['i53'].$name);
 showNav(); 
$result = dbquery("SELECT * FROM ".DB_IMH_IMAGES." WHERE image_user='".$_GET['id']."' ORDER BY image_id DESC LIMIT ".$rs.",15");
$count = dbcount("(image_id)", DB_IMH_IMAGES, "image_user='".$_GET['id']."'");
if (dbrows($result)) {
echo "<table width='100%' border='0'><tr>";
$i=0;
while ($data=dbarray($result)) {
if ($i%3 == 0 && $i != 15) {
echo "</tr><tr><td colspan='3' height='10'><img src='".INFUSIONS."imagehost/fancybox/blank.gif' border='0' /></td></tr><tr>";
}
echo "<td width='33%' align='center'><a href='".BASEDIR."imagehost.php?p=image&id=".$data['image_id']."'><img src='".IMAGES."imagehost/".$data['image_tsmall']."' border='0' /></a></td>";
$i++;
}
echo "</tr></table>";
 if ($count > 15)
echo "<div align='center' style='margin-top:5px;'>".makePageNav($rs, 15, $count, 3, BASEDIR."imagehost.php?p=user&amp;id=".$_GET['id']."&amp;")."</div>"; 
} else {
echo $locale['i52'];
}
closetable(); 
} else {
redirect(BASEDIR."imagehost.php?p=users");
}
// all users @ users
} elseif ($_GET['p'] == "users") { 

$result = dbquery("SELECT ti.image_id, ti.image_user, tu.user_name FROM ".DB_IMH_IMAGES." ti 
LEFT JOIN ".DB_USERS." tu ON ti.image_user=tu.user_id
ORDER BY user_name ASC");
$res = array();
while ($data = dbarray($result)) {
if (array_key_exists($data['image_user'], $res)) {
$res[$data['image_user']]['count']++;
} else {
$res[$data['image_user']] = array("user" => $data['image_user'], "name" => $data['user_name'], "count" => 1);
}
}
$i = 0;
add_to_title(" - ".$locale['i50']);
opentable($locale['i50']);
 showNav(); 
foreach ($res as $d) {
$i++;
echo "<a href='".BASEDIR."imagehost.php?p=user&id=".$d['user']."'>".($d['user'] != 0 ? $d['name'] : $locale['i29'])." (".$d['count'].")</a>".($i == count($res) ? "." : ", ");
}
closetable();
// all images @ images
} elseif ($_GET['p'] == "images") { 

if (isset($_GET['rs']) && isnum($_GET['rs'])) { $rs = $_GET['rs']; } else { $rs = 0; }
add_to_title(" - ".$locale['i51']); 
opentable($locale['i51']);
 showNav(); 
$result = dbquery("SELECT * FROM ".DB_IMH_IMAGES." ORDER BY image_id DESC LIMIT ".$rs.",15");
$count = dbcount("(image_id)", DB_IMH_IMAGES);
if (dbrows($result)) {
echo "<table width='100%' border='0'><tr>";
$i=0;
while ($data=dbarray($result)) {
if ($i%3 == 0 && $i != 15) {
echo "</tr><tr><td colspan='3' height='10'><img src='".INFUSIONS."imagehost/fancybox/blank.gif' border='0' /></td></tr><tr>";
}
echo "<td width='33%' align='center'><a href='".BASEDIR."imagehost.php?p=image&id=".$data['image_id']."'><img src='".IMAGES."imagehost/".$data['image_tsmall']."' border='0' /></a></td>";
$i++;
}
echo "</tr></table>";
 if ($count > 15)
echo "<div align='center' style='margin-top:5px;'>".makePageNav($rs, 15, $count, 3, BASEDIR."imagehost.php?p=images&amp;")."</div>"; 
} else {
echo $locale['i52'];
}
closetable();
// upload @ upload
} elseif ($_GET['p'] == "upload") {
if ($can_post) {
add_to_title(" - ".$locale['i11']); 
opentable($locale['i11']);
showNav();
echo "<form action='imagehost.php' method='post' enctype='multipart/form-data' name='upload'>";
echo "<table width='100%' border='0' cellpadding='0' cellspacing='1' class='tbl-border'>";

if (isset($_GET['error']) && isnum($_GET['error'])) {
$error_msg = array("1" => $locale['i22'], "2" => $locale['i23'], "3" => $locale['i24'], "4" => $locale['i25'], "5" => $locale['i26']); 
echo "<tr><td class='tbl1' colspan='2' style='text-align:center;font-weight:bold;text-decoration:underline;'>".$error_msg[$_GET['error']]."</td></tr>";
} 
echo "<tr><td width='50%' class='tbl'><strong>".$locale['i16']."</strong></td><td class='tbl'><strong>".$locale['i17']."</strong></td></tr>";
echo "<tr><td class='tbl2'><input type='file' name='image' style='width:90%;' class='textbox' /></td><td class='tbl2'><label>".$locale['i18']." 
 <select name='resize'>
			<option value='1' selected='selected'>None</option>
			<option value='2'>320x240</option>
			<option value='3'>640x480</option>
			<option value='4'>800x600</option>
			<option value='5'>1024x768</option>
 <option value='6'>1280x1024</option>
 <option value='7'>1600x1200</option> 
		</select> 
</td></tr>";
 echo "<tr><td class='tbl1'>".$locale['i20']."</td><td class='tbl1'>".$locale['i21']."</td></tr>";
echo "<tr><td class='tbl2' colspan='2' align='center'><input type='submit' class='button' name='upload' value='".$locale['i19']."'</td></tr>";
echo "</table>";
echo "</form>";
closetable();
} else {
opentable("Ooops...");
showNav();
echo "<div style='width:90%;text-align:center;'>".$locale['i10']."</div>";
closetable();
} // can post
} // @ upload

} else {
redirect(BASEDIR."imagehost.php?p=images");
}

function showNav() {
global $locale, $can_post, $userdata;
echo "<div style='width:auto;margin:15px auto;'>
<a href='".BASEDIR."imagehost.php?p=images' style='margin: auto 5px;'>".$locale['i12']."</a>
<a href='".BASEDIR."imagehost.php?p=users' style='margin: auto 5px;'>".$locale['i13']."</a>";
if (iMEMBER) { echo "<a href='".BASEDIR."imagehost.php?p=user&id=".$userdata['user_id']."' style='margin: auto 5px;'>".$locale['i14']."</a>"; }
if ($can_post) { echo "<a href='".BASEDIR."imagehost.php?p=upload' style='margin: auto 5px;'>".$locale['i15']."</a>"; }
echo "</div>";
}

// be a good guy, plz don't move this
echo "<div style='width:150px;text-align:center;margin:10px auto;'><a href='http://fusion.annetlab.tk/'>Fusion @ Annetlab</a> &copy; 2011-2012 by <a href='http://vkontakte.ru/hot.rush'>Rush</a></div>";

require_once THEMES."templates/footer.php";
?>
