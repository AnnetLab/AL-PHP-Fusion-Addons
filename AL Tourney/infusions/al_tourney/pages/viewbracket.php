<?php
if (!defined("IN_FUSION")) die("access denied");

if (!isset($_GET['id']) && !isnum($_GET['id'])) {
redirect(START_PAGE);
}
$tid = $_GET['id'];
$result = dbquery("SELECT * FROM ".DB_T_TOURS." WHERE tour_id='".$tid."'");

if (dbrows($result)) {
clearByes($tid);
$tour = dbarray($result);


if ($tour['tour_grid'] == "1" && $tour['tour_finished'] == "0") {
// matches
$check_r = dbquery("SELECT * FROM ".DB_T_PLAYERS." WHERE player_tour='".$tour['tour_id']."' AND player_user='".$userdata['user_id']."' AND player_checkin='1'");
if (dbrows($check_r)) {
$check_m = dbquery("SELECT * FROM ".DB_T_MATCHES." WHERE match_tour='".$tour['tour_id']."' AND match_played='0' AND (match_pl1='".$userdata['user_id']."' OR match_pl2='".$userdata['user_id']."')");
if (dbrows($check_m)) {
$nm = dbarray($check_m);
if ($nm['match_pl1'] == $userdata['user_id']) {
if ($nm['match_pl2'] != "0") {
$opp = dbarray(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$nm['match_pl2']."'"));
$opp_link = "vs <a href='".BASEDIR."profile.php?lookup=".$nm['match_pl2']['user_name']."</a>";
} else {
$opp_link = "wating for an opponent...";
}
} else {
if ($nm['match_pl1'] != "0") {
$opp = dbarray(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$nm['match_pl1']."'"));
$opp_link = "vs <a href='".BASEDIR."profile.php?lookup=".$nm['match_pl1']."'>".$opp['user_name']."</a>"; 
} else {
$opp_link = "wating for an opponent...";
}
}
$mres = "Your next match: 
".$opp_link." - <a href='".BASEDIR."tourney.php?p=result&id=".$nm['match_id']."'>go to match</a>";
} else {
$mres = "No more matches to play for you.";
}
} else {
$mres = "You don't take part in this tourney.";
}
} 
opentable("Tourney info");
echo "<table width='100%'><tr><td class='tbl2' width='50%'><strong>".$tour['tour_name']."</strong> - <a href='".BASEDIR."tourney.php?p=viewtour&id=".$tour['tour_id']."'>view more</a></td><td class='tbl2'>".$mres."</td></tr></table>";

closetable();


opentable("Brackets");
//mini info

$bye = bye();
$pl = dbcount("(player_id)", DB_T_PLAYERS, "player_tour='".$tid."' AND player_checkin='1'");
$max = getMaxPl($pl);
$rounds = log($max)/log(2);
$f_heigth = array(1=>0,33,98,227,482);
$m_heigth = array(1=>30,94,222,478,990);

$wi = 100/($rounds+1);
$ro = $max;
echo "<table width='100%'><tr>";
for ($x=1;$x<=$rounds;$x++) {
echo "<td class='tbl2' align='center' width='".$wi."%'><strong>1/".$ro."</strong></td>";
$ro = $ro/2;
}
echo "<td class='tbl2' align='center' width='".$wi."'><strong>winner</strong></td>"; 
echo "</tr></table>";

echo "<table><tr valign='top'>";
		for ($i=1;$i<=$rounds;$i++) {
echo "<td><table width='170'><tr><td height='".$f_heigth[$i]."'></td></tr>";
			for ($j=1;$j<=($max/pow(2,$i));$j++) {
// brackets
$m = dbarray(dbquery("SELECT * FROM ".DB_T_MATCHES." WHERE match_tour='".$tid."' AND match_round='".$i."' AND match_match='".$j."'"));

// vs
/*if (checkrights("T") || $userdata['user_id'] == $m['match_pl1'] || $userdata['user_id'] == $m['match_pl2']) {*/
$vs = "<a href='".BASEDIR."tourney.php?p=result&id=".$m['match_id']."'>".($m['match_played'] == "1" ? $m['match_score1'].":".$m['match_score2'] : "vs")."</a>";
/*} else {
$vs = $m['match_played'] == "1" ? $m['match_score1'].":".$m['match_score2'] : "vs";
}*/

// pl1
if ($m['match_pl1'] == "0") {
$pl1 = "TBA";
} elseif ($m['match_pl1'] == $bye) {
$pl1 = "freeslot";
} else {
$u1 = dbarray(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$m['match_pl1']."'"));
$pl1 = "<a href='".BASEDIR."profile.php?lookup=".$m['match_pl1']."'>".($m['match_winner'] == $m['match_pl1'] ? "<strong>".$u1['user_name']."</strong>" : $u1['user_name'])."</a>";
}

// pl2
if ($m['match_pl2'] == "0") {
$pl2 = "TBA";
} elseif ($m['match_pl2'] == $bye) {
$pl2 = "freeslot";
} else {
$u2 = dbarray(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$m['match_pl2']."'"));
$pl2 = "<a href='".BASEDIR."profile.php?lookup=".$m['match_pl2']."'>".($m['match_winner'] == $m['match_pl2'] ? "<strong>".$u2['user_name']."</strong>" : $u2['user_name'])."</a>";
} 


