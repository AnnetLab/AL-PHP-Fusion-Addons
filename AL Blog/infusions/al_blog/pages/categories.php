<?php defined("IN_FUSION") or die();

opentable($locale['alb5']);
$result = dbquery("SELECT * FROM ".DB_AL_BLOG_CATEGORIES);
if (dbrows($result)) {
    while ($data = dbarray($result)) {
        $posts = dbcount("(alb_post_id)",DB_AL_BLOG_POSTS,"alb_post_cat='".$data['alb_cat_id']."'");
        echo "<a href='".FUSION_SELF."?p=view_category&id=".$data['alb_cat_id']."'>".$data['alb_cat_title']." (".$posts.")</a><br />";
    }
} else {
    echo $locale['alb31'];
}
closetable();

?>