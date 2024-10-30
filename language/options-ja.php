<h3>カレンダーショートコード</h3>
<pre>
例：

    [choco_calendar] or [choco-calendar] or [calendar]

    [calendar year=2015 month=4]

    [calendar month=-4 year=+1 caption_format="Y年n月" weeks="日,月,火,水,木,金,土" week_format=l first_day_monday=true fill_blank=true classes="default class1 class2"]

値：

    id              : table tag id. id is auto create: choco_calendar_${distinction}_${year}_${month}
    distinction     : using post id if not set
    classes         : table tag classes, default: "default"
    fill_blank      : 0 or 1
    weeks           : e.g. "S,M,T,W,T,F,S", "日,月,火,水,木,金,土"
    week_format     : D: Mon - Sun, l: Sunday - Saturday, N: 1 (月曜日) - 7 (日曜日), w: 数値 0 (日曜日) - 6 (土曜日)
    caption_format  : e.g. "F Y", "Y年n月"
    first_day_monday: First day of week is monday if set
    year            : e.g. 2018, -3, +3
    month           : e.g. 11, -3, +3
    events          : 'thu: Closed|sun: Event|4: 4th|6: 6th|3sun: 3rd Sun|4tue: 4th Tue'

    日付フォーマット文字: <a target="_blank" href="http://php.net/manual/ja/function.date.php">http://php.net/manual/ja/function.date.php</a>
</pre>
<hr />

<h3>その他</h3>
<pre>
データーはデーターベースに保存されます。
テーブル名は標準で「wp_choco_calendar」
wp-config.php で table_prefix を変更している場合は、そのようになります。
このテーブルは、プラグインをアンインストールする際に消えます。
</pre>
