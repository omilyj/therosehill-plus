<?php

function rhp_activate_plugin() {
    if(version_compare(get_bloginfo('version'), '6.0', '<')) {
        wp_die(
            __('You must update WordPress to use this plugin', 'therosehill-plus')
        );
    }

    rhp_resident_post_type();
    rhp_label_post_type();
    rhp_event_post_type();
    flush_rewrite_rules();
}