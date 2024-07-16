<?php

function rhp_resident_post_type() {
    $labels = array(
		'name'                  => _x( 'Residents', 'Post type general name', 'therosehill-plus' ),
		'singular_name'         => _x( 'Resident', 'Post type singular name', 'therosehill-plus' ),
		'menu_name'             => _x( 'Residents', 'Admin Menu text', 'therosehill-plus' ),
		'name_admin_bar'        => _x( 'Resident', 'Add New on Toolbar', 'therosehill-plus' ),
		'add_new'               => __( 'Add New', 'therosehill-plus' ),
		'add_new_item'          => __( 'Add New Resident', 'therosehill-plus' ),
		'new_item'              => __( 'New Resident', 'therosehill-plus' ),
		'edit_item'             => __( 'Edit Resident', 'therosehill-plus' ),
		'view_item'             => __( 'View Resident', 'therosehill-plus' ),
		'all_items'             => __( 'All Residents', 'therosehill-plus' ),
		'search_items'          => __( 'Search Residents', 'therosehill-plus' ),
		'parent_item_colon'     => __( 'Parent Residents:', 'therosehill-plus' ),
		'not_found'             => __( 'No Residents found.', 'therosehill-plus' ),
		'not_found_in_trash'    => __( 'No Residents found in Trash.', 'therosehill-plus' ),
		'featured_image'        => _x( 'Resident Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'therosehill-plus' ),
		'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'therosehill-plus' ),
		'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'therosehill-plus' ),
		'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'therosehill-plus' ),
		'archives'              => _x( 'Resident archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'therosehill-plus' ),
		'insert_into_item'      => _x( 'Insert into Resident', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'therosehill-plus' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this Resident', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'therosehill-plus' ),
		'filter_items_list'     => _x( 'Filter Residents list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'therosehill-plus' ),
		'items_list_navigation' => _x( 'Residents list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'therosehill-plus' ),
		'items_list'            => _x( 'Residents list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'therosehill-plus' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true, // ?resident=artistname
		'rewrite'            => array( 'slug' => 'resident' ), // /resident/artistname
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 20,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        'show_in_rest'       => true,
        'description'        => __('A custom post type for residents', 'therosehill-plus'),
        'taxonomies'         => ['category', 'post_tag']
	);

	register_post_type( 'resident', $args );

	register_post_meta('resident', 'residency_start_date', [
		'type'              => 'string',
		'description'		=> __('The start date of a residency', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

	register_post_meta('resident', 'residency_end_date', [
		'type'              => 'string',
		'description'		=> __('The end date of a residency', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);
}