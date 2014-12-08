<?php defined("IN_FUSION") or die("DENIED");
require_once INFUSIONS."al_blog/infusion_db.php";
if (file_exists(AL_BLOG_DIR."locale/".$settings['locale'].".php")) {
    include AL_BLOG_DIR."locale/".$settings['locale'].".php";
} else {
    include AL_BLOG_DIR."locale/Russian.php";
}

$inf_title = $locale['alb1'];
$inf_description = $locale['alb2'];
$inf_version = ".99";
$inf_developer = "Rush @ AnnetLab.ru";
$inf_email = "info@annetlab.ru";
$inf_weburl = "http://annetlab.ru";

$inf_folder = "al_blog";

$inf_newtable[1] = DB_AL_BLOG_POSTS." (
alb_post_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
alb_post_datestamp INT(11) NOT NULL DEFAULT '0',
alb_post_views INT(11) NOT NULL DEFAULT '0',
alb_post_user INT(11) NOT NULL DEFAULT '0',
alb_post_cat INT(11) NOT NULL DEFAULT '0',
alb_post_status INT(1) NOT NULL DEFAULT '0',
alb_post_title VARCHAR(250) NOT NULL DEFAULT '',
alb_post_text TEXT NOT NULL,
PRIMARY KEY (alb_post_id)
) ENGINE=MYISAM;";

$inf_newtable[2] = DB_AL_BLOG_CATEGORIES." (
alb_cat_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
alb_cat_title VARCHAR(250) NOT NULL DEFAULT '',
PRIMARY KEY (alb_cat_id)
) ENGINE=MYISAM;";

$inf_newtable[3] = DB_AL_BLOG_SETTINGS." (
alb_settings_moderate INT(1) NOT NULL DEFAULT '0'
) ENGINE=MYISAM;";

$inf_insertdbrow[1] = DB_AL_BLOG_SETTINGS." (alb_settings_moderate) VALUES ('0')";

$inf_droptable[1] = DB_AL_BLOG_POSTS;
$inf_droptable[2] = DB_AL_BLOG_CATEGORIES;
$inf_droptable[3] = DB_AL_BLOG_SETTINGS;

$inf_adminpanel[1] = array(
    "title" => $locale['alb1'],
    "image" => "news.gif",
    "panel" => "admin/index.php",
    "rights" => "ALB"
);

$inf_sitelink[1] = array(
    "title" => $locale['alb1'],
    "url" => "../../blog.php",
    "visibility" => "0"
)

?>