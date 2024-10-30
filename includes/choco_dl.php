<?php

namespace choco;

/*
 * [choco_dl tag="div" name="table" style="width:80%;min-height:1em;margin:0 auto;" text="Click Here!"]
 *
 * <div class="choco_dl_1" style="width:80%;min-height:1em;margin:0 auto;">Click Here!</div>
 */

function choco_dl($atts, $content = null, $tag) {
    // echo $tag; // own name
    // print_r($atts);
    // printf("[%s]", $content);

    extract(shortcode_atts(array(
        'id' => '',
        'classes' => '',
        'style' => '',
                    ), $atts));

    if ($id) {
        $id = 'id="'.$id.'"';
    }
    if ($tag == 'timeline') {
        $classes .= ' timeline';
    }
    if ($classes) {
        $classes = 'class="'.trim($classes).'"';
    }
    if ($style) {
        $style = 'style="'.$style.'"';
    }

    $lines = explode("\n", $content);
    $dl = '';
    $ln = 0;
    foreach ($lines as $i => $line) {
        // $line = trim(preg_replace('/[\r\n]/', '', $line));
        $line = trim($line);
        /*
        if ($i === 0) {
            printf("[%s]", $line);
        }
        */
        if (!$line || $line === '<br>' || $line === '<br />') {
            continue;
        }
        if (strpos($line, '|') !== false) {
            if ($ln > 0) {
                $dl .= '</dd>';
            }
            $dl .= '<dt>' . preg_replace('/\|/', "</dt><dd>", $line, 1); // . '</dd>';
            $ln++;
        } else {
            $dl .= "\n".$line;
        }
    }
    // $dl = str_replace("\\n", "\n", $dl);

    $dl = do_shortcode($dl); // ショートコードの入れ子に対応する場合
    return "<dl $id $classes $style>".$dl.'</dd></dl>';
    // return preg_replace('/[\r\n]/', '', "<dl $id $classes $style>".$dl.'</dd></dl>');
}

add_shortcode('choco_dl', 'choco\choco_dl');

if (!shortcode_exists('list')) {
    add_shortcode('list', 'choco\choco_dl');
}

if (!shortcode_exists('timeline')) {
    add_shortcode('timeline', 'choco\choco_dl');
}
