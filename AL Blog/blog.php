<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
require_once INFUSIONS."al_blog/infusion_db.php";
if (file_exists(AL_BLOG_DIR."locale/".$settings['locale'].".php")) {
    include AL_BLOG_DIR."locale/".$settings['locale'].".php";
} else {
    include AL_BLOG_DIR."locale/Russian.php";
}
require_once AL_BLOG_DIR."includes/functions.php";
add_to_head("<link rel='stylesheet' type='text/css' href='".AL_BLOG_DIR."asset/css/blog_style.css' />");
add_to_head("<script src='".AL_BLOG_DIR."asset/js/blog.images.js'></script>");

$blog_settings = dbarray(dbquery("SELECT * FROM ".DB_AL_BLOG_SETTINGS));
$blog_settings['allow_user_blogs'] = 1;
//$blog_settings['moderate_blogs'] = 1;

require_once AL_BLOG_DIR."pages/control_bar.php";
if (isset($_GET['p'])) {
    if (file_exists(AL_BLOG_DIR."pages/".$_GET['p'].".php")){
        require_once AL_BLOG_DIR."pages/".$_GET['p'].".php";
    } else {
        redirect(FUSION_SELF);
    }
} else {
    require_once AL_BLOG_DIR."pages/index.php";
}

require_once THEMES."templates/footer.php";
?>