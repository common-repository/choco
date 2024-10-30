<h3>カレンダーショートコード</h3>
<pre>
    ※ 新しい 'choco calendar' が使用出来ます。 プラグイン 'choco calendar' をアクティブにしてください。

    例：

        [choco_calendar]

        [choco_calendar year=2015 month=4]

        [choco_calendar month=-4 year=+1 caption_format="Y年n月" weeks="日,月,火,水,木,金,土" week_format=l monday=true padding=true classes="class1 class2"]

    値：

        year: 2015, 2016, or ..., -2, -1, +1, +2, ...
        month: 1 to 12 or ..., -2, -1, +1, +2, ...
        caption_format: "F Y", "Y年n月", ...
        week_format: l, D, ...
        weeks: "S,M,T,W,T,F,S", "日,月,火,水,木,金,土", ...
        monday: true or nothing
        padding: true or nothing
        id: user define id
        classes: user define classes "class1 class2 ..."
</pre>
<hr />
<h3>日付ショートコード</h3>
<pre>
    例：

        [choco_date]

        Copyright &copy; 2015 - [choco_date format='Y' classes='not_choco'] example.com

        [choco_date year=2016 month=5 date=17]

        [choco_date month=-4 year=+1 format="Y F $k" weeks="日,月,火,水,木,金,土" id="next" classes="class1 class2"]

    値：

        year: 2015, 2016, or ..., -2, -1, +1, +2, ...
        month: 1 to 12 or ..., -2, -1, +1, +2, ...
        format: "F Y", "Y年n月", ...
        weeks: "S,M,T,W,T,F,S", "日,月,火,水,木,金,土", ...
            format 文字列内の '$k' が置換されます
        tag: span, div, ...
        id: user define id
        classes: user define classes "class1 class2 ..."
        tz: "GMT", "JST", ...

        See date/time format: http://php.net/manual/en/function.date.php
        (japanese: http://php.net/manual/ja/function.date.php)


        日付フォーマット文字についてはこちらを参照: <a target="_blank" href="http://php.net/manual/ja/function.date.php">http://php.net/manual/ja/function.date.php</a>
        (english: <a target="_blank" href=http://php.net/manual/en/function.date.php">http://php.net/manual/en/function.date.php</a>)

</pre>
<hr />
<h3>年ショートコード</h3>
<pre>
    例：

        [choco_year]

        [choco_year year=-2000 format="%d" id="next" classes="class1 class2"] 歳

    値：

        year: 2015, 2016, or ..., -2, -1, +1, +2, ...
        month: 1 to 12 or ..., -2, -1, +1, +2, ...
        format: "%d", "%05d", ...
        tag: span, div, ...
        id: user define id
        classes: user define classes "class1 class2 ..."
        tz: "GMT", "JST", ...

        日付フォーマット文字についてはこちらを参照: <a target="_blank" href="http://php.net/manual/ja/function.sprintf.php">http://php.net/manual/ja/function.sprintf.php</a>
</pre>
<hr />
<h3>一時的なメモ、お知らせなど</h3>
<pre>
    例：
        [choco_memo tag="div" name="memo" style="width:80%;min-height:1em;margin:0 auto;" text="Click Here!"]

        このショートコードは次のタグを出力します：

        &lt;div class="choco_memo" style="width:80%;min-height:1em;margin:0 auto;"&gt;Click Here!&lt;/div&gt;

        ※ HTML タグに class choco_xxx を追加するとその部分が書き換え可能となります。

    例：
        下記のように id でまとめることが出来ます。
        データーベースに保存される際に id が付加されます。内容を分けるのに使用してください。

        &lt;div id="choco_memo"&gt;
            &lt;div class="choco" style="width:80%;min-height:1em;margin:0 auto;"&gt;Click Here 1&lt;/div&gt;
            &lt;div class="choco" style="width:80%;min-height:1em;margin:0 auto;"&gt;Click Here 2&lt;/div&gt;
        &lt;/div&gt;

    例：
        class choco_hide_after_load を指定すると内容が書き換えられた後、非表示となります。

        [choco_memo tag="div" name="memo" classes="choco_hide_after_load" style="text-align:center;width:80%;min-height:1em;margin:0 auto;" text="Now Loading..."]

</pre>
<hr />
<h3>テーブル</h3>
<pre>
    例：
        [choco_table id="table-1" classes="clear full" caption="Title" title="col" style="border 1px solid #eee;"]
        test|123
        echo|123\n456
        [/choco_table]

    値：
        id: user define id
        classes: user define classes "class1 class2 ..."
        caption: table caption
        title: 'col' or 'row' or 'both'
        style: css style string
</pre>
<hr />
<h3>説明リスト</h3>
<pre>
    例：
        [choco_dl id="list-1" classes="clear full" style="margin: 0; padding: 0;"]
        test|123\n456
        echo|123
        456
        [/choco_dl]

    値：
        id: user define id
        classes: user define classes "class1 class2 ..."
        style: css style string
</pre>
<hr />
<h3>別名</h3>
<pre>
    choco_calendar as calendar
    choco_table as table
    choco_dl as list
    choco_memo as memo.
</pre>
<hr />

<h3>その他</h3>
<pre>
    データーはデーターベースに保存されます。
    テーブル名は標準で「wp_choco_data」
    wp-config.php で table_prefix を変更している場合は、そのようになります。
    このテーブルは、プラグインをアンインストールする際に消えます。
</pre>
