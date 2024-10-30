<?php

namespace choco;

// $nextyear  = mktime(0, 0, 0, date("m"),   date("d"),   date("Y")+1);
// $nextyear  = mktime(0, 0, 0, date("m")+4,   date("d"),   date("Y"));

// echo $nextyear;

// echo date("Y-m-d H:i:s", $nextyear);

function choco_calendar($atts) {
    global $PluginUrl;

    extract(shortcode_atts(array(
        'classes' => '',
        'padding' => false,
        'monday' => false,
        // 'monday' => date("d"),
        'year' => date('Y'),
        'month' => date('m')
                    ), $atts));

    $html = "";

    $now = time();

    // $nextyear = mktime(0, 0, 0, date("m")+4,   date("d"),   date("Y"));

    /*
    $monday_add = 0;
    if (preg_match('/^([\+\-])([0-9]+)/', $monday, $matches)) {
        $monday_add = $monday;
    }
    */

    $month_add = 0;
    if (preg_match('/^([\+\-])([0-9]+)/', $month, $matches)) {
        $month_add = $month;
    }

    $year_add = 0;
    if (preg_match('/^([\+\-])([0-9]+)/', $year, $matches)) {
        $year_add = $year;
    }

    // $date = mktime(0, 0, 0, intval($month), 1, intval($year));
    // $date = mktime(0, 0, 0, $month+$month_add,   $monday+$monday_add,   $year+$year_add);
    // $date = mktime(0, 0, 0, $month+$month_add,   1,   $year+$year_add);
    // $date = strtotime(sprintf("%s day %s month %s year", $monday_add, $month_add, $year_add));
    $date = strtotime(sprintf("%s month %s year", $month_add, $year_add));

    $year = date('Y', $date);
    $month = date('m', $date);

    // $html .= $month_add;

    if (is_array($atts) && array_key_exists('id', $atts)) {
        $id = $atts['id'];
    } else {
        $id = sprintf("choco_calendar_%04d_%02d", $year, $month);
    }

    if (is_array($atts) && array_key_exists('caption_format', $atts)) {
        $caption_format = $atts['caption_format'];
    } else {
        $caption_format = "F Y";
    }
    $caption = date($caption_format, $date);

    //日,月,火,水,木,金,土
    if (is_array($atts) && array_key_exists('weeks', $atts)) {
        $week_array = explode(',', $atts['weeks']);
    } else {
        if (is_array($atts) && array_key_exists('week_format', $atts)) {
            $week_format = $atts['week_format'];
        } else {
            $week_format = "D";
        }
        $sunday = mktime(0, 0, 0, 12, 6, 2015); // Sunday
        for ($i = 0; $i < 7; $i++) {
            $week_array[$i] = date($week_format, $sunday + 60 * 60 * 24 * $i + 1);
        }
    }
    // first day of weekday is monday
    if ($monday) {
        $sun = array_shift($week_array);
        $week_array[6] = $sun;
    }


    if ($monday) {
        $firstDay = 1;
    } else {
        $firstDay = 0;
    }

    require_once __DIR__ . '/calendar_old.php';
    $cal = new Calendar();
    $cal->create($year, $month, 1, $firstDay);
    $cal_now = $cal->getNow(); // $cal['now'];

    $i = 0;
    //初期出力
    $html .= '<table id="' . $id . '" class="choco_calendar ' . $classes . '">';
    $html .= '<caption class="choco_caption">' . $caption . '</caption>';
    $html .= '<tbody>';
    $html .= '<tr>';
    $html .= '<th class="choco_th sun">' . $week_array[$i++] . '</th>';
    $html .= '<th class="choco_th">' . $week_array[$i++] . '</th>';
    $html .= '<th class="choco_th">' . $week_array[$i++] . '</th>';
    $html .= '<th class="choco_th">' . $week_array[$i++] . '</th>';
    $html .= '<th class="choco_th">' . $week_array[$i++] . '</th>';
    $html .= '<th class="choco_th">' . $week_array[$i++] . '</th>';
    $html .= '<th class="choco_th sat">' . $week_array[$i++] . '</th>';
    $html .= '</tr>';


    $w = 0;
    while ($dat = $cal->getNext()) {
        $y = $dat['year'];
        $m = $dat['month'];
        $d = $dat['date'];

        if ($w == 0) {
            $html .= '<tr>';
        }
        if ($cal_now['year'] == $y && $cal_now['month'] == $m && $cal_now['date'] == $d) {
            $today = 'today';
        } else {
            $today = '';
        }
        if ($m == $month || $padding) {
            $html .= '<td class="choco_td ' . $today . '">' . $d . '</td>';
        } else {
            $html .= '<td class="choco_td"> </td>';
        }
        if ($w == 6) {
            $w = 0;
            $html .= '</tr>';
        } else {
            $w++;
        }
    }

    $html .= '</tbody>';
    $html .= '</table>';

    return $html;
}

function choco_calendar_repeatEmptyTd($n = 0) {
    echo "<p>$n</p>";
    return str_repeat("<td class=\"choco_td\"> </td>", $n);
}

add_shortcode('choco_calendar', 'choco\choco_calendar');

if (!shortcode_exists('calendar')) {
    add_shortcode('calendar', 'choco\choco_calendar');
}

