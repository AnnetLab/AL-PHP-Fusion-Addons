<?php
if ((!iADMIN || !checkrights("ALB")) || (!iMEMBER || $blog_settings['allow_user_blogs'] == 0)) redirect(FUSION_SELF);
require_once AL_BLOG_DIR."includes/redactor_include.php";



if (isset($_POST['save']) || isset($_POST['preview'])) {

    $post_title = trim(addslashes($_POST['post_title']));
//    $post_pretext = trim(addslashes($_POST['post_pretext']));
    $post_text = trim(addslashes($_POST['post_text']));
    $post_cat = isset($_POST['post_cat']) && isnum($_POST['post_cat']) ? $_POST['post_cat'] : 0;
//    $post_draft = isset($_POST['post_draft']) && $_POST['post_draft'] == 'yes' ? 1 : 0;
    $is_edit = isset($_POST['post_id']) && isnum($_POST['post_id']) ? true : false;
    $post_id = $is_edit ? $_POST['post_id'] : 0;

    if (isset($_POST['preview'])) {
        opentable($locale['alb16']);
            echo "<h3>".$post_title."</h3>";
//            echo htmlspecialchars_decode($post_pretext)."<br />";
            $post_text = htmlspecialchars_decode(stripslashes($post_text));
            echo $post_text;
        closetable();
    }

    if (isset($_POST['save'])) {

        $error_msg = array();
        if ($post_title == '') $error_msg[] = $locale['alb17'];
//        if ($post_pretext == '') $error_msg[] = $locale['alb18'];

        if (empty($error_msg)) {
            if ($is_edit) {
                $result = dbquery("UPDATE ".DB_AL_BLOG_POSTS." SET alb_post_cat='".$post_cat."',alb_post_status='".($blog_settings['alb_settings_moderate'] == 1 && !iADMIN && !checkrights("ALB") ? 0 : 1)."',alb_post_title='".$post_title."',alb_post_text='".$post_text."' WHERE alb_post_id='".$post_id."'");
            } else {
                $result = dbquery("INSERT INTO ".DB_AL_BLOG_POSTS." (alb_post_datestamp,alb_post_views,alb_post_user,alb_post_cat,alb_post_status,alb_post_title,alb_post_text) VALUES ('".time()."','0','".$userdata['user_id']."','".$post_cat."','".($blog_settings['alb_settings_moderate'] == 1 ? 0 : 1)."','".$post_title."','".$post_text."')");
            }
            redirect(FUSION_SELF."?p=post&id=".mysql_insert_id());
        } else {
            opentable($locale['alb19']);
                foreach ($error_msg as $err) {
                    echo "<strong>".$err."</strong><br />";
                }
            closetable();
        }

    }
} else if (/*isset($_GET['action']) && $_GET['action'] == "edit" && */isset($_GET['id']) && isnum($_GET['id']) && !isset($_POST['save']) && !isset($_POST['preview'])) {

    $result = dbquery("SELECT * FROM ".DB_AL_BLOG_POSTS." WHERE alb_post_id='".$_GET['id']."'");
    if (dbrows($result)) {
        $data = dbarray($result);
        if ((iMEMBER && $userdata['user_id'] == $data['alb_post_user']) || (iADMIN && cehckrights("ALB"))) {
            $post_title = $data['alb_post_title'];
//            $post_pretext = $data['alb_post_pretext'];
            $post_text = $data['alb_post_text'];
            $post_cat = $data['alb_post_cat'];
//            $post_draft = $data['alb_post_draft'];
            $is_edit = true;
            $post_id = $data['alb_post_id'];
        }
    }

} else if (isset($_GET['delete']) && isnum($_GET['delete'])) {

    $result = dbquery("SELECT * FROM ".DB_AL_BLOG_POSTS." WHERE alb_post_id='".$_GET['delete']."'");
    if (dbrows($result)) {
        $data = dbarray($result);
        if ((iMEMBER && $userdata['user_id'] == $data['alb_post_user']) || (iADMIN && cehckrights("ALB"))) {
            dbquery("DELETE FROM ".DB_AL_BLOG_POSTS." WHERE alb_post_id='".$_GET['delete']."'");
            redirect(BASEDIR."blog.php?p=my_posts");
        }
    }

} else {
    $post_title = '';
//    $post_pretext = '';
    $post_text = '';
    $post_cat = 0;
//    $post_draft = 0;
    $is_edit = false;
}



opentable($locale['alb8']);

echo "<form method='post' action='".FUSION_SELF."?p=manage_post'>";
echo "<table width='100%'>";
    echo "<tr>";
        echo "<td class='tbl'>".$locale['alb9']."<br /><input type='text' class='textbox' name='post_title' value='".$post_title."' style='width:98%;' /></td>";
    echo "</tr>";
    echo "<tr>";
        echo "<td class='tbl'>".$locale['alb10']."<br />";
        $result = dbquery("SELECT * FROM ".DB_AL_BLOG_CATEGORIES);
        if (dbrows($result)) {
            echo "<select name='post_cat'>";
                while ($data=dbarray($result)) {
                    echo "<option value='".$data['alb_cat_id']."'".($post_cat == $data['alb_cat_id'] ? " selected='selected'" : "").">".$data['alb_cat_title']."</option>";
                }
            echo "</select>";
        } else {
            echo $locale['alb13'];
        }
        echo "</td>";
    echo "</tr>";
//    echo "<tr>";
//        echo "<td class='tbl'>".$locale['alb11']."<br /><textarea name='post_pretext' class='redactor'>".$post_pretext."</textarea></td>";
//    echo "</tr>";
    echo "<tr>";
        echo "<td class='tbl'>".$locale['alb12']."<br /><textarea name='post_text' class='redactor'>".stripslashes($post_text)."</textarea></td>";
    echo "</tr>";
//    echo "<tr>";
//        echo "<td class='tbl'><label for='post_draft'><input type='checkbox' name='post_draft' value='yes'".($post_draft == 1 ? " checked='checked'" : "")." id='post_draft' /> ".$locale['alb14']."</label></td>";
//    echo "</tr>";
    echo "<tr>";
        echo "<td class='tbl'>".($is_edit ? "<input type='hidden' name='post_id' value='".$post_id."' />" : "")."<input type='submit' class='button' name='save' value='".$locale['alb15']."' /> <input type='submit' class='button' name='preview' value='".$locale['alb16']."' /></td>";
    echo "</tr>";
echo "</table>";
echo "</form>";

closetable();



?>