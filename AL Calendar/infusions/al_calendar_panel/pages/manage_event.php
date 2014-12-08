<?php
if (!alcr_can_admin($userdata['user_id'])) redirect(START_PAGE);
require_once INCLUDES."bbcode_include.php";

if (isset($_POST['save'])) {
    $title = trim(stripinput($_POST['title']));
    $desc = trim(stripinput($_POST['desc']));
    $date = array(
        'year' => $_POST['date']['year'],
        'month' => $_POST['date']['month'],
        'day' => $_POST['date']['day'],
        'hour' => $_POST['date']['hour'],
        'minute' => $_POST['date']['minute']
    );
    $confirm = $_POST['confirm'];
    if (isset($_POST['event_id']) && isnum($_POST['event_id'])) {
        $result = dbquery("UPDATE ".DB_AL_CALENDAR_EVENTS." SET alcr_event_date='".$date['year']."-".$date['month']."-".$date['day']."',alcr_event_time='".$date['hour'].":".$date['minute'].":00',alcr_event_user='".$userdata['user_id']."',alcr_event_title='".$title."',alcr_event_desc='".$desc."',alcr_event_confirm='".$confirm."' WHERE alcr_event_id='".$_POST['event_id']."'");
    } else {
        $result = dbquery("INSERT INTO ".DB_AL_CALENDAR_EVENTS." (alcr_event_date,alcr_event_time,alcr_event_user,alcr_event_title,alcr_event_desc,alcr_event_confirm) VALUES ('".$date['year']."-".$date['month']."-".$date['day']."','".$date['hour'].":".$date['minute'].":00','".$userdata['user_id']."','".$title."','".$desc."','".$confirm."')");
    }
    redirect(FUSION_SELF."?p=day&date=".$date['year']."-".$date['month']."-".$date['day']);
} else if (isset($_GET['delete']) && isnum($_GET['delete'])) {
    $result = dbquery("SELECT * FROM ".DB_AL_CALENDAR_EVENTS." WHERE alcr_event_id='".$_GET['delete']."'");
    if (dbrows($result)) {
        $result = dbquery("DELETE FROM ".DB_AL_CALENDAR_EVENTS." WHERE alcr_event_id='".$_GET['delete']."'");
    }
    redirect(FUSION_SELF);
} else if (isset($_GET['edit']) && isnum($_GET['edit'])) {

    $result = dbquery("SELECT * FROM ".DB_AL_CALENDAR_EVENTS." WHERE alcr_event_id='".$_GET['edit']."'");
    if (dbrows($result)) {
        $data = dbarray($result);
        $is_edit = true;
        $event_id = $data['alcr_event_id'];
        $title = $data['alcr_event_title'];
        $desc = $data['alcr_event_desc'];
        $date_ex = strtotime($data['alcr_event_date']." ".$data['alcr_event_time']);
        $date = array(
            'year' => date('Y',$date_ex),
            'month' => date('n',$date_ex),
            'day' => date('j',$date_ex),
            'hour' => date('H',$date_ex),
            'minute' => date('i',$date_ex)
        );
        $confirm = $data['alcr_event_confirm'];
    }

} else {
    if (isset($_GET['date']) && strtotime(trim(stripinput($_GET['date'])))) {
        $c_date = strtotime(trim(stripinput($_GET['date'])));
    } else {
        $c_date = time();
    }
    $date = array(
        'year' => date('Y',$c_date),
        'month' => date('n',$c_date),
        'day' => date('j',$c_date),
        'hour' => date('H',$c_date),
        'minute' => date('i',$c_date)
    );
    $title = '';
    $desc = '';
    $is_edit = false;
    $confirm = 0;
}

opentable($locale['alcr21']);
echo "<form method='post' name='inputform'>";
echo "<table width='100%'>";
    echo "<tr>";
        echo "<td class='tbl' width='100'>".$locale['alcr22']."</td>";
        echo "<td class='tbl'><input type='text' class='textbox' name='title' value='".$title."' style='width:250px;' /></td>";
    echo "</tr>";
    echo "<tr>";
        echo "<td class='tbl' width='100'>".$locale['alcr33']."</td>";
        echo "<td class='tbl'><select name='confirm' class='textbox'><option value='1'".($confirm == 1 ? " selected='selected'" : "").">".$locale['alcr34']."</option><option value='0'".($confirm == 0 ? " selected='selected'" : "").">".$locale['alcr35']."</option></select></td>";
    echo "</tr>";
    echo "<tr>";
        echo "<td class='tbl'>".$locale['alcr23']."</td>";
        echo "<td class='tbl'>";
            echo "<select name='date[day]'>";
                for ($i=1;$i<=31;$i++) {
                    echo "<option value='".$i."'".($i==$date['day'] ? " selected='selected'" : "").">".$i."</option>";
                }
            echo "</select>-<select name='date[month]'>";
            for ($i=1;$i<=12;$i++) {
                echo "<option value='".$i."'".($i==$date['month'] ? " selected='selected'" : "").">".$locale['alcr_mon_'.$i]."</option>";
            }
            echo "</select>-<select name='date[year]'>";
            for ($i=date('Y')-5;$i<=date('Y')+5;$i++) {
                echo "<option value='".$i."'".($i==$date['year'] ? " selected='selected'" : "").">".$i."</option>";
            }
            echo "</select> <select name='date[hour]'>";
            for ($i=0;$i<=23;$i++) {
                echo "<option value='".($i < 10 ? "0" : "").$i."'".($i==$date['hour'] ? " selected='selected'" : "").">".($i < 10 ? "0" : "").$i."</option>";
            }
            echo "</select>:<select name='date[minute]'>";
            for ($i=0;$i<=59;$i++) {
                echo "<option value='".($i < 10 ? "0" : "").$i."'".($i==$date['minute'] ? " selected='selected'" : "").">".($i < 10 ? "0" : "").$i."</option>";
            }
            echo "</select>";
        echo "</td>";
    echo "</tr>";
    echo "<tr valign='top'>";
        echo "<td class='tbl'>".$locale['alcr24']."</td>";
        echo "<td class='tbl'><textarea class='textbox' name='desc' style='width: 100%' rows='10' id='desc'>".$desc."</textarea><br />".display_bbcodes("99%", "desc")."</td>";
    echo "</tr>";
    echo "<tr><td class='tbl' colspan='2'>".($is_edit ? "<input type='hidden' name='event_id' value='".$event_id."' />" : "")."<input type='submit' class='button' name='save' value='".$locale['alcr15']."' /> <a href='".($is_edit ? FUSION_SELF."?p=day&date=".$date['year']."-".$date['month']."-".$date['day'] : FUSION_SELF)."'>".$locale['alcr27']."</a></td></tr>";
echo "</table>";
echo "</form>";
closetable();


?>