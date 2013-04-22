<?php
require_once "../../../maincore.php";
require_once INFUSIONS."al_tourney/infusion_db.php";
if (file_exists(INFUSIONS."al_tourney/locale/".$settings['locale'].".php")) {
include INFUSIONS."al_tourney/locale/".$settings['locale'].".php"; 
} else {
include INFUSIONS."al_tourney/locale/English.php";
} 
require_once INFUSIONS."al_tourney/includes/functions.php";
require_once THEMES."templates/admin_header.php";

if (!checkAdminPageAccess("T")) redirect(BASEDIR."index.php"); 

if (isset($_GET['p']) && $_GET['p'] != "" && file_exists(INFUSIONS."al_tourney/admin/".$_GET['p'].".php")) {
require_once INFUSIONS."al_tourney/admin/".$_GET['p'].".php"; 
} else {
redirect(INFUSIONS."al_tourney/admin/index.php".$aidlink."&p=main");
}

require_once THEMES."templates/footer.php";
?>
