<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
require_once INFUSIONS."al_calendar_panel/infusion_db.php";
if (file_exists(INFUSIONS."al_calendar_panel/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_calendar_panel/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_calendar_panel/locale/Russian.php";
}
require_once INFUSIONS."al_calendar_panel/includes/functions.php";

if (!isset($userdata['user_id']) && iGUEST) $userdata['user_id'] = 0;
if (!alcr_can_view($userdata['user_id'])) redirect(BASEDIR."index.php");

if (isset($_GET['p']) && file_exists(INFUSIONS."al_calendar_panel/pages/".$_GET['p'].".php")) {
    require_once INFUSIONS."al_calendar_panel/pages/".$_GET['p'].".php";
} else {
    redirect(FUSION_SELF."?p=index");
}

require_once THEMES."templates/footer.php";
?>