<?php defined('IN_FUSION') or die;
require_once INFUSIONS."al_rent_calendar/infusion_db.php";
if (file_exists(INFUSIONS."al_rent_calendar/locale/".$settings['locale'].".php")) {
    include INFUSIONS."al_rent_calendar/locale/".$settings['locale'].".php";
} else {
    include INFUSIONS."al_rent_calendar/locale/Russian.php";
}
require_once INFUSIONS."al_rent_calendar/includes/functions.php";

function show_rent_calendar($album_id) {

    global $locale;
    add_to_head("<link rel='stylesheet' href='".INFUSIONS."al_rent_calendar/asset/rent_calendar.css' />");
    add_to_head("<script src='".INFUSIONS."al_rent_calendar/asset/rent_calendar.js'></script>");

    $result = dbquery("SELECT * FROM ".DB_AL_RC_SPECIAL_DAYS." WHERE alrc_special_album_id='".$album_id."' ORDER BY alrc_special_date_start ASC");
    if (dbrows($result)) {
        opentable($locale['alrc3']);
            echo "<table width='100%'>";
                echo "<tr>";
                    echo "<td class='tbl2'>".$locale['alrc4']."</td>";
                    echo "<td class='tbl2'>".$locale['alrc5']."</td>";
                    echo "<td class='tbl2'>".$locale['alrc6']."</td>";
                    echo "<td class='tbl2'>".$locale['alrc7']."</td>";
                echo "</tr>";
                while ($data = dbarray($result)) {
                    $start_date = explode('-',$data['alrc_special_date_start']);
                    $finish_date = explode('-',$data['alrc_special_date_finish']);
                    echo "<tr>";
                        echo "<td class='tbl'>".$data['alrc_special_title']." ".$start_date[2]." ".$locale['alrc_month_short_'.trim($start_date[1],'0')]." ".$start_date[0]." - ".$finish_date[2]." ".$locale['alrc_month_short_'.trim($finish_date[1],'0')]." ".$finish_date[0]."</td>";
                        echo "<td class='tbl'>".$data['alrc_special_min_nights']."</td>";
                        echo "<td class='tbl'>".$data['alrc_special_cost_two_person']."</td>";
                        echo "<td class='tbl'>".$data['alrc_special_cost_next_person']."</td>";
                    echo "</tr>";
                }
            echo "</table>";
        closetable();
    }

    opentable($locale['alrc8']);

    /////////////////////////////////
        $total_monthes = 12;
        $per_page = 3;
    /////////////////////////////////

        $start_month = date('n');
        $start_year = date('Y');

            $finish_year = $start_year + floor(($start_month+$total_monthes-1)/12);

            $i = 0;
            $page = 1;

            echo "<div class='calendar-page page1'>";

            for ($y=$start_year;$y<=$finish_year;$y++) {



                if ($y==$finish_year && $y==$start_year) {
                    $cur_month_start = $start_month;
                    $cur_month_finish = $cur_month_start+$total_monthes-1;
                } else if ($y==$start_year && $finish_year > $y) {
                    $cur_month_start = $start_month;
                    $cur_month_finish = 12;
                } else if ($y>$start_year && $y==$finish_year) {
                    $cur_month_start = 1;
                    $cur_month_finish = ($start_month+$total_monthes-1)%12;
                } else {
                    $cur_month_start = 1;
                    $cur_month_finish = 12;
                }

                for ($m=$cur_month_start;$m<=$cur_month_finish;$m++) {
                    if ($i%$per_page == 0 && $i != 0) {
                        $page++;
                        echo "<div class='clear'></div></div><div class='calendar-page page".$page."'>";
                    }
                    build_month($m, $y, get_month($m,$y), get_month_data($album_id,$m,$y));
                    $i++;
                }
            }

            echo "<div class='clear'></div></div>";
            echo "<div class='calendar-map'>";
                echo "<div class='calendar-map-item'>";
                    echo "<div class='calendar-map-item-icon calendar-map-item-icon-type1'></div>";
                    echo "<div class='calendar-map-item-desc'>".$locale['alrc31']."</div>";
                echo "</div>";
                echo "<div class='calendar-map-item'>";
                    echo "<div class='calendar-map-item-icon calendar-map-item-icon-type2'></div>";
                    echo "<div class='calendar-map-item-desc'>".$locale['alrc32']."</div>";
                echo "</div>";
                echo "<div class='calendar-map-item'>";
                    echo "<div class='calendar-map-item-icon calendar-map-item-icon-type3'></div>";
                    echo "<div class='calendar-map-item-desc'>".$locale['alrc33']."</div>";
                echo "</div>";
                echo "<div class='calendar-map-item'>";
                    echo "<div class='calendar-map-item-icon calendar-map-item-icon-type4'></div>";
                    echo "<div class='calendar-map-item-desc'>".$locale['alrc34']."</div>";
                echo "</div>";
                echo "<div class='clear'></div>";
            echo "</div>";
            echo "<div class='calendar-nav-container'>";
                echo "<div class='calendar-nav calendar-nav-prev' data-nav-type='prev' data-current-page='1'>".$locale['alrc29']."</div>";
                echo "<div class='calendar-nav calendar-nav-next' data-nav-type='next' data-current-page='1'>".$locale['alrc30']."</div>";
                echo "<div class='clear'></div>";
            echo "</div>";


    closetable();


}

?>