<?php
if (!defined("IN_FUSION")) die("access denied");

$currents = dbquery("SELECT * FROM ".DB_T_TOURS." WHERE tour_finished='0'");

opentable("Current tournies");
if (dbrows($currents)) {
echo "<table width='95%' class='center'><tr align='center'><td class='tbl2' width='1%'><strong>#</strong></td><td class='tbl2'><strong>Name</strong></td><td class='tbl2' width='1%'><strong>Status</strong></td><td class='tbl2' width='1%'></td></tr>";
while ($current=dbarray($currents)) {
echo "<tr align='center'><td class='tbl1'>".$current['tour_id']."</td><td class='tbl1'>".$current['tour_name']."</td><td class='tbl1'>".showStatus($current['tour_id'])."</td><td class='tbl1'><a href='".BASEDIR."tourney.php?p=viewtour&id=".$current['tour_id']."'>View more</a></td></tr>"; 
}

echo "</table>";

} else {
echo "No opened tournies.";
}
closetable();

$finisheds = dbquery("SELECT tt.*, us.user_name FROM ".DB_T_TOURS." tt LEFT JOIN ".DB_USERS." us ON us.user_id=tt.tour_w1 WHERE tour_finished='1'"); 

opentable("Finished tournies");
if (dbrows($finisheds)) {
echo "<table width='95%' class='center'><tr align='center'><td class='tbl2' width='1%'><strong>#</strong></td><td class='tbl2'><strong>Name</strong></td><td class='tbl2' width='1%'><strong>Winner</strong></td><td class='tbl2' width='1%'></td></tr>";
while ($finished=dbarray($finisheds)) {
echo "<tr align='center'><td class='tbl1'>".$finished['tour_id']."</td><td class='tbl1'>".$finished['tour_name']."</td><td class='tbl1'><a href='".BASEDIR."profile.php?lookup=".$finished['tour_w1']."'>".$finished['user_name']."</a></td><td class='tbl1'><a href='".BASEDIR."tourney.php?p=viewtour&id=".$finished['tour_id']."'>View more</a></td></tr>"; 
}

echo "</table>";
 
} else {
echo "No closed tournies.";
}
closetable(); 

?>