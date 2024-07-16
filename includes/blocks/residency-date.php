<?php

function rhp_residency_date_render_cb($atts, $content, $block) {
    $postID = $block->context['postId'];

    $textAlignment = esc_attr($atts['textAlignment']);
    $alignmentClass = $textAlignment ? 'has-text-align-' . $textAlignment : '';

    // Retrieve residency start and end dates based on the post ID
    $startDate = get_post_meta($postID, 'residency_start_date', true);
    $endDate = get_post_meta($postID, 'residency_end_date', true);

    // Format the dates as "dd/mm/yyyy"
    $formattedStartDate = $startDate ? date('d/m/Y', strtotime($startDate)) : '';
    $formattedEndDate = $endDate ? date('d/m/Y', strtotime($endDate)) : '';

    ob_start();
    ?>

        <div class="wp-block-therosehill-plus-residency-date">
                <div class="residency-meta-date <?php echo $alignmentClass; ?>">
                        <?php echo $formattedStartDate; ?> - <?php echo $formattedEndDate; ?>
                </div>
        </div>

    <?php

    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}