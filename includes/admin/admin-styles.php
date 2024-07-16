<?php

function rhp_custom_admin_styles() {
    $screen = get_current_screen();
    // Check if you're on the correct post type admin page
    if ( $screen->id == 'edit-event' ) { // Replace 'event' with your actual custom post type name
        echo '<style>
            .column-event_date,
            .column-event_time,
            .column-promoter_name,
            .column-event_type {
                width: 10%;
            }
        </style>';
    }
}