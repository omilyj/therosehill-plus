<?php

function rhp_label_post_type() {
    $labels = array(
		'name'                  => _x( 'Records', 'Post type general name', 'therosehill-plus' ),
		'singular_name'         => _x( 'Record', 'Post type singular name', 'therosehill-plus' ),
		'menu_name'             => _x( 'Label', 'Admin Menu text', 'therosehill-plus' ),
		'name_admin_bar'        => _x( 'Record', 'Add New on Toolbar', 'therosehill-plus' ),
		'add_new'               => __( 'Add New', 'therosehill-plus' ),
		'add_new_item'          => __( 'Add New Record', 'therosehill-plus' ),
		'new_item'              => __( 'New Record', 'therosehill-plus' ),
		'edit_item'             => __( 'Edit Record', 'therosehill-plus' ),
		'view_item'             => __( 'View Record', 'therosehill-plus' ),
		'all_items'             => __( 'All Records', 'therosehill-plus' ),
		'search_items'          => __( 'Search Records', 'therosehill-plus' ),
		'parent_item_colon'     => __( 'Parent Records:', 'therosehill-plus' ),
		'not_found'             => __( 'No Records found.', 'therosehill-plus' ),
		'not_found_in_trash'    => __( 'No Records found in Trash.', 'therosehill-plus' ),
		'featured_image'        => _x( 'Record Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'therosehill-plus' ),
		'set_featured_image'    => _x( 'Set record image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'therosehill-plus' ),
		'remove_featured_image' => _x( 'Remove record image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'therosehill-plus' ),
		'use_featured_image'    => _x( 'Use as record image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'therosehill-plus' ),
		'archives'              => _x( 'Record archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'therosehill-plus' ),
		'insert_into_item'      => _x( 'Insert into Record', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'therosehill-plus' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this Record', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'therosehill-plus' ),
		'filter_items_list'     => _x( 'Filter Records list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'therosehill-plus' ),
		'items_list_navigation' => _x( 'Records list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'therosehill-plus' ),
		'items_list'            => _x( 'Records list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'therosehill-plus' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true, // ?label=recordname
		'rewrite'            => array( 'slug' => 'label' ), // /label/recordname
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => 20,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true,
        'description'        => __('A custom post type for label releases and records', 'therosehill-plus'),
        'taxonomies'         => ['category', 'post_tag']
	);

	register_post_type( 'label', $args );

	register_post_meta('label', 'record_release_date', [
		'type'              => 'string',
		'description'		=> __('The release date of a record', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

	register_post_meta('label', 'record_listen_link', [
		'type'              => 'string',
		'description'		=> __('A URL to listen to the record', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

	register_post_meta('label', 'record_buy_link', [
		'type'              => 'string',
		'description'		=> __('A URL to buy the record', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

    register_taxonomy('artist', array('label', 'resident', 'event'), [
        'label' => __('Artists', 'therosehill-plus'),
        'rewrite' => ['slug' => 'artist'],
        'show_in_rest' => true
    ]);

	register_term_meta('artist', 'instagram_url', [
		'type' => 'string',
		'description' => __('A URL to the artist\'s Instagram page', 'therosehill-plus'),
		'single' => true,
		'show_in_rest' => true,
		'default' => ''
	]);

	register_term_meta('artist', 'facebook_url', [
		'type' => 'string',
		'description' => __('A URL to the artist\'s Facebook page', 'therosehill-plus'),
		'single' => true,
		'show_in_rest' => true,
		'default' => ''
	]);

	register_term_meta('artist', 'youtube_url', [
		'type' => 'string',
		'description' => __('A URL to the artist\'s YouTube page', 'therosehill-plus'),
		'single' => true,
		'show_in_rest' => true,
		'default' => ''
	]);

	register_term_meta('artist', 'music_url', [
		'type' => 'string',
		'description' => __('A URL to listen to the artist\'s music', 'therosehill-plus'),
		'single' => true,
		'show_in_rest' => true,
		'default' => ''
	]);

	register_term_meta('artist', 'more_info_url', [
		'type' => 'string',
		'description' => __('A URL for more information on an artist', 'therosehill-plus'),
		'single' => true,
		'show_in_rest' => true,
		'default' => ''
	]);

	register_term_meta('artist', 'artist_image', [
        'type' => 'integer',
        'description' => __('An image of the artist', 'therosehill-plus'),
        'single' => true,
        'show_in_rest' => true,
        'default' => 0
    ]);
}