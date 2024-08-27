<?php

function rhp_save_post_event($postID) {
    // Check for autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Verify user permissions
    if (!current_user_can('edit_post', $postID)) {
        return;
    }

    // Verify the nonces
    $check_date_time_nonce = isset($_POST['rhp_event_date_time_nonce']) && wp_verify_nonce($_POST['rhp_event_date_time_nonce'], 'rhp_save_post_event');
    $check_tickets_nonce = isset($_POST['rhp_event_tickets_nonce']) && wp_verify_nonce($_POST['rhp_event_tickets_nonce'], 'rhp_save_post_event');
    $check_location_nonce = isset($_POST['rhp_event_location_nonce']) && wp_verify_nonce($_POST['rhp_event_location_nonce'], 'rhp_save_post_event');

    // If either nonce is invalid, return without saving
    if (!$check_date_time_nonce || !$check_tickets_nonce || !$check_location_nonce) {
        return;
    }

    // Sanitize and save fixed metadata
    $bookingType = sanitize_text_field($_POST['event_booking_type'] ?? '');
    add_post_meta($postID, 'event_start_date', sanitize_text_field($_POST['event_start_date'] ?? ''), true);
    add_post_meta($postID, 'event_end_date', sanitize_text_field($_POST['event_end_date'] ?? ''), true);
    update_post_meta($postID, 'event_start_time', sanitize_text_field($_POST['event_start_time'] ?? ''));
    update_post_meta($postID, 'event_end_time', sanitize_text_field($_POST['event_end_time'] ?? ''));
    $isDuplicated = get_post_meta($postID, 'event_duplicated', true);
    if (!$isDuplicated) {
        if (isset($_POST['event_recurrence']) && $_POST['event_recurrence'] === 'yes' && absint($_POST['event_recurrence_amount']) > 0) {
            update_post_meta($postID, 'event_recurrence_interval', sanitize_text_field($_POST['event_recurrence_interval']));
            update_post_meta($postID, 'event_recurrence_day', sanitize_text_field($_POST['event_recurrence_day']));
            update_post_meta($postID, 'event_recurrence_time_month', sanitize_text_field($_POST['event_recurrence_time_month']));
            $recurrenceAmount = absint($_POST['event_recurrence_amount']);
            update_post_meta($postID, 'event_recurrence_amount', $recurrenceAmount);

            define('DOING_EVENT_DUPLICATION', true); // Set the duplication flag
            rhp_duplicate_recurring_event($postID, $recurrenceAmount);
            update_post_meta($postID, 'event_duplicated', 'yes');
            define('DOING_EVENT_DUPLICATION', false); // Unset the duplication flag
        }
    } else {
        update_post_meta($postID, 'event_recurrence', 'no');
    }
    update_post_meta($postID, 'event_booking_type', $bookingType);

    // Clear all potentially conflicting meta data first
    $fields_to_clear = [
        'event_is_free_inhouse', 'event_ticket_capacity', 'event_ticket_full_price', 'event_ticket_conc_price', 'event_otd_price_inhouse',
        'event_is_free_external', 'event_adv_price', 'event_otd_price_external', 'event_ext_tickets',
        'event_is_free_no_booking', 'event_otd_price_no_booking'
    ];

    foreach ($fields_to_clear as $field) {
        delete_post_meta($postID, $field);
    }

    // Update meta based on booking type
    switch ($bookingType) {
        case 'inhouse':
            update_post_meta($postID, 'event_ticket_capacity', absint($_POST['event_ticket_capacity'] ?? 0));
            update_post_meta($postID, 'event_is_free_inhouse', $_POST['event_is_free_inhouse'] === 'yes' ? 'yes' : 'no');
            update_post_meta($postID, 'event_ticket_full_price', floatval($_POST['event_ticket_full_price'] ?? 0.0));
            update_post_meta($postID, 'event_ticket_conc_price', floatval($_POST['event_ticket_conc_price'] ?? 0.0));
            update_post_meta($postID, 'event_otd_price_inhouse', sanitize_text_field($_POST['event_otd_price_inhouse'] ?? ''));
            // Check if the product exists or create it
            $product_id = get_post_meta($postID, 'event_ticket_product_id', true);
            if ($product_id === '') {
                rhp_create_event_ticket_product($postID, $bookingType);
                $product_id = get_post_meta($postID, 'event_ticket_product_id', true); // Update after creation
            }
            if (!empty($product_id)) {
                rhp_update_event_ticket_product($postID, $product_id);
            }
            break;
        case 'external':
            update_post_meta($postID, 'event_is_free_external', $_POST['event_is_free_external'] === 'yes' ? 'yes' : 'no');
            update_post_meta($postID, 'event_adv_price', sanitize_text_field($_POST['event_adv_price'] ?? ''));
            update_post_meta($postID, 'event_otd_price_external', sanitize_text_field($_POST['event_otd_price_external'] ?? ''));
            update_post_meta($postID, 'event_ext_tickets', esc_url_raw($_POST['event_ext_tickets'] ?? ''));
            break;
        case 'none':
            update_post_meta($postID, 'event_is_free_no_booking', $_POST['event_is_free_no_booking'] === 'yes' ? 'yes' : 'no');
            update_post_meta($postID, 'event_otd_price_no_booking', sanitize_text_field($_POST['event_otd_price_no_booking'] ?? ''));
            break;
    }
    
    if (isset($_POST['event_location'])) {
        update_post_meta($postID, 'event_location', sanitize_text_field($_POST['event_location']));
    }
}

