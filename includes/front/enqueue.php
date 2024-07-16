<?php

function rhp_enqueue_scripts() {
    $authURLs = json_encode([
        'signup' => esc_url_raw(rest_url('rhp/v1/signup')),
        'signin' => esc_url_raw(rest_url('rhp/v1/signin'))
    ]);

    wp_add_inline_script(
        'therosehill-plus-auth-modal-script',
        "const rhp_auth_rest = {$authURLs}",
        'before'
    );
}