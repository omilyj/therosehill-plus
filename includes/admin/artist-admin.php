<?php

// Artists Page
function rhp_artist_taxonomy_columns($columns) {
    unset($columns['slug']);
    unset($columns['count']);

    $reorderColumns = array(
        'artist_image' => __('Image', 'therosehill-plus'),
        'name' => $columns['name'],
        'description' => $columns['description'],
    );

    return $reorderColumns;
}