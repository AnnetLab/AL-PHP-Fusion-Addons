<?php defined("IN_FUSION") or die("FU");

function pre_render_post($post) {

    global $locale;

    echo "<div class='post-item'>";
        echo "<div class='post-header'>";
            echo "<div class='post-header-avatar'>";
                echo "<a href='".BASEDIR."profile.php?lookup=".$post['alb_post_user']."'><img src='".IMAGES."avatars/".($post['user_avatar'] != '' ? $post['user_avatar'] : 'noavatar50.png')."' alt='".$post['user_name']."' /></a>";
            echo "</div>";
            echo "<div class='post-header-cap'>";
                echo "<h3 class='post-title'><a href='".FUSION_SELF."?p=view_post&id=".$post['alb_post_id']."'>".$post['alb_post_title']."</a></h3>";
            echo "</div>";
            echo "<div class='clear'></div>";
        echo "</div>";
        echo "<div class='post-body'><div class='post-body-inner'>".$post['alb_post_text']."</div><a href='#' class='post-toggle' style='display:none;'>Развернуть</a></div>";
        echo "<div class='post-footer'>";
            echo "<img src='".AL_BLOG_DIR."asset/images/user.png' class='icon' /><a href='".BASEDIR."profile.php?lookup=".$post['alb_post_user']."'>".$post['user_name']."</a>";
            echo "<img src='".AL_BLOG_DIR."asset/images/category.png' class='icon' />".($post['alb_cat_title'] != '' ? "<a href='".FUSION_SELF."?p=view_category&id=".$post['alb_post_cat']."'>".$post['alb_cat_title']."</a>" : $locale['alb21']);
            echo "<img src='".AL_BLOG_DIR."asset/images/calendar.png' class='icon' />".showdate("forumdate",$post['alb_post_datestamp']);
            echo "<img src='".AL_BLOG_DIR."asset/images/comments.png' class='icon' />".$post['comments'];
            echo " <a href='".FUSION_SELF."?p=view_post&id=".$post['alb_post_id']."'>".$locale['alb22']."</a>";
        echo "</div>";
    echo "</div>";

}

function render_post($post) {

    global $locale, $settings;

    opentable($post['alb_post_title']);
    echo "<div class='post-item'>";
        echo "<div class='post-body'>".$post['alb_post_text']."</div>";
        echo "<div class='post-footer'>";
            echo "<img src='".AL_BLOG_DIR."asset/images/user.png' class='icon' /><a href='".BASEDIR."profile.php?lookup=".$post['alb_post_user']."'>".$post['user_name']."</a>";
            echo "<img src='".AL_BLOG_DIR."asset/images/category.png' class='icon' />".($post['alb_cat_title'] != '' ? "<a href='".FUSION_SELF."?p=view_category&id=".$post['alb_post_cat']."'>".$post['alb_cat_title']."</a>" : $locale['alb21']);
            echo "<img src='".AL_BLOG_DIR."asset/images/calendar.png' class='icon' />".showdate("forumdate",$post['alb_post_datestamp']);
            echo "<img src='".AL_BLOG_DIR."asset/images/comments.png' class='icon' />".$post['comments'];
            //echo " <a href='".FUSION_SELF."?p=view_post&id=".$post['alb_post_id']."'>".$locale['alb22']."</a>";
        echo "</div>";
    echo "</div>";
    closetable();

    require_once INCLUDES."comments_include.php";
    showcomments("BL", DB_AL_BLOG_POSTS, "alb_post_id", $_GET['id'], FUSION_SELF."?p=view_post&id=".$_GET['id']);
    require_once INCLUDES."ratings_include.php";
    showratings("B", $_GET['id'], FUSION_SELF."?p=view_post&id=".$_GET['id']);

}

?>