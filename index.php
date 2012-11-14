<?php

/*
  Plugin Name: Statistico
  Plugin URI: http://www.wpsalad.com/
  Description: Visitor's browser information, operating system and screen resolution info
  Version: 1.1
  Author: WP Salad, Marko Miljus
  Author URI: http://www.wpsalad.com/
 */
?>
<?php

////////////////////////////////////INITIAL SETUP///////////////////////////////
register_activation_hook(__FILE__, 'ws_statistico_install');

function ws_statistico_install() {//Creating two necessary database tables
    global $wpdb;

    $create_first_table = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "ws_statistico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ws_statistico_width` int(11) NOT NULL,
  `ws_statistico_height` int(11) NOT NULL,
  `ws_statistico_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";

    $create_second_table = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "ws_statistico_agent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ws_statistico_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `ws_statistico_version` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `ws_os` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `ws_agent_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($create_first_table);
    dbDelta($create_second_table);
}

////////////////////////////////ADMINISTRATION PANEL////////////////////////////

include_once(ABSPATH . 'wp-includes/pluggable.php');

add_action('admin_menu', 'ws_statistico_init_create_menu'); //Action for creating plugin menu

function ws_statistico_init_create_menu() {//Add menu and submenu item
    add_menu_page('Statistico', 'Statistico', 'manage_options', 'ws_statistico_info', 'ws_statistico_info', plugins_url('/images/stat.png', __FILE__));
    add_submenu_page('ws_statistico_info', 'Browser Info', 'Browser Info', 'manage_options', "ws_statistico_info", 'ws_statistico_info');
    add_submenu_page('ws_statistico_info', 'Screen Resolutions', 'Screen Resolutions', 'manage_options', 'ws_statistico_resolutions', 'ws_statistico_resolutions');
    add_submenu_page('ws_statistico_info', 'Visual Stats', 'Visual Stats', 'manage_options', 'ws_statistico_visual', 'ws_statistico_visual');
}

function ws_statistico_info() {//Function which shows BROWSER INFO link
    include("php/ws_statistico_info.php");
}

function ws_statistico_resolutions() {//Function which shows SCREEN RESOLUTION link
    include("php/ws_statistico_resolutions.php");
}

function ws_statistico_visual() {//Function which shows VISUAL STATS link
    include("php/ws_statistico_visual.php");
}

////////////////////////////////////////////////////////////////////////////////

add_action('wp_enqueue_scripts', 'ws_statistico_scripts');
add_action('wp_enqueue_scripts', 'ws_statistico_style');

function ws_statistico_scripts() {//Include required javascript file(s)
    wp_register_script('ws-browser', plugin_dir_url(__FILE__) . 'js/ws-browser.js');
    wp_enqueue_script('ws-browser');
}

function ws_statistico_style() {//Include required CSS file(s)
    wp_register_style('ws-statistico-style', plugins_url('css/style.css', __FILE__));
    wp_enqueue_style('ws-statistico-style');
}

function ws_statistico_head() {//Function which inserts data from JavaScript
    global $wpdb;
    
    if (isset($_POST['ws_statistico_w']) && isset($_POST['ws_statistico_h']) && !isset($_COOKIE['ws_statistico'])) {
        $result = $wpdb->get_row("SELECT COUNT(id) as cnt FROM `" . $wpdb->prefix . "ws_statistico` WHERE ws_statistico_width = " . mysql_real_escape_string(@$_POST['ws_statistico_w']) . " and ws_statistico_height = " . mysql_real_escape_string(@$_POST['ws_statistico_h']), ARRAY_A);
        $ws_statistico_count = $result['cnt'];

        if ($ws_statistico_count == 0) {
            $wpdb->query("INSERT INTO `" . $wpdb->prefix . "ws_statistico` (ws_statistico_width, ws_statistico_height, ws_statistico_count) VALUES(" . mysql_real_escape_string(@$_POST['ws_statistico_w']) . ", " . mysql_real_escape_string(@$_POST['ws_statistico_h']) . ", 1);");
        } else {
            $wpdb->query("UPDATE `" . $wpdb->prefix . "ws_statistico` SET ws_statistico_count = (ws_statistico_count + 1) WHERE ws_statistico_width = " . mysql_real_escape_string(@$_POST['ws_statistico_w']) . " AND ws_statistico_height = " . mysql_real_escape_string(@$_POST['ws_statistico_h']));
        }

        $result = $wpdb->get_row("SELECT COUNT(id) as cnt FROM `" . $wpdb->prefix . "ws_statistico_agent` WHERE ws_statistico_name = '" . mysql_real_escape_string(@$_POST['ws_statistico_name']) . "' AND ws_statistico_version = '" . mysql_real_escape_string(@$_POST['ws_statistico_version']) . "' AND ws_os = '" . mysql_real_escape_string(@$_POST['ws_os']) . "'", ARRAY_A);
        $ws_agent_count = $result['cnt'];

        if ($ws_agent_count == 0) {
            $wpdb->query("INSERT INTO `" . $wpdb->prefix . "ws_statistico_agent` (ws_statistico_name, ws_statistico_version, ws_os, ws_agent_count) VALUES ('" . mysql_real_escape_string(@$_POST['ws_statistico_name']) . "', '" . mysql_real_escape_string($_POST['ws_statistico_version']) . "', '" . mysql_real_escape_string(@$_POST['ws_os']) . "', 1)");
        } else {
            $wpdb->query("UPDATE`" . $wpdb->prefix . "ws_statistico_agent` SET ws_agent_count = (ws_agent_count + 1) WHERE ws_statistico_name = '" . mysql_real_escape_string(@$_POST['ws_statistico_name']) . "' AND ws_statistico_version = '" . mysql_real_escape_string(@$_POST['ws_statistico_version']) . "' AND ws_os = '" . mysql_real_escape_string(@$_POST['ws_os']) . "'");
        }
        setcookie('ws_statistico', md5('set'), 0, '/');
    }
}

/////////////////////////////////////UNINSTALL//////////////////////////////////
if (function_exists('register_uninstall_hook'))
    register_uninstall_hook(__FILE__, 'ws_statistico_uninstall');

function ws_statistico_uninstall() {//Delete tables on uninstall
    query("DELETE TABLE " . $wpdb->prefix . "ws_statistico");
    query("DELETE TABLE " . $wpdb->prefix . "ws_statistico_agent");
}

?>