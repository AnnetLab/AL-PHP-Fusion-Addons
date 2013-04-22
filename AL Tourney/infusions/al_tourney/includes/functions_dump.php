<?php

 // 2 - prot predl vremya
function setNoti($mid, $type, $to) {
$m = dbarray(dbquery("SELECT * FROM ".DB_T_MATCHES." WHERE match_id='".$mid."'"));

if ($to == "1" || $to == "2") {
$set = dbquery("INSERT INTO ".DB_T_NOTIFICATIONS." (noti_user, noti_type, noti_match) VALUES ('".$m['match_pl'.$to]."', '".$type."', '".$mid."')");
}
if ($to == "3") {
$set = dbquery("INSERT INTO ".DB_T_NOTIFICATIONS." (noti_user, noti_type, noti_match) VALUES ('".$m['match_pl1']."', '".$type."', '".$mid."')");
$set2 = dbquery("INSERT INTO ".DB_T_NOTIFICATIONS." (noti_user, noti_type, noti_match) VALUES ('".$m['match_pl2']."', '".$type."', '".$mid."')"); 
} 

}

function clearByes($tid) {
		$bye = bye();
		$pl = dbcount("(player_id)", DB_T_PLAYERS, "player_tour='".$tid."' AND player_checkin='1'");
		$max = getMaxPl($pl);
		$rounds = log($max)/log(2);

		for ($i=1;$i<=$rounds;$i++) {
			for ($j=1;$j<=($max/pow(2,$i));$j++) {
			$result = dbquery("SELECT * FROM ".DB_T_MATCHES." WHERE match_round='".$i."' AND match_match='".$j."' AND match_tour='".$tid."'");
			$data = dbarray($result);
				if ($data['match_pl1'] == $bye && $data['match_pl2'] == $bye) {
				EnterResult($tid, $i, $j, 1, 0);
				}
				if ($data['match_pl1'] == $bye && $data['match_pl2'] != $bye && $data['match_pl2'] != 0) {
				EnterResult($tid, $i, $j, 0, 1);
				}
				if ($data['match_pl1'] != $bye && $data['match_pl2'] == $bye && $data['match_pl1'] != 0) {
				EnterResult($tid, $i, $j, 1, 0);
				}
			}
		}
$x = $rounds + 1;
$result2 = dbquery("SELECT * FROM ".DB_T_MATCHES." WHERE match_round='".$x."' AND match_match='1' AND match_tour='".$tid."'");
			$data2 = dbarray($result);
				if ($data2['match_pl1'] == $bye && $data2['match_pl2'] == $bye) {
				EnterResult($tid, $x, 1, 1, 0);
				}
				if ($data2['match_pl1'] == $bye && $data2['match_pl2'] != $bye && $data2['match_pl2'] != 0) {
				EnterResult($tid, $x, 1, 0, 1);
				}
				if ($data2['match_pl1'] != $bye && $data2['match_pl2'] == $bye && $data2['match_pl1'] != 0) {
				EnterResult($tid, $x, 1, 1, 0);
				} 
	
}	 

