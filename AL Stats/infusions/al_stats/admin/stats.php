<?php
if (!defined("IN_FUSION")) die("access denied!");

if (isset($_GET['delete']) && isnum($_GET['delete'])) {
    $delete = dbquery("DELETE FROM ".DB_ST_STATS." WHERE stat_id='".$_GET['delete']."'");
    redirect(INFUSIONS."al_stats/admin/index.php".$aidlink."&p=stats");
}

if (isset($_POST['save'])) {
    $ivent = trim(stripinput($_POST['ivent']));
    $opp = trim(stripinput($_POST['opp']));
    $ownscore = trim(stripinput($_POST['ownscore']));
    $oppscore = trim(stripinput($_POST['oppscore']));
    $date = mktime($_POST['hour'],$_POST['min'],0,$_POST['month'],$_POST['day'],$_POST['year']);
    if ($ivent != "" && $opp != "" && $ownscore != "" && isnum($ownscore) && $oppscore != "" && isnum($oppscore)) {
        if ($ownscore == $oppscore) {
            $winner = 2;
        } else {
            $winner = $ownscore > $oppscore ? 1 : 0;
        }
    if ($_POST['isedit'] == "1") {
        $update = dbquery("UPDATE ".DB_ST_STATS." SET stat_ivent='".$ivent."',stat_opp='".$opp."',stat_ownscore='".$ownscore."',stat_oppscore='".$oppscore."',stat_date='".$date."',stat_team='".$_POST['team']."',stat_game='".$_POST['game']."',stat_result='".$winner."' WHERE stat_id='".$_POST['sid']."'");
    } else {
        $insert = dbquery("INSERT INTO ".DB_ST_STATS." (stat_ivent,stat_opp,stat_ownscore,stat_oppscore,stat_date,stat_team,stat_game,stat_result) VALUES ('".$ivent."','".$opp."','".$ownscore."','".$oppscore."','".$date."','".$_POST['team']."','".$_POST['game']."','".$winner."')");
    }
    }
    //print_r($_POST);
    redirect(INFUSIONS."al_stats/admin/index.php".$aidlink."&p=stats");
}

