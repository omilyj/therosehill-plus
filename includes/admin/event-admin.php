<?php

// Event Admin Page
function rhp_event_post_type_columns($columns) {
    unset($columns['categories']);
    unset($columns['tags']);

    $columns['cb'] = '<input type="checkbox" />';

    $reorderColumns = array(
        'cb' => $columns['cb'], // Add checkbox column at the beginning
        'title' => $columns['title'],
        'event_date' => __('Event Date', 'therosehill-plus'),
        'event_time' => __('Event Time', 'therosehill-plus'),
        'promoter_name' => __('Promoter', 'therosehill-plus'),
        'event_type' => __('Event Type', 'therosehill-plus'),
        'date' => $columns['date'],
    );

    return $reorderColumns;
}

function rhp_event_custom_column_content($column, $postID) {
    switch ($column) {
        case 'cb':
            echo '<input type="checkbox" name="post[]" value="' . esc_attr($postID) . '" />';
            break;
        case 'promoter_name':
            $terms = get_the_terms($postID, 'promoter');
            if (!empty($terms) && !is_wp_error($terms)) {
                $promoters = array();
                foreach ($terms as $term) {
                    $promoter_name = esc_html($term->name);
                    $term_id = $term->term_id;
                    $edit_url = admin_url("term.php?taxonomy=promoter&tag_ID=$term_id&post_type=event&wp_http_referer=" . urlencode($_SERVER['REQUEST_URI']));
                    $promoters[] = '<a href="' . esc_url($edit_url) . '">' . $promoter_name . '</a>';
                }
                echo implode(', ', $promoters);
            } else {
                echo '-';
            }
            break;

        case 'event_type':
            $terms = get_the_terms($postID, 'event-type');
            if (!empty($terms) && !is_wp_error($terms)) {
                $eventTypes = array();
                foreach ($terms as $term) {
                    $event_type = esc_html($term->name);
                    $term_id = $term->term_id;
                    $edit_url = admin_url("term.php?taxonomy=event-type&tag_ID=$term_id&post_type=event&wp_http_referer=" . urlencode($_SERVER['REQUEST_URI']));
                    $eventTypes[] = '<a href="' . esc_url($edit_url) . '">' . $event_type . '</a>';
                }
                echo implode(', ', $eventTypes);
            } else {
                echo '-';
            }
            break;

            case 'event_date':
                $start_date = get_post_meta($postID, 'event_start_date', true);
                $end_date = get_post_meta($postID, 'event_end_date', true);
                // Format and output the start and end dates. Adjust the date format as needed.
                echo !empty($start_date) ? date('d/m/Y', strtotime($start_date)) : 'TBD';
                echo !empty($end_date) ? ' - ' . date('d/m/Y', strtotime($end_date)) : '';
                break;
    
            case 'event_time':
                $start_time = get_post_meta($postID, 'event_start_time', true);
                $end_time = get_post_meta($postID, 'event_end_time', true);
                // Format and output the start and end times. Adjust the time format as needed.
                echo !empty($start_time) ? $start_time : 'TBD';
                echo !empty($end_time) ? ' - ' . $end_time : '';
                break;
    }
}