function EnterResult($tid, $round, $match, $score1, $score2) { 
		$result1 = dbquery("SELECT * FROM ".DB_T_MATCHES." WHERE match_round='".$round."' AND match_match='".$match."' AND match_tour='".$tid."'");
		$data1 = dbarray($result1);
		if ($score1 > $score2) {$winner = $data1['match_pl1']; $looser = $data1['match_pl2'];}
		else {$winner = $data1['match_pl2']; $looser = $data1['match_pl1'];}
		$result = dbquery("UPDATE ".DB_T_MATCHES." SET match_score1='".$score1."', match_score2='".$score2."', match_winner='".$winner."', match_played='1' WHERE match_round='".$round."' AND match_match='".$match."' AND match_tour='".$tid."'");

$pl = dbcount("(player_id)", DB_T_PLAYERS, "player_tour='".$tid."' AND player_checkin='1'");
$max = getMaxPl($pl);
$rounds = log($max)/log(2);

if ($round == $rounds) {
//fin12
$set_w1 = dbquery("UPDATE ".DB_T_TOURS." SET tour_w1='".$winner."', tour_w2='".$looser."' WHERE tour_id='".$tid."'");
$check_f = dbarray(dbquery("SELECT tour_w3, tour_w4 FROM ".DB_T_TOURS." WHERE tour_id='".$tid."'"));
if ($check_f['tour_w3'] != "0" && $check_f['tour_w4'] != "0") {
$fin = dbquery("UPDATE ".DB_T_TOURS." SET tour_finished='1' WHERE tour_id='".$tid."'");
}

} elseif ($round == ($rounds+1)) {
//fin34
$set_w3 = dbquery("UPDATE ".DB_T_TOURS." SET tour_w3='".$winner."', tour_w4='".$looser."' WHERE tour_id='".$tid."'"); 
 $check_f = dbarray(dbquery("SELECT tour_w1, tour_w2 FROM ".DB_T_TOURS." WHERE tour_id='".$tid."'"));
if ($check_f['tour_w1'] != "0" && $check_f['tour_w2'] != "0") {
$fin = dbquery("UPDATE ".DB_T_TOURS." SET tour_finished='1' WHERE tour_id='".$tid."'");
} 
} else {
//just enter
		if ($match % 2 == 1) {
		$nextround = $round + 1;
		$nextmatch = ($match+1)/2;
		$result2 = dbquery("UPDATE ".DB_T_MATCHES." SET match_pl1='".$winner."' WHERE match_round='".$nextround."' AND match_match='".$nextmatch."' AND match_tour='".$tid."'");
 if ($round == ($rounds-1)) {
//looser to fin34
$x = $rounds+1;
$result3 = dbquery("UPDATE ".DB_T_MATCHES." SET match_pl1='".$looser."' WHERE match_round='".$x."' AND match_match='1' AND match_tour='".$tid."'"); 
} 
		}
		if ($match % 2 == 0) {
		$nextround = $round + 1;
		$nextmatch = $match/2;
		$result2 = dbquery("UPDATE ".DB_T_MATCHES." SET match_pl2='".$winner."' WHERE match_round='".$nextround."' AND match_match='".$nextmatch."' AND match_tour='".$tid."'");
 if ($round == ($rounds-1)) {
//looser to fin34
$x = $rounds + 1;
$result2 = dbquery("UPDATE ".DB_T_MATCHES." SET match_pl2='".$looser."' WHERE match_round='".$x."' AND match_match='1' AND match_tour='".$tid."'"); 
} 
		} 

}
} 


function bye() {
	$result = dbquery("SELECT user_id FROM ".DB_USERS." WHERE user_name = 'BYE'");
	$data = dbarray($result);
	return $data['user_id'];
} 

function showAdminNav() {
global $locale, $aidlink;
echo "<div class='center'><button class='button'><a href='".INFUSIONS."al_tourney/admin/index.php".$aidlink."&p=main'>main</a></button> <button class='button'><a href='".INFUSIONS."al_tourney/admin/index.php".$aidlink."&p=atours'>all tournies</a></button> <button class='button'><a href='".INFUSIONS."al_tourney/admin/index.php".$aidlink."&p=newtour'>new tourney</a></button> </div>";
}

function showStatus($id) {
global $locale, $settings;
$data = dbarray(dbquery("SELECT * FROM ".DB_T_TOURS." WHERE tour_id='".$id."'"));
if ($data['tour_finished'] == "1") {
return "finished";
} else {
if ($data['tour_grid'] == "1") {
return "procced";
} else {
$now = time()+($settings['timeoffset']*3600);
$checkin = $data['tour_date'] - 60*30;
if ($now < $checkin) {
return "registration";
} elseif ($now > $checkin && $now < $data['tour_date']) {
return "checkin";
} elseif ($now > $data['tour_date']) {
return "bracket's creating";
} else {
return "err";
}
}
}
}


function getMaxPl($pl, $max=32) {
if ($pl >= $max) {
return $max;
} else {
if ($pl <= 4) {
return 4;
} elseif ($pl > 4 && $pl <= 8) {
return 8;
} elseif ($pl > 8 && $pl <= 16) {
return 16;
} elseif ($pl > 16 && $pl <= 32) {
return 32;
}
}
}

?>
