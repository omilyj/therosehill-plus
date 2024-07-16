<?php

function rhp_add_record_meta_boxes() {
    add_meta_box(
        'rhp_record_information',
        __('Record Info', 'therosehill-plus'),
        'rhp_record_meta_box_callback',
        'label',
        'side',
        'default'
    );
}

function rhp_record_meta_box_callback($post) {
    wp_nonce_field('rhp_save_post_label', 'rhp_record_date_nonce');
    
    $releaseDate = get_post_meta($post->ID, 'record_release_date', true);
    $listenLink = get_post_meta($post->ID, 'record_listen_link', true);
    $buyLink = get_post_meta($post->ID, 'record_buy_link', true);

    ?>

    <p>
        <label for="record_release_date"><?php _e('Release Date', 'therosehill-plus'); ?>:</label>
        <input type="date" id="record_release_date" name="record_release_date" value="<?php echo esc_attr($releaseDate); ?>">
    </p>
    <p>
        <label for="record_listen_link"><?php _e('Listen Link:', 'therosehill-plus'); ?></label>
        <input type="text" id="record_listen_link" name="record_listen_link" value="<?php echo esc_attr($listenLink); ?>">
    </p>
    <p>
        <label for="record_buy_link"><?php _e('Buy Link:', 'therosehill-plus'); ?></label>
        <input type="text" id="record_buy_link" name="record_buy_link" value="<?php echo esc_attr($buyLink); ?>">
    </p>

    <?php
}