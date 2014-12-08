<?php
add_to_title(": ".$locale['ctg21']);


$st = dbarray(dbquery("SELECT * FROM ".DB_AL_CATALOG_SETTINGS));
require_once AL_CATALOG_DIR."functions.php";

if (isset($_POST['save_image_settings'])) {

    $photo_max_width = intval(stripinput($_POST['photo_max_width'])) != "" ? intval(stripinput($_POST['photo_max_width'])) : 0;
    $photo_max_height = intval(stripinput($_POST['photo_max_height'])) != "" ? intval(stripinput($_POST['photo_max_height'])) : 0;
    $max_photo_size = intval(stripinput($_POST['max_photo_size'])) != "" ? intval(stripinput($_POST['max_photo_size'])) : 0;
    $cat_thumb_width = intval(stripinput($_POST['cat_thumb_width'])) != "" ? intval(stripinput($_POST['cat_thumb_width'])) : 0;
    $cat_thumb_height = intval(stripinput($_POST['cat_thumb_height'])) != "" ? intval(stripinput($_POST['cat_thumb_height'])) : 0;
    $item_thumb_width = intval(stripinput($_POST['item_thumb_width'])) != "" ? intval(stripinput($_POST['item_thumb_width'])) : 0;
    $item_thumb_height = intval(stripinput($_POST['item_thumb_height'])) != "" ? intval(stripinput($_POST['item_thumb_height'])) : 0;
    $cats_in_line = intval(stripinput($_POST['cats_in_line'])) != "" ? intval(stripinput($_POST['cats_in_line'])) : 5;
    $items_in_line = intval(stripinput($_POST['items_in_line'])) != "" ? intval(stripinput($_POST['items_in_line'])) : 5;
    $items_per_page = intval(stripinput($_POST['items_per_page'])) != "" ? intval(stripinput($_POST['items_per_page'])) : 30;


    $update = dbquery("UPDATE ".DB_AL_CATALOG_SETTINGS." SET photo_max_width='".$photo_max_width."',photo_max_height='".$photo_max_height."',max_photo_size='".$max_photo_size."',cat_thumb_width='".$cat_thumb_width."',cat_thumb_height='".$cat_thumb_height."',item_thumb_width='".$item_thumb_width."',item_thumb_height='".$item_thumb_height."',cats_in_line='".$cats_in_line."',items_in_line='".$items_in_line."',items_per_page='".$items_per_page."'");
    redirect(FUSION_SELF.$aidlink."&page=settings");

}

opentable($locale['ctg21']);

echo "<form action='".FUSION_SELF.$aidlink."&page=settings' method='post'>";
echo "<table width='100%'>";
echo "<tr>";
echo "<td width='250' class='tbl'>".$locale['ctg22']."</td>";
echo "<td class='tbl'><input type='text' name='photo_max_width' value='".$st['photo_max_width']."' class='textbox' style='width:250px;' /></td>";
echo "</tr><tr>";
echo "<td width='250' class='tbl'>".$locale['ctg23']."</td>";
echo "<td class='tbl'><input type='text' name='photo_max_height' value='".$st['photo_max_height']."' class='textbox' style='width:250px;' /></td>";
echo "</tr><tr>";
echo "<td width='250' class='tbl'>".$locale['ctg24']."</td>";
echo "<td class='tbl'><input type='text' name='max_photo_size' value='".$st['max_photo_size']."' class='textbox' style='width:250px;' /></td>";
echo "</tr><tr>";
echo "<td width='250' class='tbl'>".$locale['ctg25']."</td>";
echo "<td class='tbl'><input type='text' name='cat_thumb_width' value='".$st['cat_thumb_width']."' class='textbox' style='width:250px;' /></td>";
echo "</tr><tr>";
echo "<td width='250' class='tbl'>".$locale['ctg26']."</td>";
echo "<td class='tbl'><input type='text' name='cat_thumb_height' value='".$st['cat_thumb_height']."' class='textbox' style='width:250px;' /></td>";
echo "</tr><tr>";
echo "<td width='250' class='tbl'>".$locale['ctg53']."</td>";
echo "<td class='tbl'><input type='text' name='item_thumb_width' value='".$st['item_thumb_width']."' class='textbox' style='width:250px;' /></td>";
echo "</tr><tr>";
echo "<td width='250' class='tbl'>".$locale['ctg54']."</td>";
echo "<td class='tbl'><input type='text' name='item_thumb_height' value='".$st['item_thumb_height']."' class='textbox' style='width:250px;' /></td>";
echo "</tr><tr>";
echo "<td width='250' class='tbl'>".$locale['ctg27']."</td>";
echo "<td class='tbl'><input type='text' name='cats_in_line' value='".$st['cats_in_line']."' class='textbox' style='width:250px;' /></td>";
echo "</tr><tr>";
echo "<td width='250' class='tbl'>".$locale['ctg28']."</td>";
echo "<td class='tbl'><input type='text' name='items_in_line' value='".$st['items_in_line']."' class='textbox' style='width:250px;' /></td>";
echo "</tr><tr>";
echo "<td width='250' class='tbl'>".$locale['ctg29']."</td>";
echo "<td class='tbl'><input type='text' name='items_per_page' value='".$st['items_per_page']."' class='textbox' style='width:250px;' /></td>";
echo "</tr><tr>";
echo "<td class='tbl'></td><td class='tbl'><input type='submit' name='save_image_settings' class='button' value='".$locale['ctg14']."' /></td>";
echo "</tr>";

echo "</table>";
echo "</form>";
closetable();

?>