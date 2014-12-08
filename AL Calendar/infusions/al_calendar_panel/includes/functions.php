<?php defined("IN_FUSION") or die("FU");
require_once INFUSIONS."al_calendar_panel/infusion_db.php";
$alcr_settings = dbarray(dbquery("SELECT * FROM ".DB_AL_CALENDAR_SETTINGS));

function alcr_can_view($user_id) {

    global $alcr_settings;
    if ($alcr_settings['calendar_user_group'] == 0) {
        return true;
    } else if ($alcr_settings['calendar_user_group'] == 101 && iMEMBER) {
        return true;
    } else if ($alcr_settings['calendar_user_group'] == 102 && iADMIN) {
        return true;
    } else if ($alcr_settings['calendar_user_group'] == 103 && iSUPERADMIN) {
        return true;
    } else {
        $result = dbquery("SELECT user_groups FROM ".DB_USERS." WHERE user_id='".$user_id."'");
        if (dbrows($result)) {
            $data = dbarray($result);
            if (in_array($alcr_settings['calendar_user_group'],explode(".",$data['user_groups']))) {
                return true;
            }
        }
    }
    return false;

}

function alcr_can_admin($user_id) {

    global $alcr_settings;

    if (checkrights("ALCR")) {
        return true;
    } else if ($alcr_settings['calendar_admin_group'] == 0) {
        return true;
    } else if ($alcr_settings['calendar_admin_group'] == 101 && iMEMBER) {
        return true;
    } else if ($alcr_settings['calendar_admin_group'] == 102 && iADMIN) {
        return true;
    } else if ($alcr_settings['calendar_admin_group'] == 103 && iSUPERADMIN) {
        return true;
    } else {
        $result = dbquery("SELECT user_groups FROM ".DB_USERS." WHERE user_id='".$user_id."'");
        if (dbrows($result)) {
            $data = dbarray($result);
            if (in_array($alcr_settings['calendar_admin_group'],explode(".",$data['user_groups']))) {
                return true;
            }
        }
        $result = dbquery("SELECT * FROM ".DB_AL_CALENDAR_ADMINS." WHERE alcr_admin_user='".$user_id."'");
        if (dbrows($result)) {
            return true;
        }

    }

    return false;

}

function alcr_get_month($month = 0, $year = 0) {

    if ($month == 0) $month = date('m');
    if ($year == 0) $year = date('Y');
    $dayofmonth = date('t',mktime(0,0,0,$month,1,$year));
    $day_count = 1;
    $num = 0;
    for($i = 0; $i < 7; $i++) {
        $dayofweek = date('w', mktime(0, 0, 0, $month, $day_count, $year));
        $dayofweek = $dayofweek - 1;
        if($dayofweek == -1) $dayofweek = 6;

        if($dayofweek == $i) {
            $week[$num][$i] = $day_count;
            $day_count++;
        }
        else {
            $week[$num][$i] = "";
        }
    }

    while(true) {
        $num++;
        for($i = 0; $i < 7; $i++) {
            if($day_count > $dayofmonth) {
                $week[$num][$i] = "";
            } else {
                $week[$num][$i] = $day_count;
            }
            $day_count++;
        }
        if($day_count > $dayofmonth) break;
    }
    return $week;

}

function alcr_get_events($month = 0, $year = 0) {

    if ($month == 0) $month = date('m');
    if ($year == 0) $year = date('Y');
    $dayofmonth = date('t',mktime(0,0,0,$month,1,$year));
    $events = array();

    $result = dbquery("SELECT * FROM ".DB_AL_CALENDAR_EVENTS." WHERE alcr_event_date BETWEEN '".$year."-".$month."-1' AND '".$year."-".$month."-".$dayofmonth."' ORDER BY alcr_event_time ASC");
    if (dbrows($result)) {
        while($data=dbarray($result)) {
            $events[date('j',strtotime($data['alcr_event_date']))][] = $data;
        }
    }
    return $events;

}

?>