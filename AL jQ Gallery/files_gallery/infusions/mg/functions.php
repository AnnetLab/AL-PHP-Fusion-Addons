<?php
 /*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| AL jQ gallery 1.7
| Filename: functions.php
| Author: Rush
| http://fusion.annetlab.tk
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/ 
$mg_settings = dbarray(dbquery("SELECT * FROM ".DB_MG_SETTINGS.""));

function generateName($length = 16){
  $chars = 'qwertyuiopasdfghjklzxcvbnm1234567890';
  $numChars = strlen($chars);
  $string = '';
  for ($i = 0; $i < $length; $i++) {
    $string .= substr($chars, rand(1, $numChars) - 1, 1);
  }
  return $string;
}

function imageExists($dir, $image) {
	$image_name = substr($image, 0, strrpos($image, "."));
	$image_ext = strrchr($image, ".");
	while (file_exists($dir.$image)) {
		$image = generateName().$image_ext;
	}
	return $image;
}

function makepagenav_($start, $count, $total, $range = 0, $link = "") {
	global $locale;

	if ($link == "") { $link = FUSION_SELF."?"; }

	$pg_cnt = ceil($total / $count);
	if ($pg_cnt <= 1) { return ""; }

	$idx_back = $start - $count;
	$idx_next = $start + $count;
	$cur_page = ceil(($start + 1) / $count);

	$res = $locale['global_092']." ".$cur_page.$locale['global_093'].$pg_cnt.": ";
	if ($idx_back >= 0) {
		if ($cur_page > ($range + 1)) {
			$res .= "<a href='".$link."p=0'>1</a>";
			if ($cur_page != ($range + 2)) {
				$res .= "...";
			}
		}
	}
	$idx_fst = max($cur_page - $range, 1);
	$idx_lst = min($cur_page + $range, $pg_cnt);
	if ($range == 0) {
		$idx_fst = 1;
		$idx_lst = $pg_cnt;
	}
	for ($i = $idx_fst; $i <= $idx_lst; $i++) {
		$offset_page = ($i - 1) * $count;
		if ($i == $cur_page) {
			$res .= "<span><strong>".$i."</strong></span>";
		} else {
			$res .= "<a href='".$link."p=".$offset_page."'>".$i."</a>";
		}
	}
	if ($idx_next < $total) {
		if ($cur_page < ($pg_cnt - $range)) {
			if ($cur_page != ($pg_cnt - $range - 1)) {
				$res .= "...";
			}
			$res .= "<a href='".$link."p=".($pg_cnt - 1) * $count."'>".$pg_cnt."</a>\n";
		}
	}

	return "<div class='pagenav'>\n".$res."</div>\n";
}

?>
