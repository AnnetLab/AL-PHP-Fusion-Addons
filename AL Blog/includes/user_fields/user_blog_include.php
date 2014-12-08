<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: user_aim_include.php
| Author: Digitanium
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }
require_once INFUSIONS."al_blog/infusion_db.php";

// Display user field input
if ($profile_method == "input") {

    // do nothing while edit

// Display in profile
} elseif ($profile_method == "display") {

    $total_blogs = dbcount("(alb_post_id)",DB_AL_BLOG_POSTS,"alb_post_user='".$user_data['user_id']."' AND alb_post_status='1'");
    if ($total_blogs > 0) {

        add_to_head("<script type='text/javascript' src='".INFUSIONS."al_blog/asset/js/jPages.js'></script>");
        add_to_head("<link rel='stylesheet' href='".INFUSIONS."al_blog/asset/css/blog_style.css' type='text/css' media='screen' />");

        echo "<tr>";
            echo "<td class='tbl1'>".$locale['uf_blog']."</td>";
            echo "<td align='right' class='tbl1'>".$total_blogs."</td>";
        echo "</tr>";
        echo "<tr>";
            echo "<td class='tbl1' colspan='2'>";

                $rows = dbquery("SELECT alb_post_id,alb_post_title FROM ".DB_AL_BLOG_POSTS." WHERE alb_post_user='".$user_data['user_id']."' AND alb_post_status='1' ORDER BY alb_post_datestamp DESC");

                echo "<ul id='blogs-container'>";
                    while ($row = dbarray($rows)) {
                        echo "<li><a href='".BASEDIR."blog.php?p=view_post&id=".$row['alb_post_id']."'>".$row['alb_post_title']."</a></li>";
                    }
                echo "</ul>";
                echo "<div class='blog-pagination'></div>";
            echo "</td>";
        echo "</tr>";
        echo "<script>
            $(document).ready(function(){
                $('div.blog-pagination').jPages({
                    containerID: 'blogs-container',
                    perPage: 10,
                    previous: false,
                    next: false
                });
            });
        </script>";
    }

	
// Insert and update
} elseif ($profile_method == "validate_insert"  || $profile_method == "validate_update") {
	// Get input data
	// again do nothing
}
?>