<?php
/*
 * Plugin Name: Colors Swatches-woo By Preeti Palsra 
 * Description: Custom Plugin Colors Swatches variation.
 * Author: preetipalsra
 * Text Domain: colors-swatches-woo-by-preeti-palsra
 * Version: 1.0.0
 * License: GPL2
 */

if (!defined('ABSPATH')) {
    exit;
}

// Enqueue admin scripts and styles
function colors_swatches_woo_by_preeti_palsra_admin_scripts($hook) {
    // Only enqueue on product edit pages
    if ('post.php' != $hook && 'post-new.php' != $hook) {
        return;
    }

    // Register local versions of the DataTables scripts and styles
    wp_register_style('anagha_swatches_data_table', plugins_url('/css/jquery.dataTables.min.css', __FILE__));
    wp_register_script('anagha_swatches_data_script', plugins_url('/js/jquery.dataTables.min.js', __FILE__), array('jquery'), null, true);
    wp_register_style('anagha_swatches_custom_style', plugins_url('/css/custom-style.css', __FILE__), null);

    wp_enqueue_script('anagha_swatches_data_script');
    wp_enqueue_style('anagha_swatches_data_table');
    wp_enqueue_style('anagha_swatches_custom_style');
}
add_action('admin_enqueue_scripts', 'colors_swatches_woo_by_preeti_palsra_admin_scripts');


include_once 'inc/woo-hook-action.php';


