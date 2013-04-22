<?php
if (!defined("IN_FUSION")) die("access denied!");

if (isset($_GET['delete']) && isnum($_GET['delete'])) {
    $delete = dbquery("DELETE FROM ".DB_ST_TEAMS." WHERE team_id='".$_GET['delete']."'");
    redirect(INFUSIONS."al_stats/admin/index.php".$aidlink."&p=teams");
}

if (isset($_POST['save'])) {
    $title = trim(stripinput($_POST['title']));
    if ($_POST['isedit'] == "1") {
        $update = dbquery("UPDATE ".DB_ST_TEAMS." SET team_title='".$title."' WHERE team_id='".$_POST['tid']."'");
    } else {
        $insert = dbquery("INSERT INTO ".DB_ST_TEAMS." (team_title) VALUES ('".$title."')");
    }
    redirect(INFUSIONS."al_stats/admin/index.php".$aidlink."&p=teams");
}

opentable($locale['st10']);
if (isset($_GET['edit']) && isnum($_GET['edit'])) {
    $check = dbquery("SELECT * FROM ".DB_ST_TEAMS." WHERE team_id='".$_GET['edit']."'");
    if (dbrows($check)) {
        $edited = dbarray($check);
        $title = $edited['team_title'];
        $hide = "<input type='hidden' name='tid' value='".$_GET['edit']."' /><input type='hidden' name='isedit' value='1' />";
    } else {
        die("invalid id");
    }
} else {
    $title = "";
    $hide = "<input type='hidden' name='isedit' value='0' />";
}
echo "<form name='fsdf' method='post'><table width='100%'><tr><td class='tbl2' width='250'>".$locale['st13']."</td><td class='tbl2'><input type='text' class='textbox' name='title' value='".$title."' style='width:250px;' />".$hide."</td></tr><tr><td class='tbl2' colspan='2'><input type='submit' class='button' name='save' value='".$locale['st15']."' /></td></tr></table></form>";
closetable();

opentable($locale['st11']);
$result = dbquery("SELECT * FROM ".DB_ST_TEAMS."");
if (dbrows($result)) {
    echo "<table width='100%'>";
    echo "<tr><td class='tbl2' width='1%'>#</td><td class='tbl2'>".$locale['st13']."</td><td class='tbl2' align='center' width='10%'>".$locale['st14']."</td></tr>";
    while ($data=dbarray($result)) {
        echo "<tr><td class='tbl2' width='1%'>".$data['team_id']."</td><td class='tbl2'>".$data['team_title']."</td><td class='tbl2' align='center' width='10%'><a href='".INFUSIONS."al_stats/admin/index.php".$aidlink."&p=teams&edit=".$data['team_id']."'><img src='".IMAGES."edit.png' border='0' /></a> <a href='".INFUSIONS."al_stats/admin/index.php".$aidlink."&p=teams&delete=".$data['team_id']."'><img src='".IMAGES."no.png' border='0' /></a></td></tr>";
    }
    echo "</table>";
} else {
    echo $locale['st12'];
}
closetable();

?>