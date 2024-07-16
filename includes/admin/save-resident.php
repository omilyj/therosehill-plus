<?php

function rhp_save_post_resident($postID) {
    if (!isset($_POST['rhp_residency_dates_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['rhp_residency_dates_nonce'], 'rhp_save_post_resident')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $postID)) {
        return;
    }

    $startDate = isset($_POST['residency_start_date']) ? $_POST['residency_start_date'] : '';
    $endDate = isset($_POST['residency_end_date']) ? $_POST['residency_end_date'] : '';

    update_post_meta($postID, 'residency_start_date', $startDate);
    update_post_meta($postID, 'residency_end_date', $endDate);
}