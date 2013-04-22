<?php
if (!defined("IN_FUSION")) die("fu");

if (isset($_GET['d']) && isnum($_GET['d'])) {
    $check = dbquery("SELECT * FROM ".DB_RM_FORM_FIELDS." WHERE ff_id='".$_GET['d']."'");
    if (dbrows($check)) {
        $data = dbarray($check);
        $del1 = dbquery("DELETE FROM ".DB_RM_FORM_FIELDS." WHERE ff_id='".$_GET['d']."'");
        $del2 = dbquery("ALTER TABLE ".DB_RM_FORM_APPS." DROP fa_".$data['ff_name']."");
    }
    redirect(INFUSIONS."al_register_mod/admin/index.php?p=form");
}

if (isset($_POST['save'])) {
    $title = trim(stripinput($_POST['ff_title']));
    $id = $_POST['id'];
    $infobox = $_POST['ff_infobox'] ? trim(stripinput($_POST['ff_infobox'])) : "";
    $max_order = dbcount("(ff_id)",DB_RM_FORM_FIELDS);
    $qweqwe = dbarray(dbquery("SELECT ff_order FROM ".DB_RM_FORM_FIELDS." WHERE ff_id='".$id."'"));
    $old_order = $qweqwe['ff_order'];
    if (isset($_POST['ff_order']) && isnum($_POST['ff_order']) && $_POST['ff_order'] != "0") {
        if ($_POST['ff_order'] > $max_order) {
            $order = $max_order;
        } else {
            $order = $_POST['ff_order'];
        }
    } else {
        $order = 1;
    }
    
    if ($order > $max_order) {
        $new_order = $max_order;
        $upd_order = dbquery("UPDATE ".DB_RM_FORM_FIELDS." SET ff_order='".$new_order."' WHERE ff_id='".$id."'");
        $upd_order_all = dbquery("UPDATE ".DB_RM_FORM_FIELDS." SET ff_order=ff_order-1 WHERE ff_order>'".$old_order."' AND ff_id<>'".$id."'");
    } else {
        if ($order > $old_order) {
            $upd_order = dbquery("UPDATE ".DB_RM_FORM_FIELDS." SET ff_order='".$order."' WHERE ff_id='".$id."'"); 
            $upd_order_all = dbquery("UPDATE ".DB_RM_FORM_FIELDS." SET ff_order=ff_order-1 WHERE ff_order>'".$old_order."' AND ff_order<='".$order."' AND ff_id<>'".$id."'");
        } else {
            $upd_order = dbquery("UPDATE ".DB_RM_FORM_FIELDS." SET ff_order='".$order."' WHERE ff_id='".$id."'"); 
            $upd_order_all = dbquery("UPDATE ".DB_RM_FORM_FIELDS." SET ff_order=ff_order+1 WHERE ff_order>='".$order."' AND ff_order<'".$old_order."' AND ff_id<>'".$id."'"); 
        }
    }
    
    $upd666 = dbquery("UPDATE ".DB_RM_FORM_FIELDS." SET ff_title='".$title."', ff_infobox='".$infobox."' WHERE ff_id='".$id."'");
    redirect(INFUSIONS."al_register_mod/admin/index.php?p=form");
}

if (isset($_POST['add'])) {
    $name = trim(stripinput($_POST['ff_name']));
    $title = trim(stripinput($_POST['ff_title']));
    
    $type = $_POST['ff_type'];
    $infobox = $type == "4" ? trim(stripinput($_POST['ff_infobox'])) : "";
    $value = $type == "3" ? trim(stripinput($_POST['ff_value'])) : "";
    $max_order = dbcount("(ff_id)",DB_RM_FORM_FIELDS);
    if (isset($_POST['ff_order']) && isnum($_POST['ff_order']) && $_POST['ff_order'] != "0") {
        if ($_POST['ff_order'] > $max_order+1) {
            $order = $max_order+1;
        } else {
            $order = $_POST['ff_order'];
        }
    } else {
        $order = 1;
    }
    if ($order <= $max_order) {
        //refresh order
        $refresh = dbquery("UPDATE ".DB_RM_FORM_FIELDS." SET ff_order=ff_order+1 WHERE ff_order>='".$order."'");
    }
    $add = dbquery("INSERT INTO ".DB_RM_FORM_FIELDS." (ff_name,ff_title,ff_order,ff_type,ff_value,ff_infobox) VALUES ('".$name."','".$title."','".$order."','".$type."','".$value."','".$infobox."')");
    if ($type == "1") {
        $qwe = "VARCHAR(250) NOT NULL DEFAULT ''";
    } elseif ($type == "2") {
        $qwe = "TEXT NOT NULL";
    } elseif ($type == "3") {
        $qwe = "VARCHAR(250) NOT NULL DEFAULT ''";
    }
    if ($type != "4") { $add_column = dbquery("ALTER TABLE ".DB_RM_FORM_APPS." ADD fa_".$name." ".$qwe.""); }
    redirect(INFUSIONS."al_register_mod/admin/index.php?p=form");
}

