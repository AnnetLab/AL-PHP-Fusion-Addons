<?php defined('IN_FUSION') or die;

function get_month_data($album_id, $month, $year) {


    $month_data = array();
    $dayofmonth = date('t',mktime(0,0,0,$month,1,$year));
    $result = dbquery("SELECT * FROM ".DB_AL_RC_RENTED_DAYS."
        WHERE alrc_rented_album_id='".$album_id."'
        AND (YEAR(alrc_rented_date_start)<='".$year."' AND YEAR(alrc_rented_date_finish)>='".$year."')
        AND (MONTH(alrc_rented_date_start)<='".$month."' AND MONTH(alrc_rented_date_finish)>='".$month."')
    ");
    if (dbrows($result)) {
        while ($data = dbarray($result)) {
            list($start_y,$start_m,$start_d) = explode('-',$data['alrc_rented_date_start']);
            list($finish_y,$finish_m,$finish_d) = explode('-',$data['alrc_rented_date_finish']);
            $start_y = (int) $start_y;
            $start_m = (int) $start_m;
            $start_d = (int) $start_d;
            $finish_y = (int) $finish_y;
            $finish_m = (int) $finish_m;
            $finish_d = (int) $finish_d;

            $month_data[$start_y][$start_m][$start_d]['type'][] = 1;
            $month_data[$finish_y][$finish_m][$finish_d]['type'][] = 3;

            if (($start_m < $month && $finish_m > $month) || ($start_m == 11 && $finish_m == 1)) {
                $start = 1; $finish = $dayofmonth;
            } else if (($start_m < $month && $finish_m == $month) || ($start_m == 12 && $finish_m == 1)) {
                $start = 1; $finish = $finish_d;
            } else if ($start_m == $month && $finish_m == $month) {
                $start = $start_d; $finish = $finish_d;
            } else if ($start_m == $month && $finish_m > $month) {
                $start = $start_d; $finish = $dayofmonth;
            }

            for ($d=$start;$d<=$finish;$d++) {
                $month_data[$year][$month][$d]['type'][] = 2;
            }
        }
    }

    $result = dbquery("SELECT * FROM ".DB_AL_RC_SPECIAL_DAYS."
        WHERE alrc_special_album_id='".$album_id."'
        AND (YEAR(alrc_special_date_start)<='".$year."' AND YEAR(alrc_special_date_finish)>='".$year."')
        AND (MONTH(alrc_special_date_start)<='".$month."' AND MONTH(alrc_special_date_finish)>='".$month."' )
    ");
    if (dbrows($result)) {
        while ($data = dbarray($result)) {
            list($start_y,$start_m,$start_d) = explode('-',$data['alrc_special_date_start']);
            list($finish_y,$finish_m,$finish_d) = explode('-',$data['alrc_special_date_finish']);
            $start_y = (int) $start_y;
            $start_m = (int) $start_m;
            $start_d = (int) $start_d;
            $finish_y = (int) $finish_y;
            $finish_m = (int) $finish_m;
            $finish_d = (int) $finish_d;

            if (($start_m < $month && $finish_m > $month) || ($start_m == 11 && $finish_m == 1)) {
                $start = 1; $finish = $dayofmonth;
            } else if (($start_m < $month && $finish_m == $month) || ($start_m == 12 && $finish_m == 1)) {
                $start = 1; $finish = $finish_d;
            } else if ($start_m == $month && $finish_m == $month) {
                $start = $start_d; $finish = $finish_d;
            } else if ($start_m == $month && $finish_m > $month) {
                $start = $start_d; $finish = $dayofmonth;
            }

            for ($d=$start;$d<=$finish;$d++) {
                $month_data[$year][$month][$d]['special'] = true;
            }

        }
    }
    return $month_data;


}

function get_month($month, $year) {

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

function build_month($month, $year, $weeks, $month_data) {

    global $locale;

    echo "<table class='calendar-table' cellpadding='0' cellspacing='1'>";
    echo "<tr>";
    echo "<td colspan='7' class='calendar-cap'>".$locale['alrc_m_'.$month]." ".$year."</td>";
    echo "</tr>";
    echo "<tr>";
        for ($i=1;$i<=7;$i++) {
            echo "<td class='calendar-day-cap'>".$locale['alrc_w_'.$i]."</td>";
        }
    echo "</tr>";
    foreach ($weeks as $week) {
        echo "<tr class='calendar-tr'>";
        foreach ($week as $day) {
            if (!empty($day)) {
                $cell_style = 'type-none';
                if (isset($month_data[$year][$month][$day])) {
                    if (in_array('1',$month_data[$year][$month][$day]['type']) && in_array('3',$month_data[$year][$month][$day]['type'])) {
                        $cell_style = 'type2';
                    } else if (in_array('1',$month_data[$year][$month][$day]['type'])) {
                        $cell_style = 'type1';
                    } else if (in_array('3',$month_data[$year][$month][$day]['type'])) {
                        $cell_style = 'type3';
                    } else if (in_array('2',$month_data[$year][$month][$day]['type'])) {
                        $cell_style = 'type2';
                    }
                    if (isset($month_data[$year][$month][$day]['special']) && $month_data[$year][$month][$day]['special']) {
                        $cell_style .= ' special';
                    }
                }
                echo "<td class='calendar-td ".$cell_style."'>".$day."</td>";
            } else {
                echo "<td class='calendar-td null'>".$day."</td>";
            }
        }
        echo "</tr>";
    }
    echo "</table>";

}


?>