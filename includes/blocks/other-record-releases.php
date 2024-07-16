<?php

function rhp_other_record_releases_render_cb($atts, $content, $block) {
    $postID = $block->context['postId'];
    $postTerms = get_the_terms($postID, 'artist');
    $postTerms = is_array($postTerms) ? $postTerms : [];
    $artists = '';
    $otherReleases = '';
    $displayedRecords = [];
    $allReleases = [];
    $lastKey = array_key_last($postTerms);

    foreach($postTerms as $key => $term) {
        // Query other releases by the same artist
        $releaseArgs = array(
            'post_type' => 'label',
            'tax_query' => array(
                array(
                    'taxonomy' => 'artist',
                    'field'    => 'term_id',
                    'terms'    => $term->term_id,
                ),
            ),
            'post__not_in' => array( $postID ), // Exclude the current post
            'posts_per_page' => -1, // Retrieve all other releases
        );
        $artistReleases = new WP_Query( $releaseArgs );

        if ($artistReleases->have_posts()) {
            while ($artistReleases->have_posts()) {
                $artistReleases->the_post();
                $recordID = get_the_ID();
                // Check if the record ID has been displayed before
                if (!in_array($recordID, $displayedRecords)) {
                    // If not displayed before, add it to allReleases and add its ID to the array
                    $allReleases[] = "<li><a href='" . get_permalink() . "'>" . get_the_title() . "</a></li>";
                    $displayedRecords[] = $recordID;
                }
            }
        }
        // Restore original post data
        wp_reset_postdata();
    }

    // Construct labelReleases from allReleases
    if (!empty($allReleases)) {
        $otherReleases .= "<div class='label-releases'>";
        $otherReleases .= "<h3>" . __('Other Releases', 'therosehill-plus') . "</h3>";
        $otherReleases .= "<ul>";
        $otherReleases .= implode('', $allReleases);
        $otherReleases .= "</ul>";
        $otherReleases .= "</div>";
    }

    ob_start();
    ?>

    <div class="wp-block-therosehill-plus-other-record-releases">
        <?php echo $otherReleases; ?>
    </div>

    <?php

    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}