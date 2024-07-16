<?php

function rhp_all_record_releases_render_cb($atts, $content, $block) {
    // Determine if on a post page or taxonomy page
    $isPostPage = isset($block->context['postId']);
    $postID = $isPostPage ? $block->context['postId'] : null;
    $artists = '';
    $labelReleases = '';
    $displayedRecords = [];
    $allReleases = [];

    if ($isPostPage) {
        // Fetch terms related to the post
        $postTerms = get_the_terms($postID, 'artist');
    } else {
        // Assume we're on a taxonomy page. Fetch the current term if available.
        // This requires being in a context where get_queried_object_id() will return the current term ID.
        $term_id = get_queried_object_id();
        $postTerms = $term_id ? [get_term($term_id, 'artist')] : [];
    }

    $postTerms = is_array($postTerms) ? $postTerms : [];

    foreach($postTerms as $term) {
        if (!$term) continue; // Skip if the term is not valid

        // Query releases by the same artist or related to the term
        $recordArgs = array(
            'post_type' => 'label',
            'tax_query' => array(
                array(
                    'taxonomy' => 'artist',
                    'field'    => 'term_id',
                    'terms'    => $term->term_id,
                ),
            ),
            'posts_per_page' => -1 // Retrieve all releases
        );
        $artistReleases = new WP_Query($recordArgs);

        if ($artistReleases->have_posts()) {
            while ($artistReleases->have_posts()) {
                $artistReleases->the_post();
                $recordID = get_the_ID();
                if (!in_array($recordID, $displayedRecords)) {
                    $allReleases[] = "<li><a href='" . get_permalink() . "'>" . get_the_title() . "</a></li>";
                    $displayedRecords[] = $recordID;
                }
            }
        }
        wp_reset_postdata();
    }

    if (empty($allReleases)) {
        // If no records exist, return early without rendering the block
        return '';
    }

    // Construct the output for records that do exist
    $labelReleases .= "<div class='label-releases'>";
    $labelReleases .= "<h3>" . __('Rose Hill Records Releases', 'therosehill-plus') . "</h3>";
    $labelReleases .= "<ul>";
    $labelReleases .= implode('', $allReleases);
    $labelReleases .= "</ul>";
    $labelReleases .= "</div>";

    ob_start();
    ?>
    <div class="wp-block-therosehill-plus-all-record-releases">
        <?php echo $labelReleases; ?>
    </div>
    <?php

    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}
