<?php
 /*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| AL jQ gallery 1.7
| Filename: infusion_db.php
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
if (!defined("DB_MG_SETTINGS")) {
	define("DB_MG_SETTINGS", DB_PREFIX."mg_settings");
}
if (!defined("DB_MG_ALBUMS")) {
	define("DB_MG_ALBUMS", DB_PREFIX."mg_albums");
}
if (!defined("DB_MG_PHOTOS")) {
	define("DB_MG_PHOTOS", DB_PREFIX."mg_photos");
}
?>
