<h3>calendar shortcode</h3>
<pre>
    * You can use the new 'choco calendar'. Activate plugin 'choco calendar'.

    e.g.

        [choco_calendar]

        [choco_calendar year=2015 month=4]

        [choco_calendar month=-4 year=+1 caption_format="n Y" weeks="S,M,T,W,T,F,S" week_format=l monday=true padding=true classes="class1 class2"]

    value:

        year: 2015, 2016, or ..., -2, -1, +1, +2, ...
        month: 1 to 12 or ..., -2, -1, +1, +2, ...
        caption_format: "F Y", "n Y", ...
        week_format: l, D, ...
        weeks: "S,M,T,W,T,F,S", ...
        monday: true or nothing
        padding: true or nothing
        id: user define id
        classes: user define classes "class1 class2 ..."
</pre>
<hr />
<h3>date shortcode</h3>
<pre>
    e.g.

        [choco_date]

        Copyright &copy; 2015 - [choco_date format='Y' classes='not_choco'] example.com

        [choco_date year=2016 month=5 date=17]

        [choco_date month=-4 year=+1 format="Y F $k" weeks="S,M,T,W,T,F,S" id="next" classes="class1 class2"]

    value:

        year: 2015, 2016, or ..., -2, -1, +1, +2, ...
        month: 1 to 12 or ..., -2, -1, +1, +2, ...
        format: "F Y", "$k n j Y", ...
        weeks: "S,M,T,W,T,F,S", ...
            replace '$k' in format string.
        tag: span, div, ...
        id: user define id
        classes: user define classes "class1 class2 ..."
        tz: "GMT", "JST", ...

        See date/time format: <a href=http://php.net/manual/en/function.date.php">http://php.net/manual/en/function.date.php</a>
        (japanese: <a href="http://php.net/manual/ja/function.date.php">http://php.net/manual/ja/function.date.php</a>)
</pre>
<hr />
<h3>year shortcode</h3>
<pre>
    e.g.

        [choco_year]

        [choco_year year=-2000 format="%d" id="next" classes="class1 class2"] years old

    value:

        year: 2015, 2016, or ..., -2, -1, +1, +2, ...
        month: 1 to 12 or ..., -2, -1, +1, +2, ...
        format: "%d", "%05d", ...
        tag: span, div, ...
        id: user define id
        classes: user define classes "class1 class2 ..."
        tz: "GMT", "JST", ...

        See number format: <a target="_blank" href=http://php.net/manual/en/function.sprintf.php">http://php.net/manual/en/function.sprintf.php</a>
</pre>
<hr />
<h3>such temporary memo or information</h3>
<pre>
    e.g.
        [choco_memo tag="div" name="memo" style="width:80%;min-height:1em;margin:0 auto;" text="Click Here!"]

        above shortcode output below tag:

        &lt;div class="choco_memo" style="width:80%;min-height:1em;margin:0 auto;"&gt;Click Here!&lt;/div&gt;

        * Can rewrite if added class choco_xxx to html tag.

    e.g.
        It can be summarized in the id as follows.
        will be id addition to when save database. use this when it is used in more than one content.

        &lt;div id="choco_memo"&gt;
            &lt;div class="choco" style="width:80%;min-height:1em;margin:0 auto;"&gt;Click Here 1&lt;/div&gt;
            &lt;div class="choco" style="width:80%;min-height:1em;margin:0 auto;"&gt;Click Here 2&lt;/div&gt;
        &lt;/div&gt;

    e.g.
        hidden after loaded if specify class 'choco_hide_after_load'.

        [choco_memo tag="div" name="memo" classes="choco_hide_after_load" style="text-align:center;width:80%;min-height:1em;margin:0 auto;" text="Now Loading..."]
</pre>
<hr />
<h3>table</h3>
<pre>
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
</pre>
<hr />
<h3>definition list</h3>
<pre>
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
</pre>
<hr />
<h3>aliases</h3>
<pre>
    choco_calendar as calendar
    choco_table as table
    choco_dl as list
    choco_memo as memo.
</pre>
<hr />

<h3>other</h3>
<pre>
    written text is save to database.
    table name is 'wp_choco_data'.
    if changed table_prefix variable in wp-config.php, so that.
    will be delete this table when uninstall this plugin.
</pre>
