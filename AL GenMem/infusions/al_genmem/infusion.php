<?php
if (!defined("IN_FUSION")) { die("Access Denied"); }

include INFUSIONS."al_genmem/infusion_db.php";
if (file_exists(INFUSIONS."al_genmem/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_genmem/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_genmem/locale/English.php";
}

$inf_title = $locale['gem1'];
$inf_description = "";
$inf_version = "1.0";
$inf_developer = "Rush";
$inf_email = "johny64@gmail.com";
$inf_weburl = "http://annetlab.ru";

$inf_folder = "al_genmem";

$inf_newtable[1] = DB_GEM_SETTINGS." (
mem_width INT(4) NOT NULL DEFAULT '800',
mem_height INT(4) NOT NULL DEFAULT '600',
dem_padding_top INT(4) NOT NULL DEFAULT '20',
dem_padding_side INT(4) NOT NULL DEFAULT '30',
dem_padding_bottom INT(4) NOT NULL DEFAULT '120',
dem_border INT(4) NOT NULL DEFAULT '3',
dem_after_border INT(4) NOT NULL DEFAULT '5'
) ENGINE=MyISAM;";

$inf_newtable[2] = DB_GEM_GENERATORS." (
gen_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
gen_thumb_image VARCHAR(250) NOT NULL DEFAULT '',
gen_mem_image VARCHAR(250) NOT NULL DEFAULT '',
gen_dem_image VARCHAR(250) NOT NULL DEFAULT '',
gen_name VARCHAR(250) NOT NULL DEFAULT '',
gen_rating INT(10) NOT NULL DEFAULT '0',
gen_voters TEXT,
gen_views INT(11) NOT NULL DEFAULT '0',
PRIMARY KEY (gen_id)
) ENGINE=MyISAM;";

$inf_newtable[3] = DB_GEM_MEMS." (
mem_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
mem_type INT(1) NOT NULL DEFAULT '0',
mem_text1 VARCHAR(250) NOT NULL DEFAULT '',
mem_text2 VARCHAR(250) NOT NULL DEFAULT '',
mem_gen_id INT(11) NOT NULL DEFAULT '0',
mem_datestamp INT(11) NOT NULL DEFAULT '0',
mem_image VARCHAR(250) NOT NULL DEFAULT '',
mem_rating INT(10) NOT NULL DEFAULT '0',
mem_voters TEXT,
mem_views INT(11) NOT NULL DEFAULT '0',
PRIMARY KEY (mem_id)
) ENGINE=MyISAM;";

$inf_insertdbrow[1] = DB_GEM_SETTINGS." (mem_width,mem_height,dem_padding_top,dem_padding_side,dem_padding_bottom,dem_border,dem_after_border) VALUES ('800','600','20','30','120','3','5')";

$inf_droptable[1] = DB_GEM_SETTINGS;
$inf_droptable[2] = DB_GEM_GENERATORS;
$inf_droptable[3] = DB_GEM_MEMS;

$inf_adminpanel[1] = array(
    "title" => $locale['gem1'],
    "image" => "news.gif",
    "panel" => "admin/index.php",
    "rights" => "GEM"
);
?>