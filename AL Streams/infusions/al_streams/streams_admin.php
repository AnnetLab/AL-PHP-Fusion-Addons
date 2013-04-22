<?php
require_once "../../maincore.php";
require_once THEMES."templates/admin_header.php";
include INFUSIONS."al_streams/infusion_db.php";
if (file_exists(INFUSIONS."al_streams/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_streams/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_streams/locale/Russian.php";
} 
if (!defined("IN_FUSION")) die("access denied");


if(!checkAdminPageAccess("SS")) redirect(START_PAGE);

if (isset($_POST['update'])) {
$upd = dbquery("UPDATE ".DB_SS_SETTINGS." SET set_usergroup='".$_POST['gr']."'");
redirect(FUSION_SELF.$aidlink);
} else {
$ssettings = dbarray(dbquery("SELECT * FROM ".DB_SS_SETTINGS.""));
opentable($locale['ss1']);
echo "<form name='gftg' method='post' action='".FUSION_SELF.$aidlink."'>";
echo "<table width='100%'>";
echo "<tr><td class='tbl2' width='250'>".$locale['ss2']."</td><td class='tbl2'>";
$grs = dbquery("SELECT * FROM ".DB_USER_GROUPS." ORDER BY group_id ASC");
if (dbrows($grs)) {
echo "<select name='gr'>";
echo "<option value='101'".($ssettings['set_usergroup'] == "101" ? " selected='selected'" : "").">".$locale['ss25']."</option>";
 echo "<option value='102'".($ssettings['set_usergroup'] == "102" ? " selected='selected'" : "").">".$locale['ss26']."</option>";
echo "<option value='103'".($ssettings['set_usergroup'] == "103" ? " selected='selected'" : "").">".$locale['ss27']."</option>"; 
while ($gr=dbarray($grs)) {
echo "<option value='".$gr['group_id']."'".($ssettings['set_usergroup'] == $gr['group_id'] ? " selected='selected'" : "").">".$gr['group_name']."</option>";
}
echo "</select>";
} else {
echo "No groups";
}
echo "</td></tr>";
echo "<tr><td class='tbl2' colspan='2'><input type='submit' class='button' name='update' value='".$locale['ss3']."' /></td></tr>";
echo "</table></form>";
closetable();
}

require_once THEMES."templates/footer.php";
?>
