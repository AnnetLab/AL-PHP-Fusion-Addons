<?php
if (!defined("IN_FUSION")) { die("access denied!"); }
require_once INFUSIONS."al_register_mod/infusion_db.php";
if (file_exists(INFUSIONS."al_register_mod/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_register_mod/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_register_mod/locale/English.php";
}

$inf_title = $locale['rm15'];
$inf_description = $locale['rm16'];
$inf_version = "1.0";
$inf_developer = "Rush";
$inf_email = "johny64@gmail.com";
$inf_weburl = "http://annetlab.tk";
$inf_folder = "al_register_mod";

$inf_newtable[1] = DB_RM_RULES." (
rules TEXT NOT NULL
) ENGINE=MyISAM;";

$inf_newtable[2] = DB_RM_FORM_FIELDS." (
ff_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
ff_name VARCHAR(30) NOT NULL DEFAULT '',
ff_title VARCHAR(150) NOT NULL DEFAULT '',
ff_type TINYINT(1) NOT NULL DEFAULT '0',
ff_value VARCHAR(250) NOT NULL DEFAULT '',
ff_order TINYINT(3) NOT NULL DEFAULT '0',
ff_infobox TEXT NOT NULL,
PRIMARY KEY (ff_id)
) ENGINE=MyISAM;";

$inf_newtable[3] = DB_RM_FORM_APPS." (
fa_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
PRIMARY KEY (fa_id)
) ENGINE=MyISAM;";

$inf_newtable[4] = DB_RM_APPS." (
app_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
app_rm_user SMALLINT(5) NOT NULL DEFAULT '0',
app_user SMALLINT(5) NOT NULL DEFAULT '0', 
app_date INT(13) NOT NULL DEFAULT '0',
app_status TINYINT(1) NOT NULL DEFAULT '0',
app_form SMALLINT(5) NOT NULL DEFAULT '0',
app_voted VARCHAR(150) NOT NULL DEFAULT '',
app_votes_yes TINYINT(3) NOT NULL DEFAULT '0',
app_votes_no TINYINT(3) NOT NULL DEFAULT '0',
app_username VARCHAR(100) NOT NULL DEFAULT '',
app_useremail VARCHAR(150) NOT NULL DEFAULT '',
PRIMARY KEY (app_id)
) ENGINE=MyISAM;";

$inf_newtable[5] = DB_RM_USERS." (
rmuser_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
rmuser_username VARCHAR(35) NOT NULL DEFAULT '',
rmuser_useremail VARCHAR(150) NOT NULL DEFAULT '',
rmuser_password VARCHAR(100) NOT NULL DEFAULT '',
rmuser_algo VARCHAR(50) NOT NULL DEFAULT '',
rmuser_salt VARCHAR(64) NOT NULL DEFAULT '',
rmuser_code VARCHAR(64) NOT NULL DEFAULT '',
rmuser_verified TINYINT(1) NOT NULL DEFAULT '0',
rmuser_approved TINYINT(1) NOT NULL DEFAULT '0',
PRIMARY KEY (rmuser_id)
) ENGINE=MyISAM;";

$inf_insertdbrow[1] = DB_RM_RULES." (rules) VALUES ('Your rules goes here ...')";

$inf_droptable[1] = DB_RM_RULES;
$inf_droptable[2] = DB_RM_FORM_FIELDS;
$inf_droptable[3] = DB_RM_FORM_APPS;
$inf_droptable[4] = DB_RM_APPS;
$inf_droptable[5] = DB_RM_USERS; 

$inf_adminpanel[1] = array(
	"title" => $locale['rm15'],
	"image" => "registration.gif",
	"panel" => "admin/index.php",
	"rights" => "RM"
);
?>
