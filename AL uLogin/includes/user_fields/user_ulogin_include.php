<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: user_ulogin.php
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
if (!defined("IN_FUSION")) { die("Access Denied"); }

if ($profile_method == "input") {
require_once INFUSIONS."ulogin/infusion_db.php";
 $ul = dbquery("SELECT * FROM ".DB_ULOGIN." WHERE ulogin_user='".$user_data['user_id']."'");


	if (dbrows($ul)) {
		echo "<tr>\n";
		echo "<td class='tbl1'>".$locale['ul1']."</td>\n";
		echo "<td align='right' class='tbl1'>";
while($data6 = dbarray($ul)) {
echo "<a href='".$data6['ulogin_identity']."'><img src='".INFUSIONS."ulogin/img/small/".$data6['ulogin_network'].".png' style='margin-bottom:-4px;' /> ".iconv("UTF-8",$locale['charset'],$data6['ulogin_fullname'])."</a> <a href='".INFUSIONS."ulogin/includes/ubackend.php?act=del&id=".$data6['ulogin_id']."'>[x]</a><br />";
}

echo "</td>\n";
		echo "</tr>\n";
	} 
echo "<tr>\n";
		echo "<td class='tbl1'>".$locale['ul2']."</td>\n";
		echo "<td align='right' class='tbl1'>";


 $ulsettings = dbarray(dbquery("SELECT * FROM ".DB_ULOGIN_SETTINGS));
echo "<script src='http://ulogin.ru/js/ulogin.js'></script>"; 
 $networks = array(1=>"vkontakte","facebook","google","twitter","odnoklassniki","mailru","yandex","livejournal","openid","lastfm","linkedin","liveid","soundcloud","steam","flickr","vimeo","youtube","webmoney"); 

$all_providers2 = "";
for ($x=1;$x<=18;$x++) {
if ($ulsettings['u_'.$networks[$x]] == "1" || $ulsettings['u_'.$networks[$x]] == "2") {
$all_providers2 = $all_providers2 != "" ? $all_providers2.",".$networks[$x] : $networks[$x]; 
} 
} 
 echo "<div id='uLogin-profile' x-ulogin-params='display=small;fields=email;optional=first_name,last_name,nickname;providers=".$all_providers2.";redirect_uri=".$settings['siteurl']."infusions/ulogin/includes/ubackend.php?add_identity'></div>"; 

echo "</td>\n";
		echo "</tr>\n"; 
} elseif ($profile_method == "display") {

require_once INFUSIONS."ulogin/infusion_db.php";
	
 $ul = dbquery("SELECT * FROM ".DB_ULOGIN." WHERE ulogin_user='".$user_data['user_id']."'");

	if (dbrows($ul)) {
		echo "<tr>\n";
		echo "<td class='tbl1'>".$locale['ul1']."</td>\n";
		echo "<td align='right' class='tbl1'>";
while($data6 = dbarray($ul)) {
 echo "<a href='".$data6['ulogin_identity']."'><img src='".INFUSIONS."ulogin/img/small/".$data6['ulogin_network'].".png' style='margin-bottom:-4px;' /> ".iconv("UTF-8",$locale['charset'],$data6['ulogin_fullname'])."</a><br />";
}

echo "</td>\n";
		echo "</tr>\n";
	} 



} elseif ($profile_method == "validate_insert") {
	//Nothing here
} elseif ($profile_method == "validate_update") {
	//Nothing here
}
?>
