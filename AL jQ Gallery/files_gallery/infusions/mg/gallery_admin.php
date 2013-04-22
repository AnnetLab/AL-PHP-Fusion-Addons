<?php
 /*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| AL jQ gallery 1.7
| Filename: gallery_admin.php
| Author: Rush
| http://fusion.annetlab.tk
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/ 
require_once "../../maincore.php";
require_once THEMES."templates/admin_header.php";
require_once INFUSIONS."mg/infusion_db.php";
require_once INFUSIONS."mg/functions.php";

if (checkrights("MG") && defined("iAUTH") && $_GET['aid'] == iAUTH) { $ye = true; }
if ($ye != true) { redirect(BASEDIR."index.php"); }

 if (file_exists(INFUSIONS."mg/locale/".$settings['locale'].".php")) {
require_once INFUSIONS."mg/locale/".$settings['locale'].".php"; 
} else {
require_once INFUSIONS."mg/locale/English.php"; 
} 

if (isset($_POST['save'])) {
    
    $size = trim(stripinput($_POST['max_photo_size']));
    $types = trim($_POST['photo_types']);
    $w = trim(stripinput($_POST['photo_width']));
    $h = trim(stripinput($_POST['photo_height']));
    $t_w = trim(stripinput($_POST['thumb_w']));
    $t_h = trim(stripinput($_POST['thumb_h']));
    $update = dbquery("UPDATE ".DB_MG_SETTINGS." SET user_albums='".$_POST['user_albums']."', upload_original='".$_POST['upload_original']."', max_photo_size='".$size."', photo_width='".$w."', photo_height='".$h."', thumb_width='".$t_w."', thumb_height='".$t_h."'");
    if ($update) {
        redirect(FUSION_SELF.$aidlink."&m=success");
    } else {
        redirect(FUSION_SELF.$aidlink."&m=fail");
    }
}

    
    if (isset($_GET['m']) && $_GET['m'] == "success") {
        echo "<div id='close-message'><div class='admin-message'>".$locale['mg32']."</div></div>";
    } elseif (isset($_GET['m']) && $_GET['m'] == "fail") {
        echo "<div id='close-message'><div class='admin-message'>".$locale['mg33']."</div></div>";
    }
    opentable($locale['mg34']);
    echo "<form action='".FUSION_SELF.$aidlink."' method='post'>";
    echo "<table width='90%'>";
    $select = "<select name='user_albums' class='textbox'>";
        $select .= "<option value='1'".($mg_settings['user_albums'] == 1 ? " selected='selected'" : "").">".$locale['mg35']."</option>";
        $select .= "<option value='0'".($mg_settings['user_albums'] == 0 ? " selected='selected'" : "").">".$locale['mg36']."</option>";
    $select .= "</select>";
 $select2 = "<select name='upload_original' class='textbox'>";
        $select2 .= "<option value='1'".($mg_settings['upload_original'] == 1 ? " selected='selected'" : "").">".$locale['mg46']."</option>";
        $select2 .= "<option value='0'".($mg_settings['upload_original'] == 0 ? " selected='selected'" : "").">".$locale['mg47']."</option>";
    $select2 .= "</select>"; 
    echo "<tr><td class='tbl2' width='200'>".$locale['mg37']."</td><td class='tbl2'>".$select."</td></tr>";
 echo "<tr><td class='tbl2' width='200'>".$locale['mg48']."</td><td class='tbl2'>".$select2."</td></tr>"; 
    echo "<tr><td class='tbl2' width='200'>".$locale['mg38']."</td><td class='tbl2'><input type='text' class='textbox' name='max_photo_size' style='width:250px;' value='".$mg_settings['max_photo_size']."' /></td></tr>";
    echo "<tr><td class='tbl2' width='200'>".$locale['mg39']."<br /><i class='small'>(*.type;*.type)</i></td><td class='tbl2'><input type='text' class='textbox' name='photo_types' value='".$mg_settings['photo_types']."' style='width:250px;' /></td></tr>";
    echo "<tr><td class='tbl2' width='200'>".$locale['mg40']."</td><td class='tbl2'><input type='text' class='textbox' name='photo_width' value='".$mg_settings['photo_width']."' style='width:250px;' /></td></tr>";
    echo "<tr><td class='tbl2' width='200'>".$locale['mg41']."</td><td class='tbl2'><input type='text' class='textbox' name='photo_height' value='".$mg_settings['photo_height']."' style='width:250px;' /></td></tr>";
    echo "<tr><td class='tbl2' width='200'>".$locale['mg42']."</td><td class='tbl2'><input type='text' class='textbox' name='thumb_w' value='".$mg_settings['thumb_width']."' style='width:250px;' /></td></tr>";
    echo "<tr><td class='tbl2' width='200'>".$locale['mg43']."</td><td class='tbl2'><input type='text' class='textbox' name='thumb_h' value='".$mg_settings['thumb_height']."' style='width:250px;' /></td></tr>";
    echo "<tr><td class='tbl2' colspan='2'><input type='submit' class='button' name='save' value='".$locale['mg28']."' /></td></tr>";
    
    
    
    
    echo "</table>";
    echo "</form>";
    closetable();
    







require_once THEMES."templates/footer.php";
?>
