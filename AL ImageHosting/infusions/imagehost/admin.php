<?php
 /*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: admin.php by Rush
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

if (!iSUPERADMIN || !defined("iAUTH") || $_GET['aid'] != iAUTH) { die("Access Denied!"); }

require_once INFUSIONS."imagehost/infusion_db.php";

// Locales
if (file_exists(INFUSIONS."imagehost/locale/".$settings['locale'].".php")) {
include INFUSIONS."imagehost/locale/".$settings['locale'].".php"; 
} else {
include INFUSIONS."imagehost/locale/English.php";
}

if (isset($_POST['save'])) {
$update = dbquery("UPDATE ".DB_IMH." SET lvl='".$_POST['level']."' WHERE id='1'");
}

$data = dbarray(dbquery("SELECT * FROM ".DB_IMH." WHERE id='1'"));
// Welcome message
opentable($locale['i01']);
echo "<form action='".INFUSIONS."imagehost/admin.php".$aidlink."' method='post' name='gfghcf'>";
echo "<table width='90%'>";
echo "<tr><td class='tbl2' width='200'>".$locale['i02']."</td><td class='tbl1'>
<select name='level'>
<option value='0'".($data['lvl'] == "0" ? " selected='selected'" : "").">".$locale['i03']."</option>
 <option value='101'".($data['lvl'] == "101" ? " selected='selected'" : "").">".$locale['i04']."</option>
 <option value='102'".($data['lvl'] == "102" ? " selected='selected'" : "").">".$locale['i05']."</option>
 <option value='103'".($data['lvl'] == "103" ? " selected='selected'" : "").">".$locale['i06']."</option> 
</select>
</td></tr>";
 echo "<tr><td class='tbl2' colspan='2'>
<input type='submit' class='button' name='save' value='".$locale['i07']."' />
</td></tr>"; 
echo "</table>";
echo "</form>";
closetable();

 // be a good guy, plz don't move this
echo "<div style='width:150px;text-align:center;margin:10px auto;'><a href='http://fusion.annetlab.tk/'>Fusion @ Annetlab</a> &copy; 2011-2012 by <a href='http://vkontakte.ru/hot.rush'>Rush</a></div>"; 

require_once THEMES."templates/footer.php"; 
?>
