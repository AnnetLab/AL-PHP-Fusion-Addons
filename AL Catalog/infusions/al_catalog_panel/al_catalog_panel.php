<?php defined("IN_FUSION") or die;

if (!isset($catalog_setting)) {
    require_once INFUSIONS."al_catalog/infusion_db.php";
    add_to_head("<link rel='stylesheet' href='".AL_CATALOG_DIR."asset/catalog-styles.css' />");
}
require_once INFUSIONS."al_catalog/functions.php";
if (file_exists(AL_CATALOG_DIR."locale/".$settings['locale'].".php")) {
    include AL_CATALOG_DIR."locale/".$settings['locale'].".php";
} else {
    include AL_CATALOG_DIR."locale/Russian.php";
}

$cats_result = dbquery("SELECT * FROM ".DB_AL_CATALOG_CATS);

if (dbrows($cats_result)) {

    $cats_assoc = array();
    while ($cc = dbarray($cats_result)) {
        $cats_assoc[$cc['ctg_cat_id']] = $cc;
    }
    $cats_tree = build_cats_tree_array($cats_assoc);

    opentable($locale['ctg42']);

        echo build_cats_tree_list($cats_tree,isset($_GET['cat_id']) && isnum($_GET['cat_id']) ? $_GET['cat_id'] : 0);

    closetable();

}

?>