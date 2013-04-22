<?php
require_once "../../../maincore.php";
require_once THEMES."templates/admin_header.php";
include INFUSIONS."al_news_panel/infusion_db.php";
if (!defined("IN_FUSION")) die("access denied");
if (file_exists(INFUSIONS."al_news_panel/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_news_panel/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_news_panel/locale/Russian.php";
}


if(!checkAdminPageAccess("AN")) redirect(START_PAGE);

//nav
opentable($locale['an3']);
/*
echo "<button class='button'><a href='".INFUSIONS."al_news_panel/admin/index.php".$aidlink."'>".$locale['an4']."</a></button>*/
echo "<button class='button'><a href='".INFUSIONS."al_news_panel/admin/index.php".$aidlink."&p=panel'>".$locale['an5']."</a></button> <button class='button'><a href='".INFUSIONS."al_news_panel/admin/index.php".$aidlink."&p=news'>".$locale['an6']."</a></button> ";
closetable();

if (!isset($_GET['p']) || $_GET['p'] == "" || !file_exists(INFUSIONS."al_news_panel/admin/".$_GET['p'].".php")) {
    //index
    /*opentable($locale['anecho $locale['an8'];
    closetable();*/
redirect(INFUSIONS."al_news_panel/admin/index.php".$aidlink."&p=news");
} else {
    require_once INFUSIONS."al_news_panel/admin/".$_GET['p'].".php";
}



require_once THEMES."templates/footer.php";
?>
