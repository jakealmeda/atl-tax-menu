<?php
/**
 * Plugin Name: ATL Taxonomy Menu
 * Description: Show a list of entries based on taxonomy
 * Version: 1.0
 * Author: Jake Almeda
 * Author URI: http://smarterwebpackages.com/
 * Network: true
 * License: GPL2
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Call main class
$atl_tm = new AtlasSurvivalSheltersTaxMenu();

// Include files
include_once( 'lib/atl-tax-functions.php' );
include_once( 'lib/atl-tax-acf.php' );

// Main class
class AtlasSurvivalSheltersTaxMenu {

    // simply return this plugin's main directory
    public function atl_plugin_dir_path() {

        return plugin_dir_path( __FILE__ );

    }

    // hook list
    public $genesis_hooks = array(
        'genesis_before',
        'genesis_before_header',
        'genesis_header',
        'genesis_site_title',
        'genesis_header_right',
        'genesis_site_description',
        'genesis_after_header',
        'genesis_before_content_sidebar_wrap',
        'genesis_before_content',
        'genesis_before_loop',
        'genesis_before_sidebar_widget_area',
        'genesis_after_sidebar_widget_area',
        'genesis_loop',
        'genesis_before_entry',
        'genesis_entry_header',
        'genesis_entry_content',
        'genesis_entry_footer',
        'genesis_after_entry',
        'genesis_after_endwhile',
        'genesis_after_loop',
        'genesis_after_content',
        'genesis_after_content_sidebar_wrap',
        'genesis_before_footer',
        'genesis_footer',
        'genesis_after_footer',
        'genesis_after',
    );

    // list of excluded post types from MULTI option
    public function atl_not_from_these_posttypes() {

        return array(
            'attachment',
            'revision',
            'nav_menu_item',
            'custom_css',
            'customize_changeset',
            'oembed_cache',
            'user_request',
            'wp_block',
            'wp_template',
            'wp_template_part',
            'wp_global_styles',
            'wp_navigation',
            'acf-field-group',
            'acf-field',
            '_pods_pod',
            '_pods_group',
            '_pods_field',
            '_pods_template',
        );

    }

    // order by list
    public $order_by = array(
        'title'             => 'Title',
        'date_published'    => 'Date Published',
        'date_modified'     => 'Date Modified',
    );

}


// Call sub main class
$atl_tm_2 = new ATLSubTaxMenu();