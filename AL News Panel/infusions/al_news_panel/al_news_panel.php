<?php
if (!defined("IN_FUSION")) die("ffuuu");
 if (file_exists(INFUSIONS."al_news_panel/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_news_panel/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_news_panel/locale/Russian.php";
} 

require_once INFUSIONS."al_news_panel/infusion_db.php";
add_to_head("<link rel='stylesheet' type='text/css' href='".INFUSIONS."al_news_panel/asset/style.css' media='screen' />");

$cc = dbarray(dbquery("SELECT * FROM ".DB_AN_COLUMNS.""));

echo "<table width='100%'><tr valign='top'>";
for ($i=1;$i<=6;$i++) {
if ($cc['column'.$i.'_enable'] == "1") {
if ($i > 1) {
echo "<td class='column_spacer'></td>";
}
echo "<td class='column".$i."'>";
echo "<h3 class='column".$i."_capture'>".($cc['column'.$i.'_link'] != "" ? "<a href='".$cc['column'.$i.'_link']."' class='column".$i."_link'>".$cc['column'.$i.'_name']."</a>" : $cc['column'.$i.'_name'])." ".(!empty($cc['column'.$i.'_rss']) ? "<a href='".$cc['column'.$i.'_rss']."'><img src='".$cc['column'.$i.'_rss_img']."' class='column".$i."_rss_img' /></a>" : "")."</h3><br />";
$nns = dbquery("SELECT an.*, nn.news_subject, nn.news_news FROM ".DB_AN_NEWS." an LEFT JOIN ".DB_NEWS." nn ON nn.news_id=an.anews_news WHERE anews_column='".$i."' ORDER BY anews_order ASC");
if (dbrows($nns)) {
while ($nn = dbarray($nns)) {
echo "<a href='".BASEDIR."news.php?readmore=".$nn['anews_news']."' class='column".$i."_subject'>".$nn['news_subject']."</a>";
echo "<p class='column".$i."_text'>".(!empty($nn['news_news']) ? nl2br(stripslashes($nn['news_news'])) : "")."</p>";
echo "<p style='clear:both;'></p>";
}
} else {
echo $locale['an25'];
}
echo "</td>";
}
}
echo "</tr></table>";

?>
