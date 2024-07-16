<?php

function rhp_add_event_filters_to_admin($post_type, $which) {
    // Apply only to your 'event' post type on the 'edit.php' admin page
    if ('event' === $post_type) {
        ?>
        <select name="event_status_filter" id="event_status_filter">
            <option value="">Show All Statuses</option>
            <option value="upcoming" <?php if (isset($_GET['event_status_filter']) && 'upcoming' === $_GET['event_status_filter']) echo 'selected'; ?>>Upcoming</option>
            <option value="past" <?php if (isset($_GET['event_status_filter']) && 'past' === $_GET['event_status_filter']) echo 'selected'; ?>>Past</option>
        </select>
        <?php
    }
}

function rhp_filter_events_by_custom_status($query) {
    global $pagenow;

    // Check if we are in admin 'edit.php', the post type is 'event', and our custom filter is set
    if (is_admin() && 'edit.php' === $pagenow && 'event' === $query->query_vars['post_type'] && isset($_GET['event_status_filter']) && !empty($_GET['event_status_filter'])) {
        $event_status = $_GET['event_status_filter'];

        // Add our meta query to the existing set of meta queries (if any)
        $meta_query = $query->get('meta_query') ?: [];
        $meta_query[] = [
            'key' => 'event_status',
            'value' => $event_status,
            'compare' => '=',
        ];

        $query->set('meta_query', $meta_query);
    }
}