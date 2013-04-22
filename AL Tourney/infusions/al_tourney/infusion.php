<?php
$inf_title = "AL Tourney";
$inf_description = "Tourney system";
$inf_version = "1.0";
$inf_developer = "Rush";
$inf_email = "johny64@gmail.com";
$inf_weburl = "http://fusion.annetlab.tk";

$inf_folder = "al_tourney";
require_once INFUSIONS."al_tourney/infusion_db.php";

$inf_newtable[1] = DB_T_TOURS." (
tour_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
tour_name varchar(250) NOT NULL DEFAULT '',
tour_desc text NOT NULL,
tour_game varchar(100) NOT NULL DEFAULT '',
tour_date int(15) NOT NULL DEFAULT '0',
tour_grid tinyint(1) NOT NULL DEFAULT '0',
tour_finished tinyint(1) NOT NULL DEFAULT '0',
tour_maxpl tinyint(3) NOT NULL DEFAULT '0',
tour_w1 mediumint(8) NOT NULL DEFAULT '0',
tour_w2 mediumint(8) NOT NULL DEFAULT '0',
tour_w3 mediumint(8) NOT NULL DEFAULT '0',
tour_w4 mediumint(8) NOT NULL DEFAULT '0',
PRIMARY KEY (tour_id)
) ENGINE=MYISAM;";

$inf_newtable[2] = DB_T_MATCHES." (
match_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
match_tour mediumint(8) NOT NULL DEFAULT '0',
match_round tinyint(3) NOT NULL DEFAULT '0',
match_match smallint(5) NOT NULL DEFAULT '0',
match_winner mediumint(8) NOT NULL DEFAULT '0',
match_played tinyint(1) NOT NULL DEFAULT '0',
match_pl1 mediumint(8) NOT NULL DEFAULT '0',
match_pl2 mediumint(8) NOT NULL DEFAULT '0',
match_score1 smallint(5) NOT NULL DEFAULT '0',
match_score2 smallint(5) NOT NULL DEFAULT '0',
match_date int(15) NOT NULL DEFAULT '0',
match_date1 int(15) NOT NULL DEFAULT '0',
match_date2 int(15) NOT NULL DEFAULT '0',
PRIMARY KEY (match_id)
) ENGINE=MYISAM;";

$inf_newtable[3] = DB_T_PLAYERS." (
player_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
player_user mediumint(8) NOT NULL DEFAULT '0',
player_tour mediumint(8) NOT NULL DEFAULT '0',
player_checkin tinyint(1) NOT NULL DEFAULT '0',
PRIMARY KEY (player_id)
) ENGINE=MYISAM;";

$inf_newtable[4] = DB_T_NOTIFICATIONS." (
noti_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
noti_user mediumint(8) NOT NULL DEFAULT '0',
noti_type mediumint(8) NOT NULL DEFAULT '0',
PRIMARY KEY (noti_id)
) ENGINE=MYISAM;"; 

$inf_droptable[1] = DB_T_TOURS;
$inf_droptable[2] = DB_T_MATCHES;
$inf_droptable[3] = DB_T_PLAYERS;
$inf_droptable[4] = DB_T_NOTIFICATIONS;

if (isset($_POST['infuse']) && $_POST['infusion'] == "AL Tourney") {
 $result = dbquery("INSERT INTO ".DB_USERS." (user_name, user_password, user_admin_password, user_email, user_hide_email, user_avatar, user_posts, user_threads, user_joined, user_lastvisit, user_ip, user_rights, user_groups, user_level, user_status, user_sig, user_salt, user_algo) VALUES('BYE', 'vchhbgfff', '', '".$email."', '1', '', '0', '0', '".time()."', '0', '".USER_IP."', '', '', '101', '0', '', 'dgyhvgghhvg', 'sha1')"); 
}

$inf_adminpanel[1] = array(
	"title" => "Tourney",
	"image" => "news.gif",
	"panel" => "admin/index.php",
	"rights" => "T"
); 

$inf_sitelink[1] = array(
	"title" => "Tourney",
	"url" => "../../tourney.php",
	"visibility" => "101"
); 


?>
