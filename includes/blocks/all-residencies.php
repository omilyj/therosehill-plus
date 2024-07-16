<?php

function rhp_all_residencies_render_cb($atts, $content, $block) {
    // Check if we're on a post page or a taxonomy page.
    $isPostPage = isset($block->context['postId']);
    $postID = $isPostPage ? $block->context['postId'] : null;

    if ($isPostPage) {
        // If on a post page, fetch terms related to the post.
        $postTerms = get_the_terms($postID, 'artist');
    } else {
        // If on a taxonomy page, use the current term.
        // This requires the context where get_queried_object_id() will work for term archives.
        $term_id = get_queried_object_id();
        $postTerms = $term_id ? [get_term($term_id, 'artist')] : [];
    }

    $postTerms = is_array($postTerms) ? $postTerms : [];
    $residencies = '';
    $displayedResidencies = [];
    $allResidencies = [];

    foreach ($postTerms as $term) {
        if (!$term) continue; // Skip if the term is not valid

        // Query residencies related to the term.
        $residencyArgs = array(
            'post_type' => 'resident',
            'tax_query' => array(
                array(
                    'taxonomy' => 'artist',
                    'field'    => 'term_id',
                    'terms'    => $term->term_id,
                ),
            ),
            'posts_per_page' => -1 // Retrieve all residencies
        );
        $artistResidencies = new WP_Query($residencyArgs);

        if ($artistResidencies->have_posts()) {
            while ($artistResidencies->have_posts()) {
                $artistResidencies->the_post();
                $residencyID = get_the_ID();
                if (!in_array($residencyID, $displayedResidencies)) {
                    // Get residency start and end dates
                    $startDate = get_post_meta(get_the_ID(), 'residency_start_date', true);
                    $endDate = get_post_meta(get_the_ID(), 'residency_end_date', true);
                    
                    // Format dates
                    $formattedStartDate = $startDate ? date('d/m/Y', strtotime($startDate)) : '';
                    $formattedEndDate = $endDate ? date('d/m/Y', strtotime($endDate)) : '';

                    // Build the list item with start and end dates
                    $allResidencies[] = "<li><a href='" . get_permalink() . "'>" . get_the_title() . "<br><span class='residency-dates'>$formattedStartDate - $formattedEndDate</span></a></li>";
                    
                    $displayedResidencies[] = $residencyID;
                }
            }
        }
        wp_reset_postdata();
    }

    if (empty($allResidencies)) {
        // If no residencies exist, return early without rendering the block.
        return '';
    }

    // Construct the output for residencies that do exist.
    $residencies .= "<div class='resident-residencies'>";
    $residencies .= "<h3>" . __('Residencies at The Rose Hill', 'therosehill-plus') . "</h3>";
    $residencies .= "<ul>" . implode('', $allResidencies) . "</ul>";
    $residencies .= "</div>";

    ob_start();
    ?>
    <div class="wp-block-therosehill-plus-all-residencies">
        <?php echo $residencies; ?>
    </div>
    <?php

    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}