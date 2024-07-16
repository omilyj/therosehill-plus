<?php

// Label Admin Page
function rhp_label_post_type_columns($columns) {
    unset($columns['categories']);
    unset($columns['tags']);

    $columns['cb'] = '<input type="checkbox" />';

    $reorderColumns = array(
        'cb' => $columns['cb'], // Add checkbox column at the beginning
        'title' => $columns['title'],
        'artist_name' => __('Artist', 'therosehill-plus'),
        'record_release_date' => __('Release Date', 'therosehill-plus'),
        'date' => $columns['date']
    );

    return $reorderColumns;
}

function rhp_label_custom_column_content($column, $postID) {
    switch ($column) {
        case 'cb':
            echo '<input type="checkbox" name="post[]" value="' . esc_attr($postID) . '" />';
            break;
        case 'artist_name':
            $terms = get_the_terms($postID, 'artist');
            if (!empty($terms) && !is_wp_error($terms)) {
                $artists = array();
                foreach ($terms as $term) {
                    $artist_name = esc_html($term->name);
                    $term_id = $term->term_id;
                    $edit_url = admin_url("term.php?taxonomy=artist&tag_ID=$term_id&post_type=label&wp_http_referer=" . urlencode($_SERVER['REQUEST_URI']));
                    $artists[] = '<a href="' . esc_url($edit_url) . '">' . $artist_name . '</a>';
                }
                echo implode(', ', $artists);
            } else {
                echo '-';
            }
            break;

        case 'record_release_date':
            $release_date = get_post_meta($postID, 'record_release_date', true);
            if (!empty($release_date)) {
                echo date('d/m/Y', strtotime($release_date));
            } else {
                echo 'Coming soon';
            }
            break;
    }
}