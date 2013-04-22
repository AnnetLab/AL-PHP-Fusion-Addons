<?php
if (!defined("IN_FUSION")) die("acceds denied");
 if (file_exists(INFUSIONS."al_group/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_groups/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_groups/locale/English.php";
} 
require_once INFUSIONS."al_groups/infusion_db.php";

$groups = dbquery("SELECT gr.*, gc.* FROM ".DB_GS_GROUPS." gr LEFT JOIN ".DB_GS_CATS." gc ON gc.cat_id=gr.group_cat ORDER BY group_stat DESC LIMIT 10");

openside($locale['gs16']);
if (dbrows($groups)) {
echo "<table width='100%'><tr><td class='tbl2'>".$locale['gs25']." (".$locale['gs26'].")</td><td width='1%' class='tbl2'>".$locale['gs27']."</td></tr>";
while ($group=dbarray($groups)) {
echo "<tr><td><a href='".BASEDIR."group.php?view=".$group['group_id']."'>".$group['group_name']."</a> (<a href='".BASEDIR."groups.php?cat=".$group['cat_id']."'>".$group['cat_name']."</a>)</td><td align='center'>".$group['group_stat']."</td></tr>";
}
echo "</table>";
} else {
echo $locale['gs18'];
}
echo "<br /><br /><a href='".BASEDIR."groups.php?cat=0'>".$locale['gs80']."</a>"; 
closeside();
?>
