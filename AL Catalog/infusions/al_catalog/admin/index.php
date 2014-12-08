<?php
require_once "../../../maincore.php";
require_once THEMES."templates/admin_header.php";
require_once INFUSIONS."al_catalog/infusion_db.php";
if (file_exists(AL_CATALOG_DIR."locale/".$settings['locale'].".php")) {
    include AL_CATALOG_DIR."locale/".$settings['locale'].".php";
} else {
    include AL_CATALOG_DIR."locale/Russian.php";
}

add_to_head("<link rel='stylesheet' href='".AL_CATALOG_DIR."asset/catalog-styles.css' />");

if (!checkAdminPageAccess("CTG")) redirect(START_PAGE);

opentable($locale['ctg3']);
echo "<div style='margin: 0 0 10px 10px;'>";
echo "<a class='button' href='".FUSION_SELF.$aidlink."&page=categories'>".$locale['ctg16']."</a>&nbsp;";
echo "<a class='button' href='".FUSION_SELF.$aidlink."&page=items'>".$locale['ctg17']."</a>&nbsp;";
echo "<a class='button' href='".FUSION_SELF.$aidlink."&page=settings'>".$locale['ctg18']."</a>&nbsp;";
echo "</div>";
closetable();

if (!isset($_GET['page'])) {

} else  {
    if (file_exists(AL_CATALOG_DIR."admin/".$_GET['page'].".php")) {
        require_once AL_CATALOG_DIR."admin/".$_GET['page'].".php";
    } else {
        echo 'No file found';
    }
}

require_once THEMES."templates/footer.php";
?>