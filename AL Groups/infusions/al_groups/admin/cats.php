<?php
if (!defined("IN_FUSION")) die("access denied!");

if (isset($_GET['delete']) && isnum($_GET['delete'])) {
    $delete = dbquery("DELETE FROM ".DB_GS_CATS." WHERE cat_id='".$_GET['delete']."'");
    redirect(INFUSIONS."al_groups/admin/index.php".$aidlink."&p=cats");
}

if (isset($_POST['save'])) {
    $title = trim(stripinput($_POST['title']));
    if ($_POST['isedit'] == "1") {
        $update = dbquery("UPDATE ".DB_GS_CATS." SET cat_name='".$title."' WHERE cat_id='".$_POST['tid']."'");
    } else {
        $insert = dbquery("INSERT INTO ".DB_GS_CATS." (cat_name) VALUES ('".$title."')");
    }
    redirect(INFUSIONS."al_groups/admin/index.php".$aidlink."&p=cats");
}

opentable($locale['gs10']);
if (isset($_GET['edit']) && isnum($_GET['edit'])) {
    $check = dbquery("SELECT * FROM ".DB_GS_CATS." WHERE cat_id='".$_GET['edit']."'");
    if (dbrows($check)) {
        $edited = dbarray($check);
        $title = $edited['cat_name'];
        $hide = "<input type='hidden' name='tid' value='".$_GET['edit']."' /><input type='hidden' name='isedit' value='1' />";
    } else {
        die("invalid id");
    }
} else {
    $title = "";
    $hide = "<input type='hidden' name='isedit' value='0' />";
}
echo "<form name='fsdf' method='post'><table width='100%'><tr><td class='tbl2' width='250'>".$locale['gs11']."</td><td class='tbl2'><input type='text' class='textbox' name='title' value='".$title."' style='width:250px;' />".$hide."</td></tr><tr><td class='tbl2' colspan='2'><input type='submit' class='button' name='save' value='".$locale['gs12']."' /></td></tr></table></form>";
closetable();

opentable($locale['gs13']);
$result = dbquery("SELECT * FROM ".DB_GS_CATS."");
if (dbrows($result)) {
    echo "<table width='100%'>";
    echo "<tr><td class='tbl2' width='1%'>#</td><td class='tbl2'>".$locale['gs11']."</td><td class='tbl2' align='center' width='10%'>".$locale['gs14']."</td></tr>";
    while ($data=dbarray($result)) {
        echo "<tr><td class='tbl2' width='1%'>".$data['cat_id']."</td><td class='tbl2'>".$data['cat_name']."</td><td class='tbl2' align='center' width='10%'><a href='".INFUSIONS."al_groups/admin/index.php".$aidlink."&p=cats&edit=".$data['cat_id']."'><img src='".IMAGES."edit.png' border='0' /></a> <a href='".INFUSIONS."al_groups/admin/index.php".$aidlink."&p=cats&delete=".$data['cat_id']."'><img src='".IMAGES."no.png' border='0' /></a></td></tr>";
    }
    echo "</table>";
} else {
    echo $locale['gs15'];
}
closetable();

?>
