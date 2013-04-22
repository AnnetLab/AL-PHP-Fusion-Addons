<?php
require_once "../../../maincore.php";
require_once THEMES."templates/admin_header.php";
include INFUSIONS."al_groups/infusion_db.php";
if (!defined("IN_FUSION")) die("access denied");
if (file_exists(INFUSIONS."al_group/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_groups/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_groups/locale/English.php";
}
require_once INFUSIONS."al_groups/includes/functions.php";


if(!checkAdminPageAccess("GS")) redirect(START_PAGE);

//nav
opentable($locale['gs5']);
showNav();
closetable();

if (!isset($_GET['p']) || $_GET['p'] == "" || !file_exists(INFUSIONS."al_groups/admin/".$_GET['p'].".php")) {
    //index
    opentable($locale['gs6']);
    echo $locale['gs7'];
    closetable();
} else {
    require_once INFUSIONS."al_groups/admin/".$_GET['p'].".php";
}



require_once THEMES."templates/footer.php";
?>
