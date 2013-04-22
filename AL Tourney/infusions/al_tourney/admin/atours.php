<?php
if (!defined("IN_FUSION")) die("ACCESS DENIED");

opentable("all tournies");
showAdminNav();

$result = dbquery("SELECT * FROM ".DB_T_TOURS." ORDER BY tour_id DESC");
if (dbrows($result)) {
echo "<table width='100%'>";
echo "<tr><td class='tbl2' width='1%'>id</td><td class='tbl2' width='1%'>status</td><td class='tbl2'>name</td><td class='tbl2' width='1%'>date</td><td class='tbl2' width='1%'>options</td></tr>";
while ($data=dbarray($result)) {
echo "<tr><td class='tbl2' width='1%'>".$data['tour_id']."</td><td class='tbl1' width='1%'>".showStatus($data['tour_id'])."</td><td class='tbl1'><a href='".BASEDIR."tourney.php?p=viewtour&id=".$data['tour_id']."'>".$data['tour_name']."</a></td><td class='tbl1' width='1%'>".showdate("longdate",$data['tour_date'])."</td><td class='tbl1' width='1%'><a href='".INFUSIONS."al_tourney/admin/index.php".$aidlink."&p=newtour&a=edit&id=".$data['tour_id']."''><img src='".IMAGES."edit.png' alt='edit tourney' /></a> <a href='".INFUSIONS."al_tourney/admin/index.php".$aidlink."&p=bracket&id=".$data['tour_id']."''><img src='".IMAGES."arrow.png' alt='edit bracket' /></a> <a href='".INFUSIONS."al_tourney/admin/index.php".$aidlink."&p=newtour&a=delete&id=".$data['tour_id']."''><img src='".IMAGES."no.png' alt='delete tourney' /></a></td></tr>"; 
}
echo "</table>";
} else {
echo "no tournies";
}

closetable();

?>
