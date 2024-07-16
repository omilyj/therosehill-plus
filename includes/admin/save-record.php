<?php

function rhp_save_post_label($postID) {
    if (!isset($_POST['rhp_record_date_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['rhp_record_date_nonce'], 'rhp_save_post_label')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $postID)) {
        return;
    }

    $releaseDate = isset($_POST['record_release_date']) ? $_POST['record_release_date'] : '';
    update_post_meta($postID, 'record_release_date', $releaseDate);

    // Save record_listen_link
    $listenLink = isset($_POST['record_listen_link']) ? $_POST['record_listen_link'] : '';
    update_post_meta($postID, 'record_listen_link', $listenLink);

    // Save record_buy_link
    $buyLink = isset($_POST['record_buy_link']) ? $_POST['record_buy_link'] : '';
    update_post_meta($postID, 'record_buy_link', $buyLink);
}
