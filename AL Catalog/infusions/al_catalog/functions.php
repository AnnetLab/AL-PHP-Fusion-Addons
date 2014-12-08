<?php

// if total = true - each row must have total value
function build_cats_tree_array($cats, $total = false) {
    if (is_array($cats)) {
        $tree = array();
        foreach($cats as $id => &$row){
            if(empty($row['ctg_cat_parent'])){
                $tree[$id] = &$row;
            } else {
                $cats[$row['ctg_cat_parent']]['childs'][$id] = &$row;
                if ($total) {
                    $cats[$row['ctg_cat_parent']]['total'] += $row['total'];
                }
            }
        }
    } else {
        return null;
    }
    return $tree;
}

function build_cats_tree_select($tree,$ident=0,$selected=0) {

    if (is_array($tree)) {
        $tree_select = "";
        foreach ($tree as $item) {
            $tree_select .= "<option value='".$item['ctg_cat_id']."'".($selected == $item['ctg_cat_id'] ? " selected='selected'" : "").">".make_ident($ident)." ".$item['ctg_cat_title']."</option>";
            if (isset($item['childs'])) {
                $tree_select .= build_cats_tree_select($item['childs'],$ident+1,$selected);
            }
        }
    } else {
        return null;
    }
    return $tree_select;
}

function build_cats_tree_list($tree,$active) {

    if (is_array($tree)) {
        $tree_list = "";
        $tree_list .= "<ul class='catalog-menu'>";
        foreach ($tree as $item) {
            $tree_list .= "<li".($active == $item['ctg_cat_id'] ? " class='active'" : "").">";
            $tree_list .= "<span><a href='".BASEDIR."catalog.php?action=category&cat_id=".$item['ctg_cat_id']."'>".$item['ctg_cat_title']."</a></span>";
            if (isset($item['childs'])) {
                $tree_list .= build_cats_tree_list($item['childs'],$active);
            }
            $tree_list .= "</li>";
        }
        $tree_list .= "</ul>";
    } else {
        return null;
    }
    return $tree_list;
}

function make_ident($num) {
    $ident = "";
    for ($i=1;$i<=$num;$i++) {
        if ($i==1) $ident .= "&brvbar;";
        $ident .= "&minus;";
    }
    return $ident;
}

function show_breadcrumbs($type,$id) {

    global $locale;

    switch ($type) {
        case "item":

            $item = dbarray(dbquery("SELECT ctg_item_title, ctg_item_cat, ctg_item_id FROM ".DB_AL_CATALOG_ITEMS." WHERE ctg_item_id='".$id."'"));

            $str = "<a href='".BASEDIR."catalog.php?action=item&cat_id=".$item['ctg_item_cat']."&item_id=".$item['ctg_item_id']."'>".$item['ctg_item_title']."</a>";

            $cat = dbarray(dbquery("SELECT ctg_cat_title, ctg_cat_parent, ctg_cat_id FROM ".DB_AL_CATALOG_CATS." WHERE ctg_cat_id='".$item['ctg_item_cat']."'"));
            $str = "<a href='".BASEDIR."catalog.php?action=category&cat_id=".$cat['ctg_cat_id']."'>".$cat['ctg_cat_title']."</a>&nbsp;&nbsp;/&nbsp;&nbsp;".$str;

            if ($cat['ctg_cat_parent'] != 0) {
                do {

                    $cat = dbarray(dbquery("SELECT ctg_cat_title, ctg_cat_parent, ctg_cat_id FROM ".DB_AL_CATALOG_CATS." WHERE ctg_cat_id='".$cat['ctg_cat_parent']."'"));
                    $str = "<a href='".BASEDIR."catalog.php?action=category&cat_id=".$cat['ctg_cat_id']."'>".$cat['ctg_cat_title']."</a>&nbsp;&nbsp;/&nbsp;&nbsp;".$str;

                } while ($cat['ctg_cat_parent'] != 0);

            }
            $str = "<a href='".BASEDIR."catalog.php'>".$locale['ctg52']."</a>&nbsp;&nbsp;/&nbsp;&nbsp;".$str;

            break;
        case "cat":

            $cat = dbarray(dbquery("SELECT ctg_cat_title, ctg_cat_parent, ctg_cat_id FROM ".DB_AL_CATALOG_CATS." WHERE ctg_cat_id='".$id."'"));
            $str = "<a href='".BASEDIR."catalog.php?action=category&cat_id=".$cat['ctg_cat_id']."'>".$cat['ctg_cat_title']."</a>";

            if ($cat['ctg_cat_parent'] != 0) {
                do {

                    $cat = dbarray(dbquery("SELECT ctg_cat_title, ctg_cat_parent, ctg_cat_id FROM ".DB_AL_CATALOG_CATS." WHERE ctg_cat_id='".$cat['ctg_cat_parent']."'"));
                    $str = "<a href='".BASEDIR."catalog.php?action=category&cat_id=".$cat['ctg_cat_id']."'>".$cat['ctg_cat_title']."</a>&nbsp;&nbsp;/&nbsp;&nbsp;".$str;

                } while ($cat['ctg_cat_parent'] != 0);

            }
            $str = "<a href='".BASEDIR."catalog.php'>".$locale['ctg52']."</a>&nbsp;&nbsp;/&nbsp;&nbsp;".$str;


            break;
    }
    return $str;

}

function make_assoc($result) {
    $assoc = array();
    while ($data = dbarray($result)) {
        $assoc[] = $data;
    }
    return $assoc;
}

function parse_catalog_images($text) {

    preg_match_all('#\[CATALOG_IMAGE_(.*?)\]#si', $text, $matches, PREG_PATTERN_ORDER);
    if ($matches && !empty($matches) && !empty($matches[0])) {

        for ($i=0;$i<=count($matches[0])-1;$i++) {

            $image_id = $matches[1][$i];
            $tag = $matches[0][$i];

            $result = dbquery("SELECT * FROM ".DB_AL_CATALOG_IMAGES." WHERE ctg_image_id='".stripinput($image_id)."'");
            if (dbrows($result)) {
                $image = dbarray($result);
                $text = str_replace($tag,"<img src='".($image['ctg_image_file'] && file_exists(AL_CATALOG_DIR.'uploads/'.$image['ctg_image_file']) ? AL_CATALOG_DIR.'uploads/'.$image['ctg_image_file'] : AL_CATALOG_DIR.'asset/no-image-jpg')."' class='catalog-image' />",$text);
            }

        }

    }

    return $text;

}





?>