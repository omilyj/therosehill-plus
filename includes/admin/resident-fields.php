<?php

function rhp_add_residency_date_meta_boxes() {
    add_meta_box(
        'rhp_residency_dates',
        __('Residency Date', 'therosehill-plus'),
        'rhp_residency_dates_meta_box_callback',
        'resident',
        'side',
        'default'
    );
}

function rhp_residency_dates_meta_box_callback($post) {
    wp_nonce_field('rhp_save_post_resident', 'rhp_residency_dates_nonce');
    
    $startDate = get_post_meta($post->ID, 'residency_start_date', true);
    $endDate = get_post_meta($post->ID, 'residency_end_date', true);
    ?>

    <p>
        <label for="residency_start_date"><?php _e('Start Date', 'therosehill-plus'); ?>:</label>
        <input type="date" id="residency_start_date" name="residency_start_date" value="<?php echo esc_attr($startDate); ?>">
    </p>

    <p>
        <label for="residency_end_date"><?php _e('End Date', 'therosehill-plus'); ?>:</label>
        <input type="date" id="residency_end_date" name="residency_end_date" value="<?php echo esc_attr($endDate); ?>">
    </p>

    <?php
}