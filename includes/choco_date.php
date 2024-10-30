<?php

namespace choco;

function choco_date($atts) {
    global $PluginUrl;

    extract(shortcode_atts(array(
        'tag' => 'span',
        'id' => '',
        'classes' => '',
        'year' => '',
        'month' => '',
        'date' => '',
        'tz' => '',
        'format' => 'l jS \of F Y h:i:s A',
    ), $atts));

    //日,月,火,水,木,金,土
    if (is_array($atts) && array_key_exists('weeks', $atts)) {
        $week_array = explode(',', $atts['weeks']);
    }

    $save_tz = '';
    if ($tz) {
        $save_tz = date_default_timezone_get();
        // 使用するデフォルトのタイムゾーンを指定します。PHP 5.1 以降で使用可能です。
        date_default_timezone_set($tz);
    }

    $now = time();

    if ($date === '') {
        $date = date('j', $now);
    } else if (preg_match('/^([\+\-])([0-9]+)/', $date, $matches)) {
        $date = date('j', $now) + $date;
    }

    $year_diff = 0;
    if ($month === '') {
        $month = date('n', $now);
    } else if (preg_match('/^([\+\-])([0-9]+)/', $month, $matches)) {
        $month = date('n', $now) + $month;
        if ($month < 1) {
            $month = 12 + $month % 12;
            $year_diff -= 1;
        } else if ($month > 12) {
            $month = $month % 12 - 12;
            $year_diff += 1;
        }
    }

    if ($year === '') {
        $year = date('Y', $now);
    } else if (preg_match('/^([\+\-])([0-9]+)/', $year, $matches)) {
        $year = date('Y', $now) + $year;
    }
    $year += $year_diff;

    $mkdate = mktime(0, 0, 0, intval($month), intval($date), intval($year));
    $year = date('Y', $date);
    $month = date('n', $date);
    $date = date('j', $date);
    $week = date('w', $date);

    $formatedDateString = date($format, $mkdate);

    if (is_array($week_array)) {
        $formatedDateString = str_replace('$k', $week_array[$week], $formatedDateString);
    }

    if (is_array($atts) && array_key_exists('id', $atts)) {
        $id = $atts['id'];
    } else {
        $id = "choco_date_shortcode";
    }

    if ($classes) {
        $classes = 'class="' . $classes . '"';
    } else {
        $classes = 'class="choco_date"';
    }

    $html = '';
    if ($tag != '') {
        $html .= '<' . $tag . ' id="' . $id . '" ' . $classes . '>';
    }

    $html .= $formatedDateString;

    if ($tag != '') {
        $html .= '</' . $tag . '>';
    }

    if ($save_tz) {
        date_default_timezone_set($save_tz);
    }

    return $html;
}

add_shortcode('choco_date', 'choco\choco_date');

