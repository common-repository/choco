<?php
/*
choco_calendar or calendar

id              :
distinction     :
classes         :
fill_blank      : 0 or 1
weeks           : "日,月,火,水,木,金,土"
week_format     : D: Mon - Sun, l: Sunday - Saturday, N: 1 (月曜日) - 7 (日曜日), w: 数値 0 (日曜日) - 6 (土曜日)
caption_format  : 'F Y'
first_day_monday: 0 or 1
year            : 2018, -3, +3
month           : 11, -3, +3
events          : 'thu: Closed|sun: <br>Event|4: 444|6: 666|3sun: 3S|4tue: 5T',
*/

/*
format 文字 	説明 	戻り値の例
日 	--- 	---
d 	日。二桁の数字（先頭にゼロがつく場合も） 	01 から 31
D 	曜日。3文字のテキスト形式。 	Mon から Sun
j 	日。先頭にゼロをつけない。 	1 から 31
l (小文字の 'L') 	曜日。フルスペル形式。 	Sunday から Saturday
N 	ISO-8601 形式の、曜日の数値表現 (PHP 5.1.0 で追加)。 	1（月曜日）から 7（日曜日）
S 	英語形式の序数を表すサフィックス。2 文字。 	st, nd, rd または th。 jと一緒に使用する ことができる。
w 	曜日。数値。 	0 (日曜)から 6 (土曜)
z 	年間の通算日。数字。(ゼロから開始) 	0 から 365
週 	--- 	---
W 	ISO-8601 月曜日に始まる年単位の週番号 	例: 42 (年の第 42 週目)
月 	--- 	---
F 	月。フルスペルの文字。 	January から December
m 	月。数字。先頭にゼロをつける。 	01 から 12
M 	月。3 文字形式。 	Jan から Dec
n 	月。数字。先頭にゼロをつけない。 	1 から 12
t 	指定した月の日数。 	28 から 31
年 	--- 	---
L 	閏年であるかどうか。 	1なら閏年。0なら閏年ではない。
o 	ISO-8601 形式の週番号による年。これは Y ほぼ同じだが、ISO 週番号 （W）が前年あるいは翌年に属する場合はそちらの年を使うという点が異なる（PHP 5.1.0 で追加）。 	例: 1999 あるいは 2003
Y 	年。4 桁の数字。 	例: 1999または2003
y 	年。2 桁の数字。 	例: 99 または 03
時 	--- 	---
a 	午前または午後（小文字） 	am または pm
A 	午前または午後（大文字） 	AM または PM
B 	Swatch インターネット時間 	000 から 999
g 	時。12時間単位。先頭にゼロを付けない。 	1 から 12
G 	時。24時間単位。先頭にゼロを付けない。 	0 から 23
h 	時。数字。12 時間単位。 	01 から 12
H 	時。数字。24 時間単位。 	00 から 23
i 	分。先頭にゼロをつける。 	00 から 59
s 	秒。先頭にゼロをつける。 	00 から 59
u 	マイクロ秒 (PHP 5.2.2 で追加)。 date() の場合、これは常に 000000 となることに注意しましょう。というのも、 この関数が受け取るパラメータは integer 型だからです。 一方 DateTime をマイクロ秒つきで作成した場合は、 DateTime::format() はマイクロ秒にも対応しています。 	例: 654321
v 	ミリ秒 (PHP 7.0.0 で追加) uと同じ注釈が当てはまります。 	例: 654
タイムゾーン 	--- 	---
e 	タイムゾーン識別子（PHP 5.1.0 で追加） 	例: UTC, GMT, Atlantic/Azores
I (大文字の i) 	サマータイム中か否か 	1ならサマータイム中。 0ならそうではない。
O 	グリニッジ標準時 (GMT) との時差 	例: +0200
P 	グリニッジ標準時 (GMT) との時差。時間と分をコロンで区切った形式 (PHP 5.1.3 で追加)。 	例: +02:00
T 	タイムゾーンの略称 	例: EST, MDT ...
Z 	タイムゾーンのオフセット秒数。 UTC の西側のタイムゾーン用のオフセットは常に負です。そして、 UTC の東側のオフセットは常に正です。 	-43200 から 50400
全ての日付/時刻 	--- 	---
c 	ISO 8601 日付 (PHP 5 で追加されました) 	2004-02-12T15:19:21+00:00
r 	» RFC 2822 フォーマットされた日付 	例: Thu, 21 Dec 2000 16:01:07 +0200
U 	Unix Epoch (1970 年 1 月 1 日 0 時 0 分 0 秒) からの秒数 	time() も参照
*/

