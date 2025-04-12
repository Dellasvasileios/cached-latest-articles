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

define('CLA_PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once plugin_dir_path(__FILE__) . 'admin/options-page.php';
require_once plugin_dir_path(__FILE__) . 'inc/CLA_Cache_Handler.php';
require_once plugin_dir_path(__FILE__) . 'inc/CLA_Event_Handler.php';
require_once plugin_dir_path(__FILE__) . 'admin/shortcodes/shorcode_cached_latest_articles.php';

function CLA_enqueue_admin_scripts(){

    $current_screen = get_current_screen();

    if($current_screen->id === 'toplevel_page_CLA-options'){
        wp_enqueue_style("options-page-css", plugin_dir_url(__FILE__) . 'assets/css/admin/options-page.css', [], '1.0.0', 'all');
        wp_enqueue_script("options-page-js", plugin_dir_url(__FILE__) . 'assets/js/admin/options_page.js', [], '1.0.0', true);
    }
}

add_action('admin_enqueue_scripts', 'CLA_enqueue_admin_scripts');


function CLA_enqueue_front_end_scripts(){
    wp_enqueue_script("cla-frontend-js", plugin_dir_url(__FILE__) . 'assets/js/ajaxFetchCache.js', [], '1.0.0', true);

    wp_localize_script('cla-frontend-js', 'cla_ajax_object', array(
        'domain' => get_option('home'),
    ));
}

add_action('wp_enqueue_scripts', 'CLA_enqueue_front_end_scripts');

function CLA_init_caching(){
    
    $shortcode_caching_options = get_option('cla_options');

    foreach($shortcode_caching_options as $index => $option){
        
        $CLA_Posts = new CLA_Posts((int)$option['number_of_posts']);

        $CLA_Cache_Handler = new CLA_Cache_Handler(
            $option['id'],
            $CLA_Posts,
            new CLA_File_Handler(),
            CLA_PLUGIN_DIR . 'cache/'
        );

        $CLA_Event_Hnadler = new CLA_Event_Handler(
            $CLA_Cache_Handler,
            $CLA_Posts,
            $option['refresh_on_publish'],
            $option['refresh_on_update'],
            $option['refresh_on_delete'],
            $option['is_active']
        ); 
        
    }
    
}

add_action('init', 'CLA_init_caching');

add_action('rest_api_init', function () {
    register_rest_route('cla/v1', '/shortcode/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'cla_fetch_shortcode_data',
        'permission_callback' => '__return_true', 
    ));
});

function cla_fetch_shortcode_data($data) {
    $id = $data['id'];

    $cache = false;

    $response = [];

    if(file_exists(CLA_PLUGIN_DIR . 'cache/articles-cache' . $id . '.json') === false){
        $response = array(
            'id' => $id,
            'content' => json_decode("[]" , true),
        );

        return rest_ensure_response($response);
    }

    $cache = file_get_contents(CLA_PLUGIN_DIR . 'cache/articles-cache' . $id . '.json');
    
    $response = array(
        'id' => $id,
        'content' => json_decode($cache , true),
    );

    return rest_ensure_response($response);

}