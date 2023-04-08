<?php
/**
 * Plugin Name: AskWP
 * Description: A WordPress plugin that adds a ChatGPT interface to your site.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Include necessary files
require_once plugin_dir_path(__FILE__) . 'includes/class-askwp-api-client.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-askwp-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-askwp-shortcode.php';

// Initialize the plugin
function askwp_initialize_plugin() {
    $api_client = new AskWP_API_Client();
    $settings = new AskWP_Settings($api_client);
    $shortcode = new AskWP_Shortcode($api_client);

    $settings->init();
    $shortcode->init();
}

askwp_initialize_plugin();
