<h3>calendar shortcode</h3>
<pre>
e.g.

    [choco_calendar] or [choco-calendar] or [calendar]

    [calendar year=2015 month=4]

    [calendar month=-4 year=+1 caption_format="n Y" weeks="S,M,T,W,T,F,S" week_format=l first_day_monday=true fill_blank=true classes="default class1 class2"]

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
    events          : 'thu: Closed|sun: Event|4: 4th|6: 6th|3sun: 3rd Sun|4tue: 4th Tue'

    Date format string: <a target="_blank" href="http://php.net/manual/function.date.php">http://php.net/manual/function.date.php</a>
</pre>
<hr />

<h3>other</h3>
<pre>
written text is save to database.
table name is 'wp_choco_calendar'.
if changed table_prefix variable in wp-config.php, so that.
will be delete this table when uninstall this plugin.
</pre>
