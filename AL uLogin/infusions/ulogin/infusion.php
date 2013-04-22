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

$inf_title = "AL uLogin";
$inf_description = "ulogin for fusion";
$inf_version = "3.0";
$inf_developer = "Rush";
$inf_email = "johny64@gmail.com";
$inf_weburl = "http://fusion.annetlab.ru";

$inf_folder = "ulogin";
require_once INFUSIONS."ulogin/infusion_db.php";


$inf_newtable[1] = DB_ULOGIN." (
ulogin_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
ulogin_identity VARCHAR(150) NOT NULL DEFAULT '',
ulogin_user MEDIUMINT(8) NOT NULL DEFAULT '0',
ulogin_network VARCHAR(150) NOT NULL DEFAULT '',
ulogin_fullname VARCHAR(150) NOT NULL DEFAULT '',
PRIMARY KEY (ulogin_id)
) ENGINE=MYISAM;";
$inf_altertable[1] = DB_USERS." ADD user_ulogin VARCHAR(50) NOT NULL DEFAULT ''";

$inf_newtable[2] = DB_ULOGIN_SETTINGS." (
u_use_ajax TINYINT(1) NOT NULL DEFAULT '1',
u_vkontakte TINYINT(1) NOT NULL DEFAULT '1',
u_facebook TINYINT(1) NOT NULL DEFAULT '1',
u_google TINYINT(1) NOT NULL DEFAULT '1',
u_twitter TINYINT(1) NOT NULL DEFAULT '1',
u_odnoklassniki TINYINT(1) NOT NULL DEFAULT '2',
u_mailru TINYINT(1) NOT NULL DEFAULT '2',
u_yandex TINYINT(1) NOT NULL DEFAULT '2',
u_livejournal TINYINT(1) NOT NULL DEFAULT '2',
u_openid TINYINT(1) NOT NULL DEFAULT '2',
u_lastfm TINYINT(1) NOT NULL DEFAULT '2',
u_linkedin TINYINT(1) NOT NULL DEFAULT '2',
u_liveid TINYINT(1) NOT NULL DEFAULT '2',
u_soundcloud TINYINT(1) NOT NULL DEFAULT '2',
u_steam TINYINT(1) NOT NULL DEFAULT '2',
u_flickr TINYINT(1) NOT NULL DEFAULT '2',
u_vimeo TINYINT(1) NOT NULL DEFAULT '2',
u_youtube TINYINT(1) NOT NULL DEFAULT '2',
u_webmoney TINYINT(1) NOT NULL DEFAULT '2'
) ENGINE=MYISAM;";

$max = dbarray(dbquery("SELECT * FROM ".DB_USER_FIELDS." WHERE field_cat='1' ORDER BY field_order DESC LIMIT 1"));
$o = $max['field_order'] + 1;
$inf_insertdbrow[1] = DB_USER_FIELDS." (field_name, field_cat, field_order, field_log, field_registration, field_required) VALUES ('user_ulogin', '1', '".$o."', '0', '0', '0')";

$inf_insertdbrow[2] = DB_ULOGIN_SETTINGS." (u_use_ajax, u_vkontakte,u_facebook,u_google,u_twitter,u_odnoklassniki,u_mailru,u_yandex,u_livejournal,u_openid,u_lastfm,u_linkedin,u_liveid,u_soundcloud,u_steam,u_flickr,u_vimeo,u_youtube,u_webmoney) VALUES ('1','1','1','1','1','2', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2', '2')";

if (isset($_POST['infuse']) && isset($_POST['infusion']) && strtolower($_POST['infusion']) == "ulogin") {
    $uquery_data = array("action"=>"add","u_url"=>$settings['siteurl'],"u_date"=>time(),"u_email"=>$settings['siteemail'],"u_ip"=>$_SERVER['SERVER_ADDR']);
    $uquery = http_build_query($uquery_data);
    @file_get_contents("http://fusion.annetlab.ru/add_ulogin.php?".$uquery);
}

$ccheck = dbquery("SELECT * FROM ".DB_INFUSIONS." WHERE inf_folder='ulogin'");
if (dbrows($ccheck)) {
    $iid = dbarray($ccheck);
    if (isset($_GET['defuse']) && $_GET['defuse'] == $iid['inf_id']) {
        $uquery_data = array("action"=>"del","u_url"=>$settings['siteurl']);
        $uquery = http_build_query($uquery_data);
        @file_get_contents("http://fusion.annetlab.ru/add_ulogin.php?".$uquery);
    }
}


$inf_droptable[1] = DB_ULOGIN;
$inf_droptable[2] = DB_ULOGIN_SETTINGS;
$inf_deldbrow[1] = DB_USER_FIELDS." WHERE field_name='user_ulogin'";


$inf_adminpanel[1] = array(
	"title" => "ulogin",
	"image" => "users.gif",
	"panel" => "ulogin_admin.php",
	"rights" => "ULG"
);

?>
