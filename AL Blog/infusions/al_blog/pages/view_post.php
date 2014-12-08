<?php defined("IN_FUSION") or die();

if (isset($_GET['id']) && isnum($_GET['id'])) {
    $result = dbquery("SELECT p.*,pc.*,u.user_name,u.user_avatar FROM ".DB_AL_BLOG_POSTS." p LEFT JOIN ".DB_AL_BLOG_CATEGORIES." pc ON pc.alb_cat_id=p.alb_post_cat LEFT JOIN ".DB_USERS." u ON u.user_id=p.alb_post_user WHERE alb_post_status='1' AND alb_post_id='".$_GET['id']."'");
    if (dbrows($result)) {
        $data = dbarray($result);
        $data['comments'] = dbcount("(comment_id)",DB_COMMENTS,"comment_item_id='".$data['alb_post_id']."' AND comment_type='BL'");
        render_post($data);
    } else {
        redirect(FUSION_SELF);
    }
} else {
    redirect(FUSION_SELF);
}

?>