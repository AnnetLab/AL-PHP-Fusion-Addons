<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
if (file_exists(INFUSIONS."al_stats/locale/".$settings['locale'].".php")) {
    require_once INFUSIONS."al_stats/locale/".$settings['locale'].".php";
} else {
    require_once INFUSIONS."al_stats/locale/Russian.php";
}
require_once INFUSIONS."al_stats/infusion_db.php";

$colors = array(1=>"green",0=>"red",2=>"black");

if (!isset($_GET['rowstart'])) $_GET['rowstart'] = 0;
$count = dbcount("(stat_id)",DB_ST_STATS);

opentable($locale['st30']);
$result = dbquery("SELECT st.*, gm.*, tm.* FROM ".DB_ST_STATS." st LEFT JOIN ".DB_ST_GAMES." gm ON gm.game_id=st.stat_game LEFT JOIN ".DB_USER_GROUPS." tm ON tm.group_id=st.stat_team ORDER BY stat_date DESC LIMIT ".$_GET['rowstart'].",100");
if (dbrows($result)) {
    echo "<table width='100%'>";
    echo "<tr align='center'><td class='tbl2' width='1%'>#</td><td class='tbl2' width='10%'>".$locale['st27']."</td><td class='tbl2'>".$locale['st22']."</td><td class='tbl2' width='5%'>".$locale['st24']."</td><td class='tbl2'>".$locale['st23']."</td><td class='tbl2' width='20%'>".$locale['st25']."</td><td class='tbl2' width='20%'>".$locale['st26']."</td></tr>";
    while ($data=dbarray($result)) {
        echo "<tr><td class='tbl2' width='1%'>".$data['stat_id']."</td><td class='tbl2' width='1%'>".date("G:i j-n-Y", $data['stat_date'])."</td><td class='tbl2'>".$data['group_name']."</td><td class='tbl2'><span style='color:".$colors[$data['stat_result']].";'>".$data['stat_ownscore']."-".$data['stat_oppscore']."</td><td class='tbl2'>".$data['stat_opp']."</td><td class='tbl2' width='1%'>".$data['stat_ivent']."</span></td><td class='tbl2' width='1%'>".$data['game_title']."</td></tr>";
    }
    echo "</table>";
} else {
    echo $locale['st21'];
}
echo makepagenav($_GET['rowstart'], 100, $count, 3, FUSION_SELF."?");
closetable();



require_once THEMES."templates/footer.php";
?>
