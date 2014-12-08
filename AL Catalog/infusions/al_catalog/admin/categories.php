<?php
require_once AL_CATALOG_DIR."functions.php";
require_once INCLUDES."infusions_include.php";
add_to_title(": ".$locale['ctg4']);

if (isset($_GET['status']) && !isset($message)) {
    if ($_GET['status'] == "success") {
        $message = $locale['ctg35'];
    } elseif ($_GET['status'] == "su") {
        $message = $locale['ctg36'];
    } elseif ($_GET['status'] == "del") {
        $message = $locale['ctg37'];
    }
    if ($message) {	echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; }
}

$result = dbquery("SELECT * FROM ".DB_AL_CATALOG_CATS);
if (dbrows($result)) {
    while ($data = dbarray($result)) {
        $cats[$data['ctg_cat_id']] = $data;
    }
} else {
    $cats = null;
}

$is_edit = false; $error = array();
if (isset($_POST['save'])) {

    $title = trim(stripinput($_POST['title']));
    $desc = trim(stripinput($_POST['desc']));
    $parent_cat = $_POST['parent_cat'];
    if ($title != "") {
        if ($_FILES['image']['name'] != "") {
            $image_uploaded = upload_image("image", "", AL_CATALOG_DIR."uploads/cats/", $catalog_settings['photo_max_width'], $catalog_settings['photo_max_height'],
                $catalog_settings['max_photo_size'], true, true, false,
                0, AL_CATALOG_DIR."uploads/cats/", "_t1", $catalog_settings['cat_thumb_width'], $catalog_settings['cat_thumb_height']);
            if ($image_uploaded['error'] == 0) {
                $image = $image_uploaded['thumb1_name'];
            } else {
                $error[] = $locale['ctg2'.$image_uploaded['error']];
            }
        } else {
            $image = "";
        }
    } else {
        $error[] = $locale['ctg5'];
    }


    if (empty($error)) {
        if (isset($_POST['cat_id'])) {
            if (isset($_POST['delete_image']) || $image != "") {
                $del = dbarray(dbquery("SELECT ctg_cat_image FROM ".DB_AL_CATALOG_CATS." WHERE ctg_cat_id='".$_POST['cat_id']."'"));
                if ($del['ctg_cat_image'] != "" && file_exists(AL_CATALOG_DIR."uploads/cats/".$del['ctg_cat_image'])) {
                    unlink(AL_CATALOG_DIR."uploads/cats/".$del['ctg_cat_image']);
                }

            }
            $upd = dbquery("UPDATE ".DB_AL_CATALOG_CATS." SET ctg_cat_title='".$title."',ctg_cat_desc='".$desc."',ctg_cat_parent='".$parent_cat."'".(($image && $image != "") || isset($_POST['delete_image']) ? ",ctg_cat_image='".$image."'" : "")." WHERE ctg_cat_id='".$_POST['cat_id']."'");


            redirect(FUSION_SELF.$aidlink."&page=categories&status=su");
        } else {
            $ins = dbquery("INSERT INTO ".DB_AL_CATALOG_CATS." (ctg_cat_title,ctg_cat_desc,ctg_cat_parent,ctg_cat_image) VALUES ('".$title."','".$desc."','".$parent_cat."','".$image."')");


            redirect(FUSION_SELF.$aidlink."&page=categories&status=su");
        }
    } else {
        if (isset($_POST['cat_id'])) {
            $cat_id = $_POST['cat_id'];
            $is_edit = true;
        }
    }

} else if (isset($_POST['delete'])) {

    $result = dbquery("SELECT * FROM ".DB_AL_CATALOG_CATS." WHERE ctg_cat_id='".$_POST['cat_id']."'");
    if (dbrows($result)) {
        $del = dbquery("DELETE FROM ".DB_AL_CATALOG_ITEMS."' WHERE ctg_item_cat='".$_POST['cat_id']."'");
        $del = dbquery("DELETE FROM ".DB_AL_CATALOG_CATS." WHERE ctg_cat_id='".$_POST['cat_id']."'");
    }
    redirect(FUSION_SELF.$aidlink."&page=categories&status=del");

} else if (isset($_POST['edit'])) {

    $result = dbquery("SELECT * FROM ".DB_AL_CATALOG_CATS." WHERE ctg_cat_id='".$_POST['cat_id']."'");
    if (dbrows($result)) {
        $data = dbarray($result);
        $title = $data['ctg_cat_title'];
        $desc = $data['ctg_cat_desc'];
        $parent_cat = $data['ctg_cat_parent'];
        $image = $data['ctg_cat_image'];
        $cat_id = $data['ctg_cat_id'];
        $is_edit = true;

    } else {
        redirect(FUSION_SELF.$aidlink."&page=categories");
    }

} else {
    $title = '';
    $desc = '';
    $parent_cat = 0;
    $image = '';
    $cat_id = '';
}


opentable($locale['ctg6']);

echo "<div style='width:100%;text-align:center;'>";
if ($cats !== null) {
    echo "<form action='".FUSION_SELF.$aidlink."&page=categories' method='post'>";
    echo "<select name='cat_id' class='textbox'>".build_cats_tree_select(build_cats_tree_array($cats),0,$parent_cat)."</select>";
    echo "&nbsp;<input type='submit' name='edit' value='".$locale['ctg7']."' class='button' />";
    echo "&nbsp;<input type='submit' name='delete' value='".$locale['ctg8']."' class='button' />";
    echo "</form>";
} else {
    echo $locale['ctg9'];
}
echo "</div>";
closetable();

opentable($locale['ctg20']);
echo "<form action='".FUSION_SELF.$aidlink."&page=categories' method='post' enctype='multipart/form-data'>";
echo "<table width='100%'>";
if (isset($error) && !empty($error)) {
    echo "<tr>";
    foreach ($error as $e) {
        echo "<td class='tbl'></td><td class='tbl'>".$e."</td>";
    }
    echo "</tr>";
}
echo "<tr>";
echo "<td class='tbl' width='250'>".$locale['ctg10']."</td>";
echo "<td class='tbl'><input type='text' class='textbox' style='width:250px;' name='title' value='".$title."' /></td>";
echo "</tr><tr>";
echo "<td class='tbl' width='250'>".$locale['ctg11']."</td>";
echo "<td class='tbl'><textarea class='textbox' style='width:250px;' name='desc'>".$desc."</textarea></td>";
echo "</tr><tr>";
echo "<td class='tbl'>".$locale['ctg12']."</td>";
echo "<td class='tbl'><select name='parent_cat' class='textbox'><option value='0'>".$locale['ctg19']."</option>".build_cats_tree_select(build_cats_tree_array($cats),0,$parent_cat)."</select></td>";
echo "</tr><tr valign='top'>";
echo "<td class='tbl'>".$locale['ctg13']."</td>";
echo "<td class='tbl'><input type='file' name='image' class='textbox'/><br />";
if (!empty($image)) {
    echo "<input type='checkbox' name='delete_image' class='textbox' value='yes' /> ".$locale['ctg8'];
    echo "<br /><img src='".AL_CATALOG_DIR."uploads/cats/".$image."' />";
}
echo "</td>";
echo "</tr><tr>";

echo "<td colspan='2' class='tbl'><input type='submit' class='button' name='save' value='".$locale['ctg14']."' />";
if ($is_edit) {
    echo "<input type='hidden' name='cat_id' value='".$cat_id."' />";
    echo "&nbsp;<a class='button' href='".FUSION_SELF.$aidlink."&page=categories'>".$locale['ctg15']."</a>";
}
echo "</td>";
echo "</tr>";
echo "</table>";
echo "</form>";
closetable();
?>