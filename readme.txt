=== choco ===
Contributors: AI.Takeuchi
Description: Rewrite content by ajax, using TinyMCE. Provide rewrite areas. These is html block, embed calendar, date, temporary information.
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=P8LCVREFDKWFW
Tags: plugin, ajax, TinyMCE, embed calendar, date, temporary information.
Requires at least: 4.1
Tested up to: 4.9.5
Stable tag: 1.33
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Rewrite content by ajax, using TinyMCE.
Provide rewrite areas. These is html block, embed calendar, date, temporary information.

== Installation ==

1. Install plugin and activate
2. Write Shortcodes, Html Blocks.

== Usage ==

* Write shortcode or html tag with choco class.

= calendar shortcode =

    * You can use the new 'choco calendar'. Activate plugin 'choco calendar'.

    e.g.

        [choco_calendar]

        [choco_calendar year=2015 month=4]

        [choco_calendar month=-4 year=+1 caption_format="Y F" weeks="日,月,火,水,木,金,土" week_format=l monday=true padding=true classes="class1 class2"]

    value:

        year: 2015, 2016, or ..., -2, -1, +1, +2, ...
        month: 1 to 12 or ..., -2, -1, +1, +2, ...
        caption_format: "F Y", "Y年n月", ...
        week_format: l, D, ...
        weeks: "S,M,T,W,T,F,S", "日,月,火,水,木,金,土", ...
        monday: true or nothing
        padding: true or nothing
        classes: user define classes "class1 class2 ..."

        See date/time format: http://php.net/manual/en/function.date.php
        (japanese: http://php.net/manual/ja/function.date.php)


= date shortcode =

    e.g.

        [choco_date]

        Copyright &copy; 2015 - [choco_date format='Y' classes='not_choco'] example.com

        [choco_date year=2016 month=5 date=17]

        [choco_date month=-4 year=+1 format="Y F $k" weeks="日,月,火,水,木,金,土" id="next" classes="class1 class2"]

    value:

        year: 2015, 2016, or ..., -2, -1, +1, +2, ...
        month: 1 to 12 or ..., -2, -1, +1, +2, ...
        format: "F Y", "Y年n月", ...
        weeks: "S,M,T,W,T,F,S", "日,月,火,水,木,金,土", ...
        tag: span, div, ...
        id: user define id "class1 class2 ..."
        classes: user define classes "class1 class2 ..."
        tz: "GMT", "JST", ...

        See date/time format: http://php.net/manual/en/function.date.php
        (japanese: http://php.net/manual/ja/function.date.php)


= table shortcode =

    e.g.
        [choco_table id="table-1" classes="clear full" caption="Title" title="col" style="border 1px solid #eee;"]
        test|123
        echo|123\n456
        [/choco_table]

    value:
        id: user define id
        classes: user define classes "class1 class2 ..."
        caption: table caption
        title: 'col' or 'row' or 'both'
        style: css style string


= definition list shortcode =

    e.g.
        [choco_dl id="list-1" classes="clear full" style="margin: 0; padding: 0;"]
        test|123\n456
        echo|123
        456
        [/choco_dl]

    value:
        id: user define id
        classes: user define classes "class1 class2 ..."
        style: css style string

= such temporary memo or information =

    e.g.
        [choco_memo tag="div" name="memo" style="width:80%;min-height:1em;margin:0 auto;" text="Click Here!"]

        above shortcode output below tag:

        `<div class="choco_memo" style="width:80%;min-height:1em;margin:0 auto;">Click Here!</div>`

        * Can rewrite if added class choco_xxx to html tag.

    e.g.
        It can be summarized in the id as follows.
        will be id addition to when save database. use this when it is used in more than one content.

        `
        <div id="choco_memo>
            <div class="choco" style="width:80%;min-height:1em;margin:0 auto;">Click Here 1</div>
            <div class="choco" style="width:80%;min-height:1em;margin:0 auto;">Click Here 2</div>
        </div>
        `

    e.g.
        hidden after loaded if specify class 'choco_hide_after_load'.

        [choco_memo tag="div" name="memo" classes="choco_hide_after_load" style="text-align:center;width:80%;min-height:1em;margin:0 auto;" text="Now Loading..."]

= other =

    written text is save to database.
    table name is 'wp_choco_data'.
    if changed table_prefix variable in wp-config.php, so that.
    will be delete this table when uninstall this plugin.


== Other ==

This plugin using TinyMCE https://www.tinymce.com/, https://github.com/tinymce/tinymce-dist
License is GNU LESSER GENERAL PUBLIC LICENSE.

Using cursor icon School Pencil Created By Matt Jenkins.
http://www.cursors-4u.com/

== Changelog ==

= 1.33 =

* same changed.

= 1.32 =

* fix weeks parameter.

= 1.31 =

New calendar shortcode added, need be activated 'choco calendar' plugin.

= 1.26 =

Fix nested shortcode function.

= 1.25 =

Fix nested shortcode function.

= 1.24 =

Fix choco_calendar shortcode classes option.

= 1.23 =

change table classes

= 1.22 =

added shortcode: choco_tag (alias: tag, br, lf)

= 1.21 =

define shortcode aliases.
choco_calendar as calendar, choco_table as table, choco_dl as list and timeline, choco_memo as memo.
timeline alias is same below [choco_dl classes="timeline ..."]

= 1.20 =

added two shortcode: choco_table, choco_dl.

= 1.19 =

* Fix charset encoding. support UTF-8 only.

= 1.18 =

Fix. Could not received html selector data by PHP, in the case Ajax send data has many selector array.

= 1.17 =

Fix issues.

= 1.16 =

* SVN missed.

= 1.15 =

* Fix, changed timing to update DOM data.
* Added year shortcode.
* Update TinyMCE version 4.7.4.

= 1.14 =

Fix issues.

= 1.13 =

Fix issues.

= 1.12 =

Fix issues.

= 1.11 =

* Published.


== Screenshots ==

1. Input Screen by TinyMCE
2. In case of calendar shortcode


== Frequently Asked Questions ==
