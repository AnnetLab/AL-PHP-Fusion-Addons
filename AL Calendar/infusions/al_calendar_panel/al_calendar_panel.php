<?php defined("IN_FUSION") or die("FU");
require_once THEMES."templates/header.php";
require_once INFUSIONS."al_calendar_panel/infusion_db.php";
if (file_exists(INFUSIONS."al_calendar_panel/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_calendar_panel/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_calendar_panel/locale/Russian.php";
}
require_once INFUSIONS."al_calendar_panel/includes/functions.php";

if (alcr_can_view($userdata['user_id'])) {

    openside($locale['alcr28']);
    echo "<h3>".$locale['alcr30']."</h3>";
    $result = dbquery("SELECT * FROM ".DB_AL_CALENDAR_EVENTS." WHERE alcr_event_date='".date('Y')."-".date('m')."-".date('d')."'");
    if (dbrows($result)) {
        while($data=dbarray($result)) {
            echo "<span class='".($data['alcr_event_confirm'] == 1 ? "event-confirmed" : "event-non-confirmed")."'>".substr($data['alcr_event_time'],0,5)." ".trimlink($data['alcr_event_title'],100).(strlen($data['alcr_event_title']>100) ? "..." : "")."</span><br />";
        }
        echo "<a href='".BASEDIR."calendar.php?p=day&date=".date('Y')."-".date('m')."-".date('d')."'>".$locale['alcr31']."</a>";
    } else {
        echo $locale['alcr29'];
    }

    echo "<h3>".$locale['alcr32']."</h3>";

    $cur_month_days = date('t');
    if ($cur_month_days >= date('j')+1) {
        $future_date1 = date('Y')."-".date('m')."-".(date('j')+1);
    } else {
        if (date('n') < 12) {
            $future_date1 = date('Y')."-".(date('n')+1)."-1";
        } else {
            $future_date1 = (date('Y')+1)."-1-1";
        }
    }
    if ($cur_month_days >= date('j')+7) {
        $future_date2 = date('Y')."-".date('m')."-".(date('j')+7);
    } else {
        if (date('n') < 12) {
            $future_date2 = date('Y')."-".(date('n')+1)."-7";
        } else {
            $future_date2 = (date('Y')+1)."-1-7";
        }
    }


    $result = dbquery("SELECT * FROM ".DB_AL_CALENDAR_EVENTS." WHERE alcr_event_date BETWEEN '".$future_date1."' AND '".$future_date2."'");
    if (dbrows($result)) {
        while($data=dbarray($result)) {
            echo "<span class='".($data['alcr_event_confirm'] == 1 ? "event-confirmed" : "event-non-confirmed")."'>".$data['alcr_event_date']." ".substr($data['alcr_event_time'],0,5)." ".trimlink($data['alcr_event_title'],100).(strlen($data['alcr_event_title']>100) ? "..." : "")."</span><br />";
        }
        echo "<a href='".BASEDIR."calendar.php'>".$locale['alcr31']."</a>";
    } else {
        echo $locale['alcr29'];
    }

    closeside();

}

?>