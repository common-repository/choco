<?php
/*
Plugin Name: choco calendar
Plugin URI: https://wordpress.org/plugins/choco/
Description:
Author: AI.Takeuchi
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=P8LCVREFDKWFW
Version: 1.33
Author URI:
*/

namespace choco_calendar;

// load_textdomain(DOMAIN, __DIR__ . '/lang/' . DOMAIN . '-' . get_locale() . '.mo');
// load admin page

define('CHOCO_CALENDAR_DOMAIN', 'choco-calendar');

define('CHOCO_CALENDAR_PLUGIN_URL', rtrim(esc_url(plugin_dir_url(__FILE__)), '/'));
define('CHOCO_PLUGIN_DIR', rtrim(__DIR__, '/'));

load_textdomain(CHOCO_CALENDAR_DOMAIN, __DIR__ . '/language/' . CHOCO_CALENDAR_DOMAIN . '-' . get_locale() . '.mo');

$PluginUrl = plugins_url() . '/' . basename(__DIR__);

function debug_log($text) {
    $file = __DIR__.'/debug_php.log';
    $text .= "\n";
    file_put_contents($file, $text, FILE_APPEND);
}

if (is_admin()) {
    require_once __DIR__ . '/admin.php';

    // add manage plugins screen
    add_action('plugin_row_meta', 'choco_calendar\plugin_row_meta', 10, 4);

} else {
    add_action('wp_print_scripts', 'choco_calendar\print_scripts_without_admin');
}

function activation_function() {
    crateDatabase();
}

register_activation_hook(__FILE__, 'choco_calendar\activation_function');

function uninstall_function() {
    deleteDatabase();
}

register_uninstall_hook(__FILE__, 'choco_calendar\uninstall_function');

function plugin_row_meta($plugin_meta, $plugin_file, $plugin_data, $status) {
    if ( plugin_basename( __FILE__ ) != $plugin_file ) {
        return $plugin_meta;
    }
    // print_r($plugin_meta);
    $links = $plugin_meta;
    $links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=P8LCVREFDKWFW">'.__('donate', CHOCO_CALENDAR_DOMAIN).'</a>';
    return $links;
}

function load_scripts() {
    wp_enqueue_script('jquery');
}

add_action('wp_enqueue_scripts', 'choco_calendar\load_scripts');

function print_scripts_without_admin() {
    global $PluginUrl;

    $chocoCalendarLanguage = get_locale();
    if (!file_exists(CHOCO_PLUGIN_DIR . '/tinymce/langs/' . $chocoCalendarLanguage . '.js')) {
        $chocoCalendarLanguage = '';
    }

    $url = $PluginUrl;
    // global $user_level;
    $login = isCurrentUserAllowed();

    wp_enqueue_style('choco-calendar-style',  $url . '/css/style.css');
    ?>
    <script>
        var choco_calendar_ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        var choco_calendar_ajax_nonce = '<?php echo wp_create_nonce('choco-calendar'); ?>';
        var chocoCalendarLogin = <?php echo $login; ?>;
        var chocoCalendarServer = "<?php echo home_url() . '/'; ?>";
        var chocoCalendarBaseUrl = "<?php echo $url; ?>";
        var chocoCalendarLanguage = "<?php echo $chocoCalendarLanguage; ?>";   // en, ja, ...
    </script>
    <?php
    wp_enqueue_script('tinymce', $url . '/tinymce/tinymce.min.js');
    wp_enqueue_script('choco-calendar', $url . '/js/calendar.js', array('jquery'));
}


// ajax for wordpress
function choco_calendar_session_start() {
    check_ajax_referer('choco-calendar', 'ajax_nonce');
    getJson();
    wp_die();
}

// action name: 'choco_calendar_session_start'
add_action('wp_ajax_choco_calendar_session_start', 'choco_calendar\choco_calendar_session_start');
add_action('wp_ajax_nopriv_choco_calendar_session_start', 'choco_calendar\choco_calendar_session_start');

function crateDatabase() {
    global $wpdb;
    global $table_prefix;

    $sql = <<<EOS
        CREATE TABLE IF NOT EXISTS `{$table_prefix}choco_calendar` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `create_date` datetime DEFAULT NULL,
            `modify_date` datetime DEFAULT NULL,
            `selector` varchar(256) NOT NULL,
            -- `data` text DEFAULT '',  -- 65,535bytes
            -- `data` MEDIUMTEXT DEFAULT '',  -- 1.6MB
            `data` longtext DEFAULT '',  -- 4.3GB
            `user_id` bigint(20) DEFAULT 0,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;
EOS;

    $wpdb->query($sql);
}

function deleteDatabase() {
    global $wpdb;
    global $table_prefix;
    $sql = "DROP TABLE IF EXISTS `'.$table_prefix.'choco_calendar`;";
    $wpdb->query($sql);
}

function isCurrentUserAllowed() {
    // get current user role
    $user = wp_get_current_user();
    $current_user_role = $user->roles ? $user->roles[0] : false;
    $options = get_option(CHOCO_CALENDAR_DOMAIN);
    if (!is_array($options['roles'])) {
        $options['roles'] = array('administrator' => 'administrator');
    }
    if ($current_user_role && array_key_exists($current_user_role, $options['roles'])) {
        return 99;
    } else {
        return 0;
    }
}

