<?php

function rhp_event_post_type() {
    $labels = array(
		'name'                  => _x( 'Events', 'Post type general name', 'therosehill-plus' ),
		'singular_name'         => _x( 'Event', 'Post type singular name', 'therosehill-plus' ),
		'menu_name'             => _x( 'Events', 'Admin Menu text', 'therosehill-plus' ),
		'name_admin_bar'        => _x( 'Event', 'Add New on Toolbar', 'therosehill-plus' ),
		'add_new'               => __( 'Add New', 'therosehill-plus' ),
		'add_new_item'          => __( 'Add New Event', 'therosehill-plus' ),
		'new_item'              => __( 'New Event', 'therosehill-plus' ),
		'edit_item'             => __( 'Edit Event', 'therosehill-plus' ),
		'view_item'             => __( 'View Event', 'therosehill-plus' ),
		'all_items'             => __( 'All Events', 'therosehill-plus' ),
		'search_items'          => __( 'Search Events', 'therosehill-plus' ),
		'parent_item_colon'     => __( 'Parent Events:', 'therosehill-plus' ),
		'not_found'             => __( 'No Events found.', 'therosehill-plus' ),
		'not_found_in_trash'    => __( 'No Events found in Trash.', 'therosehill-plus' ),
		'featured_image'        => _x( 'Event Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'therosehill-plus' ),
		'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'therosehill-plus' ),
		'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'therosehill-plus' ),
		'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'therosehill-plus' ),
		'archives'              => _x( 'Event archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'therosehill-plus' ),
		'insert_into_item'      => _x( 'Insert into Event', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'therosehill-plus' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this Event', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'therosehill-plus' ),
		'filter_items_list'     => _x( 'Filter Events list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'therosehill-plus' ),
		'items_list_navigation' => _x( 'Events list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'therosehill-plus' ),
		'items_list'            => _x( 'Events list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'therosehill-plus' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true, // ?event=eventtitle
		'rewrite'            => array( 'slug' => 'event' ), // /event/eventtitle
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 20,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'author', 'comments' ),
        'show_in_rest'       => true,
        'description'        => __('A custom post type for events', 'therosehill-plus'),
        'taxonomies'         => ['category', 'post_tag']
	);

	register_post_type( 'event', $args );

	register_post_meta('event', 'event_start_date', [
		'type'              => 'string',
		'description'		=> __('The start date of an event', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

	register_post_meta('event', 'event_end_date', [
		'type'              => 'string',
		'description'		=> __('The end date of an event', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

    register_post_meta('event', 'event_start_time', [
		'type'              => 'string',
		'description'		=> __('The start time of an event', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

	register_post_meta('event', 'event_end_time', [
		'type'              => 'string',
		'description'		=> __('The end time of an event', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

    register_post_meta('event', 'event_duplicated', [
		'type'              => 'string',
		'description'		=> __('Has the event already been duplicated.', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

    register_post_meta('event', 'event_recurrence', [
		'type'              => 'string',
		'description'		=> __('Does the event recur.', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

    register_post_meta('event', 'event_recurrence_interval', [
		'type'              => 'string',
		'description'		=> __('The event recurrence interval.', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

    register_post_meta('event', 'event_recurrence_amount', [
		'type'              => 'number',
		'description'		=> __('How many times the event recurs.', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

    register_post_meta('event', 'event_recurrence_day', [
		'type'              => 'string',
		'description'		=> __('What day of the week the event recurs.', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

    register_post_meta('event', 'event_recurrence_time_month', [
		'type'              => 'string',
		'description'		=> __('Does the event recur in the first, second, third, fourth or last week of the month.', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

    register_post_meta('event', 'event_booking_type', [
		'type'              => 'string',
		'description'		=> __('The booking type for an event', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

    register_post_meta('event', 'event_is_free_inhouse', [
		'type'              => 'string',
		'description'		=> __('Whether the event is free (In-House)', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

    register_post_meta('event', 'event_is_free_external', [
		'type'              => 'string',
		'description'		=> __('Whether the event is free (External)', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

    register_post_meta('event', 'event_is_free_no_booking', [
		'type'              => 'string',
		'description'		=> __('Whether the event is free (No Booking)', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

    register_post_meta('event', 'event_ticket_capacity', [
		'type'              => 'number',
		'description'		=> __('The total ticket capacity for an event', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

    register_post_meta('event', 'event_ticket_full_price', [
		'type'              => 'number',
		'description'		=> __('The full ticket price for an event', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

    register_post_meta('event', 'event_ticket_conc_price', [
		'type'              => 'number',
		'description'		=> __('The concessions ticket price for an event', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

    register_post_meta('event', 'event_adv_price', [
		'type'              => 'string',
		'description'		=> __('The advance ticket price for an event', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

    register_post_meta('event', 'event_otd_price_inhouse', [
		'type'              => 'string',
		'description'		=> __('The on the door ticket price for an event (In-House)', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

    register_post_meta('event', 'event_otd_price_external', [
		'type'              => 'string',
		'description'		=> __('The on the door ticket price for an event (External)', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

    register_post_meta('event', 'event_otd_price_no_booking', [
		'type'              => 'string',
		'description'		=> __('The on the door ticket price for an event (No Booking)', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

    register_post_meta('event', 'event_ext_tickets', [
		'type'              => 'string',
		'description'		=> __('External link to buy tickets for an event', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

    register_post_meta('event', 'event_ticket_product_id', [
		'type'              => 'number',
		'description'		=> __('The WooCommerce product ID for the event ticket', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

    register_post_meta('event', 'event_location', [
		'type'              => 'string',
		'description'		=> __('The location of an event', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

    register_post_meta('event', 'event_status', [
		'type'              => 'string',
		'description'		=> __('If the event is upcoming on in the past', 'therosehill-plus'),
        'single'            => true,
        'show_in_rest'      => true,
	]);

    register_taxonomy('event-type', array('event'), [
        'label' => __('Event Types', 'therosehill-plus'),
        'rewrite' => ['slug' => 'event-type'],
        'show_in_rest' => true,
    ]);

    register_taxonomy('promoter', array('event'), [
        'label' => __('Promoters', 'therosehill-plus'),
        'rewrite' => ['slug' => 'promoter'],
        'show_in_rest' => true,
    ]);

	register_term_meta('promoter', 'instagram_url', [
		'type' => 'string',
		'description' => __('A URL to the promoter\'s Instagram page', 'therosehill-plus'),
		'single' => true,
		'show_in_rest' => true,
		'default' => ''
	]);

	register_term_meta('promoter', 'facebook_url', [
		'type' => 'string',
		'description' => __('A URL to the promoter\'s Facebook page', 'therosehill-plus'),
		'single' => true,
		'show_in_rest' => true,
		'default' => ''
	]);

	register_term_meta('promoter', 'youtube_url', [
		'type' => 'string',
		'description' => __('A URL to the promoter\'s YouTube page', 'therosehill-plus'),
		'single' => true,
		'show_in_rest' => true,
		'default' => ''
	]);

	register_term_meta('promoter', 'more_info_url', [
		'type' => 'string',
		'description' => __('A URL for more information on an promoter', 'therosehill-plus'),
		'single' => true,
		'show_in_rest' => true,
		'default' => ''
	]);
}