function rhp_duplicate_recurring_event($postID, $recurrenceAmount) {
    $startDate = get_post_meta($postID, 'event_start_date', true);
    $endDate = get_post_meta($postID, 'event_end_date', true);
    $recurrenceInterval = get_post_meta($postID, 'event_recurrence_interval', true);
    $recurrenceDay = get_post_meta($postID, 'event_recurrence_day', true);
    $recurrenceWeekOfMonth = get_post_meta($postID, 'event_recurrence_time_month', true);

    $currentDate = new DateTime($startDate);

    for ($i = 1; $i < $recurrenceAmount; $i++) {
        if ($recurrenceInterval === 'monthly') {
            if ($i > 0) {
                $currentDate->modify('first day of next month');
            }
            $weekAdjustment = strtolower($recurrenceWeekOfMonth); // Convert 'First', 'Second', etc. to lowercase
            $dateModificationString = $weekAdjustment . ' ' . $recurrenceDay . ' of this month';
            $currentDate->modify($dateModificationString);
        } else if ($recurrenceInterval === 'weekly') {
            if ($i > 0) {
                $currentDate->modify('next ' . $recurrenceDay);
            }
        }

        $newPost = [
            'post_type'    => 'event',
            'post_title'   => get_the_title($postID),
            'post_content' => get_post_field('post_content', $postID),
            'post_status'  => 'publish',
            'post_author'  => get_post_field('post_author', $postID),
            'meta_input' => ['event_duplicated' => 'yes'],
        ];

        $newPostID = wp_insert_post($newPost);
        if ($newPostID) {
            $meta_keys = get_post_custom_keys($postID);
            foreach ($meta_keys as $meta_key) {
                if (!in_array($meta_key, ['event_start_date', 'event_end_date', 'event_recurrence_amount'])) {
                    update_post_meta($newPostID, $meta_key, get_post_meta($postID, $meta_key, true));
                }
            }

            // Set the new dates
            $newStartDate = $currentDate->format('Y-m-d');
            update_post_meta($newPostID, 'event_start_date', $newStartDate);
            if ($endDate) {
                $currentEndDate = new DateTime($endDate);
                if ($recurrenceInterval === 'monthly') {
                    $currentEndDate->modify('first day of this month');
                    $currentEndDate->modify('+1 month');
                    $currentEndDate->modify($dateModificationString);
                } else if ($recurrenceInterval === 'weekly') {
                    $currentEndDate->modify('next ' . $recurrenceDay);
                }
                $newEndDate = $currentEndDate->format('Y-m-d');
                update_post_meta($newPostID, 'event_end_date', $newEndDate);
            }

            // Update the slug to make it unique
            wp_update_post([
                'ID'        => $newPostID,
                'post_name' => wp_unique_post_slug(get_post_field('post_name', $postID) . '-' . ($i + 1), $newPostID, 'publish', 'event', 0)
            ]);
        }
    }
}

