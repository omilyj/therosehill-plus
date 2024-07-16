<?php

function rhp_add_event_meta_boxes() {
    add_meta_box(
        'rhp_event_date_time',
        __('Event Date/Time', 'therosehill-plus'),
        'rhp_event_date_time_meta_box_callback',
        'event',
        'side',
        'default'
    );

    add_meta_box(
        'rhp_event_tickets',
        __('Event Tickets', 'therosehill-plus'),
        'rhp_event_tickets_meta_box_callback',
        'event',
        'side',
        'default'
    );

    add_meta_box(
        'rhp_event_location',
        __('Event Location', 'therosehill-plus'),
        'rhp_event_location_meta_box_callback',
        'event',
        'side',
        'default'
    );
}

function rhp_event_date_time_meta_box_callback($post) {
    wp_nonce_field('rhp_save_post_event', 'rhp_event_date_time_nonce');
    
    $startDate = get_post_meta($post->ID, 'event_start_date', true);
    $endDate = get_post_meta($post->ID, 'event_end_date', true);
    $startTime = get_post_meta($post->ID, 'event_start_time', true);
    $endTime = get_post_meta($post->ID, 'event_end_time', true);
    $recurrence = get_post_meta($post->ID, 'event_recurrence', true);
    $recurrenceInterval = get_post_meta($post->ID, 'event_recurrence_interval', true);
    $recurrenceAmount = get_post_meta($post->ID, 'event_recurrence_amount', true);
    $recurrenceDay = get_post_meta($post->ID, 'event_recurrence_day', true);
    $recurrenceTimeMonth = get_post_meta($post->ID, 'event_recurrence_time_month', true);
    
    ?>

    <p>
        <label for="event_start_date"><?php _e('Start Date', 'therosehill-plus'); ?>:</label>
        <input type="date" id="event_start_date" name="event_start_date" value="<?php echo esc_attr($startDate); ?>">
    </p>

    <p>
        <label for="event_end_date"><?php _e('End Date', 'therosehill-plus'); ?>:</label>
        <input type="date" id="event_end_date" name="event_end_date" value="<?php echo esc_attr($endDate); ?>">
    </p>

    <p>
        <label for="event_start_time"><?php _e('Start Time', 'therosehill-plus'); ?>:</label>
        <input type="time" id="event_start_time" name="event_start_time" value="<?php echo esc_attr($startTime); ?>">
    </p>

    <p>
        <label for="event_end_time"><?php _e('End Time', 'therosehill-plus'); ?>:</label>
        <input type="time" id="event_end_time" name="event_end_time" value="<?php echo esc_attr($endTime); ?>">
    </p>
    <p>
        <label for="event_recurrence"><?php _e('Recurring Event', 'therosehill-plus'); ?>:</label>
        <input type="checkbox" id="event_recurrence" name="event_recurrence" value="yes" <?php checked($recurrence, 'yes'); ?>>
    </p>
    <div id="recurrence_options" style="<?php echo ($recurrence === 'yes' ? '' : 'display:none;'); ?>">
        <p>
            <label for="event_recurrence_amount"><?php _e('Number of Recurrences', 'therosehill-plus'); ?>:</label>
            <input type="number" id="event_recurrence_amount" name="event_recurrence_amount" value="<?php echo esc_attr($recurrenceAmount); ?>" min="1">
        </p>
        <p>
            <label for="event_recurrence_interval"><?php _e('Recurrence Interval', 'therosehill-plus'); ?>:</label>
            <select id="event_recurrence_interval" name="event_recurrence_interval">
                <option value="weekly" <?php selected($recurrenceInterval, 'weekly'); ?>><?php _e('Weekly', 'therosehill-plus'); ?></option>
                <option value="monthly" <?php selected($recurrenceInterval, 'monthly'); ?>><?php _e('Monthly', 'therosehill-plus'); ?></option>
            </select>
        </p>
    </div>
    <!-- Weekly Recurrence Options -->
    <div id="weekly_options" style="<?php echo ($recurrenceInterval === 'weekly' ? '' : 'display:none;'); ?>">
        <p>
            <label for="event_recurrence_day"><?php _e('Day of the Week', 'therosehill-plus'); ?>:</label>
            <select id="event_recurrence_day" name="event_recurrence_day">
                <option value="Monday" <?php selected($recurrenceDay, 'Monday'); ?>><?php _e('Monday', 'therosehill-plus'); ?></option>
                <option value="Tuesday" <?php selected($recurrenceDay, 'Tuesday'); ?>><?php _e('Tuesday', 'therosehill-plus'); ?></option>
                <option value="Wednesday" <?php selected($recurrenceDay, 'Wednesday'); ?>><?php _e('Wednesday', 'therosehill-plus'); ?></option>
                <option value="Thursday" <?php selected($recurrenceDay, 'Thursday'); ?>><?php _e('Thursday', 'therosehill-plus'); ?></option>
                <option value="Friday" <?php selected($recurrenceDay, 'Friday'); ?>><?php _e('Friday', 'therosehill-plus'); ?></option>
                <option value="Saturday" <?php selected($recurrenceDay, 'Saturday'); ?>><?php _e('Saturday', 'therosehill-plus'); ?></option>
                <option value="Sunday" <?php selected($recurrenceDay, 'Sunday'); ?>><?php _e('Sunday', 'therosehill-plus'); ?></option>
            </select>
        </p>
    </div>
    <!-- Monthly Recurrence Options -->
    <div id="monthly_options" style="<?php echo ($recurrenceInterval === 'monthly' ? '' : 'display:none;'); ?>">
        <p>
            <label for="event_recurrence_time_month"><?php _e('Week of the Month', 'therosehill-plus'); ?>:</label>
            <select id="event_recurrence_time_month" name="event_recurrence_time_month">
                <option value="First" <?php selected($recurrenceTimeMonth, 'First'); ?>><?php _e('First', 'therosehill-plus'); ?></option>
                <option value="Second" <?php selected($recurrenceTimeMonth, 'Second'); ?>><?php _e('Second', 'therosehill-plus'); ?></option>
                <option value="Third" <?php selected($recurrenceTimeMonth, 'Third'); ?>><?php _e('Third', 'therosehill-plus'); ?></option>
                <option value="Fourth" <?php selected($recurrenceTimeMonth, 'Fourth'); ?>><?php _e('Fourth', 'therosehill-plus'); ?></option>
                <option value="Last" <?php selected($recurrenceTimeMonth, 'Last'); ?>><?php _e('Last', 'therosehill-plus'); ?></option>
            </select>
        </p>
        <p>
            <label for="event_recurrence_day"><?php _e('Day of the Week', 'therosehill-plus'); ?>:</label>
            <select name="event_recurrence_day">
                <option value="Monday" <?php selected($recurrenceDay, 'Monday'); ?>><?php _e('Monday', 'therosehill-plus'); ?></option>
                <option value="Tuesday" <?php selected($recurrenceDay, 'Tuesday'); ?>><?php _e('Tuesday', 'therosehill-plus'); ?></option>
                <option value="Wednesday" <?php selected($recurrenceDay, 'Wednesday'); ?>><?php _e('Wednesday', 'therosehill-plus'); ?></option>
                <option value="Thursday" <?php selected($recurrenceDay, 'Thursday'); ?>><?php _e('Thursday', 'therosehill-plus'); ?></option>
                <option value="Friday" <?php selected($recurrenceDay, 'Friday'); ?>><?php _e('Friday', 'therosehill-plus'); ?></option>
                <option value="Saturday" <?php selected($recurrenceDay, 'Saturday'); ?>><?php _e('Saturday', 'therosehill-plus'); ?></option>
                <option value="Sunday" <?php selected($recurrenceDay, 'Sunday'); ?>><?php _e('Sunday', 'therosehill-plus'); ?></option>
            </select>
        </p>
    </div>

    <?php
}

