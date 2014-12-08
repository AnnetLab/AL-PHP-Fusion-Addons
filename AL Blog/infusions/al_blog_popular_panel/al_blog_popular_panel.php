<?php defined("IN_FUSION") or die;
require_once INFUSIONS."al_blog/infusion_db.php";
if (file_exists(AL_BLOG_DIR."locale/".$settings['locale'].".php")) {
    include AL_BLOG_DIR."locale/".$settings['locale'].".php";
} else {
    include AL_BLOG_DIR."locale/Russian.php";
}

openside($locale['alb49']);

$result = dbquery("SELECT p.alb_post_title,p.alb_post_id,p.alb_post_user,u.user_avatar, COUNT(c.comment_id) as comments FROM ".DB_AL_BLOG_POSTS." p LEFT JOIN ".DB_COMMENTS." c ON c.comment_item_id=p.alb_post_id AND c.comment_type='BL' LEFT JOIN ".DB_USERS." u on p.alb_post_user=u.user_id GROUP BY p.alb_post_id ORDER BY comments DESC LIMIT 10");
//$result = dbquery("SELECT p.alb_post_title,p.alb_post_id,p.alb_post_user,u.user_avatar FROM ".DB_AL_BLOG_POSTS." p LEFT JOIN ".DB_USERS." u on p.alb_post_user=u.user_id ORDER BY alb_post_datestamp DESC LIMIT 10");
if (dbrows($result)) {
    echo "<ul>";
    while ($data = dbarray($result)) {
        echo "<li><a href='".BASEDIR."profile.php?lookup=".$data['alb_post_user']."' class='post-panel-profile-link'><img src='".IMAGES."avatars/".($data['user_avatar'] != '' ? $data['user_avatar'] : 'noavatar50.png')."' width='20' height='20' class='post-panel-profile-img' /></a> <a href='".BASEDIR."blog.php?p=view_post&id=".$data['alb_post_id']."' class='post-panel-post-link'>".$data['alb_post_title']."</a></li>";
    }
    echo "</ul>";
} else {
    echo $locale['alb50'];
}

closeside();
