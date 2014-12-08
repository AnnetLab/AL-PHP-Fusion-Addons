<?php defined("IN_FUSION") or die("DEnied");
require_once INFUSIONS."al_catalog/infusion_db.php";
if (file_exists(AL_CATALOG_DIR."locale/".$settings['locale'].".php")) {
    include AL_CATALOG_DIR."locale/".$settings['locale'].".php";
} else {
    include AL_CATALOG_DIR."locale/Russian.php";
}

$inf_title = $locale['ctg1'];
$inf_description = $locale['ctg2'];
$inf_version = "1.00";
$inf_developer = "Rush @ AnnetLab.ru";
$inf_email = "johny64@gmail.com";
$inf_weburl = "http://annetlab.ru";

$inf_folder = "al_catalog";

$inf_newtable[1] = DB_AL_CATALOG_CATS." (
ctg_cat_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
ctg_cat_parent INT(11) NOT NULL DEFAULT '0',
ctg_cat_title VARCHAR(250) NOT NULL DEFAULT '',
ctg_cat_desc TEXT NOT NULL DEFAULT '',
ctg_cat_image VARCHAR(250) NOT NULL DEFAULT '',
PRIMARY KEY (ctg_cat_id)
) ENGINE=MYISAM;";

$inf_newtable[2] = DB_AL_CATALOG_ITEMS." (
ctg_item_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
ctg_item_title VARCHAR(250) NOT NULL DEFAULT '',
ctg_item_short_desc TEXT NOT NULL,
ctg_item_desc TEXT NOT NULL,
ctg_item_cat INT(11) NOT NULL DEFAULT '0',
ctg_item_image INT(11) NOT NULL DEFAULT '0',
ctg_item_cost VARCHAR(100) NOT NULL DEFAULT '0',
ctg_item_tab_1_title VARCHAR(250) NOT NULL DEFAULT '',
ctg_item_tab_1_desc TEXT NOT NULL,
ctg_item_tab_2_title VARCHAR(250) NOT NULL DEFAULT '',
ctg_item_tab_2_desc TEXT NOT NULL,
ctg_item_tab_3_title VARCHAR(250) NOT NULL DEFAULT '',
ctg_item_tab_3_desc TEXT NOT NULL,
ctg_item_tab_4_title VARCHAR(250) NOT NULL DEFAULT '',
ctg_item_tab_4_desc TEXT NOT NULL,
ctg_item_tab_5_title VARCHAR(250) NOT NULL DEFAULT '',
ctg_item_tab_5_desc TEXT NOT NULL,
ctg_item_tab_6_title VARCHAR(250) NOT NULL DEFAULT '',
ctg_item_tab_6_desc TEXT NOT NULL,
ctg_item_tab_7_title VARCHAR(250) NOT NULL DEFAULT '',
ctg_item_tab_7_desc TEXT NOT NULL,
ctg_item_tab_8_title VARCHAR(250) NOT NULL DEFAULT '',
ctg_item_tab_8_desc TEXT NOT NULL,
ctg_item_tab_9_title VARCHAR(250) NOT NULL DEFAULT '',
ctg_item_tab_9_desc TEXT NOT NULL,
ctg_item_tab_10_title VARCHAR(250) NOT NULL DEFAULT '',
ctg_item_tab_10_desc TEXT NOT NULL,
PRIMARY KEY (ctg_item_id)
) ENGINE=MYISAM;";

$inf_newtable[3] = DB_AL_CATALOG_SETTINGS." (
photo_max_width int(5) NOT NULL,
photo_max_height int(5) NOT NULL,
cat_thumb_width int(5) NOT NULL,
cat_thumb_height int(5) NOT NULL,
item_thumb_width int(5) NOT NULL,
item_thumb_height int(5) NOT NULL,
max_photo_size int(11) NOT NULL,
cats_in_line INT(2) NOT NULL DEFAULT '5',
items_in_line INT(2) NOT NULL DEFAULT '5',
items_per_page INT(2) NOT NULL DEFAULT '30'
) ENGINE=MYISAM;";

$inf_newtable[4] = DB_AL_CATALOG_IMAGES." (
ctg_image_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
ctg_image_file VARCHAR(250) NOT NULL DEFAULT '',
ctg_image_thumb VARCHAR(250) NOT NULL DEFAULT '',
ctg_image_thumb_item VARCHAR(250) NOT NULL DEFAULT '',
ctg_image_show INT(1) NOT NULL DEFAULT '1',
PRIMARY KEY (ctg_image_id)
) ENGINE=MYISAM;";

$inf_newtable[5] = DB_AL_CATALOG_IMAGES_ITEMS." (
ctg_image_id INT(11) UNSIGNED NOT NULL,
ctg_item_id INT(11) UNSIGNED NOT NULL
) ENGINE=MYISAM;";

$inf_insertdbrow[1] = DB_AL_CATALOG_SETTINGS." (photo_max_width,photo_max_height,cat_thumb_width,cat_thumb_height,item_thumb_width,item_thumb_height,max_photo_size,cats_in_line,items_in_line,items_per_page) VALUES ('1800','1600','150','150','200','200','2000000','5','5','30')";

$inf_droptable[1] = DB_AL_CATALOG_CATS;
$inf_droptable[2] = DB_AL_CATALOG_ITEMS;
$inf_droptable[3] = DB_AL_CATALOG_SETTINGS;
$inf_droptable[4] = DB_AL_CATALOG_IMAGES;
$inf_droptable[5] = DB_AL_CATALOG_IMAGES_ITEMS;

$inf_adminpanel[1] = array(
    "title" => $locale['ctg1'],
    "image" => "banners.gif",
    "panel" => "admin/index.php",
    "rights" => "CTG"
);

$inf_sitelink[1] = array(
    "title" => $locale['ctg1'],
    "url" => "../../catalog.php",
    "visibility" => "0"
)

?>