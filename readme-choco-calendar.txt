=== choco calendar ===
Contributors: AI.Takeuchi
Description:
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=P8LCVREFDKWFW
Tags:
Requires at least: 4.1
Tested up to: 4.9.5
Stable tag: 1.33
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Rewrite content by ajax, using TinyMCE.


== Installation ==

1. Install plugin and activate
2. Write Shortcode

== Usage ==

= calendar shortcode =

    e.g.

        [choco_calendar] or [choco-calendar] or [calendar]

        [choco_calendar year=2015 month=4]

        [choco_calendar month=-4 year=+1 caption_format="Y F" weeks="日,月,火,水,木,金,土" week_format=l monday=true fill_blank=true classes="default class1 class2"]

    value:

        id              : table tag id. id is auto create: choco_calendar_${distinction}_${year}_${month}
        distinction     : using post id if not set
        classes         : table tag classes, default: "default"
        fill_blank      : 0 or 1
        weeks           : e.g. "S,M,T,W,T,F,S", "日,月,火,水,木,金,土"
        week_format     : D: Mon - Sun, l: Sunday - Saturday, N: 1 (Mon) - 7 (Sun), w: number 0 (Sun) - 6 (Sat)
        caption_format  : e.g. "F Y", "Y年n月"
        first_day_monday: First day of week is monday if set
        year            : e.g. 2018, -3, +3
        month           : e.g. 11, -3, +3
        events          : 'thu: Closed|sun: <br>Event|4: 4th|6: 6th|3sun: 3rd Sun|4tue: 4th Tue'

        See date/time format: http://php.net/manual/en/function.date.php
        (japanese: http://php.net/manual/ja/function.date.php)


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

Using icons
http://gamejumble.com


== Changelog ==

= 1.33 =

* same changed.

= 1.32 =

* fix weeks parameter.

= 1.31 =

* Published.


== Screenshots ==

1. Input Screen by TinyMCE
2. In case of calendar shortcode


== Frequently Asked Questions ==
