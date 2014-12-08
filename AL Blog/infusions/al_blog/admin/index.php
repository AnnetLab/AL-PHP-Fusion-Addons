<?php
require_once "../../../maincore.php";
require_once THEMES."templates/admin_header.php";
require_once INFUSIONS."al_blog/infusion_db.php";
if (file_exists(AL_BLOG_DIR."locale/".$settings['locale'].".php")) {
    include AL_BLOG_DIR."locale/".$settings['locale'].".php";
} else {
    include AL_BLOG_DIR."locale/Russian.php";
}
require_once AL_BLOG_DIR."includes/functions.php";

if (!checkAdminPageAccess("ALB")) redirect(BASEDIR."index.php");

opentable($locale['alb23']);
    echo "<a href='".FUSION_SELF.$aidlink."&p=posts'>".$locale['alb24']."</a> ";
    echo "<a href='".FUSION_SELF.$aidlink."&p=categories'>".$locale['alb25']."</a> ";
    echo "<a href='".FUSION_SELF.$aidlink."&p=settings'>".$locale['alb26']."</a> ";
closetable();

if (isset($_GET['p']) && $_GET['p'] != 'index' && file_exists(AL_BLOG_DIR."admin/".$_GET['p'].".php")) {
    require_once AL_BLOG_DIR."admin/".$_GET['p'].".php";
} else {
    redirect(FUSION_SELF.$aidlink."&p=posts");
}

require_once THEMES."templates/footer.php";
?>