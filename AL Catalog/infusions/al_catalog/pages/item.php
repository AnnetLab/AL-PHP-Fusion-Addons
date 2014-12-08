<?php defined("IN_FUSION") or die;

add_to_head("<script type='text/javascript' src='".AL_CATALOG_DIR."asset/jquery-migrate-1.2.1.min.js'></script>");
add_to_head("<script type='text/javascript' src='".AL_CATALOG_DIR."asset/fancybox/lib/jquery.mousewheel-3.0.6.pack.js'></script>");
add_to_head("<link rel='stylesheet' href='".AL_CATALOG_DIR."asset/fancybox/source/jquery.fancybox.css?v=2.1.3' type='text/css' media='screen' />");
add_to_head("<script type='text/javascript' src='".AL_CATALOG_DIR."asset/fancybox/source/jquery.fancybox.pack.js?v=2.1.3'></script>");

if (isset($_GET['item_id']) && isnum($_GET['item_id']) && isset($_GET['cat_id']) && isnum($_GET['cat_id'])) {

    echo show_breadcrumbs("item",stripinput($_GET['item_id']));

    $result = dbquery("SELECT * FROM ".DB_AL_CATALOG_CATS." WHERE ctg_cat_id='".stripinput($_GET['cat_id'])."'");
    if (!dbrows($result)) redirect(FUSION_SELF);
    $cat = dbarray($result);

    $result = dbquery("SELECT i.*,ii.* FROM ".DB_AL_CATALOG_ITEMS." i LEFT JOIN ".DB_AL_CATALOG_IMAGES." ii ON ii.ctg_image_id=i.ctg_item_image WHERE ctg_item_cat='".stripinput($_GET['cat_id'])."' AND ctg_item_id='".stripinput($_GET['item_id'])."'");
    if (!dbrows($result)) redirect(FUSION_SELF);
    $item = dbarray($result);

    opentable($item['ctg_item_title']);

        // breadcrumbs

        echo "<div id='tabs-content'>";

            echo "<div id='tab-content-main' class='tab-content-div active'>";
                echo "<img src='".($item['ctg_image_file'] && file_exists(AL_CATALOG_DIR.'uploads/'.$item['ctg_image_file']) ? AL_CATALOG_DIR.'uploads/'.$item['ctg_image_file'] : AL_CATALOG_DIR.'asset/no-image.jpg')."' alt='' />";
                echo "<h3>".$item['ctg_item_title']."</h3>";
                echo "<p>".$item['ctg_item_desc']."</p>";
            echo "</div>";
            for ($i=1;$i<=10;$i++) {
                if (!empty($item['ctg_item_tab_'.$i.'_title'])) {
                    echo "<div id='tab-content-".$i."' class='tab-content-div'>".parse_catalog_images(stripslashes($item['ctg_item_tab_'.$i.'_desc']))."</div>";
                }
            }

            echo "<div class='item-cost'>".$item['ctg_item_cost']."</div>";

        echo "</div>";

        echo "<div id='tabs'>";

            echo "<ul>";
                echo "<li id='tab-main' class='tab-li active' data-tab-id='main'>".$locale['ctg50']."</li>";
                for ($i=1;$i<=10;$i++) {
                    if (!empty($item['ctg_item_tab_'.$i.'_title'])) {
                        echo "<li class='tab-li' data-tab-id='".$i."'>".$item['ctg_item_tab_'.$i.'_title']."</li>";
                    }
                }
            echo "</ul>";

        echo "</div>";
        echo "<div class='clearfix'></div>";

    closetable();

    $result = dbquery("SELECT i.* FROM ".DB_AL_CATALOG_IMAGES_ITEMS." ii LEFT JOIN ".DB_AL_CATALOG_IMAGES." i ON i.ctg_image_id=ii.ctg_image_id WHERE ii.ctg_item_id='".$item['ctg_item_id']."' AND ii.ctg_image_id<>'".$item['ctg_item_image']."' AND i.ctg_image_show='1'");
    if (dbrows($result)) {
        opentable($locale['ctg51']);
            while ($image = dbarray($result)) {
                echo "<a class='goods-fancybox item-image-preview' rel='good-images' href='".AL_CATALOG_DIR."uploads/".$image['ctg_image_file']."'><img src='".($image['ctg_image_thumb'] && file_exists(AL_CATALOG_DIR.'uploads/'.$image['ctg_image_thumb']) ? AL_CATALOG_DIR.'uploads/'.$image['ctg_image_thumb'] : AL_CATALOG_DIR.'asset/no-image.jpg')."' alt='' /></a>";
            }
        closetable();
    }

    ?>

    <script type="text/javascript">
        $(document).ready(function(){

            $('#tabs .tab-li').click(function(e){
                e.preventDefault();
                if ($(this).hasClass('active')) return false;
                $('#tabs .tab-li').removeClass('active');
                $(this).addClass('active');
                $('#tabs-content .tab-content-div').removeClass('active');
                $('#tabs-content').find('#tab-content-'+$(this).attr('data-tab-id')).addClass('active');
            });
            $('.goods-fancybox').fancybox();

        });
    </script>

    <?php

} else {
    redirect(FUSION_SELF);
}
?>