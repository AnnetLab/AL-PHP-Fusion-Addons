<?php
if (!defined("IN_FUSION")) { die("access denied!"); }
require_once INFUSIONS."al_rent_calendar/infusion_db.php";
if (file_exists(INFUSIONS."al_rent_calendar/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_rent_calendar/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_rent_calendar/locale/Russian.php";
}

$inf_title = $locale['alrc1'];
$inf_description = $locale['alrc2'];
$inf_version = "1.0";
$inf_developer = "Rush";
$inf_email = "johny64@gmail.com";
$inf_weburl = "http://annetlab.ru";
$inf_folder = "al_rent_calendar";

$inf_newtable[1] = DB_AL_RC_RENTED_DAYS." (
    alrc_rented_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    alrc_rented_album_id INT(10) NOT NULL DEFAULT '0',
    alrc_rented_date_start DATE,
    alrc_rented_date_finish DATE,
    PRIMARY KEY (alrc_rented_id)
) ENGINE=MyISAM;";

$inf_newtable[2] = DB_AL_RC_SPECIAL_DAYS." (
    alrc_special_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    alrc_special_album_id INT(10) NOT NULL DEFAULT '0',
    alrc_special_date_start DATE,
    alrc_special_date_finish DATE,
    alrc_special_title VARCHAR(250) NOT NULL DEFAULT '',
    alrc_special_min_nights INT(3) NOT NULL DEFAULT '0',
    alrc_special_cost_two_person VARCHAR(20) NOT NULL DEFAULT '',
    alrc_special_cost_next_person VARCHAR(20) NOT NULL DEFAULT '',
    PRIMARY KEY (alrc_special_id)
) ENGINE=MyISAM;";

$inf_droptable[1] = DB_AL_RC_RENTED_DAYS;
$inf_droptable[2] = DB_AL_RC_SPECIAL_DAYS;

$inf_adminpanel[1] = array(
    "title" => $locale['alrc1'],
    "image" => "settings_time.gif",
    "panel" => "admin/index.php",
    "rights" => "ALRC"
);

?>