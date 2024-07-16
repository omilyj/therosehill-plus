<?php

function rhp_event_info_render_cb($atts, $content, $block) {
    $postID = $block->context['postId'];

    $startDate = get_post_meta($postID, 'event_start_date', true);
    $endDate = get_post_meta($postID, 'event_end_date', true);
    $startTime = get_post_meta($postID, 'event_start_time', true);
    $endTime = get_post_meta($postID, 'event_end_time', true);
    $bookingType = get_post_meta($postID, 'event_booking_type', true);
    $isEventFreeInh = get_post_meta($postID, 'event_is_free_inhouse', true);
    $isEventFreeExt = get_post_meta($postID, 'event_is_free_external', true);
    $isEventFreeNb = get_post_meta($postID, 'event_is_free_no_booking', true);
    $ticketFullPrice = get_post_meta($postID, 'event_ticket_full_price', true);
    $ticketConcPrice = get_post_meta($postID, 'event_ticket_conc_price', true);
    $advPrice = get_post_meta($postID, 'event_adv_price', true);
    $otdPriceInh = get_post_meta($postID, 'event_otd_price_inhouse', true);
    $otdPriceExt = get_post_meta($postID, 'event_otd_price_external', true);
    $otdPriceNb = get_post_meta($postID, 'event_otd_price_no_booking', true);
    $extTickets = get_post_meta($postID, 'event_ext_tickets', true);
    $location = get_post_meta($postID, 'event_location', true);

    if (class_exists('WooCommerce')) {
        $productID = get_post_meta($postID, 'event_ticket_product_id', true);
        if (!$productID) {
            error_log('No product ID found for post ID: ' . $postID);
        } else {
            $product = wc_get_product($productID);
            if (!$product) {
                error_log('Failed to retrieve product with ID: ' . $productID);
            } else {
                // Assuming the product is correctly set up to be a ticket
                $ticketLink = do_shortcode('[tickets products=' . $productID . ' amount="all" order="date" columns=3]');
            }
        }
    }

    $formattedStartDate = $startDate ? date('l jS F', strtotime($startDate)) : '';
    $formattedEndDate = $endDate ? date('l jS F', strtotime($endDate)) : '';
    $dateRange = $formattedStartDate;
    if (!empty($formattedEndDate)) {
        $dateRange .= ' - '  . $formattedEndDate;
    }

    $formatTime = function ($time) {
        $timestamp = strtotime($time);
        $minutes = date('i', $timestamp);
        return $minutes === "00" ? date_i18n('ga', $timestamp) : date_i18n('g:ia', $timestamp);
    };
    $formattedStartTime = $formatTime($startTime);
    $formattedEndTime = $formatTime($endTime);

    $ticketPrice = '';
    $discountInfo = '';
    $ticketLink = '';

    if ($bookingType === 'inhouse') {
        if ($isEventFreeInh === 'yes') {
            $ticketPrice .= esc_html__('FREE', 'therosehill-plus');
        } else {
            if (!empty($ticketFullPrice) && !empty($ticketConcPrice) && !empty($otdPriceInh)) {
                $ticketPrice .= esc_html__('ADV: £', 'therosehill-plus') . esc_html($ticketConcPrice) . esc_html__('-', 'therosehill-plus') . esc_html($ticketFullPrice) . ' / ' . esc_html__('OTD: ', 'therosehill-plus') . esc_html($otdPriceInh);
            } else {
                if (!empty($ticketFullPrice) && !empty($ticketConcPrice)) {
                    $ticketPrice .= esc_html__('ADV: £', 'therosehill-plus') . esc_html($ticketConcPrice) . esc_html__('-', 'therosehill-plus') . esc_html($ticketFullPrice);
                }
                if (!empty($otdPriceInh)) {
                    $ticketPrice .= (!empty($ticketFullPrice) && !empty($ticketConcPrice) ? ' / ' : '') . esc_html__('OTD: ', 'therosehill-plus') . esc_html($otdPriceInh);
                }
                $discountInfo = '<span class="member-offers"><i class="bi bi-heart-fill"></i>' . esc_html__('Discount for members', 'therosehill-plus') . '</span>';
            }
        }
    } elseif ($bookingType === 'external') {
        if ($isEventFreeExt === 'yes') {
            $ticketPrice .= esc_html__('FREE', 'therosehill-plus');
        } else {
            if (!empty($advPrice) && !empty($otdPriceExt)) {
                $ticketPrice .= esc_html__('ADV: ', 'therosehill-plus') . esc_html($advPrice) . ' / ' . esc_html__('OTD: ', 'therosehill-plus') . esc_html($otdPriceExt);
            } else {
                if (!empty($advPrice)) {
                    $ticketPrice .= esc_html__('ADV: ', 'therosehill-plus') . esc_html($advPrice);
                }
                if (!empty($otdPriceExt)) {
                    $ticketPrice .= (!empty($advPrice) ? ' / ' : '') . esc_html__('OTD: ', 'therosehill-plus') . esc_html($otdPriceExt);
                }
            }
        }
        if (!empty($extTickets)) {
            $ticketLink = '<a class="button" href="' . esc_url($extTickets) . '" target="_blank" rel="noopener noreferrer">' . esc_html__('Get Tickets', 'therosehill-plus') . '</a>';
        }        
    } elseif ($bookingType === 'none') {
        $ticketPrice = ($isEventFreeNb === 'yes') ? esc_html__('FREE', 'therosehill-plus') : (!empty($otdPriceNb) ? esc_html__('OTD: ', 'therosehill-plus') . esc_html($otdPriceNb) : '');
    }

    $promoters = '';
    $eventTypes = [];

    if (is_tax('event-type')) {
        $queried_object = get_queried_object();
        if ($queried_object) {
            $eventTypeTerms = [$queried_object];
        }
    } elseif (!empty($block->context['postId'])) {
        $postID = $block->context['postId'];
        $eventTypeTerms = get_the_terms($postID, 'event-type');
    } else {
        $eventTypeTerms = [];
    }
    if (!empty($eventTypeTerms) && !is_wp_error($eventTypeTerms)) {
        foreach($eventTypeTerms as $key => $term) {
            $eventTypes[] = $term->name;
        }
    }
    $typeInfo = join(', ', $eventTypes);

    if (is_tax('promoter')) {
        $queried_object = get_queried_object();
        if ($queried_object) {
            $promoterTerms = [$queried_object];
        }
    } elseif (!empty($block->context['postId'])) {
        $postID = $block->context['postId'];
        $promoterTerms = get_the_terms($postID, 'promoter');
    } else {
        $promoterTerms = [];
    }

    if (!empty($promoterTerms) && !is_wp_error($promoterTerms)) {
        foreach($promoterTerms as $key => $term) {
            $instagramURL = get_term_meta($term->term_id, 'instagram_url', true);
            $facebookURL = get_term_meta($term->term_id, 'facebook_url', true);
            $youtubeURL = get_term_meta($term->term_id, 'youtube_url', true);
            $musicURL = get_term_meta($term->term_id, 'music_url', true);
            $websiteURL = get_term_meta($term->term_id, 'more_info_url', true);

            $socialLinks = '';

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

            $promoterName = "<h3 class='wp-block-heading has-rh-anonymous-pro-font-family'>{$term->name}</h3>";
            $promoterBreak = "<span class='promoter-break'>//</span>";

            if ($socialLinks) {
                $promoterName .= $promoterBreak . $socialLinks;
            }

            $promoters .= $promoterName;
            }
    }

    ob_start();
    ?>

    <div class="wp-block-therosehill-plus-event-info">
        <div class="event-meta">
            <span class="event-type"><?php echo $typeInfo; ?></span>
            <span><i class="bi bi-calendar2-event"></i><?php echo $dateRange; ?></span>
            <span><i class="bi bi-clock"></i><?php echo $formattedStartTime; ?> - <?php echo $formattedEndTime; ?></span>
            <span><i class="bi bi-ticket-perforated"></i><?php echo $ticketPrice; ?></span>
            <?php echo $discountInfo; ?>
        </div>

        <?php echo $ticketLink; ?>

        <?php if (!empty($promoters)): ?>
            <h2 class="wp-block-heading has-rh-anonymous-pro-font-family has-medium-font-size">
                <?php esc_html_e('Promoter:', 'therosehill-plus'); ?>
            </h2>
            <div class="promoter-about">
                <?php echo $promoters; ?>
            </div>
        <?php endif; ?>

        <div class="location">
            <h2 class="wp-block-heading has-rh-anonymous-pro-font-family has-medium-font-size">
                <?php esc_html_e('Location:', 'therosehill-plus'); ?>
            </h2>
            <p>
                <?php echo $location; ?>
            </p>
        </div>
    </div>

    <?php

    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}