<?php

function rhp_record_release_date_render_cb($atts, $content, $block) {
    $postID = $block->context['postId'];
    $blockWrapperAtts = get_block_wrapper_attributes();
    $releaseDate = get_post_meta($postID, 'record_release_date', true);

    $bgColor = esc_attr($atts['bgColor']);
    $textColor = esc_attr($atts['textColor']);
    $comingSoonBg = esc_attr($atts['comingSoonBg']);
    $comingSoonText = esc_attr($atts['comingSoonText']);
    $textAlignment = esc_attr($atts['textAlignment']);

    $alignmentClass = $textAlignment ? 'has-text-align-' . $textAlignment : '';
    
    $outNowStyle = "background-color:{$bgColor};color:{$textColor};";
    $comingSoonStyle = "background-color:{$comingSoonBg};color:{$comingSoonText};";

    ob_start();
    ?>

    <div class="wp-block-therosehill-plus-record-release-date" style="<?php echo !empty($releaseDate) ? $outNowStyle : $comingSoonStyle; ?>">
    <div <?php echo $blockWrapperAtts; ?>>
        <div class="record-meta-date <?php echo $alignmentClass; ?>">
            <?php echo !empty($releaseDate) ? date('d/m/Y', strtotime($releaseDate)) : esc_html__('Coming soon!', 'therosehill-plus'); ?>
        </div>
    </div>
    </div>

    <?php

    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}