<?php

function rhp_record_links_render_cb($atts, $content, $block) {
    $bgColor = esc_attr($atts['bgColor']);
    $textColor = esc_attr($atts['textColor']);
    $styleAttr = "background-color:{$bgColor};color:{$textColor};";
    $postID = $block->context['postId'];

    $listenLink = get_post_meta($postID, 'record_listen_link', true);
    $buyLink = get_post_meta($postID, 'record_buy_link', true);

    $recordLinks = '';

    if ($listenLink) {
        $recordLinks .= '<a href="' . esc_url($listenLink) . '" target="_blank" rel="noopener noreferrer" class="button" style="' . $styleAttr . '"><i class="bi bi-ear-fill record-button-icon"></i>' . __('Listen', 'therosehill-plus') . '</a>';
    }
    if ($listenLink && $buyLink) {
        $recordLinks .= '<span class="button-space"></span>';
    }
    if ($buyLink) {
        $recordLinks .= '<a href="' . esc_url($buyLink) . '" target="_blank" rel="noopener noreferrer" class="button" style="' . $styleAttr . '"><i class="bi bi-bag-heart-fill record-button-icon"></i>' . __('Buy', 'therosehill-plus') . '</a>';
    }
    if (empty($listenLink) && empty($buyLink)) {
        $recordLinks = '<div class="links-not-available">Not available. Check back later.</div>';
    }

    ob_start();
    ?>

        <div class="wp-block-therosehill-plus-record-links">
            <div class="record-meta-links">
                <?php echo $recordLinks; ?>
            </div>
        </div>

    <?php

    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}