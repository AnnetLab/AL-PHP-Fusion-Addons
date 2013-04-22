<?php
 require_once "maincore.php";
require_once INFUSIONS."al_tourney/infusion_db.php";
/*if (file_exists(INFUSIONS."al_tourney/locale/".$settings['locale'].".php")) {
include INFUSIONS."al_tourney/locale/".$settings['locale'].".php"; 
} else {
include INFUSIONS."al_tourney/locale/English.php";
} */
require_once INFUSIONS."al_tourney/includes/functions.php";
require_once THEMES."templates/header.php"; 

if (isset($_GET['p']) && $_GET['p'] != "" && file_exists(INFUSIONS."al_tourney/pages/".$_GET['p'].".php")) {
require_once INFUSIONS."al_tourney/pages/".$_GET['p'].".php"; 
} else {
redirect(BASEDIR."tourney.php?p=tournies");
}

require_once THEMES."templates/footer.php";
?>
