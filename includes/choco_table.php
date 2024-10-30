<?php

namespace choco;

/*
 * [choco_table tag="div" name="table" style="width:80%;min-height:1em;margin:0 auto;" text="Click Here!"]
 *
 * <div class="choco_table_1" style="width:80%;min-height:1em;margin:0 auto;">Click Here!</div>
 */

function choco_table($atts, $content = null) {
    // print_r($atts);
    // printf("[%s]", $content);

    extract(shortcode_atts(array(
        'id' => '',
        'classes' => 'border',
        'caption' => '',
        'title' => '', // col, row, both
        'style' => '',
                    ), $atts));

    if ($id) {
        $id = 'id="'.$id.'"';
    }
    // if ($classes) {
    $classes = 'class="choco '.$classes.'"';
    // }
    if ($style) {
        $style = 'style="'.$style.'"';
    }
    if ($caption) {
        $caption = "<caption>$caption</caption>";
    }

    $lines = explode("\n", $content);
    $table = '';
    $ln = 0;
    foreach ($lines as $i => $line) {
        $line = trim($line);
        /*
        if ($i === 0) {
            printf("[%s]", $line);
        }
        */
        if (!$line || $line === '<br>' || $line === '<br />') {
            continue;
        }
        if (($title === 'col' || $title === 'both') && $ln === 0) {
            $table .= '<tr><th>' . str_replace("|", "</th><th>", $line) . '</th></tr>';
        } else if ($title === 'row' || $title === 'both') {
            $line = '<tr><th>' . preg_replace('/\|/', "</th><td>", $line, 1);
            $table .= str_replace("|", "</td><td>", $line) . '</td></tr>';
        } else {
            $table .= '<tr><td>' . str_replace("|", "</td><td>", $line) . '</td></tr>';
        }
        $ln++;
    }
    // $table = str_replace("\\n", "\n", $table);

    $table = do_shortcode($table); // ショートコードの入れ子に対応する場合
    return "<table $id $classes $style>$caption<tbody>".$table.'</tbody></table>';
}

add_shortcode('choco_table', 'choco\choco_table');

if (!shortcode_exists('table')) {
    add_shortcode('table', 'choco\choco_table');
}
