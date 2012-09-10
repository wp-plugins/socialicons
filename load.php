<?php

/*
  Plugin Name: SocialIcons
  Plugin URI: http://eduardostuart.com.br/wordpress-plugin-socialicons/
  Description: SocialIcons is a wordPress pugin that makes it easy to include social icons into your WordPress blog.
  Version: 1.2.1
  Author: Eduardo Stuart
  Author URI: http://facebook.com/eduardostuart
  License: GPLv2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */


define('SOCIALICONS_LANG', 'social-icons');
define('SOCIALICONS_URL', plugin_dir_url(__FILE__));
define('SOCIALICONS_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('SOCIALICONS_VERSION', '1.2.1');
define('SOCIALICONS_DEFAULT_CSSCLASS', 'socialicons_listicons');



load_plugin_textdomain(SOCIALICONS_LANG, false, dirname(plugin_basename(__FILE__)) . '/lang/');


require_once 'admin/class/Install.php';
require_once 'admin/class/IconFinder.php';
require_once 'admin/class/SocialIcons.php';
require_once 'admin/class/SocialIconsView.php';
require_once 'admin/class/SocialIconsWidget.php';


require_once 'admin/include/functions.php';



add_action('wp_loaded', 'init_socialicons');
add_action('widgets_init', create_function('', 'register_widget( "SocialIconsWidget" );'));

add_shortcode('socialicons', 'socialicons_shortcode');

if (!is_admin()) {
    $_shortcode_priority = 11;
    if (defined('SHORTCODE_PRIORITY')) {
        $_shortcode_priority = SHORTCODE_PRIORITY;
    }
    add_filter('widget_text', 'do_shortcode', $_shortcode_priority);
}


if (is_admin()) {
    add_action('wp_ajax_search_icons_iconfinder', 'wp_ajax_search_icons_iconfinder');
    add_action('wp_ajax_downloadicon_iconfinder', 'wp_ajax_downloadicon_iconfinder');
    add_action('wp_ajax_save_sociallinks_settings', 'wp_ajax_save_sociallinks_settings');
    add_action('wp_ajax_save_sociallinks_newicon', 'wp_ajax_save_sociallinks_newicon');
    add_action('wp_ajax_sociallinks_load_socialicons', 'wp_ajax_sociallinks_load_socialicons');
    add_action('wp_ajax_sociallinks_remove_link', 'wp_ajax_sociallinks_remove_link');
    add_action('wp_ajax_sociallinks_changestatus_link', 'wp_ajax_sociallinks_changestatus_link');
}


register_activation_hook(__FILE__, 'wp_socialicons_install');