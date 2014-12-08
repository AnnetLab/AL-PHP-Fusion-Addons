<?php
if (!defined("IN_FUSION")) { die("access denied!"); }
require_once INFUSIONS."al_calendar_panel/infusion_db.php";
if (file_exists(INFUSIONS."al_calendar_panel/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_calendar_panel/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_calendar_panel/locale/Russian.php";
}

$inf_title = $locale['alcr1'];
$inf_description = $locale['alcr2'];
$inf_version = "1.0";
$inf_developer = "Rush";
$inf_email = "johny64@gmail.com";
$inf_weburl = "http://annetlab.ru";
$inf_folder = "al_calendar_panel";

$inf_newtable[1] = DB_AL_CALENDAR_EVENTS." (
	alcr_event_id SMALLINT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	alcr_event_date DATE,
	alcr_event_time TIME,
	alcr_event_user INT(10) NOT NULL DEFAULT '0',
	alcr_event_title VARCHAR(250) NOT NULL DEFAULT '',
	alcr_event_desc TEXT NOT NULL,
	alcr_event_confirm INT(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (alcr_event_id)
) ENGINE=MyISAM;";

$inf_newtable[2] = DB_AL_CALENDAR_ADMINS." (
    alcr_admin_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    alcr_admin_user INT(10) NOT NULL DEFAULT '0',
    PRIMARY KEY (alcr_admin_id)
) ENGINE=MyISAM;";

$inf_newtable[3] = DB_AL_CALENDAR_SETTINGS." (
    calendar_user_group INT(10) NOT NULL DEFAULT '0',
    calendar_admin_group INT(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM;";

$inf_insertdbrow[1] = DB_AL_CALENDAR_SETTINGS." (calendar_user_group,calendar_admin_group) VALUES ('0','0')";

$inf_droptable[1] = DB_AL_CALENDAR_EVENTS;
$inf_droptable[2] = DB_AL_CALENDAR_ADMINS;
$inf_droptable[3] = DB_AL_CALENDAR_SETTINGS;

$inf_adminpanel[1] = array(
    "title" => $locale['alcr1'],
    "image" => "settings_time.gif",
    "panel" => "admin.php",
    "rights" => "ALCR"
);

?>
