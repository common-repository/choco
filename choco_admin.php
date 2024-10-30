<?php

namespace choco;

function wp_custom_admin_enqueue_scripts($hook) {
    if (strstr($hook, CHOCO_DOMAIN.'-settings') === FALSE) {
        return;
    }

    wp_enqueue_style('choco_admin',  CHOCO_PLUGIN_URL . '/css/choco_admin.css');

    wp_enqueue_script('jquery.cookie', CHOCO_PLUGIN_URL . '/js/jquery.cookie.js', array('jquery'));
    wp_enqueue_script('choco_admin', CHOCO_PLUGIN_URL . '/js/choco_admin.js', array('jquery'));
}

add_action('admin_enqueue_scripts', 'choco\wp_custom_admin_enqueue_scripts');

function plugin_menu() {
    add_options_page(__('Plugin Options', CHOCO_DOMAIN), __('choco', CHOCO_DOMAIN), 'administrator', CHOCO_DOMAIN.'-settings.php', 'choco\plugin_options');
}

add_action('admin_menu', 'choco\plugin_menu');

function plugin_options() {
    $options_file = __DIR__ . '/language/choco_options' . '-' . get_locale() . '.php';
    if (!file_exists($options_file)) {
        $options_file = __DIR__ . '/language/choco_options.php';
    }

    $key = "choco-welcome-panel";
    if (array_key_exists($key, $_COOKIE)) {
        $welcom_panel_hidden = esc_html($_COOKIE[$key]);
    }
    //$welcom_panel_hidden = esc_html($_COOKIE["choco-welcome-panel"]);
    ?>
    <div class="wrap <?php echo CHOCO_DOMAIN; ?>_options">

        <h2><?php _e('choco', CHOCO_DOMAIN); ?><div class="support-link"><a class="button" href="https://wordpress.org/plugins/choco/" target="_blank">WordPress.org</a>&nbsp;<form style="float:right;" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="P8LCVREFDKWFW"><button type="submit" class="button" border="0" name="submit"><?php _e('donate', CHOCO_DOMAIN); ?></button></form></div></h2>


        <form name="form1" method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>" enctype="multipart/form-data">
            <input type="hidden" name="submit_hidden" value="Y">
            <hr />
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingOne">
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body" style="width:100%; overflow-x:auto;">
                            <?php require_once $options_file; ?>
                        </div>
                    </div>
                </div>
            </div>
            <hr />

            <?php
            // get current user role
            $user = wp_get_current_user();
            $current_user_role = $user->roles ? $user->roles[0] : false;
            if ($current_user_role == 'administrator') {

                $options = get_option(CHOCO_DOMAIN);
                if (array_key_exists('submit_hidden', $_POST) && $_POST['submit_hidden'] == 'Y') {
                    if (!wp_verify_nonce($_POST[CHOCO_DOMAIN.'-nonce'], CHOCO_DOMAIN.'-nonce')) {
                        die('Security check');
                    } else if (array_key_exists('cmd-update', $_POST)) {
                        $options = get_option(CHOCO_DOMAIN);
                        $options['roles'] = getPOSTvalue('roles', $_POST);
                        update_option(CHOCO_DOMAIN, $options);
                        ?>
                        <div class="updated"><p><strong><?php _e('Updated.', CHOCO_DOMAIN); ?></strong></p></div>
                        <?php
                    } else {
                    }
                }

                // Settings
                echo '<h3>'.__('Editor Groups', CHOCO_DOMAIN).'</h3>';
                $editable_roles = get_editable_roles();
                $editable_roles_keys = array_keys($editable_roles);
                foreach ($editable_roles_keys as $editable_roles_key) {
                    $name = $editable_roles[$editable_roles_key]['name'];
                    $checked = '';
                    //print_r($options['roles']);
                    if (!is_array($options['roles'])) {
                        $options['roles'] = array('administrator' => 'administrator');
                    }
                    if (array_key_exists($editable_roles_key, $options['roles'])) {
                        $checked = 'checked="checked"';
                    }
                    echo '<input type="checkbox" name="roles['.$editable_roles_key.']" id="'.$editable_roles_key.'" value="'.$editable_roles_key.'" '.$checked.' /><label for="'.$editable_roles_key.'">'.__($name).'</label> ';
                }
                // nonce を生成し、アクションを実行するリンクのクエリ変数に追加。
                echo '<input type="hidden" name="'.CHOCO_DOMAIN.'-nonce" value="'.wp_create_nonce(CHOCO_DOMAIN.'-nonce').'" />';
                ?>
                <hr />
                <input type="hidden" name="submit_hidden" value="Y" />
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body" style="width:100%">
                        <button type="submit" name="cmd-update" class="button button-primary"><?php _e('Update', CHOCO_DOMAIN) ?></button>
                    </div>
                </div>
            <?php } // if administrator ?>


        </form>
    </div>
    <?php
}
