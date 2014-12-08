<?php
add_to_head("<link rel='stylesheet' type='text/css' href='".INFUSIONS."al_calendar_panel/calendar_styles.css' />");

//////////////////////
$cell_width = '80px';
//////////////////////

$month = isset($_GET['month']) && isnum($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) && isnum($_GET['year']) ? $_GET['year'] : date('Y');

$weeks = alcr_get_month($month,$year);
$events = alcr_get_events($month,$year);

echo "<select name='change_month'>";
    for ($i=1;$i<=12;$i++) {
        echo "<option value='".$i."'".($month==$i ? " selected='selected'" : "").">".$locale['alcr_mon_'.$i]."</option>";
    }
echo "</select>";
echo " <select name='change_year'>";
    for ($i=date('Y')-5;$i<=date('Y')+5;$i++) {
        echo "<option value='".$i."'".($year==$i ? " selected='selected'" : "").">".$i."</option>";
    }
echo "</select>";

echo "<h3>".$locale['alcr_mon_'.ltrim($month,0)]." ".$year."</h3>";
echo "<table width='100%' class='calendar'>";
echo "<tr>";
    for ($i=1;$i<=7;$i++) {
        $dof_class = $i > 5 ? "dof-tbl-v" : "dof-tbl-b";
        echo "<td class='".$dof_class."' width='".round(100/7,2)."%'>".$locale['alcr_dof_'.$i]."</td>";
    }
echo "</tr>";
foreach ($weeks as $week) {
    echo "<tr valign='top'>";
        $i = 1;
        foreach ($week as $day) {
            $day_class_event = '';
            if ($day != '') {
                $day_class = $i > 5 ? "dof-tbl1-v" : "dof-tbl1-b";
                if (isset($events[$day])) {
                    $day_class_event = $i > 5 ? " dof-tbl1-event-v" : " dof-tbl1-event-b";
                }
            } else {
                $day_class = "dof-tbl-none";
            }
            echo "<td class='".$day_class.$day_class_event.(date('Y-m-j') == $year."-".$month."-".$day ? " dof-current" : "")."'>".($day != '' ? "<a href='".FUSION_SELF."?p=day&date=".$year."-".$month."-".$day."' class='td-link'>" : "")."<div style='width:".$cell_width.";' class='ellipsis'><span class='day-num ".($i > 5 ? "v" : "b")."'>".$day."</span>".($day != '' && alcr_can_admin($userdata['user_id']) ? " <img src='".IMAGES."plus.png' width='16' class='add-event-icon' data-url='".FUSION_SELF."?p=manage_event&date=".$year."-".$month."-".$day."' />" : "")."<br />";
            if (isset($events[$day])) {
                foreach ($events[$day] as $event) {
                    echo "<span class='".($event['alcr_event_confirm'] == 1 ? "event-confirmed" : "event-non-confirmed")."'>".substr($event['alcr_event_time'],0,5)." ".$event['alcr_event_title']."</span><br />";
                }
            }
            echo "</div>".($day != '' ? "</a>" : "")."</td>";
            $i++;
        }
    echo "</tr>";
}
echo "</table>";

echo "<script>
    $(document).ready(function(){
        var stock_url = '".FUSION_SELF."?p=index&';
        $('select[name=change_month]').change(function(){
            location.href = stock_url + 'month=' + $(this).val() + '&year=".$year."';
        });
        $('select[name=change_year]').change(function(){
            location.href = stock_url + 'month=".$month."&year=' + $(this).val();
        });
        $('.add-event-icon').click(function(event){
            event.preventDefault();
            location.href = $(this).attr('data-url');
        });
    });
</script>";


//echo showdate("forumdate",strtotime("2013-02-14 12:00:00"));


?>