if (isset($_GET['e']) && isnum($_GET['e'])) {
    $editable = dbquery("SELECT * FROM ".DB_RM_FORM_FIELDS." WHERE ff_id='".$_GET['e']."'");
    if (dbrows($result)) {
        $editable = dbarray($editable);
        $ff_title = $editable['ff_title'];
        $ff_name = $editable['ff_name'];
        $ff_type = $field_types[$editable['ff_type']];
        $ff_value = $editable['ff_type'] == "3" ? $editable['ff_value'] : "";
        $ff_order = $editable['ff_order'];
        $ff_infobox = $editable['ff_type'] == "4" ? $editable['ff_infobox'] : "";
        $is_edit = true;
    } else {
        redirect(BASEDIR);
    }
} else {
   $is_edit = false;
   $ff_title = "";
   $ff_name = "";
   $ff_type = 1;
   $ff_value = "";
   $ff_order = 1;
   $ff_infobox = "";
}

$types_select = "<select name='ff_type' id='type_select'>";
for ($i=1;$i<=count($field_types);$i++) {
    $types_select .= "<option value='".$i."'".($i == $ff_type ? " selected='selected'" : "").">".$field_types[$i]."</option>";
}
$types_select .= "</select>";

add_to_head("<script type='text/javascript'>
$(document).ready(function(){
    var isedit = '".$is_edit."';
        if (!isedit) {
            $('#svalue').hide();
            $('#sinfobox').hide();
            $('#stitle').show();
            $('#sname').show();
        }
    $('#type_select').change(function(){
        var val = $('#type_select').val();
        if (val == '1') {
            $('#svalue').hide();
            $('#sinfobox').hide();
            $('#stitle').show();
            $('#sname').show();
        } else if (val == '2') {
            $('#svalue').hide();
            $('#sinfobox').hide();
            $('#stitle').show();
            $('#sname').show();
        } else if (val == '3') {
            $('#svalue').show();
            $('#sinfobox').hide();
            $('#stitle').show();
            $('#sname').show();
        } else if (val == '4') {
            $('#svalue').hide();
            $('#sinfobox').show();
            $('#stitle').show();
            $('#sname').hide();
        }
    });
});
</script>");



opentable($locale['ar58']);
echo "<form name='inputform' method='post'>";
echo "<table width='100%'>";
echo "<tr><td class='tbl2'>".$locale['ar61']."</td><td class='tbl2'>".($is_edit ? $ff_type : $types_select)."</td></tr>";
echo "<tr id='stitle'><td class='tbl2' width='300'>".$locale['ar59']."</td><td class='tbl2'><input type='text' class='textbox' style='width:250px;' name='ff_title' value='".$ff_title."' /></td></tr>";
echo "<tr id='sname'><td class='tbl2'>".$locale['ar60']."</td><td class='tbl2'>".($is_edit ? $ff_name : "<input type='text' class='textbox' style='width:250px;' name='ff_name' />")."</td></tr>";
echo "<tr id='svalue'><td class='tbl2'>".$locale['ar62']."</td><td class='tbl2'>".($is_edit ? $ff_value : "<input type='text' class='textbox' style='width:250px;' name='ff_value' />")."</td></tr>";
echo "<tr id='sinfobox'><td class='tbl2'>".$locale['ar65']."</td><td class='tbl2'>".($is_edit ? "<textarea name='ff_infobox' class='textbox' rows='3' cols='30'>".$ff_infobox."</textarea>" : "<textarea name='ff_infobox' class='textbox' rows='3' cols='30'></textarea>")."</td></tr>";
echo "<tr><td class='tbl2'>".$locale['ar63']."</td><td class='tbl2'>".($is_edit ? "<input type='hidden' name='id' value='".$_GET['e']."' />" : "")."<input type='text' class='textbox' size='3' name='ff_order' value='".$ff_order."' /></td></tr>";
echo "<tr><td class='tbl2' colspan='2'><input type='submit' class='button' name='".($is_edit ? "save" : "add")."' value='Save' /></td></tr>";
echo "</table>";
echo "</form>";
closetable();





opentable($locale['ar50']);
$result = dbquery("SELECT * FROM ".DB_RM_FORM_FIELDS." ORDER BY ff_order ASC");
if (dbrows($result)) {
    echo "<table width='100%'>";
    echo "<tr><td class='tbl2' align='center' width='1%'>".$locale['ar51']."</td><td class='tbl2' width='30%'>".$locale['ar52']."</td><td class='tbl2'>".$locale['ar53']."</td><td class='tbl2' width='15%' align='center'>".$locale['ar54']."</td><td class='tbl2' width='20%'>".$locale['ar55']."</td><td class='tbl2' width='10%'>".$locale['ar56']."</td></tr>";
    $i=0;
    while ($data=dbarray($result)) {
        $tbl = $i % 2 == 0 ? "tbl1" : "tbl2";
        echo "<tr>
        <td class='".$tbl."'>".$data['ff_id']."</td>
        <td class='".$tbl."'>".$data['ff_title']."</td>
        <td class='".$tbl."'>".$data['ff_name']."</td>
        <td class='".$tbl."' align='center'>".$data['ff_order']."</td>
        <td class='".$tbl."'>".$field_types[$data['ff_type']]."</td>
        <td class='".$tbl."' align='center'><a href='".INFUSIONS."al_register_mod/admin/index.php?p=form&e=".$data['ff_id']."'><img src='".IMAGES."edit.png' /><a href='".INFUSIONS."al_register_mod/admin/index.php?p=form&d=".$data['ff_id']."'><img src='".IMAGES."no.png' /></a></td>
        </tr>";
        $i++;
    }
    echo "</table>";
} else {
    echo $locale['ar57'];
}
closetable();


?>
