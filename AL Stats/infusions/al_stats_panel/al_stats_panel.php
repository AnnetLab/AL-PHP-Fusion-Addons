<?php
if (!defined("IN_FUSION")) die("access denied");
if (file_exists(INFUSIONS."al_stats/locale/".$settings['locale'].".php")) {
    require_once INFUSIONS."al_stats/locale/".$settings['locale'].".php";
} else {
    require_once INFUSIONS."al_stats/locale/Russian.php";
}
require_once INFUSIONS."al_stats/infusion_db.php";

openside($locale['st30']);
$stotal = dbcount("(stat_id)",DB_ST_STATS);
$swin = dbcount("(stat_id)",DB_ST_STATS,"stat_result='1'");
$slose = dbcount("(stat_id)",DB_ST_STATS,"stat_result='0'");
$sdraw = dbcount("(stat_id)",DB_ST_STATS,"stat_result='2'");
$swinp = round(($swin/$stotal)*100);
$slosep = round(($slose/$stotal)*100);
$sdrawp = round(($sdraw/$stotal)*100);

echo $locale['st31'].$stotal."<br />";
echo $locale['st32'].$swinp."%<br />";
echo $locale['st33'].$slosep."%<br />";
echo $locale['st34'].$sdrawp."%<br />";
echo "<a href='".BASEDIR."statistics.php'>".$locale['st35']."</a>";

closeside();
?>