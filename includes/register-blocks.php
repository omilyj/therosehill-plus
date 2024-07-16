<?php

function rhp_register_blocks() {
    $blocks = [
        [ 'name' => 'fancy-header' ],
        [ 'name' => 'led-sign' ],
        [ 'name' => 'related-content' ],
        // 'options' and 'render_callback' renders block on server instead of client side.
        // Server-side rendering allows developers to pre-populate a web page with custom user data directly on the server.
        // Render in includes/blocks
        [ 'name' => 'search-form', 'options' => [
            'render_callback' => 'rhp_search_form_render_cb'
        ]],
        [ 'name' => 'menu-search', 'options' => [
            'render_callback' => 'rhp_menu_search_render_cb'
        ]],
        [ 'name' => 'page-header', 'options' => [
            'render_callback' => 'rhp_page_header_render_cb'
        ]],
        [ 'name' => 'header-tools', 'options' => [
            'render_callback' => 'rhp_header_tools_render_cb'
        ]],
        [ 'name' => 'auth-modal', 'options' => [
            'render_callback' => 'rhp_auth_modal_render_cb'
        ]],
        [ 'name' => 'record-summary', 'options' => [
            'render_callback' => 'rhp_record_summary_render_cb'
        ]],
        [ 'name' => 'artist-info', 'options' => [
            'render_callback' => 'rhp_artist_info_render_cb'
        ]],
        [ 'name' => 'artist-name', 'options' => [
            'render_callback' => 'rhp_artist_name_render_cb'
        ]],
        [ 'name' => 'artist-image', 'options' => [
            'render_callback' => 'rhp_artist_image_render_cb'
        ]],
        [ 'name' => 'all-record-releases', 'options' => [
            'render_callback' => 'rhp_all_record_releases_render_cb'
        ]],
        [ 'name' => 'other-record-releases', 'options' => [
            'render_callback' => 'rhp_other_record_releases_render_cb'
        ]],
        [ 'name' => 'residency-date', 'options' => [
            'render_callback' => 'rhp_residency_date_render_cb'
        ]],
        [ 'name' => 'record-release-date', 'options' => [
            'render_callback' => 'rhp_record_release_date_render_cb'
        ]],
        [ 'name' => 'record-links', 'options' => [
            'render_callback' => 'rhp_record_links_render_cb'
        ]],
        [ 'name' => 'all-residencies', 'options' => [
            'render_callback' => 'rhp_all_residencies_render_cb'
        ]],
        [ 'name' => 'event-info', 'options' => [
            'render_callback' => 'rhp_event_info_render_cb'
        ]],
        [ 'name' => 'events', 'options' => [
            'render_callback' => 'rhp_events_render_cb'
        ]]
    ];

    foreach($blocks as $block) {
        register_block_type(
            RHP_PLUGIN_DIR . 'build/blocks/' . $block['name'],
            isset($block['options']) ? $block['options'] : []
        );
    }
}