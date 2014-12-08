<?php defined("IN_FUSION") or die();

if (isset($_POST['update'])) {

    $moderate = in_array($_POST['alb_moderate'],array(0,1)) ? $_POST['alb_moderate'] : 0;
    dbquery("UPDATE ".DB_AL_BLOG_SETTINGS." SET alb_settings_moderate='".$moderate."'");
    redirect(FUSION_SELF.$aidlink."&p=settings");

}

$alb_settings = dbarray(dbquery("SELECT * FROM ".DB_AL_BLOG_SETTINGS));

opentable($locale['alb26']);
echo "<form method='post'>";
echo "<table width='100%'>";
    echo "<tr>";
        echo "<td class='tbl' width='280'>".$locale['alb27']."</td>";
        echo "<td class='tbl'><select name='alb_moderate' class='textbox'><option value='1'".($alb_settings['alb_settings_moderate'] == 1 ? " selected='selected'" : "").">".$locale['alb28']."</option><option value='0'".($alb_settings['alb_settings_moderate'] == 0 ? " selected='selected'" : "").">".$locale['alb29']."</option></select></td>";
    echo "</tr>";
    echo "<tr>";
        echo "<td class='tbl' colspan='2'><input type='submit' class='button' name='update' value='".$locale['alb30']."' /></td>";
    echo "</tr>";
echo "</table>";
echo "</form>";
closetable();

?>