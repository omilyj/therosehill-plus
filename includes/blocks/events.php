<?php

function rhp_events_render_cb($atts) {
    $excerptLength = isset($atts['excerptLength']) ? intval($atts['excerptLength']) : 100;
    $eventView = isset($atts['eventView']) ? $atts['eventView'] : 'grid';
    $columns = isset($atts['columns']) ? intval($atts['columns']) : 3;
    $hideShowFilter = isset($atts['hideShowFilter']) ? $atts['hideShowFilter'] : true;
    $currentPost = get_queried_object();
    $currentPostId = is_singular('event') ? get_queried_object_id() : null;

    $dateRange = isset($_GET['date']) ? sanitize_text_field($_GET['date']) : '';
    $keyword = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';
    $promoterSearch = isset($_GET['promoter']) ? sanitize_title($_GET['promoter']) : '';
    $eventTypeSearch = isset($_GET['event-type']) ? sanitize_title($_GET['event-type']) : '';
    $tax_query = [];

    $promoterTerms = get_terms(['taxonomy' => 'promoter', 'hide_empty' => false]);
    $eventTypeTerms = get_terms(['taxonomy' => 'event-type', 'hide_empty' => false]);
    
    if (!empty($dateRange)) {
        $startDate = new DateTime();
        $endDate = new DateTime();

        switch ($dateRange) {
            case 'this-week':
                $startDate->modify('monday this week');
                $endDate->modify('sunday this week');
                break;
            case 'this-weekend':
                $startDate->modify('next friday');
                $endDate = clone $startDate;
                $endDate->modify('+2 days');
                break;
            case 'this-month':
                $startDate->modify('first day of this month');
                $endDate->modify('last day of this month');
                break;
            case 'next-week':
                $startDate->modify('next monday');
                $endDate = clone $startDate;
                $endDate->modify('+6 days');
                break;
            case 'next-weekend':
                $startDate->modify('next friday +1 week');
                $endDate = clone $startDate;
                $endDate->modify('+2 days');
                break;
            case 'next-month':
                $startDate->modify('first day of next month');
                $endDate->modify('last day of next month');
                break;
        }
        $startDate = $startDate->format('Y-m-d');
        $endDate = $endDate->format('Y-m-d');
    }

    $dateRanges = [
        'this-week' => 'This Week',
        'this-weekend' => 'This Weekend',
        'this-month' => 'This Month',
        'next-week' => 'Next Week',
        'next-weekend' => 'Next Weekend',
        'next-month' => 'Next Month',
    ];
    
    $dateRangeOptions = '';
    $promoterOptions = '';
    $eventTypeOptions = '';
    foreach ($dateRanges as $value => $label) {
        $isSelected = ($value == $dateRange) ? ' selected="selected"' : '';
        $dateRangeOptions .= "<option value='{$value}'{$isSelected}>{$label}</option>";
    }
    foreach ($promoterTerms as $term) {
        $isSelected = ($term->slug == $promoterSearch) ? ' selected="selected"' : '';
        $promoterOptions .= "<option value='{$term->slug}' $isSelected>{$term->name}</option>";
    }
    foreach ($eventTypeTerms as $term) {
        $isSelected = ($term->slug == $eventTypeSearch) ? ' selected="selected"' : '';
        $eventTypeOptions .= "<option value='{$term->slug}' $isSelected>{$term->name}</option>";
    }

    $promoterIDs = array_map(function($term) {
        return $term['id'];
    }, $atts['promoters']);

    $eventTypeIDs = array_map(function($term) {
        return $term['id'];
    }, $atts['eventTypes']);


    if (!empty($tax_query) && count($tax_query) > 1) {
        $tax_query['relation'] = 'AND';
    }

    $search_query = [
        'relation' => 'AND',
    ];
    if (!empty($promoterSearch)) {
        $search_query[] = [
            'taxonomy' => 'promoter',
            'field' => 'slug',
            'terms' => $promoterSearch,
        ];
    }
    if (!empty($eventTypeSearch)) {
        $search_query[] = [
            'taxonomy' => 'event-type',
            'field' => 'slug',
            'terms' => $eventTypeSearch,
        ];
    }
    if (count($search_query) > 1) {
        $tax_query[] = $search_query;
    }

    $related_query = [
        'relation' => 'OR',
    ];
    $relatedArtists = [];
    $relatedPromoters = [];
    if (is_singular('event')) {
        $relatedArtists = wp_get_post_terms($currentPost->ID, 'artist', ['fields' => 'ids']);
        if (empty($relatedArtists)) {
            $relatedPromoters = wp_get_post_terms($currentPost->ID, 'promoter', ['fields' => 'ids']);
        }
    } elseif (is_singular(['label', 'resident'])) {
        $relatedArtists = wp_get_post_terms($currentPost->ID, 'artist', ['fields' => 'ids']);
    } elseif (is_tax('artist')) {
        $relatedArtists = [$currentPost->term_id];
    }
    if (!empty($relatedArtists)) {
        $related_query[] = [
            'taxonomy' => 'artist',
            'field'    => 'term_id',
            'terms'    => $relatedArtists,
        ];
    } elseif (!empty($relatedPromoters)) {
        $related_query[] = [
            'taxonomy' => 'promoter',
            'field'    => 'term_id',
            'terms'    => $relatedPromoters,
        ];
    }
    if (count($related_query) > 1) {
        $tax_query[] = $related_query;
    }

    $meta_query = [
        [
            'key' => 'event_status',
            'value' => 'upcoming',
            'compare' => '=',
        ]
    ];
    if (!empty($dateRange)) {
        $meta_query[] = [
            'relation' => 'AND',
            [
                'key' => 'event_start_date',
                'value' => [$startDate, $endDate],
                'compare' => 'BETWEEN',
                'type' => 'DATE',
            ]
        ];
    }

    $args = [
        'post_type' => 'event',
        'posts_per_page' => $atts['count'],
        'orderby' => 'meta_value',
        'meta_key' => 'event_start_date',
        'order' => 'ASC',
        's' => $keyword,
        'tax_query' => $tax_query,
        'meta_query' => $meta_query,
    ];

    if ($currentPostId) {
        $args['post__not_in'] = [$currentPostId];
    }

    $query = new WP_Query($args);

    if ((is_singular(['event', 'label', 'resident']) || is_tax('artist')) && !$query->have_posts()) {
        // Return early without rendering the block if no events found
        return '';
    }


    ob_start();
    ?>
    <div class="wp-block-therosehill-plus-events">
        <?php if ($hideShowFilter): ?>
            <div class="event-filters">
                <div id="search-toggle"><?php esc_html_e('Filter Events', 'therosehill-plus'); ?><i class="bi bi-filter"></i></div>
                <form id="event-search-form" action="">
                    <div class="input-wrapper">
                        <div class="select-wrapper">
                            <select name="date">
                                <option value="">Date Range</option>
                                <?php echo $dateRangeOptions; ?>
                            </select>
                        </div>
                        <button type="button" class="clear-field"><i class="bi bi-x"></i></button>
                    </div>
                    <div class="input-wrapper">
                        <input type="text"
                        placeholder="<?php esc_html_e('Keyword', 'therosehill-plus'); ?>"
                        name="keyword" 
                        value="<?php echo esc_attr($keyword); ?>" />
                        <button type="button" class="clear-field"><i class="bi bi-x"></i></button>
                    </div>
                    <div class="input-wrapper">
                        <div class="select-wrapper">
                            <select name="promoter">
                                <option value="">Promoter</option>
                                <?php echo $promoterOptions; ?>
                            </select>
                        </div>
                        <button type="button" class="clear-field"><i class="bi bi-x"></i></button>
                    </div>
                    <div class="input-wrapper">
                        <div class="select-wrapper">
                            <select name="event-type">
                                <option value="">Event Type</option>
                                <?php echo $eventTypeOptions; ?>
                            </select>
                        </div>
                        <button type="button" class="clear-field"><i class="bi bi-x"></i></button>
                    </div>
                    <div class="button-wrapper">
                        <button type="submit">Search</button>
                        <button type="reset">Clear All</button>
                    </div>
                </form>
                <div class="event-view">
                    <span class="grid-view"><i class="bi bi-grid-3x3-gap"></i></span>
                    <span class="list-view"><i class="bi bi-list"></i></span>
                </div>
            </div>
        <?php endif; ?>
        <div class="event-listings <?php echo esc_attr($eventView) . '-view-active'; ?> <?php echo $eventView === 'grid' ? 'cols-' . esc_attr($columns) : ''; ?>">
        
            <?php if ($query->have_posts()): ?>

                <?php 
                if ($eventView === 'minimal') : ?>
                    <h3>
                        <?php
                        if (is_singular('event')) {
                            esc_html_e('Similar Events', 'therosehill-plus');
                        } elseif (is_singular(['label', 'resident']) || is_tax('artist')) {
                            esc_html_e('Events', 'therosehill-plus');
                        }
                        ?>
                    </h3>
                <?php endif; ?>
                
                <?php while($query->have_posts()) {
                    $query->the_post();
                    $promoterTerms = get_the_terms(get_the_ID(), 'promoter');
                    $eventTypeTerms = get_the_terms(get_the_ID(), 'event-type');

                    $promoterNames = [];
                    if ( !empty($promoterTerms) && !is_wp_error($promoterTerms) ) {
                        // Loop through each term and add its name to the array
                        foreach ($promoterTerms as $term) {
                            $promoterNames[] = $term->name;
                        }
                    }
                    $eventTypeNames = [];
                    if ( !empty($eventTypeTerms) && !is_wp_error($eventTypeTerms) ) {
                        // Loop through each term and add its name to the array
                        foreach ($eventTypeTerms as $term) {
                            $eventTypeNames[] = $term->name;
                        }
                    }
                    $formattedEventTypeNames = join(', ', $eventTypeNames);
                    $formattedPromoterNames = join(', ', $promoterNames);
                    if (count($promoterNames) > 1) {
                        $last_comma = strrpos($formattedPromoterNames, ', ');
                        $formattedPromoterNames = substr_replace($formattedPromoterNames, ' & ', $last_comma, 2);
                    }

                    $startDate = get_post_meta(get_the_ID(), 'event_start_date', true);
                    $endDate = get_post_meta(get_the_ID(), 'event_end_date', true);
                    $startTime = get_post_meta(get_the_ID(), 'event_start_time', true);
                    $endTime = get_post_meta(get_the_ID(), 'event_end_time', true);
                    $formatTime = function ($time) {
                        $timestamp = strtotime($time);
                        $minutes = date('i', $timestamp);
                        return $minutes === "00" ? date_i18n('ga', $timestamp) : date_i18n('g:ia', $timestamp);
                    };
                    $formattedStartTime = $formatTime($startTime);
                    $formattedEndTime = $formatTime($endTime);
                    $formattedStartDate = date_i18n('D jS M', strtotime($startDate));
                    $formattedEndDate = !empty($endDate) ? date_i18n('D jS M', strtotime($endDate)) : '';

                    $advTicketPrice = get_post_meta(get_the_ID(), 'event_adv_ticket_price', true);

                    $fullExcerpt = get_the_excerpt();
                    $trimmedExcerpt = mb_strimwidth($fullExcerpt, 0, $excerptLength + 1, '...');

                    $typeInfo = !empty($eventTypeNames) ? "<span class='event-type'>$formattedEventTypeNames</span>" : '';
                    $dateInfo = "<span class='event-date'><i class='bi bi-calendar2-event'></i>$formattedStartDate" . (!empty($formattedEndDate) ? " - $formattedEndDate" : "") . "</span>";
                    $timeInfo = "<span class='event-time'><i class='bi bi-clock'></i>$formattedStartTime - $formattedEndTime</span>";
                    $ticketInfo = "<span class='tickets'><i class='bi bi-ticket-perforated'></i>$advTicketPrice</span>";
                    $moreButton = "<a class='button' href='" . esc_url(get_the_permalink()) . "'>" . esc_html__('Find out more', 'therosehill-plus') . "</a>";

                    ?>

                    <?php if ($eventView === 'grid' || $eventView === 'list'): ?>
                        <div class="single-post">
                            <span class="single-post-image">
                                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
                                <?php echo $typeInfo; ?>
                            </span>
                            <div class="single-post-detail">
                                <div class="event-content">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php if (!empty($formattedPromoterNames)): ?>
                                            <span class="promoter">
                                                <?php echo esc_html($formattedPromoterNames); ?> <?php esc_html_e('presents', 'therosehill-plus'); ?>
                                            </span>
                                        <?php endif; ?>
                                        <h2>
                                            <?php the_title(); ?>
                                        </h2>
                                    </a>
                                    <p class="excerpt"><?php echo esc_html($trimmedExcerpt); ?></p>
                                </div>
                                <div class="event-extras">
                                    <?php echo $dateInfo; ?>
                                    <?php echo $timeInfo; ?>
                                    <?php echo $ticketInfo; ?>
                                    <?php echo $moreButton; ?>
                                </div>
                            </div>
                        </div>
                    <?php elseif ($eventView === 'minimal'): ?>
                        <div class="single-post">
                            <a href="<?php the_permalink(); ?>">
                                <div class="single-post-detail">
                                    <div class="event-content">
                                        <?php if (!empty($formattedPromoterNames)): ?>
                                            <span class="promoter">
                                                <?php echo esc_html($formattedPromoterNames); ?> <?php esc_html_e('presents', 'therosehill-plus'); ?>
                                            </span>
                                        <?php endif; ?>
                                        <h2>
                                            <?php the_title(); ?>
                                        </h2>
                                    </div>
                                    <div class="event-extras">
                                        <?php echo $dateInfo; ?>
                                        <?php echo $timeInfo; ?>
                                        <?php echo $ticketInfo; ?>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>

                <?php
                }
            ?>
            <?php else: ?>
                <?php
                if (!is_singular('event') && !is_singular('label') && !is_singular('resident') && !is_tax('artist')) {
                    $searchTerms = [];

                    if (!empty($dateRange) && isset($dateRanges[$dateRange])) {
                        $searchTerms[] = '<b>' . __('date range:', 'therosehill-plus') . '</b> "' . esc_html($dateRanges[$dateRange]) . '"';
                    }
                    
                    if (!empty($keyword)) {
                        $searchTerms[] = '<b>' . __('keyword:', 'therosehill-plus') . '</b> "' . esc_html($keyword) . '"';
                    }
                    
                    if (!empty($promoterSearch)) {
                        $promoterTerm = get_term_by('slug', $promoterSearch, 'promoter');
                        if ($promoterTerm && !is_wp_error($promoterTerm)) {
                            $searchTerms[] = '<b>' . __('promoter:', 'therosehill-plus') . '</b> "' . esc_html($promoterTerm->name) . '"';
                        }
                    }

                    if (!empty($eventTypeSearch)) {
                        $eventTypeTerm = get_term_by('slug', $eventTypeSearch, 'event-type');
                        if ($eventTypeTerm && !is_wp_error($eventTypeTerm)) {
                            $searchTerms[] = '<b>' . __('event type:', 'therosehill-plus') . '</b> "' . esc_html($eventTypeTerm->name) . '"';
                        }
                    }
                    
                    $noEventsMessage = __('No events found', 'therosehill-plus');
                    if (!empty($searchTerms)) {
                        $noEventsMessage .= ' ' . __('for', 'therosehill-plus') . ' ' . join(' ' . __('and', 'therosehill-plus') . ' ', $searchTerms);
                    }
                    $noEventsMessage .= '.';
                    
                    echo '<p>' . $noEventsMessage . '</p>';
                }
                ?>
            <?php endif; ?>
            
        </div>
    </div>
    <?php

    wp_reset_postdata();

    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}