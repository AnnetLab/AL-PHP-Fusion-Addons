<?php
require_once "../../maincore.php";
require_once THEMES."templates/admin_header.php";
require_once INFUSIONS."al_calendar_panel/infusion_db.php";
if (file_exists(INFUSIONS."al_calendar_panel/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_calendar_panel/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_calendar_panel/locale/Russian.php";
}

if (!checkAdminPageAccess("ALCR")) redirect(START_PAGE);

if (isset($_POST['update'])) {

    $user_group = isset($_POST['user_group']) && isnum($_POST['user_group']) ? $_POST['user_group'] : 0;
    $admin_group = isset($_POST['admin_group']) && isnum($_POST['admin_group']) ? $_POST['admin_group'] : 0;
    $result = dbquery("UPDATE ".DB_AL_CALENDAR_SETTINGS." SET calendar_user_group='".$user_group."', calendar_admin_group='".$admin_group."'");
    redirect(FUSION_SELF.$aidlink);

}

if (isset($_POST['add_admin'])) {

    if (isset($_POST['user_id']) && isnum($_POST['user_id'])) {
        if (dbrows(dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$_POST['user_id']."'"))) {
            if (!dbrows(dbquery("SELECT * FROM ".DB_AL_CALENDAR_ADMINS." WHERE alcr_admin_user='".$_POST['user_id']."'"))) {
                dbquery("INSERT INTO ".DB_AL_CALENDAR_ADMINS." (alcr_admin_user) VALUES ('".$_POST['user_id']."')");
            }
        }
    }
    redirect(FUSION_SELF.$aidlink);

}

if (isset($_GET['action']) && $_GET['action'] == "del" && isset($_GET['id']) && isnum($_GET['id'])) {

    if (dbrows(dbquery("SELECT * FROM ".DB_AL_CALENDAR_ADMINS." WHERE alcr_admin_id='".$_GET['id']."'"))) {
        dbquery("DELETE FROM ".DB_AL_CALENDAR_ADMINS." WHERE alcr_admin_id='".$_GET['id']."'");
    }
    redirect(FUSION_SELF.$aidlink);

}




opentable($locale['alcr3']);

echo "<form method='post'>";
    echo $locale['alcr10']."<br />";
    echo "<input type='text' class='textbox' name='user_id' /> <input type='submit' name='add_admin' class='button' value='".$locale['alcr9']."' />";
echo "</form>";

$result = dbquery("SELECT cu.*, u.user_name FROM ".DB_AL_CALENDAR_ADMINS." cu LEFT JOIN ".DB_USERS." u ON u.user_id=cu.alcr_admin_user");
if (dbrows($result)) {
    echo $locale['alcr4'];
    echo "<br /><br /><table width='100%'>";
        echo "<tr>";
            echo "<td class='tbl2' width='1%'>id</td>";
            echo "<td class='tbl2'>".$locale['alcr6']."</td>";
            echo "<td class='tbl2' width='200'>".$locale['alcr7']."</td>";
        echo "</tr>";
        while ($data=dbarray($result)) {
            echo "<tr>";
                echo "<td class='tbl1' width='1%'>".$data['alcr_admin_user']."</td>";
                echo "<td class='tbl1'>".$data['user_name']."</td>";
                echo "<td class='tbl1' width='200'><a href='".FUSION_SELF.$aidlink."&action=del&id=".$data['alcr_admin_id']."'>".$locale['alcr8']."</a></td>";
            echo "</tr>";
        }
    echo "</table>";
} else {
    echo "<br /><br />".$locale['alcr5'];
}

closetable();

$result = dbquery("SELECT * FROM ".DB_USER_GROUPS);
if (dbrows($result)) {
    while ($data=dbarray($result)) {
        $user_groups[$data['group_id']] = $data['group_name'];
    }
} else {
    $user_groups = false;
}

$alcr_settings = dbarray(dbquery("SELECT * FROM ".DB_AL_CALENDAR_SETTINGS));

opentable($locale['alcr11']);
    echo "<form method='post'>";
        echo "<table width='100%'>";
            echo "<tr>";
                echo "<td class='tbl' width='250'>".$locale['alcr14']."</td>";
                echo "<td class='tbl'>";
                    echo "<select name='user_group'>";
                        echo "<option value='0'".($alcr_settings['calendar_user_group'] == 0 ? " selected='selected'" : "").">".$locale['alcr16']."</option>";
                        echo "<option value='101'".($alcr_settings['calendar_user_group'] == 101 ? " selected='selected'" : "").">".$locale['alcr17']."</option>";
                        echo "<option value='102'".($alcr_settings['calendar_user_group'] == 102 ? " selected='selected'" : "").">".$locale['alcr18']."</option>";
                        echo "<option value='103'".($alcr_settings['calendar_user_group'] == 103 ? " selected='selected'" : "").">".$locale['alcr19']."</option>";
                        if ($user_groups) {
                            foreach ($user_groups as $ug_id=>$ug_name) {
                                echo "<option value='".$ug_id."'".($alcr_settings['calendar_user_group'] == $ug_id ? " selected='selected'" : "").">".$ug_name."</option>";
                            }
                        }
                    echo "</select>";
                echo "</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='tbl' width='250'>".$locale['alcr12']."</td>";
                echo "<td class='tbl'>";
                    echo "<select name='admin_group'>";
                            echo "<option value='0'".($alcr_settings['calendar_admin_group'] == 0 ? " selected='selected'" : "").">".$locale['alcr16']."</option>";
                            echo "<option value='101'".($alcr_settings['calendar_admin_group'] == 101 ? " selected='selected'" : "").">".$locale['alcr17']."</option>";
                            echo "<option value='102'".($alcr_settings['calendar_admin_group'] == 102 ? " selected='selected'" : "").">".$locale['alcr18']."</option>";
                            echo "<option value='103'".($alcr_settings['calendar_admin_group'] == 103 ? " selected='selected'" : "").">".$locale['alcr19']."</option>";
                            if ($user_groups) {
                            foreach ($user_groups as $ug_id=>$ug_name) {
                                echo "<option value='".$ug_id."'".($alcr_settings['calendar_admin_group'] == $ug_id ? " selected='selected'" : "").">".$ug_name."</option>";
                            }
                        } else {
                            echo $locale['alcr13']."<input type='hidden' name='admin_group' value='0' />";
                        }
                    echo "</select>";
                echo "</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td colspan='2' class='tbl'><input type='submit' name='update' class='button' value='".$locale['alcr15']."' /></td>";
            echo "</tr>";
        echo "</table>";
    echo "</form>";
closetable();

require_once THEMES."templates/footer.php";
?>