<?php
require_once "../../maincore.php";
require_once THEMES."templates/admin_header.php";
include INFUSIONS."al_content_panel/infusion_db.php";
if (!defined("IN_FUSION")) die("access denied");
if (file_exists(INFUSIONS."al_content_panel/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_content_panel/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_content_panel/locale/Russian.php";
}


if(!checkAdminPageAccess("CO")) redirect(START_PAGE);

if (isset($_POST['co_update'])) {
$len = isset($_POST['len']) && isnum($_POST['len']) ? $_POST['len'] : 100;
$upd = dbquery("UPDATE ".DB_CO_SETTINGS." SET co_time='".$_POST['time']."', co_len='".$len."', co_news='".$_POST['cnews']."', co_articles='".$_POST['carticles']."', co_comments='".$_POST['ccomments']."', co_photos='".$_POST['cphotos']."', co_downloads='".$_POST['cdownloads']."', co_forums='".$_POST['cforums']."', co_weblinks='".$_POST['cweblinks']."'");
redirect(INFUSIONS."al_content_panel/admin.php".$aidlink);
}

$csettings = dbarray(dbquery("SELECT * FROM ".DB_CO_SETTINGS.""));

opentable($locale['co3']);
echo "<form method='post' name='gfhhf'>";
echo "<table width='100%'>";
echo "<tr><td class='tbl2' width='250'>".$locale['co4']."</td><td class='tbl2'><select name='time'><option value='12'".($csettings['co_time'] == "12" ? " selected='selected'" : "").">12</option><option value='24'".($csettings['co_time'] == "24" ? " selected='selected'" : "").">24</option><option value='48'".($csettings['co_time'] == "48" ? " selected='selected'" : "").">48</option><option value='72'".($csettings['co_time'] == "72" ? " selected='selected'" : "").">72</option></select></td></tr>";
echo "<tr><td class='tbl2' width='250'>".$locale['co5']."</td><td class='tbl2'><input type='text' name='len' class='textbox' size='3' value='".$csettings['co_len']."' /></td></tr>"; 
 echo "<tr><td class='tbl2' width='250'>".$locale['co6']."</td><td class='tbl2'><select name='cnews'><option value='1'".($csettings['co_news'] == "1" ? " selected='selected'" : "").">Yes</option><option value='0'".($csettings['co_news'] == "0" ? " selected='selected'" : "").">No</option></select></td></tr>"; 
 echo "<tr><td class='tbl2' width='250'>".$locale['co7']."</td><td class='tbl2'><select name='carticles'><option value='1'".($csettings['co_articles'] == "1" ? " selected='selected'" : "").">Yes</option><option value='0'".($csettings['co_articles'] == "0" ? " selected='selected'" : "").">No</option></select></td></tr>"; 
 echo "<tr><td class='tbl2' width='250'>".$locale['co8']."</td><td class='tbl2'><select name='ccomments'><option value='1'".($csettings['co_comments'] == "1" ? " selected='selected'" : "").">Yes</option><option value='0'".($csettings['co_comments'] == "0" ? " selected='selected'" : "").">No</option></select></td></tr>"; 
 echo "<tr><td class='tbl2' width='250'>".$locale['co9']."</td><td class='tbl2'><select name='cforums'><option value='1'".($csettings['co_forums'] == "1" ? " selected='selected'" : "").">Yes</option><option value='0'".($csettings['co_forums'] == "0" ? " selected='selected'" : "").">No</option></select></td></tr>"; 
 echo "<tr><td class='tbl2' width='250'>".$locale['co10']."</td><td class='tbl2'><select name='cphotos'><option value='1'".($csettings['co_photos'] == "1" ? " selected='selected'" : "").">Yes</option><option value='0'".($csettings['co_photos'] == "0" ? " selected='selected'" : "").">No</option></select></td></tr>"; 
 echo "<tr><td class='tbl2' width='250'>".$locale['co11']."</td><td class='tbl2'><select name='cdownloads'><option value='1'".($csettings['co_downloads'] == "1" ? " selected='selected'" : "").">Yes</option><option value='0'".($csettings['co_downloads'] == "0" ? " selected='selected'" : "").">No</option></select></td></tr>"; 
 echo "<tr><td class='tbl2' width='250'>".$locale['co12']."</td><td class='tbl2'><select name='cweblinks'><option value='1'".($csettings['co_weblinks'] == "1" ? " selected='selected'" : "").">Yes</option><option value='0'".($csettings['co_weblinks'] == "0" ? " selected='selected'" : "").">No</option></select></td></tr>"; 
echo "<tr><td colspan='2' class='tbl2'><input type='submit' class='button' name='co_update' value='".$locale['co13']."' /></td></tr>";
echo "</table>";
echo "</form>";
closetable();

require_once THEMES."templates/footer.php";
?>
