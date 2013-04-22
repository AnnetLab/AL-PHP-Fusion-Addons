<?php
if (!defined("IN_FUSION")) { die("access denied!"); }
require_once INFUSIONS."al_streams/infusion_db.php";

$inf_title = "Streams infusion";
$inf_description = "by AnnetLab";
$inf_version = "1.0";
$inf_developer = "Rush";
$inf_email = "johny64@gmail.com";
$inf_weburl = "http://annetlab.ru";
$inf_folder = "al_streams";

$inf_newtable[1] = DB_SS_SETTINGS." (
set_usergroup SMALLINT(5) NOT NULL DEFAULT '1'
) ENGINE=MyISAM;";

$inf_newtable[2] = DB_SS_STREAMS." (
st_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
st_user MEDIUMINT(8) NOT NULL DEFAULT '0',
st_desc TEXT NOT NULL,
st_provider TINYINT(1) NOT NULL DEFAULT '0',
st_provider_id VARCHAR(150) NOT NULL DEFAULT '',
PRIMARY KEY (st_id)
) ENGINE=MyISAM;";

$inf_newtable[3] = DB_SS_CHAT_MESSAGES." (
cm_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
cm_user_id MEDIUMINT(8) NOT NULL DEFAULT '0',
cm_channel_id MEDIUMINT(8) NOT NULL DEFAULT '0',
cm_timestamp INT(11) NOT NULL DEFAULT '0',
cm_message TEXT NOT NULL,
PRIMARY KEY (cm_id)
) ENGINE=MyISAM;";

$inf_newtable[4] = DB_SS_CHAT_ONLINE." (
co_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
co_user_id MEDIUMINT(8) NOT NULL DEFAULT '0',
co_timestamp INT(11) NOT NULL DEFAULT '0',
co_channel MEDIUMINT(8) NOT NULL DEFAULT '0',
PRIMARY KEY (co_id)
) ENGINE=MyISAM;";

$inf_insertdbrow[1] = DB_SS_SETTINGS." (set_usergroup) VALUES ('0')";

$inf_droptable[1] = DB_SS_SETTINGS;
$inf_droptable[2] = DB_SS_STREAMS;
$inf_droptable[3] = DB_SS_CHAT_MESSAGES;
$inf_droptable[4] = DB_SS_CHAT_ONLINE;

$inf_adminpanel[1] = array(
	"title" => "Streams",
	"image" => "forums.gif",
	"panel" => "streams_admin.php",
	"rights" => "SS"
);

?>
