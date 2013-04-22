<?php
if (!defined("IN_FUSION")) die("fu");
require_once INCLUDES."bbcode_include.php";

if (isset($_POST['preview'])) {
$rpreview = true;

$rtext = trim(nl2br(parseubb($_POST['rrules'])));
$rrules = $_POST['rrules'];

 opentable($locale['rm10']);
echo $rtext;
closetable(); 

} elseif (isset($_POST['save'])) {
$rrules = trim($_POST['rrules']);
$update = dbquery("UPDATE ".DB_RM_RULES." SET rules='".$rrules."'");
redirect(INFUSIONS."al_register_mod/admin/index.php?p=rules");
} else {
$rules = dbarray(dbquery("SELECT * FROM ".DB_RM_RULES.""));

$rrules = $rules['rules'];
$rpreview = false;

}

// form
opentable($locale['rm8']);
echo "<form method='post' name='inputform'>";
echo "<table width='100%>";
echo "<tr><td class='tbl2'><textarea name='rrules' rows='15' cols='50'>".$rrules."</textarea>";
 echo display_bbcodes("240px;", "rrules", "inputform"); 
echo "</td></tr>";
echo "<tr><td class='tbl2'><input type='submit' name='save' class='button' value='".$locale['rm9']."' /> <input type='submit' name='preview' class='button' value='".$locale['rm10']."' /> </td></tr>";
echo "</table>";
echo "</form>";
closetable();


?>
