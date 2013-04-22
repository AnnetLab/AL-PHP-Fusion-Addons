<?php
if (!defined("IN_FUSION")) die("access denied");

if (!isset($_GET['id']) && !isnum($_GET['id'])) {
redirect(START_PAGE);
}

if (isset($_POST['cr_bracket'])) {
$tid = $_POST['tid'];
$max = $_POST['max'];
$pl = $_POST['pl'];
$free = $_POST['free']; 
$rounds = log($max)/log(2);
for ($i = 1; $i <= $rounds; $i++) {
	for ($j = 1; $j <= $max/(pow(2,$i)); $j++) {
	$result5 = dbquery("INSERT INTO ".DB_T_MATCHES." (match_tour, match_round, match_match) VALUES ('".$tid."', '".$i."', '".$j."')");
	}
}
$x = $rounds+1;
$final34 = dbquery("INSERT INTO ".DB_T_MATCHES." (match_tour, match_round, match_match) VALUES ('".$tid."', '".$x."', '1')");
for ($i=1;$i<=$max;$i++) {
//1- 1,2; 2 - 3,4; 3 - 5,6; 4 - 7,8
$n1 = (2*$i)-1;
$n2 = 2*$i;
$pl1 = $_POST['pl'.$n1];
$pl2 = $_POST['pl'.$n2];
if ($pl1 == "0") {
$byete = dbquery("INSERT INTO ".DB_T_PLAYERS." (player_user, player_tour, player_checkin) VALUES ('".bye()."', '".$tid."', '1')");
$pl1 = bye();
}
if ($pl2 == "0") {
$byete = dbquery("INSERT INTO ".DB_T_PLAYERS." (player_user, player_tour, player_checkin) VALUES ('".bye()."', '".$tid."', '1')");
$pl2 = bye();
} 
 $ins_pl = dbquery("UPDATE ".DB_T_MATCHES." SET match_pl1='".$pl1."', match_pl2='".$pl2."' WHERE match_tour='".$tid."' AND match_round='1' AND match_match='".$i."'"); 
}
$upd_t = dbquery("UPDATE ".DB_T_TOURS." SET tour_grid='1' WHERE tour_id='".$tid."'");
clearByes($tid);

}

$result = dbquery("SELECT * FROM ".DB_T_TOURS." WHERE tour_id='".$_GET['id']."'");

if (dbrows($result)) {
$tour = dbarray($result);
$now = time()+(($settings['timeoffset']+$settings['serveroffset'])*3600);
if ($tour['tour_date'] < $now) {
$pl = dbcount("(player_id)", DB_T_PLAYERS, "player_tour='".$_GET['id']."' AND player_checkin='1'");
if ($pl > 0) {
$max = getMaxPl($pl, $tour['tour_maxpl']);
$free = $max - $pl;
opentable("Create brackets");
echo "You have ".$pl." registered, and you need for ".$free." freeslots.";
$pl_options = "<option value='0'>freeslot</option>";
$players = dbquery("SELECT tpl.*, tu.user_name FROM ".DB_T_PLAYERS." tpl LEFT JOIN ".DB_USERS." tu ON tu.user_id=tpl.player_user WHERE player_tour='".$_GET['id']."' AND player_checkin='1'");
while ($player=dbarray($players)) {
$pl_options .= "<option value='".$player['player_user']."'>".$player['user_name']."</option>";
}
echo "<form name='ghcfdg' method='post'><table width='100%'>";
for ($i=1;$i<=$max;$i++) {
echo "<tr><td class='tbl2'>Player ".$i." <select name='pl".$i."'>".$pl_options."</select></td></tr>";
}
echo "<tr><td class='tbl2'><input type='hidden' name='tid' value='".$_GET['id']."' /><input type='hidden' name='pl' value='".$pl."' /><input type='hidden' name='max' value='".$max."' /><input type='hidden' name='free' value='".$free."' /><input type='submit' class='button' name='cr_bracket' value='create' /></td></tr>";
echo "</table></form>";

closetable();


} else {
echo "<div class='admin-message'>No registered players(.</div>"; 
}
} else {
echo "<div class='admin-message'>Registration do not complete yet.</div>";
}
} else {
die("invalid id");
}


?>
