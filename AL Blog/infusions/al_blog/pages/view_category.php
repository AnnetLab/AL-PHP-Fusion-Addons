<?php defined("IN_FUSION") or die();

if (!isset($_GET['id']) || !isnum($_GET['id'])) redirect(FUSION_SELF);
$result = dbquery("SELECT * FROM ".DB_AL_BLOG_CATEGORIES." WHERE alb_cat_id='".$_GET['id']."'");
if (dbrows($result)) {
    $cat = dbarray($result);
    if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) $_GET['rowstart'] = 0;
    opentable($cat['alb_cat_title'].$locale['alb44']);
    $total_posts = dbcount("(alb_post_id)",DB_AL_BLOG_POSTS,"alb_post_status='1' AND alb_post_cat='".$_GET['id']."'");

    $result = dbquery("SELECT p.*,pc.*,u.user_name,u.user_avatar FROM ".DB_AL_BLOG_POSTS." p LEFT JOIN ".DB_AL_BLOG_CATEGORIES." pc ON pc.alb_cat_id=p.alb_post_cat LEFT JOIN ".DB_USERS." u ON u.user_id=p.alb_post_user WHERE alb_post_status='1' AND alb_post_cat='".$_GET['id']."' ORDER BY alb_post_datestamp DESC LIMIT ".$_GET['rowstart'].",10");
    if (dbrows($result)) {
        while ($data = dbarray($result)) {
            $data['comments'] = dbcount("(comment_id)",DB_COMMENTS,"comment_item_id='".$data['alb_post_id']."' AND comment_type='BL'");
            pre_render_post($data);
        }
        if ($total_posts > 10) {
            echo "<div align='center' style=';margin-top:5px;'>\n".makepagenav($_GET['rowstart'],10,$total_posts,3,FUSION_SELF."?p=view_category&id=".$_GET['id'])."</div>";
        }
    } else {
        echo "<div style='width:100%;text-align:center;'>".$locale['alb7']."</div>";
    }
    closetable();
} else {
    redirect(FUSION_SELF);
}


?>