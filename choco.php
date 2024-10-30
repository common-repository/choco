<?php

namespace choco;

/*
  Plugin Name: choco
  Plugin URI: https://wordpress.org/plugins/choco/
  Description: Rewrite content by ajax, using TinyMCE. Provide rewrite areas. These is html block, embed calendar, date, temporary information.
  Author: AI.Takeuchi
  Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=P8LCVREFDKWFW
  Version: 1.33
  Author URI:
 */

// load_textdomain(DOMAIN, __DIR__ . '/lang/' . DOMAIN . '-' . get_locale() . '.mo');
// load admin page

define('CHOCO_DOMAIN', 'choco');

define('CHOCO_PLUGIN_URL', rtrim(esc_url(plugin_dir_url(__FILE__)), '/'));
define('CHOCO_PLUGIN_DIR', rtrim(__DIR__, '/'));

load_textdomain(CHOCO_DOMAIN, __DIR__ . '/language/' . CHOCO_DOMAIN . '-' . get_locale() . '.mo');

$PluginUrl = plugins_url() . '/' . basename(__DIR__);

function debug_log($text) {
    $file = __DIR__.'/debug_php.log';
    $text .= "\n";
    file_put_contents($file, $text, FILE_APPEND);
}

if (is_admin()) {
    require_once __DIR__ . '/choco_admin.php';
    // add manage plugins screen
    add_action('plugin_row_meta', 'choco\plugin_row_meta', 10, 4);
} else {
    add_action('wp_print_scripts', 'choco\print_scripts_without_admin');
}

function activation_function() {
    crateDatabase();
}

register_activation_hook(__FILE__, 'choco\activation_function');

function uninstall_function() {
    deleteDatabase();
}

register_uninstall_hook(__FILE__, 'choco\uninstall_function');

function plugin_row_meta($plugin_meta, $plugin_file, $plugin_data, $status) {
    if ( plugin_basename( __FILE__ ) != $plugin_file ) {
        return $plugin_meta;
    }
    //print_r($plugin_meta);
    $links = $plugin_meta;
    $links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=P8LCVREFDKWFW">'.__('donate',CHOCO_DOMAIN).'</a>';
    return $links;
}

function load_scripts() {
    wp_enqueue_script('jquery');
}

add_action('wp_enqueue_scripts', 'choco\load_scripts');

function print_scripts_without_admin() {
    global $PluginUrl;

    $chocolanguage = get_locale();
    if (!file_exists(CHOCO_PLUGIN_DIR . '/tinymce/langs/' . $chocolanguage . '.js')) {
        $chocolanguage = '';
    }

    $url = $PluginUrl;
    //global $user_level;
    $login = isCurrentUserAllowed();

    wp_enqueue_style('choco_style',  $url . '/css/choco_style.css');
    ?>
    <script>
        var choco_ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        var choco_ajax_nonce = '<?php echo wp_create_nonce('choco'); ?>';
        var chocoLogin = <?php echo $login; ?>;
        var chocoServer = "<?php echo home_url() . '/'; ?>";
        var chocoBaseUrl = "<?php echo $url; ?>";
        var chocolanguage = "<?php echo $chocolanguage; ?>";   // en, ja, ...
    </script>
    <?php
    wp_enqueue_script('tinymce', $url . '/tinymce/tinymce.min.js');
    wp_enqueue_script('choco', $url . '/js/choco.js', array('jquery'));
}


// ajax for wordpress
function choco_session_start() {
    check_ajax_referer('choco', 'ajax_nonce');
    getJson();
    wp_die();
}

// action name: 'choco_session_start'
add_action('wp_ajax_choco_session_start', 'choco\choco_session_start');
add_action('wp_ajax_nopriv_choco_session_start', 'choco\choco_session_start');

