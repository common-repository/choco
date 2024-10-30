/*
 * calendar.js
 *
 * -*- Encoding: utf8n -*-
 *
 */

(function () {

    var calendar_selector = '.choco-calendar';
    var selector = calendar_selector + ' td, ' + calendar_selector + ' th, ' + calendar_selector + ' caption ';

    var login = chocoCalendarLogin;
    var action = 'choco_calendar_session_start';
    var chocoCalendarServer = choco_calendar_ajaxurl;
    var baseUrl = chocoCalendarBaseUrl;

    var maxPostSize = 5 * 1024 * 1024;        // mysql longtext  : 4.3GB.
    var templatePath = baseUrl + "/templates/";
    var tinyMceForm = null;

    var current_edit = null;


    var loadTinyMceForm = function () {

        if (tinyMceForm != null) {
            return;
        }

        // console.log(baseUrl);

        // load tiny_mce.html
        jQuery.ajax({
            global: false,
            type: 'GET',
            url: baseUrl + "/js/choco-calendar-tinymce.html",
            dataType: 'html',
        }).success(function (tiny_mce_html) {
            // console.log(tiny_mce_html);
            tinyMceForm = tiny_mce_html;
            jQuery(tiny_mce_html).prependTo('body');
            initTinyMce();
            setClickEventTinyMceButton();
        }).error(function () {
            console.log("choco calendar: Error: Can't get tiny_mce screen.");
        });
    };


    var initTinyMce = function () {
        var height = parseInt(jQuery(window).height() * 0.70);

        tinymce.init({
            //setup: chocoCustomOnInit,
            language: chocoCalendarLanguage,
            selector: "textarea#choco-calendar-tinymce",
            //height: "400",
            height: height,
            paste_data_images: true,
            // p tag
            forced_root_block: "",
            force_br_newlines: true,
            force_p_newlines: false,
            fontsize_formats: "8px 9px 10px 11px 12px 14px 16px 18px 20px 22px 24px 26px 30px 36px",
            /*
            templates: [
                {
                    title: "Fill Cell",
                    url: templatePath + "fillcell.html",
                    description: ""
                },
                {
                    title: "My Snippet",
                    url: templatePath + "snippet.htm",
                    description: "Adds a HTML snippet"
                },
            ],
            // Replace values for the template plugin
            template_replace_values: {
                username: "Some User",
                staffid: "991234"
            },
            */
            // menubar: "file edit view insert format table",
            menubar: "edit insert format table",
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste textcolor emoticons code template colorpicker hr",
            ],
            // toolbar: "insertfile undo redo | fontsizeselect | styleselect | bold italic | forecolor backcolor emoticons | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image hr | template | code"
            toolbar: "insertfile undo redo | fontsizeselect | styleselect | bold italic | forecolor backcolor emoticons | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image hr | code",
        });
    };


    var chocoCustomOnInit = function () {
        var docWidth = jQuery(document).width();
        var docHeight = jQuery(document).height();

        jQuery("#choco-calendar-tiny-mce-bg").css({ "width": docWidth, "height": docHeight });
        var padding = 4; // %
        jQuery("#choco-calendar-tiny-mce-input").css({ "width": (100 - padding) + '%', 'left': (padding / 2) + '%', 'top': (padding) + '%' });
    };

    var setClickEventTinyMceButton = function () {
        // button save
        // jQuery("#choco-calendar-save").unbind();

        jQuery("#choco-calendar-save").on('click', function () {
            var value = tinyMCE.get('choco-calendar-tinymce').getContent();         // visual mode
            // var value = jQuery('textarea[name="choco-calendar-tinymce"]').val(); // html mode
            // console.log("Data size = " + value.length);
            if (value.length > maxPostSize) {
                if (!window.confirm("Can't save. Content size is larger than " + maxPostSize + " bytes.")) {
                    return false;
                }
            }

            // 2018-10-28 16:22:13
            // jQuery(current_edit).html(decodeHtmlSpecialChars(value));
            jQuery(current_edit).html(value);

            // save data to server
            saveData();

            // close tiny mce dialog window
            // jQuery("#choco-calendar-tiny-mce").remove();
            jQuery('#choco-calendar-tiny-mce-bg').css('display', 'none');
            jQuery('#choco-calendar-tiny-mce-input').css('display', 'none');
            tinyMCE.execCommand('mceSetContent', false, '');
        });


        // jQuery("#choco-calendar-cancel,#choco-calendar-tiny-mce-bg").unbind();
        // button cancel and click background
        jQuery("#choco-calendar-cancel,#choco-calendar-tiny-mce-bg").click(function () {
            //jQuery("#choco-calendar-tiny-mce").remove();
            jQuery('#choco-calendar-tiny-mce-bg').css('display', 'none');
            jQuery('#choco-calendar-tiny-mce-input').css('display', 'none');
            tinyMCE.execCommand('mceSetContent', false, '');
        });

        jQuery(document).on('click', '#choco-calendar-reset', function(event) {
            resetCalendar();
        });

    };

    // 2018-10-28 15:18:20
    var getSelector = function (obj) {
        var selector = '';
        var id = obj.id; // has class id?
        var classes = obj.className.trim().split(/\s+/);
        // var class1st = '';
        for (var i = 0; i < classes.length; i++) {
            if (classes[i].match(/^choco-calendar/)) {
                class1st = classes[i];
                break;
            }
        }

        if (id) {
            selector = '#' + id;
        } else {
            // console.log("both id and class not found.");
            return {};
        }

        // console.log("getSelector: selector: " + selector);
        return { 'selector': selector, 'data': jQuery(obj).html() };
    };


    var resetCalendar = function () {
        var selector = getSelector(jQuery(current_edit).parents(calendar_selector)[0]);
        // console.log(selector);

        var sendData = {
            "ajax_nonce": choco_calendar_ajax_nonce,
            "action": action,
            "cmd": "reset",
            "selector": selector.selector,
        };

        jQuery.ajax({
            global: false,
            url: chocoCalendarServer,
            type: "POST",
            dataType: "json",
            data: sendData,
        }).error(function (data) {
            var msg = "choco calendar: Error: Data reset request failed.\n selector: " + selector.selector;
            console.log(msg);
            alert(msg);
        }).success(function (data) {
            // console.log(data);
            if (data.status == "error") {
                var msg = "choco calendar: Error: Data reset failed.\n selector: " + selector.selector;
                console.log(msg);
                alert(msg);
                return false;
            }
            location.reload();
        });
    };


    /**
     * Send save request to server.
     *
     * @param sendData
     */
    var saveData = function () {
        var selector = getSelector(jQuery(current_edit).parents(calendar_selector)[0]);

        var sendData = {
            "ajax_nonce": choco_calendar_ajax_nonce,
            "action": action,
            "cmd": "save",
            "selector": selector.selector,
            "data": selector.data,
        };

        jQuery.ajax({
            global: false,
            url: chocoCalendarServer,
            type: "POST",
            dataType: "json",
            data: sendData,
        }).error(function (data) {
            var msg = "choco calendar: Error: Data save request failed.\n selector: " + selector.selector;
            console.log(msg);
            alert(msg);
        }).success(function (data) {
            // console.log(data);
            if (data.status == "error") {
                var msg = "choco calendar: Error: Data save failed.\n selector: " + selector.selector;
                console.log(msg);
                alert(msg);
                return false;
            }
        });
    };

    var ready = function () {

        // -- NEED LOGIN --------------------------------------
        if (!login) {
            return;
        }

        loadTinyMceForm();

        jQuery(selector).live({
            mouseenter: function () {
                jQuery(this).addClass("choco-calendar-mouse-hover").css({ "cursor": 'url(' + baseUrl + '/img/use151.cur),pointer' });
            },
            mouseleave: function () {
                jQuery(this).removeClass("choco-calendar-mouse-hover").css("cursor");
            }
        });

        jQuery(document).on('click', selector, function () {
            current_edit = this;

            jQuery(selector).removeClass("choco-calendar-mouse-hover").css("cursor", "");

            var html = jQuery(this).html();
            // tinyMCE.execCommand('mceSetContent', false, html);
            // textarea id
            tinyMCE.get("choco-calendar-tinymce").execCommand('mceSetContent', false, html);
            chocoCustomOnInit();
            jQuery('#choco-calendar-tiny-mce-bg').css('display', 'block');
            jQuery('#choco-calendar-tiny-mce-input').css('display', 'table');
        });

    };

    jQuery(document).ready(function () {
        ready();
    });

})();