namespace choco_calendar;

$selector_index = array();

function choco_calendar($atts) {
    global $PluginUrl;
    global $post;
    global $selector_index;

    extract(shortcode_atts(array(
        'id' => '',
        'distinction' => '',
        'classes' => 'default',
        'fill_blank' => false,
        'weeks' => '',
        'week_format' => 'D',
        'caption_format' => 'F Y',
        'first_day_monday' => false,
        'year' => date('Y'),
        'month' => date('m'),
        'events' => '', // 'thu: Closed|sun: <br>Event|4: 4th|6: 6th|3sun: 3rd Sun|4tue: 4th Tue',
    ), $atts));

    if (array_key_exists($post->ID, $selector_index)) {
        $selector_index[$post->ID]++;
    } else {
        $selector_index[$post->ID] = 1;
    }
    if (!$distinction) {
        $distinction = $post->ID.'-'.$selector_index[$post->ID];
    }
    $distinction = preg_replace('/[^a-zA-Z0-9_\-]/', '',  $distinction);

    // echo $events;
    // タグを利用可能にして、禁止タグを取り除き、配列に変換
    $events = eventToArray(strip_tags(htmlspecialchars_decode($events), '<title><h1><h2><h3><h4><h5><h6><hr><hr/><br><br/><p><center><div><pre><span><blockquote><address><font><i><tt><b><u><strike><big><small><sub><sup><em><strong><code><ul><ol><li><dl><dt><dd><table><tbody><tr><th><td><caption><a><img><img/>'));

    $w7class = array('sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat');

    $classes = explode(' ', $classes);
    if ($fill_blank) {
        $classes[] = 'fill_blank';
    }
    $classes = join(' ', $classes);

    $html = '';

    // $now = time();

    $month_add = 0;
    if (preg_match('/^([\+\-])([0-9]+)/', $month, $matches)) {
        $month_add = $month;
    }

    $year_add = 0;
    if (preg_match('/^([\+\-])([0-9]+)/', $year, $matches)) {
        $year_add = $year;
    }

    $date = strtotime(sprintf("%s month %s year", $month_add, $year_add));

    $year = date('Y', $date);
    $month = date('m', $date);

    $caption = date($caption_format, $date);

    // 日,月,火,水,木,金,土
    $week_array = explode(',', $weeks);
    if (count($week_array) < 7) {
        $sunday = mktime(0, 0, 0, 12, 6, 2015); // Sunday
        for ($i = 0; $i < 7; $i++) {
            $week_array[$i] = date($week_format, $sunday + 60 * 60 * 24 * $i + 1);
        }
    }
    // first day of weekday is monday
    if ($first_day_monday) {
        $sun = array_shift($week_array);
        $week_array[6] = $sun;

        $sun = array_shift($w7class);
        $w7class[6] = $sun;
    }

    if ($first_day_monday) {
        $firstDay = 1;
    } else {
        $firstDay = 0;
    }

    require_once __DIR__ . '/calendar.php';
    $cal = new Calendar();
    $cal->create($year, $month, 1, $firstDay);
    $cal_now = $cal->getNow(); // $cal['now'];

    if (!$id) {
        $id = sprintf("choco-calendar_%s_%04d_%02d", $distinction, $year, $month);
    }

    $record = getData('#'.$id);  // selector
    $innerTable = htmlspecialchars_decode($record['data']);

    $i = 0;
    // 初期出力
    $html .= '<table id="' . $id . '" class="choco-calendar ' . $classes . '">';

    if ($innerTable) {
        // save data is exists
        $html .= $innerTable;
    } else {
        // make inner table

        if ($caption) {
            $html .= '<caption>' . $caption . '</caption>';
        }
        $html .= '<tbody>';
        $html .= '<tr>';
        $html .= '<th class="'.$w7class[$i].'">' . $week_array[$i++] . '</th>';
        $html .= '<th class="'.$w7class[$i].'">' . $week_array[$i++] . '</th>';
        $html .= '<th class="'.$w7class[$i].'">' . $week_array[$i++] . '</th>';
        $html .= '<th class="'.$w7class[$i].'">' . $week_array[$i++] . '</th>';
        $html .= '<th class="'.$w7class[$i].'">' . $week_array[$i++] . '</th>';
        $html .= '<th class="'.$w7class[$i].'">' . $week_array[$i++] . '</th>';
        $html .= '<th class="'.$w7class[$i].'">' . $week_array[$i++] . '</th>';
        $html .= '</tr>';


        $weekdayCount = array('sun' => 0, 'mon' => 0, 'tue' => 0, 'wed' => 0, 'thu' => 0, 'fri' => 0, 'sat' => 0);
        $w = 0;
        while ($dat = $cal->getNext()) {
            $y = $dat['year'];
            $m = $dat['month'];
            $d = $dat['date'];

            if ($m == $month) {
                $weekdayCount[$w7class[$w]]++;
            }

            // events
            $event = '';
            if (array_key_exists($d, $events)) {
                $event .= $events[$d];
            }
            if (array_key_exists($w7class[$w], $events)) {
                $event .= $events[$w7class[$w]];
            }
            $nWeek = sprintf("%d%s", $weekdayCount[$w7class[$w]], $w7class[$w]);
            if (array_key_exists($nWeek, $events)) {
                $event .= $events[$nWeek];
            }

            if ($w == 0) {
                $html .= '<tr>';
            }
            if ($cal_now['year'] == $y && $cal_now['month'] == $m && $cal_now['date'] == $d) {
                $today = ' today';
            } else {
                $today = '';
            }

            if ($m == $month) {
                $html .= '<td class="' . $w7class[$w] . $today . '">' . $d . $event . '</td>';
            } else if ($m < $month) {
                if ($fill_blank) {
                    $html .= '<td class="' . $w7class[$w] . $today . ' prev-month">' . $d . $event . '</td>';
                } else {
                    $html .= '<td class="' . $w7class[$w] . $today . ' prev-month"> </td>';
                }
            } else if ($m > $month && $fill_blank) {
                if ($fill_blank) {
                    $html .= '<td class="' . $w7class[$w] . $today . ' next-month">' . $d . $event . '</td>';
                } else {
                    $html .= '<td class="' . $w7class[$w] . $today . ' next-month"> </td>';
                }
            } else {
                $html .= '<td> </td>';
            }

            if ($w == 6) {
                $w = 0;
                $html .= '</tr>';
            } else {
                $w++;
            }
        }
        // end of make inner table
    }

    $html .= '</tbody>';
    $html .= '</table>';

    return $html;
}

function eventToArray($add) {
    $result = array();

    $records = explode('|', $add);
    foreach ($records as $i => $record) {
        $fields = explode(':', $record, 2);
        if (count($fields) != 2 || !$fields[0] || !$fields[1]) {
            continue;
        }
        $result += array($fields[0] => $fields[1]);
    }
    return $result;
}

add_shortcode('choco-calendar', 'choco_calendar\choco_calendar');
add_shortcode('choco_calendar', 'choco_calendar\choco_calendar');

if (!shortcode_exists('calendar')) {
    add_shortcode('calendar', 'choco_calendar\choco_calendar');
}
