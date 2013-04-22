<?php
if (!defined("IN_FUSION")) { die("access denied!"); }
require_once INFUSIONS."al_groups/infusion_db.php";
if (file_exists(INFUSIONS."al_groups/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_groups/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_groups/locale/English.php";
}

$inf_title = $locale['gs1'];
$inf_description = $locale['gs2'];
$inf_version = "1.0";
$inf_developer = "Rush";
$inf_email = "johny64@gmail.com";
$inf_weburl = "http://annetlab.tk";
$inf_folder = "al_groups";

$inf_newtable[1] = DB_GS_NEWS." (
news_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
news_title VARCHAR(200) NOT NULL DEFAULT '',
news_pre TEXT NOT NULL,
news_news TEXT NOT NULL,
news_published tinyint(1) NOT NULL DEFAULT '0',
news_date int(15) NOT NULL DEFAULT '0',
news_author mediumint(8) NOT NULL DEFAULT '0',
news_group mediumint(8) NOT NULL DEFAULT '0',
PRIMARY KEY (news_id)
) ENGINE=MyISAM;";

$inf_newtable[2] = DB_GS_GROUPS." (
group_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
group_cat SMALLINT(5) NOT NULL DEFAULT '0',
group_name VARCHAR(150) NOT NULL DEFAULT '',
group_image varchar(150) NOT NULL DEFAULT '',
group_creator SMALLINT(5) NOT NULL DEFAULT '0',
group_stat INT(15) NOT NULL DEFAULT '0',
PRIMARY KEY (group_id)
) ENGINE=MyISAM;";

 $inf_newtable[3] = DB_GS_CATS." (
cat_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
cat_name VARCHAR(150) NOT NULL DEFAULT '',
PRIMARY KEY (cat_id)
) ENGINE=MyISAM;";

 $inf_newtable[4] = DB_GS_GROUP_USERS." (
guser_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
guser_user SMALLINT(5) NOT NULL DEFAULT '0',
guser_group smallint(5) NOT NULL DEFAULT '0',
PRIMARY KEY (guser_id)
) ENGINE=MyISAM;";

 $inf_newtable[5] = DB_GS_VOTES_USERS." (
vuser_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
vuser_user SMALLINT(5) NOT NULL DEFAULT '0',
vuser_group SMALLINT(150) NOT NULL DEFAULT '0',
vuser_type TINYINT(1) NOT NULL DEFAULT '1',
vuser_date INT(13) NOT NULL DEFAULT '0',
vuser_voted varchar(250) NOT NULL DEFAULT '',
vuser_canvote varchar(250) NOT NULL DEFAULT '',
vuser_need SMALLINT(5) NOT NULL DEFAULT '0',
vuser_have SMALLINT(5) NOT NULL DEFAULT '0',
vuser_unhave SMALLINT(5) NOT NULL DEFAULT '0',
PRIMARY KEY (vuser_id)
) ENGINE=MyISAM;";

 $inf_newtable[6] = DB_GS_VOTES_NEWS." (
vnews_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
vnews_news SMALLINT(5) NOT NULL DEFAULT '0',
vnews_group SMALLINT(150) NOT NULL DEFAULT '0',
vnews_type TINYINT(1) NOT NULL DEFAULT '1', 
vnews_voted varchar(250) NOT NULL DEFAULT '',
vnews_canvote varchar(250) NOT NULL DEFAULT '',
vnews_need SMALLINT(5) NOT NULL DEFAULT '0',
vnews_have SMALLINT(5) NOT NULL DEFAULT '0',
vnews_unhave SMALLINT(5) NOT NULL DEFAULT '0',
PRIMARY KEY (vnews_id)
) ENGINE=MyISAM;";

 $inf_newtable[7] = DB_GS_VOTERS_GROUPS." (
voter_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
voter_group SMALLINT(5) NOT NULL DEFAULT '0',
voter_ip VARCHAR(15) NOT NULL DEFAULT '0.0.0.0',
voter_date int(13) NOT NULL DEFAULT '0',
PRIMARY KEY (voter_id)
) ENGINE=MyISAM;"; 

 $inf_newtable[8] = DB_GS_VOTES_RESULTS." (
vres_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
vres_result SMALLINT(5) NOT NULL DEFAULT '0',
vres_group SMALLINT(5) NOT NULL DEFAULT '0',
vres_type TINYINT(1) NOT NULL DEFAULT '1', 
vres_voted varchar(250) NOT NULL DEFAULT '',
vres_canvote varchar(250) NOT NULL DEFAULT '',
vres_need SMALLINT(5) NOT NULL DEFAULT '0',
vres_have SMALLINT(5) NOT NULL DEFAULT '0',
vres_unhave SMALLINT(5) NOT NULL DEFAULT '0',
PRIMARY KEY (vres_id)
) ENGINE=MyISAM;"; 

 $inf_newtable[9] = DB_GS_RESULTS." (
res_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT, 
res_score VARCHAR(15) NOT NULL DEFAULT '',
res_opponent VARCHAR(150) NOT NULL DEFAULT '',
res_img VARCHAR(150) NOT NULL DEFAULT '',
res_img_big VARCHAR(150) NOT NULL DEFAULT '',
res_comment TEXT NOT NULL,
res_published TINYINT(1) NOT NULL DEFAULT '0',
PRIMARY KEY (res_id)
) ENGINE=MyISAM;"; 

$inf_droptable[1] = DB_GS_NEWS;
$inf_droptable[2] = DB_GS_GROUPS;
$inf_droptable[3] = DB_GS_GROUP_USERS;
$inf_droptable[4] = DB_GS_CATS;
$inf_droptable[5] = DB_GS_VOTES_USERS;
$inf_droptable[6] = DB_GS_VOTES_NEWS;
$inf_droptable[7] = DB_GS_VOTERS_GROUPS;
 $inf_droptable[8] = DB_GS_VOTES_RESULTS;
$inf_droptable[9] = DB_GS_RESULTS; 


$inf_adminpanel[1] = array(
	"title" => $locale['gs1'],
	"image" => "forums.gif",
	"panel" => "admin/index.php",
	"rights" => "GS"
);
$inf_sitelink[1] = array(
	"title" => $locale['gs1'],
	"url" => "../../groups.php",
	"visibility" => "0"
);
 $inf_sitelink[2] = array(
	"title" => $locale['gs3'],
	"url" => "../../group_news.php",
	"visibility" => "0"
); 

?>