function getJson() {
    $login = isCurrentUserAllowed();

    $cmd = getPostValue('cmd', $_POST);
    if ($cmd == 'save') {
        if (!$login) {
            return;
        }
        $result = save();
        wp_send_json($result);
    } else if ($cmd == 'get_data') {
        $result = getData(getPOSTvalue('selector', $_POST));
        wp_send_json($result);
    } else if ($cmd == 'reset') {
        $result = resetCalendar();
        wp_send_json($result);
    } else {
        header("HTTP/1.1 403 Forbidden");
        ?>
        <!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
        <html>
            <head>
                <title>403 Forbidden</title>
            </head>
            <body>
                <h1>Forbidden</h1>
                <p>You don't have permission to access <?php echo $_SERVER['SCRIPT_NAME']; ?>
                    on this server.</p>
                <p>Additionally, a 403 Forbidden
                    error was encountered while trying to use an ErrorDocument to handle the request.</p>
            </body>
        </html>
        <?php
    }
}

function resetCalendar() {
    global $wpdb;
    global $table_prefix;

    $table = $table_prefix . 'choco_calendar';

    $selector = getPOSTvalue('selector', $_POST);

    $wpdb->delete($table, array('selector' => $selector), array('%s'));
    if ($wpdb->last_error) {
        return $wpdb->last_error;
    }
    return array('selector' => $selector, 'data' => '');
}

// 2018-10-28 15:29:25
function getData($selector) {
    global $wpdb;
    global $table_prefix;

    $table = $table_prefix . 'choco_calendar';

    // $selector = getPOSTvalue('selector', $_POST);

    $r = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$table} where selector = %s", $selector), ARRAY_A);
    if ($wpdb->last_error) {
        return $wpdb->last_error;
    }
    return array('selector' => $selector, 'data' => getHtmlFromDatabaseValue($r[0]['data']));
}

function getStripDataSize($data) {
    return strlen(trim(preg_replace('/[\r\n]/', '', strip_tags($data, '<img><iframe>'))));
}

function save() {
    global $wpdb;
    global $table_prefix;

    $selector = getPostValue('selector', $_POST);
    $data = getPostValue('data', $_POST);

    $table = $table_prefix . 'choco_calendar';

    $dbDateTime = getDbDateTime();

    if (!$selector || is_null($data)) {
        return false;
    }
    $count = $wpdb->get_var($wpdb->prepare("select count(*) from {$table} where selector = %s", $selector));
    if (getStripDataSize($data) == 0) {
        if ($count > 0) {
            $wpdb->query($wpdb->prepare("DELETE FROM {$table} WHERE selector = %s LIMIT 1", $selector));
        }
    } else if ($count > 0) {
        $wpdb->update($table, array('data' => $data, 'modify_date' => $dbDateTime, 'user_id' => get_current_user_id()), array('selector' => $selector), array('%s', '%s', '%d'), array('%s'));
    } else {
        $wpdb->insert($table, array('selector' => $selector, 'data' => $data, 'create_date' => $dbDateTime, 'modify_date' => $dbDateTime, 'user_id' => get_current_user_id()), array('%s', '%s', '%s', '%s', '%d'));
    }

    if ($wpdb->last_error) {
        $status = 'error';
    } else {
        $status = 'ok';
    }
    return array('status' => $status, 'data' => getHtmlFromDatabaseValue($data), 'last_error' => $wpdb->last_error);
}

function getPOSTvalue($key, $post) {
    if (!is_array($post) || !array_key_exists($key, $post)) {
        return null;
    } else if ($key === 'cmd') {
        return preg_replace('/[^a-zA-Z0-9_\- ]/', '',  $post[$key]);
    } else if ($key === 'data') {
        $a = $post[$key];
        // $a = urldecode($a);
        $a = mb_convert_encoding($a, 'UTF-8');
        $a = strip_tags($a, '<title><h1><h2><h3><h4><h5><h6><hr><hr/><br><br/><p><center><div><pre><span><blockquote><address><font><i><tt><b><u><strike><big><small><sub><sup><em><strong><code><ul><ol><li><dl><dt><dd><table><tbody><tr><th><td><caption><a><img><img/>');
        // ENT_IGNORE >= PHP5.3, ENT_SUBSTITUTE >= PHP5.4
        $a = htmlentities($a, ENT_QUOTES, 'UTF-8');
        $a = sanitize_text_field($a);
        return $a;
    } else if ($key === 'selector') {
        return preg_replace('/[^a-zA-Z0-9_\- #\.]/', '',  $post[$key]);
    } else if ($key === 'reset') {
        return intval($post[$key]);
    } else if ($key === 'id') {
        return intval($post[$key]);
    } else if ($key === 'roles') {
        return preg_replace('/[^a-zA-Z0-9_\- \.]/', '',  $post[$key]);
    } else {
        return null;
    }
}

function getHtmlFromDatabaseValue($value) {
     return esc_html(stripslashes($value));
}

function getDbDateTime() {
    return date("Y-m-d H:i:s", time());
}

require_once 'includes/shortcode.php';
