<?php

namespace choco;

/*
 * [choco_tag div id="" classes="" style="color:red;"]Test[/choco_tag]
 * [choco_tag br] or [tag br] or [br] or [lf]
 *
 */

function choco_tag($atts, $content = null, $tag) {
    // echo $tag; // own name
    if (!is_array($atts)) {
        $atts = array('');
    }
    // print_r($atts);
    // printf("[%s]", $content);
    // $htmlTag = strtolower(array_shift($atts));
    $htmlTag = strtolower($atts[0]);

    // new line
    if ($htmlTag === "lf" || $htmlTag === "br" || $tag === "lf" || $tag === "br") {
        return "<br />";
    }

    extract(shortcode_atts(array(
        'id' => '',
        'classes' => '',
        'style' => '',
        ), $atts));

    $starStyle = 'margin-left: 1em; text-indent: -1em; display: block; ';
    if ($tag === "star") {
        // $tag = "star";
        if (!$htmlTag) {
            $htmlTag = "span";
        }
        $style = $starStyle . $style;
    }
    if ($htmlTag === "star") {
        // $tag = "star";
        $htmlTag = "span";
        $style = $starStyle . $style;
    }

    if ($id) {
        $id = 'id="'.$id.'"';
    }
    if ($classes) {
        $classes = 'class="'.trim($classes).'"';
    }
    if ($style) {
        $style = 'style="'.$style.'"';
    }

    $content = do_shortcode($content); // ショートコードの入れ子に対応

    return "<$htmlTag $id $classes $style>$content</$htmlTag>";
}

add_shortcode('choco_tag', 'choco\choco_tag');

if (!shortcode_exists('tag')) {
    add_shortcode('tag', 'choco\choco_tag');

}
if (!shortcode_exists('lf')) {
    add_shortcode('lf', 'choco\choco_tag');
}
if (!shortcode_exists('br')) {
    add_shortcode('br', 'choco\choco_tag');
}

if (!shortcode_exists('star')) {
    add_shortcode('star', 'choco\choco_tag');
}