echo "<tr><td class='tbl2' width='100%' height='30'>".$pl1."</td></tr>";
echo "<tr><td align='right' width='100%' height='".$m_heigth[$i]."'>".$vs."</td></tr>"; 
echo "<tr><td class='tbl2' width='100%' height='30'>".$pl2."</td></tr>"; 

if ($j < ($max/pow(2,$i))) {
echo "<tr><td width='100%' height='".$m_heigth[$i]."'></td></tr>"; 
}

}
echo "</table></td>";
} 
//winner
if ($tour['tour_w1'] != "0") {
$uv = dbarray(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$tour['tour_w1']."'")); 
$winner = "<a href='".BASEDIR."profile.php?lookup=".$tour['tour_w1']."'><strong>".$uv['user_name']."</strong></a>";
} else {
$winner = "TBA";
}
echo "<td valign='middle'><table width='170'><tr><td class='tbl2' width='100%' height='30'>".$winner."</td></tr></table></td>";
echo "</tr></table><br /><br /><br />";


// 3-4
$z = $rounds+1;
$f34 = dbarray(dbquery("SELECT * FROM ".DB_T_MATCHES." WHERE match_tour='".$tid."' AND match_round='".$z."' AND match_match='1'"));
// vs
if (checkrights("T") || $userdata['user_id'] == $f34['match_pl1'] || $userdata['user_id'] == $f34['match_pl2']) {
$vs34 = "<a href='".BASEDIR."tourney.php?p=result&id=".$f34['match_id']."'>".($f34['match_played'] == "1" ? $f34['match_score1'].":".$f34['match_score2'] : "vs")."</a>";
} else {
$vs34 = $f34['match_played'] == "1" ? $f34['match_score1'].":".$f34['match_score2'] : "vs";
}

// pl1
if ($f34['match_pl1'] == "0") {
$pl134 = "TBA";
} elseif ($f34['match_pl1'] == $bye) {
$pl134 = "freeslot";
} else {
$u134 = dbarray(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$f34['match_pl1']."'"));
$pl134 = "<a href='".BASEDIR."profile.php?lookup=".$f34['match_pl1']."'>".($f34['match_winner'] == $f34['match_pl1'] ? "<strong>".$u134['user_name']."</strong>" : $u134['user_name'])."</a>";
}

// pl2
if ($f34['match_pl2'] == "0") {
$pl234 = "TBA";
} elseif ($f34['match_pl2'] == $bye) {
$pl234 = "freeslot";
} else {
$u234 = dbarray(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$f34['match_pl2']."'"));
$pl234 = "<a href='".BASEDIR."profile.php?lookup=".$f34['match_pl2']."'>".($f34['match_winner'] == $f34['match_pl2'] ? "<strong>".$u234['user_name']."</strong>" : $u234['user_name'])."</a>";
} 
if ($f34['match_winner'] != "0" && $f34['match_winner'] != $bye) {
$winner34 = $f34['match_winner'] == $f34['match_pl1'] ? $pl134 : $pl234;
} elseif ($f34['match_winner'] == "0") {
$winner34 = "TBA";
} elseif ($f34['match_winner'] == $bye) {
$winner34 = "freeslot";
} 
echo "<table width='340'><tr><td class='tbl2' align='center' colspan='2'><strong>3-4th place</strong></td></tr>";
echo "<tr><td width='170'><table width='100%'><tr><td class='tbl2' width='100%' height='30'>".$pl134."</td></tr><tr><td height='30' align='right'>".$vs34."</td></tr><tr><td class='tbl2' height='30'>".$pl234."</td></tr></table></td><td><table width='100%'><tr><td width='100%' height='30'></td></tr><tr><td height='30' class='tbl2'>".$winner34."</td></tr><tr><td height='30'></td></tr></table></td></tr>";
echo "</table>";
closetable();
} else {
echo "invalid id";
}

?>
