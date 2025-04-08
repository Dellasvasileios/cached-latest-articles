<?php
/**
 * Plugin Name: Cached Latest Articles
 * Description: Displays the latest articles using a JSON cache. Supports card or list view through a shortcode.
 * Version: 1.0
 * Author: Vasilis Dellas
 */

 if(!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once plugin_dir_path(__FILE__) . 'admin/options-page.php';

function CLA_enqueue_admin_scripts(){

    $current_screen = get_current_screen();

    if($current_screen->id === 'toplevel_page_CLA-options'){
        wp_enqueue_style("options-page-css", plugin_dir_url(__FILE__) . 'assets/css/admin/options-page.css', [], '1.0.0', 'all');
        wp_enqueue_script("options-page-js", plugin_dir_url(__FILE__) . 'assets/js/admin/options_page.js', [], '1.0.0', true);
    }
}

add_action('admin_enqueue_scripts', 'CLA_enqueue_admin_scripts');
