<?php
 /*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: infusion.php
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

$inf_title = "ImageHosting";
$inf_description = "ImageHosting plugin for PHP-Fusion";
$inf_version = "0.9.4";
$inf_developer = "Rush";
$inf_email = "johny64@gmail.com";
$inf_weburl = "http://fusion.annetlab.tk";

$inf_folder = "imagehost";
require_once INFUSIONS."imagehost/infusion_db.php";


$inf_newtable[1] = DB_IMH." (
id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
lvl VARCHAR(3) NOT NULL DEFAULT '',
PRIMARY KEY (id)
) ENGINE=MYISAM;";

$inf_newtable[2] = DB_IMH_IMAGES." (
image_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
image_user MEDIUMINT(8) NOT NULL DEFAULT '0',
image_size INT(15) NOT NULL DEFAULT '0',
image_type VARCHAR(10) NOT NULL DEFAULT '',
image_views INT(15) NOT NULL DEFAULT '0',
image_resize TINYINT(1) NOT NULL DEFAULT '1',
image_original VARCHAR(50) NOT NULL DEFAULT '',
image_tsmall VARCHAR(50) NOT NULL DEFAULT '',
image_tbig VARCHAR(50) NOT NULL DEFAULT '',
image_resized VARCHAR(50) NOT NULL DEFAULT '', 
PRIMARY KEY (image_id)
) ENGINE=MYISAM;";

$inf_insertdbrow[1] = DB_IMH." (lvl) VALUES ('103')"; 


$inf_droptable[1] = DB_IMH;
$inf_droptable[2] = DB_IMH_IMAGES;

$inf_adminpanel[1] = array(
	"title" => "ImageHosting",
	"image" => "images.gif",
	"panel" => "admin.php",
	"rights" => "IMH"
); 

$inf_sitelink[1] = array(
	"title" => "ImageHost",
	"url" => "../../imagehost.php",
	"visibility" => "101"
); 


?>