opentable($locale['st19']);
if (isset($_GET['edit']) && isnum($_GET['edit'])) {
    $check = dbquery("SELECT * FROM ".DB_ST_STATS." WHERE stat_id='".$_GET['edit']."'");
    if (dbrows($check)) {
        $edited = dbarray($check);
        $edit['ivent'] = $edited['stat_ivent'];
        $edit['opp'] = $edited['stat_opp'];
        $game_sel = $edited['stat_game'];
        $team_sel = $edited['stat_team'];
        $edit['ownscore'] = $edited['stat_ownscore'];
        $edit['oppscore'] = $edited['stat_oppscore'];
        $edit['min'] = date("i", $edited['stat_date']);
        $edit['hour'] = date("G", $edited['stat_date']);
        $edit['day'] = date("j", $edited['stat_date']);
        $edit['month'] = date("n", $edited['stat_date']);
        $edit['year'] = date("Y", $edited['stat_date']);
        $hide = "<input type='hidden' name='sid' value='".$_GET['edit']."' /><input type='hidden' name='isedit' value='1' />";
    } else {
        die("invalid id");
    }
} else {
        $edit['ivent'] = "";
        $edit['opp'] = "";
        $game_sel = 0;
        $team_sel = 0;
        $edit['ownscore'] = 0;
        $edit['oppscore'] = 0;
        $now = time()+(($settings['timeoffset']+$settings['serveroffset'])*3600);
        $edit['min'] = date("i", $now);
        $edit['hour'] = date("G", $now);
        $edit['day'] = date("j", $now);
        $edit['month'] = date("n", $now);
        $edit['year'] = date("Y", $now);
    $hide = "<input type='hidden' name='isedit' value='0' />";
}
echo "<form name='fsdf' method='post'><table width='100%'>
    <tr><td class='tbl2' width='250'>".$locale['st25']."</td><td class='tbl2'><input type='text' class='textbox' name='ivent' value='".$edit['ivent']."' style='width:250px;' />
    ".$hide."</td></tr>
    <tr><td class='tbl2'>".$locale['st26']."</td><td class='tbl2'>";
    $games = dbquery("SELECT * FROM ".DB_ST_GAMES."");
    echo "<select name='game'>";
    while ($game=dbarray($games)) {
        echo "<option value='".$game['game_id']."'".(isset($game_sel) && $game_sel == $game['game_id'] ? " selected='selected'" : "").">".$game['game_title']."</option>";
    }
    echo "</select>";
    echo "</td></tr>
    <tr><td class='tbl2'>".$locale['st22']."</td><td class='tbl2'>";
    $teams = dbquery("SELECT * FROM ".DB_USER_GROUPS."");
    echo "<select name='team'>";
    while ($team=dbarray($teams)) {
        echo "<option value='".$team['group_id']."'".(isset($team_sel) && $team_sel == $team['group_id'] ? " selected='selected'" : "").">".$team['group_name']."</option>";
    }
    echo "</select>";
    echo "</td></tr>
    <tr><td class='tbl2'>".$locale['st23']."</td><td class='tbl2'><input type='text' class='textbox' name='opp' value='".$edit['opp']."' style='width:250px;' /></td></tr>
    <tr><td class='tbl2'>".$locale['st24']."</td><td class='tbl2'><input type='text' class='textbox' name='ownscore' value='".$edit['ownscore']."' style='width:30px;' /> : <input type='text' class='textbox' name='oppscore' value='".$edit['oppscore']."' style='width:30px;' /></td></tr>
    <tr><td class='tbl2'>".$locale['st27']."</td><td class='tbl2'>";
    echo "<select name='hour'>";
    for ($i=0;$i<=23;$i++) {
        echo "<option value='".$i."'".($edit['hour'] == $i ? " selected='selected'" : "").">".$i."</option>";
    }
    echo "</select>:<select name='min'>";
    for ($i=0;$i<=9;$i++) {
        $x = "0".$i;
        echo "<option value='".$x."'".($edit['min'] == $x ? " selected='selected'" : "").">0".$i."</option>";
    }
    for ($i=10;$i<=59;$i++) {
        echo "<option value='".$i."'".($edit['min'] == $i ? " selected='selected'" : "").">".$i."</option>";
    }
    echo "</select> <select name='day'>";
    for ($i=1;$i<=31;$i++) {
        echo "<option value='".$i."'".($edit['day'] == $i ? " selected='selected'" : "").">".$i."</option>";
    }
    echo "</select><select name='month'>";
    for ($i=1;$i<=12;$i++) {
        echo "<option value='".$i."'".($edit['month'] == $i ? " selected='selected'" : "").">".$i."</option>";
    }
    echo "</select><select name='year'>";
    for ($i=2010;$i<=2020;$i++) {
        echo "<option value='".$i."'".($edit['year'] == $i ? " selected='selected'" : "").">".$i."</option>";
    }
    echo "</select>";
    echo "</td></tr>
    <tr><td class='tbl2' colspan='2'><input type='submit' class='button' name='save' value='".$locale['st15']."' /></td></tr></table></form>";
closetable();

opentable($locale['st20']);
$result = dbquery("SELECT st.*, gm.*, tm.* FROM ".DB_ST_STATS." st LEFT JOIN ".DB_ST_GAMES." gm ON gm.game_id=st.stat_game LEFT JOIN ".DB_USER_GROUPS." tm ON tm.group_id=st.stat_team ORDER BY stat_id DESC");
if (dbrows($result)) {
    echo "<table width='100%'>";
    echo "<tr><td class='tbl2' width='1%'>#</td><td class='tbl2'>".$locale['st22']."</td><td class='tbl2'>".$locale['st23']."</td><td class='tbl2'>".$locale['st24']."</td><td class='tbl2' width='10%'>".$locale['st25']."</td><td class='tbl2' width='10%'>".$locale['st26']."</td><td class='tbl2' width='10%'>".$locale['st27']."</td><td class='tbl2' align='center' width='10%'>".$locale['st14']."</td></tr>";
    while ($data=dbarray($result)) {
        echo "<tr><td class='tbl2' width='1%'>".$data['stat_id']."</td><td class='tbl2'>".$data['group_name']."</td><td class='tbl2'>".$data['stat_opp']."</td><td class='tbl2'>".$data['stat_ownscore']."-".$data['stat_oppscore']."</td><td class='tbl2' width='1%'>".$data['stat_ivent']."</td><td class='tbl2' width='1%'>".$data['game_title']."</td><td class='tbl2' width='1%'>".date("G:i j-n-Y", $data['stat_date'])."</td><td class='tbl2' align='center' width='10%'><a href='".INFUSIONS."al_stats/admin/index.php".$aidlink."&p=stats&edit=".$data['stat_id']."'><img src='".IMAGES."edit.png' border='0' /></a> <a href='".INFUSIONS."al_stats/admin/index.php".$aidlink."&p=stats&delete=".$data['stat_id']."'><img src='".IMAGES."no.png' border='0' /></a></td></tr>";
    }
    echo "</table>";
} else {
    echo $locale['st21'];
}
closetable();

?>
