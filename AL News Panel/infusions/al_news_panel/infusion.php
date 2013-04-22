<?php
if (!defined("IN_FUSION")) { die("access denied!"); }
require_once INFUSIONS."al_news_panel/infusion_db.php";
if (file_exists(INFUSIONS."al_news_panel/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_news_panel/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_news_panel/locale/Russian.php";
}

$inf_title = $locale['an1'];
$inf_description = $locale['an2'];
$inf_version = "1.0";
$inf_developer = "Rush";
$inf_email = "johny64@gmail.com";
$inf_weburl = "http://annetlab.tk";
$inf_folder = "al_news_panel";

$inf_newtable[1] = DB_AN_COLUMNS." (
column1_name VARCHAR(150) NOT NULL DEFAULT '',
column1_enable TINYINT(1) NOT NULL DEFAULT '0',
column1_max TINYINT(2) NOT NULL DEFAULT '15',
column1_rss VARCHAR(250) NOT NULL DEFAULT '',
column1_rss_img VARCHAR(250) NOT NULL DEFAULT '".$settings['siteurl']."infusions/al_news_panel/asset/rss.png',
column1_link VARCHAR(250) NOT NULL DEFAULT '',
column2_name VARCHAR(150) NOT NULL DEFAULT '',
column2_enable TINYINT(1) NOT NULL DEFAULT '0',
column2_max TINYINT(2) NOT NULL DEFAULT '15',
column2_rss VARCHAR(250) NOT NULL DEFAULT '',
column2_rss_img VARCHAR(250) NOT NULL DEFAULT '".$settings['siteurl']."infusions/al_news_panel/asset/rss.png',
column2_link VARCHAR(250) NOT NULL DEFAULT '',
column3_name VARCHAR(150) NOT NULL DEFAULT '',
column3_enable TINYINT(1) NOT NULL DEFAULT '0',
column3_max TINYINT(2) NOT NULL DEFAULT '15',
column3_rss VARCHAR(250) NOT NULL DEFAULT '',
column3_rss_img VARCHAR(250) NOT NULL DEFAULT '".$settings['siteurl']."infusions/al_news_panel/asset/rss.png',
column3_link VARCHAR(250) NOT NULL DEFAULT '',
column4_name VARCHAR(150) NOT NULL DEFAULT '',
column4_enable TINYINT(1) NOT NULL DEFAULT '0',
column4_max TINYINT(2) NOT NULL DEFAULT '15',
column4_rss VARCHAR(250) NOT NULL DEFAULT '',
column4_rss_img VARCHAR(250) NOT NULL DEFAULT '".$settings['siteurl']."infusions/al_news_panel/asset/rss.png',
column4_link VARCHAR(250) NOT NULL DEFAULT '',
column5_name VARCHAR(150) NOT NULL DEFAULT '',
column5_enable TINYINT(1) NOT NULL DEFAULT '0',
column5_max TINYINT(2) NOT NULL DEFAULT '15',
column5_rss VARCHAR(250) NOT NULL DEFAULT '',
column5_rss_img VARCHAR(250) NOT NULL DEFAULT '".$settings['siteurl']."infusions/al_news_panel/asset/rss.png',
column5_link VARCHAR(250) NOT NULL DEFAULT '',
column6_name VARCHAR(150) NOT NULL DEFAULT '',
column6_enable TINYINT(1) NOT NULL DEFAULT '0',
column6_max TINYINT(2) NOT NULL DEFAULT '15',
column6_rss VARCHAR(250) NOT NULL DEFAULT '',
column6_rss_img VARCHAR(250) NOT NULL DEFAULT '".$settings['siteurl']."infusions/al_news_panel/asset/rss.png',
column6_link VARCHAR(250) NOT NULL DEFAULT ''
) ENGINE=MyISAM;";

$inf_newtable[2] = DB_AN_NEWS." (
anews_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
anews_news SMALLINT(5) NOT NULL DEFAULT '0',
anews_order TINYINT(2) NOT NULL DEFAULT '0',
anews_column TINYINT(1) NOT NULL DEFAULT '1', 
anews_text TEXT NOT NULL,
PRIMARY KEY (anews_id)
) ENGINE=MyISAM;";

$inf_insertdbrow[1] = DB_AN_COLUMNS." (column1_name, column1_enable, column1_max, column1_rss, column2_name, column2_enable, column2_max, column2_rss, column3_name, column3_enable, column3_max, column3_rss, column4_name, column4_enable, column4_max, column4_rss, column5_name, column5_enable, column5_max, column5_rss, column6_name, column6_enable, column6_max, column6_rss) VALUES ('test1','0','15','', 'test2','0','15','', 'test3','0','15','', 'test4','0','15','', 'test5','0','15','', 'test6','0','15','')";

$inf_droptable[1] = DB_AN_COLUMNS;
$inf_droptable[2] = DB_AN_NEWS;

$inf_adminpanel[1] = array(
	"title" => $locale['an1'],
	"image" => "news.gif",
	"panel" => "admin/index.php",
	"rights" => "AN"
);
?>
