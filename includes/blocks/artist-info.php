<?php

function rhp_artist_info_render_cb($atts, $content, $block) {
    $artists = '';

    if (is_tax('artist')) {
        // We're on a taxonomy term page for the 'artist' taxonomy.
        $queried_object = get_queried_object();
        if ($queried_object) {
            // Assuming $queried_object is a term object here.
            $postTerms = [$queried_object]; // Wrapping it in an array to keep the foreach structure below.
        }
    } elseif (!empty($block->context['postId'])) {
        // We're on a post page, fetch terms associated with the post.
        $postID = $block->context['postId'];
        $postTerms = get_the_terms($postID, 'artist');
    } else {
        // If it's neither, you might want to set a default or do nothing.
        $postTerms = [];
    }

    if (!empty($postTerms) && !is_wp_error($postTerms)) {
        foreach($postTerms as $key => $term) {
            $instagramURL = get_term_meta($term->term_id, 'instagram_url', true);
            $facebookURL = get_term_meta($term->term_id, 'facebook_url', true);
            $youtubeURL = get_term_meta($term->term_id, 'youtube_url', true);
            $musicURL = get_term_meta($term->term_id, 'music_url', true);
            $websiteURL = get_term_meta($term->term_id, 'more_info_url', true);

            $socialLinks = '';
            // Check if any social links are provided and construct the HTML accordingly
            if ($instagramURL) {
                $socialLinks .= "<a href='{$instagramURL}' target='_blank'><i class='bi bi-instagram' title='" . __('Instagram', 'therosehill-plus') . "'></i></a>";
            }
            if ($facebookURL) {
                $socialLinks .= "<a href='{$facebookURL}' target='_blank'><i class='bi bi-facebook' title='" . __('Facebook', 'therosehill-plus') . "'></i></a>";
            }
            if ($youtubeURL) {
                $socialLinks .= "<a href='{$youtubeURL}' target='_blank'><i class='bi bi-youtube' title='" . __('YouTube', 'therosehill-plus') . "'></i></a>";
            }
            if ($musicURL) {
                $socialLinks .= "<a href='{$musicURL}' target='_blank'><i class='bi bi-music-note-beamed' title='" . __('Music', 'therosehill-plus') . "'></i></a>";
            }
            if ($websiteURL) {
                $socialLinks .= "<a href='{$websiteURL}' target='_blank'><i class='bi bi-info-circle-fill' title='" . __('Website', 'therosehill-plus') . "'></i></a>";
            }
            // Construct artist name, conditional on being on a post page
            $artistName = '';
            if (!is_tax('artist')) {
                // Not on an artist taxonomy page, so link the artist's name
                $artistLink = get_term_link($term);
                if (!is_wp_error($artistLink)) {
                    $artistName = "<h2><a href='{$artistLink}'>{$term->name}</a></h2>";
                } else {
                    // Fallback if term link is an error
                    $artistName = "<h2>{$term->name}</h2>";
                }
            } else {
                // On an artist taxonomy page, just display the name without a link
                $artistName = "<h2>{$term->name}</h2>";
            }

            // Construct the artist info HTML
            $artistInfo = "<div class='artist-about'>{$artistName}<p>{$term->description}</p>";
            
            // If social links are provided, add them to the artist info
            if ($socialLinks) {
                $artistInfo .= $socialLinks;
            }
            // Close the artist info div
            $artistInfo .= "</div>";
            // Add artist info to the $artists variable
            $artists .= $artistInfo;
        }
    }

    ob_start();
    ?>

    <div class="wp-block-therosehill-plus-artist-info">
        <?php echo $artists; ?>
    </div>

    <?php

    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}