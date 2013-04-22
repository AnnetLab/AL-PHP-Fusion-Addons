<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: ulogin_admin.php
| Author: Rush
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once "../../maincore.php";
require_once THEMES . "templates/admin_header.php";
include INFUSIONS . "ulogin/infusion_db.php";
if (!defined("IN_FUSION")) die("access denied");
if (file_exists(INFUSIONS . "ulogin/locale/" . $settings['locale'] . "_admin.php")) {
    include INFUSIONS . "ulogin/locale/" . $settings['locale'] . "_admin.php";
} else {
    include INFUSIONS . "ulogin/locale/English_admin.php";
}


if (!checkAdminPageAccess("ULG")) redirect(START_PAGE);

$networks = array(1 => "vkontakte", "facebook", "google", "twitter", "odnoklassniki", "mailru", "yandex", "livejournal", "openid", "lastfm", "linkedin", "liveid", "soundcloud", "steam", "flickr", "vimeo", "youtube", "webmoney");

if (isset($_POST['ula_update'])) {

    $upd = dbquery("UPDATE " . DB_ULOGIN_SETTINGS . " SET u_use_ajax='" . $_POST['u_use_ajax'] . "', u_vkontakte='" . $_POST['u_vkontakte'] . "',u_facebook='" . $_POST['u_facebook'] . "',u_google='" . $_POST['u_google'] . "',u_twitter='" . $_POST['u_twitter'] . "',u_odnoklassniki='" . $_POST['u_odnoklassniki'] . "',u_mailru='" . $_POST['u_mailru'] . "',u_yandex='" . $_POST['u_yandex'] . "',u_livejournal='" . $_POST['u_livejournal'] . "',u_openid='" . $_POST['u_openid'] . "',u_lastfm='" . $_POST['u_lastfm'] . "',u_linkedin='" . $_POST['u_linkedin'] . "',u_liveid='" . $_POST['u_liveid'] . "',u_soundcloud='" . $_POST['u_soundcloud'] . "',u_steam='" . $_POST['u_steam'] . "',u_flickr='" . $_POST['u_flickr'] . "',u_vimeo='" . $_POST['u_vimeo'] . "',u_youtube='" . $_POST['u_youtube'] . "',u_webmoney='" . $_POST['u_webmoney'] . "'");

    redirect(INFUSIONS . "ulogin/ulogin_admin.php" . $aidlink);
}

$usettings = dbarray(dbquery("SELECT * FROM " . DB_ULOGIN_SETTINGS . ""));

opentable($locale['ula3']);
echo "<form method='post' name='gfhhf'>";
echo "<table width='100%'>";

echo "<tr><td class='tbl2' width='250'>" . $locale['ula4'] . "</td><td class='tbl2'><select name='u_use_ajax' disabled='desabled'><option value='1'" . ($usettings['u_use_ajax'] == "1" ? " selected='selected'" : "") . ">" . $locale['ula5'] . "</option><option value='0'" . ($usettings['u_use_ajax'] == "0" ? " selected='selected'" : "") . ">" . $locale['ula6'] . "</option></select></td></tr>";

for ($i = 1; $i <= 18; $i++) {
    echo "<tr><td class='tbl2' width='250'><img src='" . INFUSIONS . "ulogin/img/small/" . $networks[$i] . ".png' /> " . $locale['ulan' . $i] . "</td><td class='tbl2'><select name='u_" . $networks[$i] . "'><option value='1'" . ($usettings['u_' . $networks[$i]] == "1" ? " selected='selected'" : "") . ">" . $locale['ula7'] . "</option><option value='2'" . ($usettings['u_' . $networks[$i]] == "2" ? " selected='selected'" : "") . ">" . $locale['ula8'] . "</option><option value='3'" . ($usettings['u_' . $networks[$i]] == "3" ? " selected='selected'" : "") . ">" . $locale['ula9'] . "</option></select></td></tr>";
}


echo "<tr><td colspan='2' class='tbl2'><input type='submit' class='button' name='ula_update' value='" . $locale['ula20'] . "' /></td></tr>";
echo "</table>";
echo "</form>";
closetable();


require_once THEMES . "templates/footer.php";
?>
