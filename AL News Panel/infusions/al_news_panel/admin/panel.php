<?php
if (!defined("IN_FUSION")) die("fu");

if (isset($_POST['csave'])) {
for ($i=1;$i<=6;$i++) {
$cname[$i] = trim(stripinput($_POST['cname'.$i]));
$cenable[$i] = $_POST['cenable'.$i];
$cmax[$i] = isnum(trim($_POST['cmax'.$i])) ? trim($_POST['cmax'.$i]) : 15; 
$crss[$i] = trim(stripinput($_POST['crss'.$i])) != "http://" ? trim(stripinput($_POST['crss'.$i])) : "";
$crssimg[$i] = trim(stripinput($_POST['crssimg'.$i])) != "http://" ? trim(stripinput($_POST['crssimg'.$i])) : "";
$clink[$i] = trim(stripinput($_POST['clink'.$i])) != "http://" ? trim(stripinput($_POST['clink'.$i])) : ""; 
}

$update = dbquery("UPDATE ".DB_AN_COLUMNS." SET column1_name='".$cname[1]."', column1_enable='".$cenable[1]."', column1_max='".$cmax[1]."', column1_rss='".$crss[1]."', column1_rss_img='".$crssimg[1]."', column1_link='".$clink[1]."', column2_name='".$cname[2]."', column2_enable='".$cenable[2]."', column2_max='".$cmax[2]."', column2_rss='".$crss[2]."', column2_rss_img='".$crssimg[2]."', column2_link='".$clink[2]."', column3_name='".$cname[3]."', column3_enable='".$cenable[3]."', column3_max='".$cmax[3]."', column3_rss='".$crss[3]."', column3_rss_img='".$crssimg[3]."', column3_link='".$clink[3]."', column4_name='".$cname[4]."', column4_enable='".$cenable[4]."', column4_max='".$cmax[4]."', column4_rss='".$crss[4]."', column4_rss_img='".$crssimg[4]."', column4_link='".$clink[4]."', column5_name='".$cname[5]."', column5_enable='".$cenable[5]."', column5_max='".$cmax[5]."', column5_rss='".$crss[5]."', column5_rss_img='".$crssimg[5]."', column5_link='".$clink[5]."', column6_name='".$cname[6]."', column6_enable='".$cenable[6]."', column6_max='".$cmax[6]."', column6_rss='".$crss[6]."', column6_rss_img='".$crssimg[6]."', column6_link='".$clink[6]."'");

/*print_r($cname);
print_r($cenable);
print_r($cmax);
print_r($crss);*/
redirect(INFUSIONS."al_news_panel/admin/index.php".$aidlink."&p=panel");
}

$panel = dbarray(dbquery("SELECT * FROM ".DB_AN_COLUMNS.""));
//print_r($panel);

opentable($locale['an5']);
echo "<form name='inputform' method='post'><table width='100%'>";
for ($i=1;$i<=6;$i++) {
echo "<tr><td class='tbl2' colspan='2'><strong>".$locale['an10'].$i."</strong></td></tr>";
echo "<tr><td class='tbl2' width='250'>".$locale['an11']."</td><td class='tbl2'><input type='text' class='textbox' style='width:250px;' name='cname".$i."' value='".$panel['column'.$i.'_name']."' /></td></tr>";
 echo "<tr><td class='tbl2' width='250'>".$locale['an12']."</td><td class='tbl2'><select name='cenable".$i."'>";
for ($j=0;$j<=1;$j++) {
echo "<option value='".$j."'".($j == $panel['column'.$i.'_enable'] ? " selected='selected'" : "").">".($j == 0 ? $locale['an16'] : $locale['an15'])."</option>";
}
echo "</select></td></tr>";
 echo "<tr><td class='tbl2' width='250'>".$locale['an13']."</td><td class='tbl2'><input type='text' class='textbox' size='2' name='cmax".$i."' value='".$panel['column'.$i.'_max']."' /></td></tr>";
 echo "<tr><td class='tbl2' width='250'>".$locale['an14']."</td><td class='tbl2'><input type='text' class='textbox' style='width:250px;' name='crss".$i."' value='".($panel['column'.$i.'_rss'] == "" ? "http://" : $panel['column'.$i.'_rss'])."' /></td></tr>"; 
 echo "<tr><td class='tbl2' width='250'>".$locale['an26']."</td><td class='tbl2'>".($panel['column'.$i.'_rss_img'] != "" ? "<img src='".$panel['column'.$i.'_rss_img']."' /><br />" : "")."<input type='text' class='textbox' style='width:250px;' name='crssimg".$i."' value='".($panel['column'.$i.'_rss_img'] == "" ? "http://" : $panel['column'.$i.'_rss_img'])."' /></td></tr>"; 
 echo "<tr><td class='tbl2' width='250'>".$locale['an27']."</td><td class='tbl2'><input type='text' class='textbox' style='width:250px;' name='clink".$i."' value='".($panel['column'.$i.'_link'] == "" ? "http://" : $panel['column'.$i.'_link'])."' /></td></tr>"; 
}
echo "<tr><td class='tbl2' colspan='2'><input type='submit' class='button' name='csave' value='".$locale['an17']."' /></td></tr>";
echo "</table></form>";
closetable();

?>
