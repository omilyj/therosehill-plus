<?php

function rhp_save_promoter_meta($termID) {
    // Replace 'your_nonce_field_name' and 'your_action_name' with your actual values
    if (!isset($_POST['rhp_meta_nonce']) || !wp_verify_nonce($_POST['rhp_meta_nonce'], 'rhp_save_promoter_meta_action')) {
        return; // Exit the function if the nonce check fails
    }

    if (
        !isset($_POST['rhp_instagram_url']) ||
        !isset($_POST['rhp_facebook_url']) ||
        !isset($_POST['rhp_youtube_url']) ||
        !isset($_POST['rhp_more_info_url'])
    ) {
        return; // Exit the function if any required fields are missing
    }

    update_term_meta(
        $termID,
        'instagram_url',
        esc_url_raw($_POST['rhp_instagram_url'])
    );

    update_term_meta(
        $termID,
        'facebook_url',
        esc_url_raw($_POST['rhp_facebook_url'])
    );

    update_term_meta(
        $termID,
        'youtube_url',
        esc_url_raw($_POST['rhp_youtube_url'])
    );

    update_term_meta(
        $termID,
        'more_info_url',
        esc_url_raw($_POST['rhp_more_info_url'])
    );
}