function rhp_create_event_ticket_product($postID, $bookingType) {
    if (get_post_type($postID) !== 'event' || $bookingType !== 'inhouse') {
        return; // Ensure this function only runs for 'event' post type and 'inhouse' booking type
    }

    if (!class_exists('WooCommerce')) {
        return; // Ensure WooCommerce is active
    }

    $product_id = get_post_meta($postID, 'event_ticket_product_id', true);
    $is_free = get_post_meta($postID, 'event_is_free_inhouse', true) === 'yes';
    $full_price = floatval(get_post_meta($postID, 'event_ticket_full_price', true));
    $conc_price = floatval(get_post_meta($postID, 'event_ticket_conc_price', true));

    // Check if both full price and concessions are provided
    $need_variable = $full_price > 0 && $conc_price > 0;

    // Prepare the product object based on the type required
    $product = empty($product_id) ? ($need_variable ? new WC_Product_Variable() : new WC_Product_Simple()) : wc_get_product($product_id);
    if ((!$product || !$need_variable && !$product->is_type('simple')) || ($need_variable && !$product->is_type('variable'))) {
        $product = $need_variable ? new WC_Product_Variable() : new WC_Product_Simple();
    }

    $product->set_name('Ticket for ' . get_the_title($postID)); // Set product name
    $product->set_status('private'); // Set product as private
    $product->set_catalog_visibility('hidden'); // Make product not visible in catalog
    $product->set_virtual('yes'); // Mark product as virtual
    $product->set_manage_stock(true); // Enable stock management
    $product->set_stock_quantity($_POST['event_ticket_capacity'] ?? 0); // Set stock quantity
    $product->update_meta_data('_ticket', 'yes');

    if ($product instanceof WC_Product_Simple) {
        $product->set_regular_price($is_free ? 0 : $full_price); // Set price for simple product
    } else {
        // Setup attributes for a variable product
        $attribute = new WC_Product_Attribute();
        $attribute_taxonomy_id = wc_attribute_taxonomy_id_by_name('Ticket Type');
        $attribute->set_id($attribute_taxonomy_id);
        $attribute->set_name('pa_ticket-type');
        $attribute->set_options(['Full Price', 'Concessions']);
        $attribute->set_position(0);
        $attribute->set_visible(true);
        $attribute->set_variation(true);
        $product->set_attributes([$attribute]);
    }

    $product_id = $product->save(); // Save the product and get the ID

    // Assign product to a category if necessary
    $tickets_category = get_term_by('slug', 'tickets', 'product_cat');
    if ($tickets_category) {
        // This will overwrite any existing terms assigned to the product
        wp_set_object_terms($product_id, [$tickets_category->term_id], 'product_cat');
    }

    update_post_meta($postID, 'event_ticket_product_id', $product_id); // Store/Update product ID in post meta

    if ($product instanceof WC_Product_Variable) {
        create_product_variations($product, $postID); // Create or update variations if variable product
    } else {
        $product = wc_get_product($product_id);
        if ($product instanceof WC_Product_Variable && $product) {
            // Update existing variations
            update_product_variations($product, $postID);
        } else {
            error_log('Failed to retrieve product or product is not a variable product with ID ' . $product_id);
        }
    }
}

function rhp_update_event_ticket_product($postID, $product_id) {
    $product = wc_get_product($product_id);

    if ($product instanceof WC_Product_Simple) {
        // Update simple product price and stock
        $is_free = get_post_meta($postID, 'event_is_free_inhouse', true) === 'yes';
        $full_price = floatval(get_post_meta($postID, 'event_ticket_full_price', true));
        $product->set_regular_price($is_free ? 0 : $full_price);
        $product->set_stock_quantity($_POST['event_ticket_capacity'] ?? 0); // Update stock quantity
        $product->save();
    } elseif ($product instanceof WC_Product_Variable) {
        // Update existing variations
        update_product_variations($product, $postID);
    } else {
        error_log('Failed to retrieve product or product is not valid with ID ' . $product_id);
    }
}

function create_product_variations($product, $postID) {
    $variations_data = [
        'Full Price' => $_POST['event_ticket_full_price'] ?? 0,
        'Concessions' => $_POST['event_ticket_conc_price'] ?? 0,
    ];

    foreach ($variations_data as $key => $value) {
        $variation = new WC_Product_Variation();
        $variation->set_parent_id($product->get_id());
        $variation->set_regular_price($value);
        $variation->set_virtual('yes');
        $variation->set_attributes(['pa_ticket-type' => $key]);
        $variation->save();
    }
}

function update_product_variations($product, $postID) {
    $variations = $product->get_children();
    foreach ($variations as $variation_id) {
        $variation = new WC_Product_Variation($variation_id);
        $attributes = $variation->get_attributes();

        $price = ($attributes['pa_ticket-type'] == 'Full Price') ? $_POST['event_ticket_full_price'] ?? 0 : $_POST['event_ticket_conc_price'] ?? 0;
        $variation->set_regular_price($price);
        $variation->save();
    }
}