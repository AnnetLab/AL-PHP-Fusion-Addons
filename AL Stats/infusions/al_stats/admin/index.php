<?php
require_once "../../../maincore.php";
require_once THEMES."templates/admin_header.php";
include INFUSIONS."al_stats/infusion_db.php";
if (!defined("IN_FUSION")) die("access denied");
if (file_exists(INFUSIONS."al_stats/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_stats/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_stats/locale/Russian.php";
}
require_once INFUSIONS."al_stats/includes/functions.php";


if(!checkAdminPageAccess("ST")) redirect(START_PAGE);

//nav
opentable($locale['st7']);
showNav();
closetable();

if (!isset($_GET['p']) || $_GET['p'] == "" || !file_exists(INFUSIONS."al_stats/admin/".$_GET['p'].".php")) {
    //index
    opentable($locale['st8']);
    echo $locale['st9'];
    closetable();
} else {
    require_once INFUSIONS."al_stats/admin/".$_GET['p'].".php";
}



require_once THEMES."templates/footer.php";
?>