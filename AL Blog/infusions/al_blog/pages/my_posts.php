<?php defined("IN_FUSION") or die("FU");

if (!iMEMBER) redirect(FUSION_SELF);

$result = dbquery("SELECT p.*,pc.* FROM ".DB_AL_BLOG_POSTS." p LEFT JOIN ".DB_AL_BLOG_CATEGORIES." pc ON pc.alb_cat_id=p.alb_post_cat ORDER BY alb_post_datestamp DESC");
if (dbrows($result)) {
    opentable($locale['alb20']);
    while ($data=dbarray($result)) {
//        if ($data['alb_post_draft'] == 1) {
//            $status = $locale['alb14'];
//        } else {
            $status = $data['alb_post_status'] == 1 ? $locale['alb45'] : $locale['alb46'];
//        }
        echo "<a href='".FUSION_SELF."?p=view_post&id=".$data['alb_post_id']."'>".$data['alb_post_title']."</a> <a href='".FUSION_SELF."?p=manage_post&id=".$data['alb_post_id']."'><img src='".AL_BLOG_DIR."asset/images/edit.png' width='16' /></a> <a href='".FUSION_SELF."?p=manage_post&delete=".$data['alb_post_id']."'><img src='".AL_BLOG_DIR."asset/images/delete.png' width='16' /></a><br />";
    }
    closetable();
} else {
    opentable("!?...");
        echo "<div style='width:100%;text-align:center;'>".$locale['alb7']."</div>";
    closetable();
}


?>