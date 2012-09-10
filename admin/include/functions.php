<?php

function wp_socialicons_install() {
    if (!class_exists('Install')) {
        require_once SOCIALICONS_DIR . 'admin/class/Install.php';
    }

    $Install = new Install();
    if ($Install->init()) {
        update_option('wp-socialicons-version', SOCIALICONS_VERSION);
    }
}

function init_socialicons() {

    //only admin
    if (is_admin()) {
        wp_register_script('socialicons', SOCIALICONS_URL . 'admin/view/js/socialicons.js', array('jquery', 'jquery-ui-core', 'jquery-ui-dialog'), '1.0', true);

        if (!defined('SOCIALICONS_WPNONCE')) {
            define('SOCIALICONS_WPNONCE', wp_create_nonce('socialicons_wpnonce'));
        }

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_script('socialicons');

        wp_localize_script('socialicons', 'socialicons_js', array(
            'wpnonce_search_iconfinder' => SOCIALICONS_WPNONCE,
            'admin_ajax_url' => admin_url('admin-ajax.php'),
            'trl_searching' => __('Searching', SOCIALICONS_LANG),
            'trl_downloading' => __('Downloading...', SOCIALICONS_LANG),
            'trl_usethis' => __('Use this!', SOCIALICONS_LANG),
            'trl_download_success' => __('Download OK!', SOCIALICONS_LANG),
            'trl_saving' => __('Saving...', SOCIALICONS_LANG),
            'trl_id' => __('ID', SOCIALICONS_LANG),
            'trl_url' => __('URL', SOCIALICONS_LANG),
            'trl_name' => __('Name', SOCIALICONS_LANG),
            'trl_status' => __('Status', SOCIALICONS_LANG),
            'trl_enabled' => __('Enabled', SOCIALICONS_LANG),
            'trl_disabled' => __('Disabled', SOCIALICONS_LANG),
            'trl_loading' => __('Loading...', SOCIALICONS_LANG),
            'trl_changing' => __('Changing status...', SOCIALICONS_LANG),
            'trl_changestatus' => __('Change status', SOCIALICONS_LANG)
        ));

        add_action('admin_menu', 'add_options_to_wp_admin');
        add_action('admin_head', 'admin_socialicons_header');
    } else {
        sociallinks_frontend();
    }
}

function admin_socialicons_header() {
    echo '<link href = "' . SOCIALICONS_URL . 'admin/view/css/admin-socialicons.css" type = "text/css" rel = "stylesheet" />';
    echo '<link href = "' . SOCIALICONS_URL . 'admin/view/css/jquery-ui-1.8.23.custom.css" type = "text/css" rel = "stylesheet" />';
}

function add_options_to_wp_admin() {
    add_menu_page('SocialIcons', 'SocialIcons', 'manage_options', 'socialicons', 'socialicons_dashboard', plugins_url('img/ic-15x15.png', dirname(dirname(__FILE__))));
}

function sociallinks_frontend() {
    $SocialIcons = new SocialIcons();
    $settings = $SocialIcons->getSettings();

    $usedefault_css = $settings['usedefault_css'];

    if ($usedefault_css == 1) {
        wp_register_style('socialicons_css', SOCIALICONS_URL . 'css/socialicons.css');
        wp_enqueue_style('socialicons_css');
    }
}

function wp_ajax_search_icons_iconfinder() {

    $nonce = $_REQUEST['_ajax_wpnonce'];
    $q = urldecode($_REQUEST['q']);

    $response = $response_json = '';

    if (!$q) {
        $response = __('Search param cannot be empty', SOCIALICONS_LANG);
    }

    if (!wp_verify_nonce($nonce, 'socialicons_wpnonce')) {
        die();
    }

    $Iconfinder = new Iconfinder();
    $response = $Iconfinder->get($q);

    $response_json = $response['body'];

    header('content-type:application/json');
    if ($response_json) {
        echo $response_json;
    } else {
        echo json_encode($response);
    }
    die;
}

function wp_ajax_downloadicon_iconfinder() {

    $image = $_REQUEST['icon'];
    $nonce = $_REQUEST['_ajax_wpnonce'];
    if (!wp_verify_nonce($nonce, 'socialicons_wpnonce')) {
        die();
    }

    $Iconfinder = new Iconfinder();
    $response = $Iconfinder->download($image);

    header('content-type:application/json');

    if (!empty($response)) {
        echo json_encode($response);
    } else {
        echo json_encode(__('Error! Could not download selected image', SOCIALICONS_LANG));
    }

    die();
}

function wp_ajax_save_sociallinks_settings() {

    $nonce = $_REQUEST['_ajax_wpnonce'];

    if (!wp_verify_nonce($nonce, 'socialicons_wpnonce')) {
        die();
    }

    $SocialIcons = new SocialIcons();
    $SocialIcons->saveSettings($_REQUEST);
    $response = __('Saved!', SOCIALICONS_LANG);
    echo $response;
    die();
}

