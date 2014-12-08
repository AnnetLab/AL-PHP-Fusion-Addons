<?php defined("IN_FUSION") or die;

$result = dbquery("SELECT * FROM ".DB_AL_CATALOG_CATS." WHERE ctg_cat_parent='0'");

opentable($locale['ctg43']);

    if (dbrows($result)) {

        while ($cat = dbarray($result)) {

            echo "<div class='cat-row'>";

                echo "<a href='".FUSION_SELF."?action=category&cat_id=".$cat['ctg_cat_id']."'><img src='".(!empty($cat['ctg_cat_image']) && file_exists(AL_CATALOG_DIR."uploads/cats/".$cat['ctg_cat_image']) ? AL_CATALOG_DIR."uploads/cats/".$cat['ctg_cat_image'] : AL_CATALOG_DIR."asset/no-image.jpg")."' alt='".$cat['ctg_cat_title']."' style='max-width:".$catalog_settings['cat_thumb_width']."px; max-height: ".$catalog_settings['cat_thumb_height']."px;' class='cat-image' />";

                echo "<div class='cat-row-desc'>";

                    echo "<a href='".FUSION_SELF."?action=category&cat_id=".$cat['ctg_cat_id']."' class='cat-row-link'>".$cat['ctg_cat_title']."</a>";
                    echo "<p class='cat-row-desc-text'>".$cat['ctg_cat_desc']."</p>";
                    $subcats_result = dbquery("SELECT * FROM ".DB_AL_CATALOG_CATS." WHERE ctg_cat_parent='".$cat['ctg_cat_id']."'");
                    $subcats_num = dbrows($subcats_result);
                    if ($subcats_num > 0) {
                        echo "<p class='cat-row-subcats'>".$locale['ctg45'];
                        $c = 0;
                        while ($subcat = dbarray($subcats_result)) {
                            echo "<a href='".FUSION_SELF."?action=category&cat_id=".$subcat['ctg_cat_id']."'>".$subcat['ctg_cat_title']."</a>".($c<$subcats_num-1 ? ", " : "");
                            $c++;
                        }
                        echo "</p>";
                    }

                echo "</div>";
                echo "<div class='clearfix'></div>";

            echo "</div>";

        }

    } else {
        echo "<div class='text-align:center;'>".$locale['ctg44']."</div>";
    }

closetable();