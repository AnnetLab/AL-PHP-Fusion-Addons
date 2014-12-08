<?php
if (!alcr_can_view($userdata['user_id'])) redirect(START_PAGE);
require_once INCLUDES."bbcode_include.php";

opentable($locale['alcr25'].date('d.m.Y',strtotime(trim(stripinput($_GET['date'])))));

$result = dbquery("SELECT ev.*,u.user_name FROM ".DB_AL_CALENDAR_EVENTS." ev LEFT JOIN ".DB_USERS." u ON u.user_id=ev.alcr_event_user WHERE alcr_event_date='".trim(stripinput($_GET['date']))."' ORDER BY alcr_event_time ASC");
if (dbrows($result)) {
    while ($data=dbarray($result)) {
        echo "<h3>".$data['alcr_event_date']." ".$data['alcr_event_time']." - ".$data['alcr_event_title']." ".(alcr_can_admin($userdata['user_id']) ? "<a href='".FUSION_SELF."?p=manage_event&edit=".$data['alcr_event_id']."'><img src='".IMAGES."edit.png' /></a> <a href='".FUSION_SELF."?p=manage_event&delete=".$data['alcr_event_id']."'><img src='".IMAGES."no.png' /></a>" : "")." <span class='small'>".($data['alcr_event_confirm'] == 1 ? $locale['alcr36'] : $locale['alcr37'])."</span></h3>";
        echo "";
        echo "<div>".parsesmileys(parseubb(nl2br($data['alcr_event_desc'])))."</div>";
    }
} else {
    echo $locale['alcr26'];
}
closetable();


?>