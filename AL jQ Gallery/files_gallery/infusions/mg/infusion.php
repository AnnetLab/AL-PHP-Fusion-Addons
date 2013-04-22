<?php
 /*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| AL jQ gallery 1.7
| Filename: infusion.php
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
if (!defined("IN_FUSION")) { die("Access Denied"); }

include INFUSIONS."mg/infusion_db.php";

$inf_title = "AL jQ Gallery";
$inf_description = "Mega Gallery with mass photo uploader, ajax viewer and ajax comments";
$inf_version = "1.7";
$inf_developer = "Rush";
$inf_email = "johny64@gmail.com";
$inf_weburl = "http://fusion.annetlab.tk";
$inf_folder = "mg";


$inf_newtable[1] = DB_MG_ALBUMS." (
    album_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    album_title VARCHAR(225) NOT NULL DEFAULT '',
    album_desc TEXT NOT NULL,
    album_cover VARCHAR(225) NOT NULL DEFAULT '',
    album_date VARCHAR(225) NOT NULL DEFAULT '',
    album_user SMALLINT(5) NOT NULL DEFAULT '0',
PRIMARY KEY (album_id)
) ENGINE=MyISAM;";
$inf_newtable[2] = DB_MG_PHOTOS." (
    photo_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    photo_title VARCHAR(225) NOT NULL DEFAULT '',
    photo_desc TEXT NOT NULL,
    photo_date VARCHAR(225) NOT NULL DEFAULT '',
    photo_user SMALLINT(5) NOT NULL DEFAULT '0',
    photo_album INT(11) NOT NULL DEFAULT '0',
    photo_file VARCHAR(225) NOT NULL DEFAULT '',
    photo_t1 VARCHAR(225) NOT NULL DEFAULT '',
    photo_t2 VARCHAR(225) NOT NULL DEFAULT '',
PRIMARY KEY (photo_id)
) ENGINE=MyISAM;";
$inf_newtable[3] = DB_MG_SETTINGS." (
	user_albums TINYINT(1) NOT NULL DEFAULT '1',
	 upload_original TINYINT(1) NOT NULL DEFAULT '0', 
	max_photo_size INT(16) NOT NULL DEFAULT '2097151',
	photo_types VARCHAR(225) NOT NULL DEFAULT '*.jpg;*.jpeg;*.gif;*.png',
    photo_height SMALLINT(5) NOT NULL DEFAULT '600',
    photo_width SMALLINT(5) NOT NULL DEFAULT '600',
    thumb_height SMALLINT(5) NOT NULL DEFAULT '100',
    thumb_width SMALLINT(5) NOT NULL DEFAULT '100'
) ENGINE=MyISAM;";

$inf_insertdbrow[1] = DB_MG_SETTINGS." (user_albums, upload_original, max_photo_size, photo_types, photo_height, photo_width, thumb_height, thumb_width) VALUES('1', '0',  '2097151', '*.png;*.jpg;*.jpeg;*.gif', '600', '600', '100', '100')";

$inf_droptable[1] = DB_MG_ALBUMS;
$inf_droptable[2] = DB_MG_PHOTOS;
$inf_droptable[3] = DB_MG_SETTINGS;

$inf_adminpanel[1] = array(
	"title" => "Mega Gallery",
	"image" => "photoalbums.gif",
	"panel" => "gallery_admin.php",
	"rights" => "MG"
);

$inf_sitelink[1] = array(
	"title" => "Gallery",
	"url" => "../../gallery.php",
	"visibility" => "101"
); 
$inf_sitelink[2] = array(
	"title" => "Create album",
	"url" => "../../mg_gallery.php?action=create",
	"visibility" => "101"
); 

?>