function crateDatabase() {
    global $wpdb;
    global $table_prefix;

    $sql = <<<EOS
        CREATE TABLE IF NOT EXISTS `{$table_prefix}choco_data` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `create_date` datetime DEFAULT NULL,
            `modify_date` datetime DEFAULT NULL,
            `selector` varchar(256) NOT NULL,
            `selector_index` int(10) unsigned NOT NULL,
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
    $sql = "DROP TABLE IF EXISTS `'.$table_prefix.'choco_data`;";
    $wpdb->query($sql);
}

function isCurrentUserAllowed() {
    // get current user role
    $user = wp_get_current_user();
    $current_user_role = $user->roles ? $user->roles[0] : false;
    $options = get_option(CHOCO_DOMAIN);
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
    } else if ($cmd == 'get_all_data') {
        $result = getAllData();
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

function getAllData() {
    global $wpdb;
    global $table_prefix;

    $table = $table_prefix . 'choco_data';

    if (!array_key_exists('selectors', $_POST) || !is_array($_POST['selectors'])) {
        return null;
    }
    $post_selectors = $_POST['selectors']; // array

    $result = array();
    // $max = count($post_selectors);
    // debug_log("post size " . print_r($_POST, true));
    foreach ($post_selectors as $key => $post_selector) {
    // for ($i = 0; $i < $max; $i++) {
        $selector = getPOSTvalue('selector', $post_selector);
        $selector_index = getPOSTvalue('selector_index', $post_selector);
        // debug_log("post size " . strlen(serialize($_POST)));
        // debug_log("$selector, $selector_index");

        $r = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$table} where selector = %s and selector_index = %d", $selector, $selector_index), ARRAY_A);
        if ($wpdb->last_error) {
            return $wpdb->last_error;
        }
        $r[0]['data'] = getHtmlFromDatabaseValue($r[0]['data']);
        $result = array_merge($result, $r);
    }
    return $result;
}

function getStripDataSize($data) {
    return strlen(trim(preg_replace('/[\r\n]/', '', strip_tags($data, '<img><iframe>'))));
}

function save() {
    global $wpdb;
    global $table_prefix;

    $selector = getPostValue('selector', $_POST);
    $selector_index = getPostValue('selector_index', $_POST);
    $data = getPostValue('data', $_POST);
    $id = getPostValue('id', $_POST);

    $table = $table_prefix . 'choco_data';

    $dbdatetime = getDbDateTime();

    if (!$selector || is_null($data)) {
        return false;
    }
    $count = $wpdb->get_var($wpdb->prepare("select count(*) from {$table} where selector = %s and selector_index = %d", $selector, $selector_index));
    if (getStripDataSize($data) == 0) {
        if ($count > 0) {
            $wpdb->query($wpdb->prepare("DELETE FROM {$table} WHERE selector = %s and selector_index = %d LIMIT 1", $selector, $selector_index));
        }
    } else if ($count > 0) {
        $wpdb->update($table, array('data' => $data, 'modify_date' => $dbdatetime, 'user_id' => get_current_user_id()), array('selector' => $selector, 'selector_index' => $selector_index), array('%s', '%s', '%d'), array('%s', '%d'));
    } else {
        $wpdb->insert($table, array('selector' => $selector, 'selector_index' => $selector_index, 'data' => $data, 'create_date' => $dbdatetime, 'modify_date' => $dbdatetime, 'user_id' => get_current_user_id()), array('%s', '%d', '%s', '%s', '%s', '%d'));
    }

    if ($wpdb->last_error) {
        $status = 'error';
    } else {
        $status = 'ok';
    }
    return array('status' => $status, 'data' => getHtmlFromDatabaseValue($data), 'id' => $id, 'last_error' => $wpdb->last_error);
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
    } else if ($key === 'selector_index') {
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

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
// if (!is_plugin_active('choco_calendar/choco-calendar.php')) {
if (!is_plugin_active('choco/choco-calendar.php')) {
        require_once 'includes/choco_calendar.php';
}
require_once 'includes/choco_date.php';
require_once 'includes/choco_year.php';
require_once 'includes/choco_memo.php';
require_once 'includes/choco_table.php';
require_once 'includes/choco_dl.php';
require_once 'includes/choco_tag.php';
