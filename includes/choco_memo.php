<?php

namespace choco;

/*
 * [choco_memo tag="div" name="memo" style="width:80%;min-height:1em;margin:0 auto;" text="Click Here!"]
 *
 * <div class="choco_memo_1" style="width:80%;min-height:1em;margin:0 auto;">Click Here!</div>
 */

function choco_memo($atts) {

    extract(shortcode_atts(array(
        'tag' => 'span',
        'id' => '',
        'classes' => '',
        'name' => 'memo',
        'style' => 'min-width:20%; min-height:1em;',
        'text' => 'Click Here!'
                    ), $atts));

    if ($id) {
        $id = 'id="'.$id.'"';
    }

    return sprintf("<%s %s class=\"choco_%s %s\" style=\"%s\">%s</%s>", $tag, $id, $name, $classes, $style, $text, $tag);
}

add_shortcode('choco_memo', 'choco\choco_memo');

if (!shortcode_exists('memo')) {
    add_shortcode('memo', 'choco\choco_memo');
}

