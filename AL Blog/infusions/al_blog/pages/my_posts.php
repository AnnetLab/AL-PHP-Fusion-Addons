<?php defined("IN_FUSION") or die("FU");

if (!iMEMBER) redirect(FUSION_SELF);

if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) $_GET['rowstart'] = 0;

$total_posts = dbcount("(alb_post_id)",DB_AL_BLOG_POSTS,"alb_post_user='".$userdata['user_id']."'");
$result = dbquery("SELECT p.*,pc.* FROM ".DB_AL_BLOG_POSTS." p LEFT JOIN ".DB_AL_BLOG_CATEGORIES." pc ON pc.alb_cat_id=p.alb_post_cat WHERE p.alb_post_user='".$userdata['user_id']."' ORDER BY alb_post_datestamp DESC");
if (dbrows($result)) {
    opentable($locale['alb20']);
    while ($data=dbarray($result)) {
//        if ($data['alb_post_draft'] == 1) {
//            $status = $locale['alb14'];
//        } else {
            $status = $data['alb_post_status'] == 1 ? $locale['alb45'] : $locale['alb46'];
//        }
        echo "<a href='".FUSION_SELF."?p=view_post&id=".$data['alb_post_id']."'>".$data['alb_post_title']."</a> <a href='".FUSION_SELF."?p=manage_post&id=".$data['alb_post_id']."'><img src='".AL_BLOG_DIR."asset/images/edit.png' width='16' /></a> <a href='".FUSION_SELF."?p=manage_post&delete=".$data['alb_post_id']."'><img src='".AL_BLOG_DIR."asset/images/delete.png' width='16' /></a><br />";

        if ($total_posts > 10) {
            echo "<div align='center' style=';margin-top:5px;'>\n".makepagenav($_GET['rowstart'],10,$total_posts,3,FUSION_SELF)."</div>";
        }
    }
    closetable();
} else {
    opentable("!?...");
        echo "<div style='width:100%;text-align:center;'>".$locale['alb7']."</div>";
    closetable();
}


?>
