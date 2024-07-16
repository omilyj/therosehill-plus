<?php

function rhp_artist_name_render_cb($atts, $content, $block) {
    // Ensure you have a post ID to work with
    if (empty($block->context['postId'])) {
        return ''; // Return early if no post ID is found
    }

    $postID = $block->context['postId'];
    $postTerms = get_the_terms($postID, 'artist');
    // Check if terms were retrieved successfully
    if (is_wp_error($postTerms) || empty($postTerms)) {
        return ''; // Return early if there was an error or no terms were found
    }

    $artists = '';
    $lastKey = array_key_last($postTerms);

    $textAlignment = esc_attr($atts['textAlignment']);
    $alignmentClass = $textAlignment ? 'has-text-align-' . $textAlignment : '';

    foreach ($postTerms as $key => $term) {
        // Get the link to the artist's taxonomy term page
        $artistLink = get_term_link($term);
        // Check for errors in getting the term link
        if (!is_wp_error($artistLink)) {
            // Wrap the artist name in a link if the term link is successfully retrieved
            $artistName = "<a href='{$artistLink}'>" . esc_html($term->name) . "</a>";
        } else {
            // Fallback to just displaying the name without a link in case of an error
            $artistName = esc_html($term->name);
        }

        $comma = $lastKey === $key ? "" : " + ";
        $artists .= $artistName . $comma;
    }

    ob_start();
    ?>

    <div class="wp-block-therosehill-plus-artist-name">
        <p class="<?php echo $alignmentClass; ?>"><span class="prefix"><?php esc_html_e('by:', 'therosehill-plus'); ?></span> <?php echo $artists; ?></p>
    </div>

    <?php

    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}
