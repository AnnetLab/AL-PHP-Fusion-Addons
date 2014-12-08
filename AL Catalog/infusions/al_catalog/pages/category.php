<?php defined("IN_FUSION") or die;

if (isset($_GET['cat_id']) && isnum($_GET['cat_id'])) {

    echo show_breadcrumbs("cat",stripinput($_GET['cat_id']));

    $result = dbquery("SELECT * FROM ".DB_AL_CATALOG_CATS." WHERE ctg_cat_id='".intval(stripinput($_GET['cat_id']))."'");
    if (!dbrows($result)) redirect(FUSION_SELF);
    $category = dbarray($result);
    set_title($category['ctg_cat_title']." | ".$locale['ctg43']." | ".$settings['sitename']);

    $result = dbquery("SELECT * FROM ".DB_AL_CATALOG_CATS." WHERE ctg_cat_parent='".$category['ctg_cat_id']."'");
    $has_subcats = false;

    if (dbrows($result)) {

        $has_subcats = true;

        opentable($category['ctg_cat_title'].$locale['ctg46']);

        while ($cat = dbarray($result)) {

            echo "<div class='cat-row'>";

            echo "<a href='".FUSION_SELF."?action=category&cat_id=".$cat['ctg_cat_id']."'><img src='".(!empty($cat['ctg_cat_image']) && file_exists(AL_CATALOG_DIR."uploads/cats/".$cat['ctg_cat_image']) ? AL_CATALOG_DIR."uploads/cats/".$cat['ctg_cat_image'] : AL_CATALOG_DIR."asset/no-image.jpg")."' alt='".$cat['ctg_cat_title']."' style='max-width:".$catalog_settings['cat_thumb_width']."px; max-height: ".$catalog_settings['cat_thumb_height']."px;' class='cat-image' />";

            echo "<div class='cat-row-desc'>";

            echo "<a href='".FUSION_SELF."?action=category&cat_id=".$cat['ctg_cat_id']."' class='cat-row-link'>".$cat['ctg_cat_title']."</a>";
            echo "<p class='cat-row-desc-text'>".$cat['ctg_cat_desc']."</p>";
            $subcats_result = dbquery("SELECT * FROM ".DB_AL_CATALOG_CATS." WHERE ctg_cat_parent='".$cat['ctg_cat_id']."'");
            if ($subcats_num = dbrows($subcats_result) > 0) {
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

        closetable();

    }

    if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }

    $total = dbcount("(ctg_item_id)",DB_AL_CATALOG_ITEMS,"ctg_item_cat='".$category['ctg_cat_id']."'");
    $result = dbquery("SELECT i.*,ii.* FROM ".DB_AL_CATALOG_ITEMS." i LEFT JOIN ".DB_AL_CATALOG_IMAGES." ii ON ii.ctg_image_id=i.ctg_item_image WHERE ctg_item_cat='".$category['ctg_cat_id']."' LIMIT ".$_GET['rowstart'].",".$catalog_settings['items_per_page']);

    if (dbrows($result)) {

        opentable($category['ctg_cat_title'].$locale['ctg47']);

        echo "<table width='100%'>";
            echo "<tr>";
            $i = 0;
            while ($data = dbarray($result)) {

                if ($i%$catalog_settings['items_in_line'] == 0 && $i != 0) {
                    echo "</tr><tr>";
                }

                echo "<td width='".(round(100/$catalog_settings['items_in_line']))."' align='center'>";
                    echo "<a href='".FUSION_SELF."?action=item&cat_id=".$category['ctg_cat_id']."&item_id=".$data['ctg_item_id']."'><img src='".(!empty($data['ctg_image_thumb_item']) && file_exists(AL_CATALOG_DIR."uploads/".$data['ctg_image_thumb_item']) ? AL_CATALOG_DIR."uploads/".$data['ctg_image_thumb_item'] : AL_CATALOG_DIR."asset/no-image.jpg")."' alt='".$data['ctg_item_title']."' style='max-width:".$catalog_settings['item_thumb_width']."px; max-height: ".$catalog_settings['item_thumb_height']."px;' /></a><br />";
                    echo "<a href='".FUSION_SELF."?action=item&cat_id=".$category['ctg_cat_id']."&item_id=".$data['ctg_item_id']."'>".$data['ctg_item_title']."</a>";
                    echo "<p>".$data['ctg_item_short_desc']."</p>";
                echo "</td>";

                $i++;
            }
            if ($i%$catalog_settings['items_in_line'] != 0){
                do {
                    echo "<td width='".(round(100/$catalog_settings['items_in_line']))."'> </td>";
                    $i++;
                } while ($i%$catalog_settings['items_in_line'] != 0);

            }
            echo "</tr>";
        echo "</table>";

        closetable();

    } else {
        
        if (!$has_subcats) {
            opentable($category['ctg_cat_title'].$locale['ctg47']);
            echo "<div class='text-align:center;'>".$locale['ctg48']."</div>";
            closetable();
        }
        
    }


} else {
    redirect(FUSION_SELF);
}
?>