function wp_ajax_save_sociallinks_newicon() {
    global $wpdb;
    $nonce = $_REQUEST['_ajax_wpnonce'];
    $__error = null;

    if (!wp_verify_nonce($nonce, 'socialicons_wpnonce')) {
        die();
    }

    $name = $_REQUEST['socialicons_name'];
    $url = $_REQUEST['socialicons_url'];
    $target = $_REQUEST['socialicons_target'];
    $icon_file = $_REQUEST['socialicons_iconurl'];

    if (!$name) {
        $__error = new WP_Error('error_add_socialicon', 'Field name cannot be empty');
    }
    if (!$url) {
        $__error = new WP_Error('error_add_socialicon', 'Field url cannot be empty');
    }
    if (!$target) {
        $__error = new WP_Error('error_add_socialicon', 'Field target cannot be empty');
    }
    if (!$icon_file) {
        $__error = new WP_Error('error_add_socialicon', 'Field icon url cannot be empty');
    }

    header('content-type:application/json');

    if ($__error) {
        echo json_encode($__error);
        die();
    }

    $SocialIcons = new SocialIcons($wpdb);
    $response = $SocialIcons->AddNewSocialIconUrl($name, $url, $target, $icon_file);
    echo json_encode($response);
    die();
}

function wp_ajax_sociallinks_remove_link() {
    global $wpdb;
    $nonce = $_REQUEST['_ajax_wpnonce'];

    if (!wp_verify_nonce($nonce, 'socialicons_wpnonce')) {
        die();
    }

    $id = $_REQUEST['id'];

    $SocialIcons = new SocialIcons($wpdb);
    header('content-type:application/json');
    echo json_encode($SocialIcons->DeleteSocialIconUrl($id));
    die();
}

function wp_ajax_sociallinks_changestatus_link() {
    global $wpdb;
    $nonce = $_REQUEST['_ajax_wpnonce'];
    $status = $_REQUEST['status'];

    if (!wp_verify_nonce($nonce, 'socialicons_wpnonce')) {
        die();
    }

    if ($status == 'enabled') {
        $status = '0';
    } else {
        $status = '1';
    }

    $id = $_REQUEST['id'];
    $SocialIcons = new SocialIcons($wpdb);
    echo $SocialIcons->ChangeStatusIconUrl($id, $status);
    die;
}

function wp_ajax_sociallinks_load_socialicons() {
    global $wpdb;
    $nonce = $_REQUEST['_ajax_wpnonce'];

    if (!wp_verify_nonce($nonce, 'socialicons_wpnonce')) {
        die();
    }

    $SocialIcons = new SocialIcons($wpdb);
    header('content-type:application/json');
    echo json_encode(array('icons' => $SocialIcons->getAllSocialIcons()));
    die();
}

function getSocialIconsHTML($params = array()) {
    global $wpdb;
    $SocialIcons = new SocialIcons($wpdb);
    $results = $SocialIcons->getActiveSocialIconsLinks();

    $class = $id = '';

    $__html = array();


    if (!isset($params['class']) || empty($params['class'])) {
        $class = SOCIALICONS_DEFAULT_CSSCLASS;
    } else {
        $class = $params['class'];
    }


    if (!isset($params['id']) || empty($params['id'])) {
        $id = '';
    } else {
        $id = $params['id'];
    }

    if ($id && !empty($id)) {
        $id = sprintf(' id="%s" ', $id);
    }



    array_push($__html, '<div class="socialiconsbox clearfix">');
    array_push($__html, sprintf('<ul class="%s" %s>', $class, $id));

    if (sizeof($results) > 0) {
        foreach ($results as $value) {
            $__img = sprintf('<img src="%s"/>', $value['icon_file']);
            $__href = sprintf('<a href="%s" target="%s">%s</a>', $value['url'], $value['target'], $__img);
            $__li = sprintf('<li>%s</li>', $__href);
            array_push($__html, $__li);
        }
    }

    array_push($__html, '</ul>');
    array_push($__html, '</div>');
    return implode('', $__html);
}

function socialicons_shortcode($atts) {

    $id = $class = null;

    $default = array(
        'class' => SOCIALICONS_DEFAULT_CSSCLASS,
        'id' => '',
    );

    extract(shortcode_atts($default, $atts));


    echo getSocialIconsHTML(array(
        'id' => $id,
        'class' => $class
    ));
}

function socialicons_dashboard() {
    global $wpdb;

    $SocialIcons = new SocialIcons($wpdb);

    $socialicon_values = $SocialIcons->getAllSocialIcons();


    $SocialIconsView = new SocialIconsView();
    $SocialIconsView->socialicons = $socialicon_values;
    $SocialIconsView->_wpnonce = SOCIALICONS_WPNONCE;
    $SocialIconsView->settings = $SocialIcons->getSettings();


    $SocialIconsView->render('addedit_socialicons.php');
}