function rhp_event_tickets_meta_box_callback($post) {
    wp_nonce_field('rhp_save_post_event', 'rhp_event_tickets_nonce');
    
    $bookingType = get_post_meta($post->ID, 'event_booking_type', true);
    $isEventFreeInh = get_post_meta($post->ID, 'event_is_free_inhouse', true);
    $isEventFreeExt = get_post_meta($post->ID, 'event_is_free_external', true);
    $isEventFreeNb = get_post_meta($post->ID, 'event_is_free_no_booking', true);
    $ticketCapacity = get_post_meta($post->ID, 'event_ticket_capacity', true);
    $ticketFullPrice = get_post_meta($post->ID, 'event_ticket_full_price', true);
    $ticketConcPrice = get_post_meta($post->ID, 'event_ticket_conc_price', true);
    $advPrice = get_post_meta($post->ID, 'event_adv_price', true);
    $otdPriceInh = get_post_meta($post->ID, 'event_otd_price_inhouse', true);
    $otdPriceExt = get_post_meta($post->ID, 'event_otd_price_external', true);
    $otdPriceNb = get_post_meta($post->ID, 'event_otd_price_no_booking', true);
    $extTickets = get_post_meta($post->ID, 'event_ext_tickets', true);

    $displayInhouse = $bookingType === 'inhouse' ? '' : 'style="display:none;"';
    $displayExternal = $bookingType === 'external' ? '' : 'style="display:none;"';
    $displayNone = $bookingType === 'none' ? '' : 'style="display:none;"';
    ?>

    <p>
        <label for="event_booking_type"><?php _e('Booking Type:', 'therosehill-plus'); ?></label>
        <select id="event_booking_type" name="event_booking_type">
            <option value="inhouse" <?php selected($bookingType, 'inhouse'); ?>><?php _e('In-House Booking', 'therosehill-plus'); ?></option>
            <option value="external" <?php selected($bookingType, 'external'); ?>><?php _e('External Booking', 'therosehill-plus'); ?></option>
            <option value="none" <?php selected($bookingType, 'none'); ?>><?php _e('No Booking', 'therosehill-plus'); ?></option>
        </select>
    </p>

    <div id="inhouse_fields" <?php echo $displayInhouse; ?>>
        <p>
            <label for="event_ticket_capacity"><?php _e('Ticket Capacity', 'therosehill-plus'); ?>:</label>
            <input type="number" id="event_ticket_capacity" name="event_ticket_capacity" value="<?php echo esc_attr($ticketCapacity); ?>">
        </p>
        <p>
            <label for="event_is_free_inhouse"><?php _e('Is this event free?', 'therosehill-plus'); ?></label>
            <input type="checkbox" id="event_is_free_inhouse" name="event_is_free_inhouse" value="yes" <?php echo ($isEventFreeInh === 'yes' ? 'checked' : ''); ?>>
        </p>
        <div id="inhouse_price_fields">
            <p>
                <label for="event_ticket_full_price"><?php _e('Full Price: £', 'therosehill-plus'); ?></label>
                <input type="number" id="event_ticket_full_price" name="event_ticket_full_price" value="<?php echo esc_attr($ticketFullPrice); ?>">
            </p>
            <p>
                <label for="event_ticket_conc_price"><?php _e('Concessions Price: £', 'therosehill-plus'); ?></label>
                <input type="number" id="event_ticket_conc_price" name="event_ticket_conc_price" value="<?php echo esc_attr($ticketConcPrice); ?>">
            </p>
            <p>
                <label for="event_otd_price_inhouse"><?php _e('OTD Ticket Price', 'therosehill-plus'); ?>:</label>
                <br>
                <small><?php _e('Format as lowest to highest, e.g. £5-10', 'therosehill-plus'); ?></small>
                <input type="text" id="event_otd_price_inhouse" name="event_otd_price_inhouse" value="<?php echo esc_attr($otdPriceInh); ?>">
            </p>
        </div>
    </div>

    <div id="external_fields" <?php echo $displayExternal; ?>>
        <p>
            <label for="event_ext_tickets"><?php _e('Ticket Link', 'therosehill-plus'); ?>:</label>
            <input type="url" id="event_ext_tickets" name="event_ext_tickets" value="<?php echo esc_attr($extTickets); ?>">
        </p>
        <p>
            <label for="event_is_free_external"><?php _e('Is this event free?', 'therosehill-plus'); ?></label>
            <input type="checkbox" id="event_is_free_external" name="event_is_free_external" value="yes" <?php echo ($isEventFreeExt === 'yes' ? 'checked' : ''); ?>>
        </p>
        <div id="external_price_fields">
            <p>
                <label for="event_adv_price"><?php _e('Advance Ticket Price', 'therosehill-plus'); ?>:</label>
                <br>
                <small><?php _e('Format as lowest to highest, e.g. £5-10', 'therosehill-plus'); ?></small>
                <input type="text" id="event_adv_price" name="event_adv_price" value="<?php echo esc_attr($advPrice); ?>">
            </p>
            <p>
                <label for="event_otd_price_external"><?php _e('OTD Ticket Price', 'therosehill-plus'); ?>:</label>
                <br>
                <small><?php _e('Format as lowest to highest, e.g. £5-10', 'therosehill-plus'); ?></small>
                <input type="text" id="event_otd_price_external" name="event_otd_price_external" value="<?php echo esc_attr($otdPriceExt); ?>">
            </p>
        </div>
    </div>

    <div id="no_booking_fields" <?php echo $displayNone; ?>>
        <p>
            <label for="event_is_free_no_booking"><?php _e('Is this event free?', 'therosehill-plus'); ?></label>
            <input type="checkbox" id="event_is_free_no_booking" name="event_is_free_no_booking" value="yes" <?php echo ($isEventFreeNb === 'yes' ? 'checked' : ''); ?>>
        </p>
        <div id="no_booking_price_fields">
            <p>
                <label for="event_otd_price_no_booking"><?php _e('OTD Ticket Price', 'therosehill-plus'); ?>:</label>
                <input type="text" id="event_otd_price_no_booking" name="event_otd_price_no_booking" value="<?php echo esc_attr($otdPriceNb); ?>">
            </p>
        </div>
    </div>

    <script>
        jQuery(document).ready(function($) {
            function toggleRecurrenceOptions() {
                var isRecurring = $('#event_recurrence').is(':checked');
                var interval = $('#event_recurrence_interval').val();
                $('#recurrence_options').toggle(isRecurring);
                $('#weekly_options, #monthly_options').hide();

                if (isRecurring) {
                    if (interval === 'weekly') {
                        $('#weekly_options').show();
                    } else if (interval === 'monthly') {
                        $('#monthly_options').show();
                    }
                }
            }

            $('#event_recurrence').change(toggleRecurrenceOptions);
            $('#event_recurrence_interval').change(toggleRecurrenceOptions);
            toggleRecurrenceOptions();

            function toggleFields() {
                var selection = $('#event_booking_type').val();
                $('#inhouse_fields').hide();
                $('#external_fields').hide();
                $('#no_booking_fields').hide();
                
                switch (selection) {
                    case 'inhouse':
                        $('#inhouse_fields').show();
                        break;
                    case 'external':
                        $('#external_fields').show();
                        break;
                    case 'none':
                        $('#no_booking_fields').show();
                        break;
                }
                togglePriceFields();
            }

            function togglePriceFields() {

                switch ($('#event_booking_type').val()) {
                    case 'inhouse':
                        var isFreeInhouse = $('#event_is_free_inhouse').is(':checked');
                        $('#inhouse_price_fields').toggle(!isFreeInhouse);
                        break;
                    case 'external':
                        var isFreeExternal = $('#event_is_free_external').is(':checked');
                        $('#external_price_fields').toggle(!isFreeExternal);
                        break;
                    case 'none':
                        var isFreeNoBooking = $('#event_is_free_no_booking').is(':checked');
                        $('#no_booking_price_fields').toggle(!isFreeNoBooking);
                        break;
                }
            }

            $('#event_booking_type').change(toggleFields);
            $('#event_is_free_inhouse, #event_is_free_external, #event_is_free_no_booking').change(togglePriceFields);
            toggleFields();
            togglePriceFields();
        });
        </script>

    <?php
}

function rhp_event_location_meta_box_callback($post) {
    wp_nonce_field('rhp_save_post_event', 'rhp_event_location_nonce');
    
    $location = get_post_meta($post->ID, 'event_location', true) ?: 'The Rose Hill, Rose Hill Terrace, Brighton, BN1 4JL';
    ?>

    <p>
        <label for="event_location"><?php _e('Event Location', 'therosehill-plus'); ?>:</label>
        <input type="text" id="event_location" name="event_location" value="<?php echo esc_attr($location); ?>">
    </p>

    <?php
}