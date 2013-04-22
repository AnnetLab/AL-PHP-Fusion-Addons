<?php
require_once "../../../maincore.php";
require_once THEMES."templates/admin_header.php";
include INFUSIONS."al_register_mod/infusion_db.php";
if (!defined("IN_FUSION")) die("access denied");
if (file_exists(INFUSIONS."al_register_mod/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_register_mod/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_register_mod/locale/English.php";
}
require_once INFUSIONS."al_register_mod/includes/functions.php";


if(!checkgroup(1) && !checkgroup(3)) redirect(BASEDIR);

//nav
opentable($locale['rm1']);
 echo "<a href='".INFUSIONS."al_register_mod/admin/index.php'>".$locale['rm2']."</a> <a href='".INFUSIONS."al_register_mod/admin/index.php?p=rules'>".$locale['rm3']."</a> <a href='".INFUSIONS."al_register_mod/admin/index.php?p=form'>".$locale['rm4']."</a> <a href='".INFUSIONS."al_register_mod/admin/index.php?p=apps'>".$locale['rm5']."</a> <a href='".INFUSIONS."al_register_mod/admin/index.php?p=addapp'>Add app</a>"; 
closetable();

if (!isset($_GET['p']) || $_GET['p'] == "" || !file_exists(INFUSIONS."al_register_mod/admin/".$_GET['p'].".php")) {
    //index
    opentable($locale['rm6']);
    echo $locale['rm7'];
    closetable();
} else {
    require_once INFUSIONS."al_register_mod/admin/".$_GET['p'].".php";
}



require_once THEMES."templates/footer.php";
?>
