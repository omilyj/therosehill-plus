<?php

function rhp_artist_image_render_cb($atts, $content, $block) {

    if (is_tax() || is_category() || is_tag()) {
        $queried_object = get_queried_object();
        $term_id = $queried_object->term_id;
    } else {
        return '';
    }

    $artist_image_id = get_term_meta($term_id, 'artist_image', true);
    if (!$artist_image_id) {
        return '<p>' . esc_html__('No artist image found.', 'therosehill-plus') . '</p>';
    }

    $artist_image_url = wp_get_attachment_url($artist_image_id);
    // Retrieve the alt text for the artist image
    $artist_image_alt = get_post_meta($artist_image_id, '_wp_attachment_image_alt', true);
    // Ensure there's a default alt text if none is provided
    $artist_image_alt_text = !empty($artist_image_alt) ? $artist_image_alt : 'Artist Image';
    
    // Retrieve the photo credit and source URL from the attachment's post meta
    $photo_credit = get_post_meta($artist_image_id, 'rhp_photo_credit', true);
    $photo_source_url = get_post_meta($artist_image_id, 'rhp_photo_source_url', true);

    ob_start();
    ?>

    <div class="wp-block-therosehill-plus-artist-image">
        <img src="<?php echo esc_url($artist_image_url); ?>" alt="<?php echo esc_attr($artist_image_alt_text); ?>" />
        <?php if (!empty($photo_credit)): ?>
            <div class="photo-credit">
                <?php if (!empty($photo_source_url)): ?>
                    <span class="prefix"><?php esc_html_e('Credit: ', 'therosehill-plus'); ?></span><a href="<?php echo esc_url($photo_source_url); ?>" target="_blank"><?php echo esc_html($photo_credit); ?></a>
                <?php else: ?>
                    <span class="prefix"><?php esc_html_e('Credit: ', 'therosehill-plus'); ?></span><?php echo esc_html($photo_credit); ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php

    $output = ob_get_clean();

    return $output;
}