<?php defined("IN_FUSION") or die("Denied");

if (!defined("DB_AL_CATALOG_CATS")) {
    define("DB_AL_CATALOG_CATS",DB_PREFIX."al_catalog_cats");
}
if (!defined("DB_AL_CATALOG_ITEMS")) {
    define("DB_AL_CATALOG_ITEMS",DB_PREFIX."al_catalog_items");
}
if (!defined("DB_AL_CATALOG_SETTINGS")) {
    define("DB_AL_CATALOG_SETTINGS",DB_PREFIX."al_catalog_settings");
}
if (!defined("DB_AL_CATALOG_IMAGES")) {
    define("DB_AL_CATALOG_IMAGES",DB_PREFIX."al_catalog_images");
}
if (!defined("DB_AL_CATALOG_IMAGES_ITEMS")) {
    define("DB_AL_CATALOG_IMAGES_ITEMS",DB_PREFIX."al_catalog_images_items");
}
if (!defined("AL_CATALOG_DIR")) {
    define("AL_CATALOG_DIR",INFUSIONS."al_catalog/");
}
if (!isset($catalog_settings)) {
    $result = dbquery("SELECT * FROM ".DB_INFUSIONS." WHERE inf_folder='al_catalog'");
    if (dbrows($result)) {
        $result = dbquery("SELECT * FROM ".DB_AL_CATALOG_SETTINGS);
        if ($result && dbrows($result)) {
            $catalog_settings = dbarray($result);
        }
    }
}




?>