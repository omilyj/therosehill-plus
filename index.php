<?php
/*
 * Plugin Name:       The Rose Hill Plus
 * Plugin URI:        https://www.therosehill.co.uk/
 * Description:       A plugin for adding blocks to a theme.
 * Version:           1.0.0
 * Requires at least: 5.9
 * Requires PHP:      8.0
 * Author:            Emily Jones
 * Author URI:        
 * Text Domain:       therosehill-plus
 * Domain Path:       /languages
 */

 if( !function_exists('add_action') ) {
    echo 'Seems like you stumbled here by accident!';
    exit;
 }

 // Setup
define('RHP_PLUGIN_DIR', plugin_dir_path(__FILE__));

 // Includes
$rootFiles = glob(RHP_PLUGIN_DIR . 'includes/*.php');
$subdirectoryFiles = glob(RHP_PLUGIN_DIR . 'includes/**/*.php');
$allFiles = array_merge($rootFiles, $subdirectoryFiles);

foreach($allFiles as $filename) {
   include_once($filename);
}

// HOOKS
register_activation_hook(__FILE__, 'rhp_activate_plugin');
add_action('init', 'rhp_register_blocks');
add_action('rest_api_init', 'rhp_rest_api_init');
add_action('wp_enqueue_scripts', 'rhp_enqueue_scripts');
add_action('admin_head', 'rhp_custom_admin_styles');
// Custom post types
add_action('init', 'rhp_resident_post_type');
add_action('init', 'rhp_label_post_type');
add_action('init', 'rhp_event_post_type');
add_action('save_post_label', 'rhp_save_post_label');
add_action('save_post_resident', 'rhp_save_post_resident');
remove_action('save_post_event', 'rhp_save_post_event');
add_action('add_meta_boxes', 'rhp_add_residency_date_meta_boxes');
add_action('add_meta_boxes', 'rhp_add_record_meta_boxes');
add_action('add_meta_boxes', 'rhp_add_event_meta_boxes');
add_filter('rest_event_query', 'rhp_rest_event_query', 10, 2 );
// Artist taxonomy
add_action('artist_add_form_fields', 'rhp_artist_add_form_fields');
add_action('create_artist', 'rhp_save_artist_meta');
add_action('artist_edit_form_fields', 'rhp_artist_edit_form_fields');
add_action('edited_artist', 'rhp_save_artist_meta');
add_action('create_term', 'save_artist_image_data', 10, 2);
// Promoter taxonomy
add_action('promoter_add_form_fields', 'rhp_promoter_add_form_fields');
add_action('create_promoter', 'rhp_save_promoter_meta');
add_action('promoter_edit_form_fields', 'rhp_promoter_edit_form_fields');
add_action('edited_promoter', 'rhp_save_promoter_meta');
add_action('create_term', 'save_promoter_image_data', 10, 2);
// Edit Label admin page
add_filter('manage_edit-label_columns', 'rhp_label_post_type_columns');
add_action('manage_label_posts_custom_column', 'rhp_label_custom_column_content', 10, 2);
// Edit Resident admin page
add_filter('manage_edit-resident_columns', 'rhp_resident_post_type_columns');
add_action('manage_resident_posts_custom_column', 'rhp_resident_custom_column_content', 10, 2);
// Edit Event admin page
add_action('rhp_update_event_status_hook', 'rhp_update_event_status');
add_action('admin_init', function() {
if (!wp_next_scheduled('rhp_update_event_status_hook')){
   wp_schedule_event(time(), 'daily', 'rhp_update_event_status_hook');
}
});
add_action('restrict_manage_posts', 'rhp_add_event_filters_to_admin', 10, 2);
add_filter('pre_get_posts', 'rhp_filter_events_by_custom_status');
add_filter('manage_edit-event_columns', 'rhp_event_post_type_columns');
add_action('manage_event_posts_custom_column', 'rhp_event_custom_column_content', 10, 2);
// Edit media uploader fields
add_filter('attachment_fields_to_edit', 'rhp_add_image_attachment_fields_to_edit', 10, 2);
add_filter('attachment_fields_to_save', 'rhp_save_image_attachment_fields', 10, 2);