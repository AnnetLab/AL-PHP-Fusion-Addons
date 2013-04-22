<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: user_shouts-stat_include.php
| Author: Digitanium
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
	//Nothing here
} elseif ($profile_method == "display") {
	include_once INFUSIONS."mg/infusion_db.php";
$anumgal = dbcount("(album_id)", DB_MG_ALBUMS, "album_user='".$user_data['user_id']."'"); 
	echo "<tr>\n";
	echo "<td class='tbl1'>".$locale['uf_gal']."</td>\n";
	echo "<td align='right' class='tbl1'>".($anumgal > 0 ? "<a href='".BASEDIR."gallery.php?action=user&id=".$user_data['user_id']."'>".$locale['uf_gal_see']." (".$anumgal.")</a>" : "0")."</td>\n";
	echo "</tr>\n";
} elseif ($profile_method == "validate_insert") {
	//Nothing here
} elseif ($profile_method == "validate_update") {
	//Nothing here
}
?>
