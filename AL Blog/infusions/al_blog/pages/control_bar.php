<?php
opentable($locale['alb47']);
echo "<div style='float:right;min-height: 30px;'>";
if ((iMEMBER && $blog_settings['allow_user_blogs']) || (iADMIN && checkrights("ALB"))) {
    $my_posts = dbcount("(alb_post_id)",DB_AL_BLOG_POSTS,"alb_post_user='".$userdata['user_id']."'");
    echo "<a href='".FUSION_SELF."?p=my_posts'>".$locale['alb3']." (".$my_posts.")</a> <a href='".FUSION_SELF."?p=manage_post'><img src='".AL_BLOG_DIR."asset/images/add.png' alt='".$locale['alb6']."' title='".$locale['alb6']."' width='16' /></a>";
}
echo "<a href='".FUSION_SELF."' style='margin-left:25px;'>".$locale['alb4']."</a>";
echo "<a href='".FUSION_SELF."?p=categories' style='margin-left:25px;'>".$locale['alb5']."</a>";
echo "</div><div style='clear:both;'></div>";
closetable();
?>