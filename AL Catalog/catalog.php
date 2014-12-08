<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
require_once INFUSIONS."al_catalog/infusion_db.php";

if (file_exists(AL_CATALOG_DIR."locale/".$settings['locale'].".php")) {
    include AL_CATALOG_DIR."locale/".$settings['locale'].".php";
} else {
    include AL_CATALOG_DIR."locale/Russian.php";
}
require_once AL_CATALOG_DIR."functions.php";
add_to_head("<link rel='stylesheet' href='".AL_CATALOG_DIR."asset/catalog-styles.css' />");

add_to_title($locale['ctg42']);

if (isset($_GET['action'])) {

    if (in_array($_GET['action'],array("category","item")) && file_exists(AL_CATALOG_DIR."pages/".$_GET['action'].".php")) {
        if (in_array($_GET['action'],array("category","item")) && (!isset($_GET['cat_id']) || !isnum($_GET['cat_id']))) {
            redirect(FUSION_SELF);
        }
        if ($_GET['action'] == "item" && (!isset($_GET['item_id']) || !isnum($_GET['item_id']))) {
            redirect(FUSION_SELF);
        }

        require_once AL_CATALOG_DIR."pages/".$_GET['action'].".php";
    } else {
        redirect(FUSION_SELF);
    }

} else {
    require_once AL_CATALOG_DIR."pages/index.php";
}

require_once THEMES."templates/footer.php";
?>