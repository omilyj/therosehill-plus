<?php

function rhp_update_event_status() {
    $today = date('Y-m-d'); // Today's date in the correct format
    $tomorrow = date('Y-m-d', strtotime('+1 day', strtotime($today)));

    // Fetch all events
    $args = [
        'post_type' => 'event',
        'posts_per_page' => -1,
        'post_status' => 'publish', // Fetch only published events
        'meta_query' => [
            'relation' => 'OR',
            // Select events based on their end date or start date if the end date is not available
            [
                'key' => 'event_end_date',
                'compare' => 'EXISTS',
            ],
            [
                'key' => 'event_start_date',
                'compare' => 'EXISTS',
            ],
        ],
    ];

    $events = get_posts($args);

    foreach ($events as $event) {
        $endDate = get_post_meta($event->ID, 'event_end_date', true);
        $startDate = get_post_meta($event->ID, 'event_start_date', true);

        // Use the end date if available; otherwise, use the start date
        $eventDate = $endDate ?: $startDate;

        // Update a custom field to indicate whether the event is upcoming or past
        if ($eventDate < $tomorrow) {
            update_post_meta($event->ID, 'event_status', 'past');
        } else if ($eventDate >= $today) {
            update_post_meta($event->ID, 'event_status', 'upcoming');
        }
    }
}

