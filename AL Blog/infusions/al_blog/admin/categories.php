<?php defined("IN_FUSION") or die();

if (isset($_POST['save'])) {

    $cat_title = trim(stripinput($_POST['cat_title']));
    if (isset($_POST['cat_id']) && isnum($_POST['cat_id'])) {
        dbquery("UPDATE ".DB_AL_BLOG_CATEGORIES." SET alb_cat_title='".$cat_title."' WHERE alb_cat_id='".$_POST['cat_id']."'");
    } else {
        dbquery("INSERT INTO ".DB_AL_BLOG_CATEGORIES." (alb_cat_title) VALUES ('".$cat_title."')");
    }
    redirect(FUSION_SELF.$aidlink."&p=categories");

} else if (isset($_POST['delete']) && isset($_POST['cat_id']) && isnum($_POST['cat_id'])) {
    if (dbrows(dbquery("SELECT * FROM ".DB_AL_BLOG_CATEGORIES." WHERE alb_cat_id='".$_POST['cat_id']."'"))) {
        dbquery("DELETE FROM ".DB_AL_BLOG_CATEGORIES." WHERE alb_cat_id='".$_POST['cat_id']."'");
        dbquery("DELETE FROM ".DB_AL_BLOG_POSTS." WHERE alb_post_cat='".$_POST['cat_id']."'");
    }
    redirect(FUSION_SELF.$aidlink."&p=categories");
} else if (isset($_POST['edit']) && isset($_POST['cat_id']) && isnum($_POST['cat_id'])) {
    $result = dbquery("SELECT * FROM ".DB_AL_BLOG_CATEGORIES." WHERE alb_cat_id='".$_POST['cat_id']."'");
    if (dbrows($result)) {
        $data = dbarray($result);
        $cat_title = $data['alb_cat_title'];
        $is_edit = true;
        $cat_id = $data['alb_cat_id'];
    } else {
        redirect(FUSION_SELF.$aidlink."&p=categories");
    }
} else {
    $cat_title = '';
    $is_edit = false;
}

opentable($locale['alb25']);

$result = dbquery("SELECT * FROM ".DB_AL_BLOG_CATEGORIES." ORDER BY alb_cat_id DESC");
echo "<div style='text-align:center;width:100%;'>";
if (dbrows($result)) {
    echo "<form method='post'>";
    echo "<select name='cat_id' class='textbox'>";
    while ($data = dbarray($result)) {
        echo "<option value='".$data['alb_cat_id']."'>".$data['alb_cat_title']."</option>";
    }
    echo "</select> <input type='submit' class='button' name='edit' value='".$locale['alb32']."' /> <input type='submit' class='button' name='delete' value='".$locale['alb33']."' />";
    echo "</form>";
} else {
    echo $locale['alb31'];
}
echo "</div>";
closetable();

opentable($locale['alb34']);
echo "<form method='post'>";
echo "<table width='100%'>";
    echo "<tr>";
        echo "<td class='tbl' width='250'>".$locale['alb35']."</td>";
        echo "<td class='tbl'><input type='text' name='cat_title' class='textbox' style='width:250px;' value='".$cat_title."' /></td>";
    echo "</tr>";
    echo "<tr><td class='tbl' colspan='2'><input type='submit' name='save' class='button' value='".$locale['alb30']."' />".($is_edit ? "<input type='hidden' name='cat_id' value='".$cat_id."' />" : "")."</td></tr>";
echo "</table>";
echo "</form>";
closetable();

?>