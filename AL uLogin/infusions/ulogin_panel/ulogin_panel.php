<?php
 /*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: ulogin_panel.php
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
if (!defined("IN_FUSION")) { die("Access Denied"); }

if (iMEMBER) {
	openside($userdata['user_name']);
	$msg_count = dbcount("(message_id)", DB_MESSAGES, "message_to='".$userdata['user_id']."' AND message_read='0'AND message_folder='0'");
	echo THEME_BULLET." <a href='".BASEDIR."edit_profile.php' class='side'>".$locale['global_120']."</a><br />\n";
	echo THEME_BULLET." <a href='".BASEDIR."messages.php' class='side'>".$locale['global_121']."</a><br />\n";
	echo THEME_BULLET." <a href='".BASEDIR."members.php' class='side'>".$locale['global_122']."</a><br />\n";
	if (iADMIN && (iUSER_RIGHTS != "" || iUSER_RIGHTS != "C")) {
		echo THEME_BULLET." <a href='".ADMIN."index.php".$aidlink."' class='side'>".$locale['global_123']."</a><br />\n";
	}
	echo THEME_BULLET." <a href='".BASEDIR."setuser.php?logout=yes' class='side'>".$locale['global_124']."</a>\n";
	if ($msg_count) { echo "<br /><br /><div style='text-align:center'><strong><a href='".BASEDIR."messages.php' class='side'>".sprintf($locale['global_125'], $msg_count).($msg_count == 1 ? $locale['global_126'] : $locale['global_127'])."</a></strong></div>\n"; }
} else {
	openside($locale['global_100']);
	echo "<div style='text-align:center'>\n";
	echo "<form name='loginform' method='post' action='".FUSION_SELF."'>\n";
	echo $locale['global_101']."<br />\n<input type='text' name='user_name' class='textbox' style='width:100px' /><br />\n";
	echo $locale['global_102']."<br />\n<input type='password' name='user_pass' class='textbox' style='width:100px' /><br />\n";
	echo "<input type='checkbox' name='remember_me' value='y' title='".$locale['global_103']."' style='vertical-align:middle;' />\n";
	echo "<input type='submit' name='login' value='".$locale['global_104']."' class='button' /><br />\n";
	echo "</form>\n<br />\n";
	if ($settings['enable_registration']) {
		echo "".$locale['global_105']."<br /><br />\n";
	}
	echo $locale['global_106']."\n</div>\n";

require_once INFUSIONS."ulogin/includes/uinclude.php";
// uLogin div
    echo "<div style='width:100%;text-align:center'>";
        showUloginWidget();
    echo "</div>";
// uLogin div end

}
closeside();
?>
