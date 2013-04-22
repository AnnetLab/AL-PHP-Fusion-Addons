<?php
if (!defined("IN_FUSION")) { die("access denied!"); }
require_once INFUSIONS."al_stats/infusion_db.php";
if (file_exists(INFUSIONS."al_stats/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_stats/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_stats/locale/Russian.php";
}

$inf_title = $locale['st1'];
$inf_description = $locale['st2'];
$inf_version = "1.0";
$inf_developer = "Rush";
$inf_email = "johny64@gmail.com";
$inf_weburl = "http://annetlab.tk";
$inf_folder = "al_stats";

$inf_newtable[1] = DB_ST_GAMES." (
	game_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	game_title VARCHAR(200) NOT NULL DEFAULT '',
PRIMARY KEY (game_id)
) ENGINE=MyISAM;";

$inf_newtable[2] = DB_ST_TEAMS." (
	team_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	team_title VARCHAR(200) NOT NULL DEFAULT '',
PRIMARY KEY (team_id)
) ENGINE=MyISAM;";

$inf_newtable[3] = DB_ST_STATS." (
	stat_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    stat_team SMALLINT(5) NOT NULL DEFAULT '0',
    stat_opp VARCHAR(100) NOT NULL DEFAULT '',
    stat_ownscore SMALLINT(5) NOT NULL DEFAULT '0',
    stat_oppscore SMALLINT(5) NOT NULL DEFAULT '0',
    stat_ivent VARCHAR(150) NOT NULL DEFAULT '',
    stat_game SMALLINT(5) NOT NULL DEFAULT '0',
    stat_result TINYINT(1) NOT NULL DEFAULT '0',
    stat_date INT(15) NOT NULL DEFAULT '0',
PRIMARY KEY (stat_id)
) ENGINE=MyISAM;";

$inf_droptable[1] = DB_ST_GAMES;
$inf_droptable[2] = DB_ST_TEAMS;
$inf_droptable[3] = DB_ST_STATS;

$inf_adminpanel[1] = array(
	"title" => $locale['st1'],
	"image" => "forums.gif",
	"panel" => "admin/index.php",
	"rights" => "ST"
);
$inf_sitelink[1] = array(
	"title" => $locale['st1'],
	"url" => "../../statistics.php",
	"visibility" => "0"
);

?>