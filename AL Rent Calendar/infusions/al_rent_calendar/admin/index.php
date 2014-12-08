<?php
require_once "../../../maincore.php";
require_once INFUSIONS."al_rent_calendar/infusion_db.php";
if (file_exists(INFUSIONS."al_rent_calendar/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_rent_calendar/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_rent_calendar/locale/Russian.php";
}
require_once INFUSIONS."al_rent_calendar/includes/functions.php";
require_once THEMES."templates/admin_header.php";

if (!checkAdminPageAccess('ALRC')) redirect(BASEDIR);

$denied_albums = array(1,2,9);

opentable($locale['alrc9']);

    echo "<div style='width:100%;text-align:center;'>";
    $result = dbquery("SELECT * FROM ".DB_PHOTO_ALBUMS." WHERE album_id NOT IN (".implode(',',$denied_albums).")");
    if (dbrows($result)) {
        echo "<form method='post' action='".FUSION_SELF.$aidlink."'>";
        echo "<select name='id'>";
        while ($data = dbarray($result)) {
            echo "<option value='".$data['album_id']."'>".$data['album_title']."</option>";
        }
        echo "</select> <input type='submit' name='edit' value='".$locale['alrc10']."' />";
        echo "</form>";
    } else {
        echo $locale['alrc11'];
    }
    echo "</div>";

closetable();

if (isset($_POST['edit']) && isset($_POST['id']) && isnum($_POST['id'])) {
    redirect(FUSION_SELF.$aidlink."&edit=".$_POST['id']);
} else if (isset($_POST['add_rent'])) {

    $errors = array();
    $album_id = isset($_POST['album_id']) && isnum($_POST['album_id']) ? $_POST['album_id'] : null;

    $start_date = $_POST['from_year'].'-'.$_POST['from_month'].'-'.$_POST['from_day'];
    $finish_date = $_POST['to_year'].'-'.$_POST['to_month'].'-'.$_POST['to_day'];

    if (!$album_id) $errors[] = $locale['alrc22'];
    if (mktime(0,0,0,$_POST['from_month'],$_POST['from_day'],$_POST['from_year']) > mktime(0,0,0,$_POST['to_month'],$_POST['to_day'],$_POST['to_year'])) $errors[] = $locale['alrc23'];

    if (empty($errors)) {

        $result = dbquery("SELECT * FROM ".DB_PHOTO_ALBUMS." WHERE album_id='".$album_id."'");
        if (!dbrows($result)) {
            $errors[] = $locale['alrc22'];
        } else {

            if (isset($_POST['rented_id']) && isnum($_POST['rented_id'])) {
                $rented_id = $_POST['rented_id'];
                $rented_is_edit = true;
                $result = dbquery("SELECT * FROM ".DB_AL_RC_RENTED_DAYS." WHERE alrc_rented_album_id='".$album_id."' AND alrc_rented_id<>'".$rented_id."' AND ((alrc_rented_date_start>'".$start_date."' AND alrc_rented_date_start<'".$finish_date."') OR (alrc_rented_date_finish>'".$start_date."' AND alrc_rented_date_finish<'".$finish_date."') OR (alrc_rented_date_start<'".$start_date."' AND alrc_rented_date_finish>'".$finish_date."'))");
                if (dbrows($result)) {
                    $errors[] = $locale['alrc24'];
                } else {
                    dbquery("UPDATE ".DB_AL_RC_RENTED_DAYS." SET alrc_rented_date_start='".$start_date."',alrc_rented_date_finish='".$finish_date."' WHERE alrc_rented_id='".$rented_id."'");
                    redirect(FUSION_SELF.$aidlink."&edit=".$album_id."&message=rs");
                }
            } else {
                $result = dbquery("SELECT * FROM ".DB_AL_RC_RENTED_DAYS." WHERE alrc_rented_album_id='".$album_id."' AND ((alrc_rented_date_start>'".$start_date."' AND alrc_rented_date_start<'".$finish_date."') OR (alrc_rented_date_finish>'".$start_date."' AND alrc_rented_date_finish<'".$finish_date."') OR (alrc_rented_date_start<'".$start_date."' AND alrc_rented_date_finish>'".$finish_date."'))");
                $rented_is_edit = true;
                if (dbrows($result)) {
                    $errors[] = $locale['alrc24'];
                } else {
                    dbquery("INSERT INTO ".DB_AL_RC_RENTED_DAYS." (alrc_rented_album_id,alrc_rented_date_start,alrc_rented_date_finish) VALUES ('".$album_id."','".$start_date."','".$finish_date."')");
                    redirect(FUSION_SELF.$aidlink."&edit=".$album_id."&message=rs");
                }
            }

        }


    }

} else if (isset($_POST['add_special'])) {

    $errors2 = array();
    $album_id = isset($_POST['album_id']) && isnum($_POST['album_id']) ? $_POST['album_id'] : null;

    $start_date = $_POST['from_year'].'-'.$_POST['from_month'].'-'.$_POST['from_day'];
    $finish_date = $_POST['to_year'].'-'.$_POST['to_month'].'-'.$_POST['to_day'];
    $title = trim(stripinput($_POST['title']));
    $min_night = trim(stripinput($_POST['min_nights']));
    $cost_2p = trim(stripinput($_POST['cost_2p']));
    $cost_nextp = trim(stripinput($_POST['cost_nextp']));

    if (!$album_id) $errors2[] = $locale['alrc22'];
    if (mktime(0,0,0,$_POST['from_month'],$_POST['from_day'],$_POST['from_year']) > mktime(0,0,0,$_POST['to_month'],$_POST['to_day'],$_POST['to_year'])) $errors2[] = $locale['alrc23'];

    if (empty($errors2)) {

        $result = dbquery("SELECT * FROM ".DB_PHOTO_ALBUMS." WHERE album_id='".$album_id."'");
        if (!dbrows($result)) {
            $errors2[] = $locale['alrc22'];
        } else {

            if (isset($_POST['special_id']) && isnum($_POST['special_id'])) {
                $special_id = $_POST['special_id'];
                $special_is_edit = true;
                $result = dbquery("SELECT * FROM ".DB_AL_RC_SPECIAL_DAYS." WHERE alrc_special_album_id='".$album_id."' AND alrc_special_id<>'".$special_id."' AND ((alrc_special_date_start>'".$start_date."' AND alrc_special_date_start<'".$finish_date."') OR (alrc_special_date_finish>'".$start_date."' AND alrc_special_date_finish<'".$finish_date."') OR (alrc_special_date_start<'".$start_date."' AND alrc_special_date_finish>'".$finish_date."'))");
                if (dbrows($result)) {
                    $errors2[] = $locale['alrc25'];
                } else {
                    dbquery("UPDATE ".DB_AL_RC_SPECIAL_DAYS." SET alrc_special_date_start='".$start_date."',alrc_special_date_finish='".$finish_date."',alrc_special_title='".$title."',alrc_special_min_nights='".$min_night."',alrc_special_cost_two_person='".$cost_2p."',alrc_special_cost_next_person='".$cost_nextp."' WHERE alrc_special_id='".$special_id."'");
                    redirect(FUSION_SELF.$aidlink."&edit=".$album_id."&message=ss");
                }
            } else {
                $special_id = 0;
                $special_is_edit = false;
                $result = dbquery("SELECT * FROM ".DB_AL_RC_SPECIAL_DAYS." WHERE alrc_special_album_id='".$album_id."' AND ((alrc_special_date_start>'".$start_date."' AND alrc_special_date_start<'".$finish_date."') OR (alrc_special_date_finish>'".$start_date."' AND alrc_special_date_finish<'".$finish_date."') OR (alrc_special_date_start<'".$start_date."' AND alrc_special_date_finish>'".$finish_date."'))");
                if (dbrows($result)) {
                    $errors2[] = $locale['alrc25'];
                } else {
                    dbquery("INSERT INTO ".DB_AL_RC_SPECIAL_DAYS." (alrc_special_album_id,alrc_special_date_start,alrc_special_date_finish,alrc_special_title,alrc_special_min_nights,alrc_special_cost_two_person,alrc_special_cost_next_person) VALUES ('".$album_id."','".$start_date."','".$finish_date."','".$title."','".$min_night."','".$cost_2p."','".$cost_nextp."')");
                    redirect(FUSION_SELF.$aidlink."&edit=".$album_id."&message=ss");
                }
            }


        }


    }

}

if (isset($_GET['edit']) && isnum($_GET['edit'])) {

    if (isset($_GET['delete_rented']) && isnum($_GET['delete_rented'])) {

        $result = dbquery("SELECT * FROM ".DB_AL_RC_RENTED_DAYS." WHERE alrc_rented_id='".$_GET['delete_rented']."'");
        if (dbrows($result)) {
            dbquery("DELETE FROM ".DB_AL_RC_RENTED_DAYS." WHERE alrc_rented_id='".$_GET['delete_rented']."'");
        }
        redirect(FUSION_SELF.$aidlink."&edit=".$_GET['edit']);

    }
    if (isset($_GET['delete_special']) && isnum($_GET['delete_special'])) {

        $result = dbquery("SELECT * FROM ".DB_AL_RC_SPECIAL_DAYS." WHERE alrc_special_id='".$_GET['delete_special']."'");
        if (dbrows($result)) {
            dbquery("DELETE FROM ".DB_AL_RC_SPECIAL_DAYS." WHERE alrc_special_id='".$_GET['delete_special']."'");
        }
        redirect(FUSION_SELF.$aidlink."&edit=".$_GET['edit']);

    }

    if (isset($_GET['edit_rented']) && isnum($_GET['edit_rented'])) {

        $result = dbquery("SELECT * FROM ".DB_AL_RC_RENTED_DAYS." WHERE alrc_rented_id='".$_GET['edit_rented']."' AND alrc_rented_album_id='".$_GET['edit']."'");
        if (dbrows($result)) {
            $data = dbarray($result);
            $rented_is_edit = true;
            $rented_id = $_GET['edit_rented'];
            list($rented_start_year,$rented_start_month,$rented_start_day) = explode('-',$data['alrc_rented_date_start']);
            list($rented_finish_year,$rented_finish_month,$rented_finish_day) = explode('-',$data['alrc_rented_date_finish']);
        } else {
            redirect(FUSION_SELF.$aidlink."&edit=".$_GET['edit']);
        }

    } else {
        $rented_is_edit = false;
        $rented_id = 0;
        list($rented_start_year,$rented_start_month,$rented_start_day) = array(date('Y'),date('n'),date('j'));
        list($rented_finish_year,$rented_finish_month,$rented_finish_day) = array(date('Y'),date('n'),date('j'));
    }

    if (isset($_GET['edit_special']) && isnum($_GET['edit_special'])) {

        $result = dbquery("SELECT * FROM ".DB_AL_RC_SPECIAL_DAYS." WHERE alrc_special_id='".$_GET['edit_special']."' AND alrc_special_album_id='".$_GET['edit']."'");
        if (dbrows($result)) {
            $data = dbarray($result);
            $special_is_edit = true;
            $special_id = $_GET['edit_special'];
            list($special_start_year,$special_start_month,$special_start_day) = explode('-',$data['alrc_special_date_start']);
            list($special_finish_year,$special_finish_month,$special_finish_day) = explode('-',$data['alrc_special_date_finish']);
            $special_title = $data['alrc_special_title'];
            $special_min_nights = $data['alrc_special_min_nights'];
            $special_cost_two_person = $data['alrc_special_cost_two_person'];
            $special_cost_next_person = $data['alrc_special_cost_next_person'];
        } else {
            redirect(FUSION_SELF.$aidlink."&edit=".$_GET['edit']);
        }

    } else {
        $special_is_edit = false;
        $special_id = 0;
        list($special_start_year,$special_start_month,$special_start_day) = array(date('Y'),date('n'),date('j'));
        list($special_finish_year,$special_finish_month,$special_finish_day) = array(date('Y'),date('n'),date('j'));
        $special_title = '';
        $special_min_nights = '';
        $special_cost_two_person = '';
        $special_cost_next_person = '';
    }

    $result = dbquery("SELECT * FROM ".DB_PHOTO_ALBUMS." WHERE album_id='".$_GET['edit']."'");
    if (!dbrows($result)) redirect(FUSION_SELF.$aidlink);
    $album = dbarray($result);

    opentable($album['album_title']);
        echo "<form action='".FUSION_SELF.$aidlink."&edit=".$_GET['edit']."' method='post'>";
        echo "<table width='100%'>";
            if (isset($errors) && !empty($errors)) {
                echo "<tr>";
                    echo "<td class='tbl'>";
                        foreach ($errors as $error) {
                            echo $error."<br />";
                        }
                    echo "</td>";
                echo "</tr>";
            }
            echo "<tr>";
                echo "<td class='tbl'>".$locale['alrc12']."</td>";
            echo "<tr>";
            echo "</tr>";
                echo "<td class='tbl'>";
                    echo $locale['alrc14'];
                    echo "<select name='from_day'>";
                        for ($i=1;$i<=31;$i++) {
                            echo "<option value='".$i."'".($rented_start_day == $i ? " selected='selected'" : "").">".$i."</option>";
                        }
                    echo "</select> ";
                    echo "<select name='from_month'>";
                        for ($i=1;$i<=12;$i++) {
                            echo "<option value='".$i."'".($rented_start_month == $i ? " selected='selected'" : "").">".$i."</option>";
                        }
                    echo "</select> ";
                    echo "<select name='from_year'>";
                        for ($i=date('Y');$i<=date('Y')+5;$i++) {
                            echo "<option value='".$i."'".($rented_start_year == $i ? " selected='selected'" : "").">".$i."</option>";
                        }
                    echo "</select>";
                    echo $locale['alrc15'];
                    echo "<select name='to_day'>";
                        for ($i=1;$i<=31;$i++) {
                            echo "<option value='".$i."'".($rented_finish_day == $i ? " selected='selected'" : "").">".$i."</option>";
                        }
                    echo "</select> ";
                    echo "<select name='to_month'>";
                        for ($i=1;$i<=12;$i++) {
                            echo "<option value='".$i."'".($rented_finish_month == $i ? " selected='selected'" : "").">".$i."</option>";
                        }
                    echo "</select> ";
                    echo "<select name='to_year'>";
                        for ($i=date('Y');$i<=date('Y')+5;$i++) {
                            echo "<option value='".$i."'".($rented_finish_year == $i ? " selected='selected'" : "").">".$i."</option>";
                        }
                    echo "</select>";
                echo "</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='tbl'><input type='hidden' name='album_id' value='".$album['album_id']."' /><input type='submit' name='add_rent' value='".$locale['alrc13']."' />";
                    if ($rented_is_edit) {
                        echo "<input type='hidden' name='rented_id' value='".$rented_id."' />";
                    }
                echo "</td>";
            echo "</tr>";
        echo "</table>";
        echo "</form>";

        $result = dbquery("SELECT * FROM ".DB_AL_RC_RENTED_DAYS." WHERE alrc_rented_album_id='".$album['album_id']."' AND (alrc_rented_date_start>='".date('Y').'-'.date('m').'-'.date('d')."' OR alrc_rented_date_finish>='".date('Y').'-'.date('m').'-'.date('d')."')");
        if (dbrows($result)) {
            echo "<table width='100%'>";
                echo "<tr>";
                    echo "<td class='tbl2' colspan='2'>".$locale['alrc26']."</td>";
                echo "</tr>";
                while ($data = dbarray($result)) {
                    echo "<tr>";
                        echo "<td class='tbl1'>".$locale['alrc14'].$data['alrc_rented_date_start'].$locale['alrc15'].$data['alrc_rented_date_finish']."</td>";
                        echo "<td class='tbl1' width='150'><a href='".FUSION_SELF.$aidlink."&edit=".$album['album_id']."&delete_rented=".$data['alrc_rented_id']."'>".$locale['alrc27']."</a> <a href='".FUSION_SELF.$aidlink."&edit=".$album['album_id']."&edit_rented=".$data['alrc_rented_id']."'>".$locale['alrc27a']."</a></td>";
                    echo "</tr>";
                }
            echo "</table>";
        }

    closetable();
    opentable($album['album_title']);

        echo "<form action='".FUSION_SELF.$aidlink."&edit=".$_GET['edit']."' method='post'>";
        echo "<table width='100%'>";
        if (isset($errors2) && !empty($errors2)) {
            echo "<tr>";
            echo "<td class='tbl'>";
            foreach ($errors2 as $error) {
                echo $error."<br />";
            }
            echo "</td>";
            echo "</tr>";
        }
            echo "<tr>";
                echo "<td class='tbl' colspan='2'>".$locale['alrc16']."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='tbl' width='150'>".$locale['alrc21']."</td>";
                echo "<td class='tbl'>";
                    echo $locale['alrc14'];
                    echo "<select name='from_day'>";
                        for ($i=1;$i<=31;$i++) {
                            echo "<option value='".$i."'".($special_start_day == $i ? " selected='selected'" : "").">".$i."</option>";
                        }
                    echo "</select> ";
                    echo "<select name='from_month'>";
                        for ($i=1;$i<=12;$i++) {
                            echo "<option value='".$i."'".($special_start_month == $i ? " selected='selected'" : "").">".$i."</option>";
                        }
                    echo "</select> ";
                    echo "<select name='from_year'>";
                        for ($i=date('Y');$i<=date('Y')+5;$i++) {
                            echo "<option value='".$i."'".($special_start_year == $i ? " selected='selected'" : "").">".$i."</option>";
                        }
                    echo "</select>";
                    echo $locale['alrc15'];
                    echo "<select name='to_day'>";
                        for ($i=1;$i<=31;$i++) {
                            echo "<option value='".$i."'".($special_finish_day == $i ? " selected='selected'" : "").">".$i."</option>";
                        }
                    echo "</select> ";
                    echo "<select name='to_month'>";
                        for ($i=1;$i<=12;$i++) {
                            echo "<option value='".$i."'".($special_finish_month == $i ? " selected='selected'" : "").">".$i."</option>";
                        }
                    echo "</select> ";
                    echo "<select name='to_year'>";
                        for ($i=date('Y');$i<=date('Y')+5;$i++) {
                            echo "<option value='".$i."'".($special_finish_year == $i ? " selected='selected'" : "").">".$i."</option>";
                        }
                    echo "</select>";
                echo "</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='tbl'>".$locale['alrc17']."</td>";
                echo "<td class='tbl'><input type='text' class='textbox' name='title' value='".$special_title."' /></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='tbl'>".$locale['alrc18']."</td>";
                echo "<td class='tbl'><input type='text' class='textbox' name='min_nights' value='".$special_min_nights."' /></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='tbl'>".$locale['alrc19']."</td>";
                echo "<td class='tbl'><input type='text' class='textbox' name='cost_2p' value='".$special_cost_two_person."' /></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='tbl'>".$locale['alrc20']."</td>";
                echo "<td class='tbl'><input type='text' class='textbox' name='cost_nextp' value='".$special_cost_next_person."' /></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td class='tbl'></td><td class='tbl'><input type='hidden' name='album_id' value='".$album['album_id']."' /><input type='submit' name='add_special' value='".$locale['alrc13']."' />";
                    if ($special_is_edit) {
                        echo "<input type='hidden' name='special_id' value='".$special_id."' />";
                    }
                echo "</td>";
            echo "</tr>";
        echo "</table>";
        echo "</form>";

    $result = dbquery("SELECT * FROM ".DB_AL_RC_SPECIAL_DAYS." WHERE alrc_special_album_id='".$album['album_id']."' AND (alrc_special_date_start>='".date('Y').'-'.date('m').'-'.date('d')."' OR alrc_special_date_finish>='".date('Y').'-'.date('m').'-'.date('d')."')");
    if (dbrows($result)) {
        echo "<table width='100%'>";
        echo "<tr>";
            echo "<td class='tbl2' colspan='6'>".$locale['alrc28']."</td>";
        echo "</tr>";
        echo "<tr>";
            echo "<td class='tbl2'>".$locale['alrc21']."</td>";
            echo "<td class='tbl2'>".$locale['alrc17']."</td>";
            echo "<td class='tbl2'>".$locale['alrc18']."</td>";
            echo "<td class='tbl2'>".$locale['alrc19']."</td>";
            echo "<td class='tbl2'>".$locale['alrc20']."</td>";
            echo "<td class='tbl2'></td>";
        echo "</tr>";
        while ($data = dbarray($result)) {
            echo "<tr>";
            echo "<td class='tbl1' width='350'>".$locale['alrc14'].$data['alrc_special_date_start'].$locale['alrc15'].$data['alrc_special_date_finish']."</td>";
            echo "<td class='tbl1'>".$data['alrc_special_title']."</td>";
            echo "<td class='tbl1'>".$data['alrc_special_min_nights']."</td>";
            echo "<td class='tbl1'>".$data['alrc_special_cost_two_person']."</td>";
            echo "<td class='tbl1'>".$data['alrc_special_cost_next_person']."</td>";
            echo "<td class='tbl1' width='150'><a href='".FUSION_SELF.$aidlink."&edit=".$album['album_id']."&delete_special=".$data['alrc_special_id']."'>".$locale['alrc27']."</a> <a href='".FUSION_SELF.$aidlink."&edit=".$album['album_id']."&edit_special=".$data['alrc_special_id']."'>".$locale['alrc27a']."</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    closetable();

}



require_once THEMES."templates/footer.php";
?>