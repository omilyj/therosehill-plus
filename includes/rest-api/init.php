<?php

function rhp_rest_api_init() {
    register_rest_route('rhp/v1', '/signup', [
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'rhp_rest_api_signup_handler',
        'permission_callback' => '__return_true'
    ]);
    register_rest_route('rhp/v1', '/signin', [
        'methods' => WP_REST_Server::EDITABLE,
        'callback' => 'rhp_rest_api_signin_handler',
        'permission_callback' => '__return_true'
    ]);
}