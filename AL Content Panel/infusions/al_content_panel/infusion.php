<?php
if (!defined("IN_FUSION")) { die("access denied!"); }
require_once INFUSIONS."al_content_panel/infusion_db.php";
if (file_exists(INFUSIONS."al_content_panel/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_content_panel/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_content_panel/locale/Russian.php";
}

$inf_title = $locale['co1'];
$inf_description = $locale['co2'];
$inf_version = "1.0";
$inf_developer = "Rush";
$inf_email = "johny64@gmail.com";
$inf_weburl = "http://annetlab.tk";
$inf_folder = "al_content_panel";

 $inf_newtable[1] = DB_CO_SETTINGS." (
co_time tinyint(3) NOT NULL DEFAULT '24',
co_len tinyint(3) NOT NULL DEFAULT '100',
co_news tinyint(1) NOT NULL DEFAULT '1',
co_articles tinyint(1) NOT NULL DEFAULT '1',
co_forums tinyint(1) NOT NULL DEFAULT '1',
co_comments tinyint(1) NOT NULL DEFAULT '1',
co_downloads tinyint(1) NOT NULL DEFAULT '1',
co_photos tinyint(1) NOT NULL DEFAULT '1',
co_weblinks tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM;";

$inf_insertdbrow[1] = DB_CO_SETTINGS." (co_time,co_len,co_news,co_articles,co_comments,co_forums,co_downloads,co_weblinks,co_photos) VALUES ('24','100','1','1', '1','1','1','1', '1')";

$inf_droptable[1] = DB_CO_SETTINGS;

$inf_adminpanel[1] = array(
	"title" => $locale['co1'],
	"image" => "news.gif",
	"panel" => "admin.php",
	"rights" => "CO"
);
?>
