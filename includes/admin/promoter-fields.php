<?php

function rhp_promoter_add_form_fields() {
    ?>
    <div class="form-field">
        <label><?php _e('Instagram', 'therosehill-plus'); ?></label>
        <input type="text" name="rhp_instagram_url" />
        <p><?php _e('A URL to the promoter\'s Instagram page.', 'therosehill-plus'); ?></p>
    </div>
    <div class="form-field">
        <label><?php _e('Facebook', 'therosehill-plus'); ?></label>
        <input type="text" name="rhp_facebook_url" />
        <p><?php _e('A URL to the promoter\'s Facebook page.', 'therosehill-plus'); ?></p>
    </div>
    <div class="form-field">
        <label><?php _e('YouTube', 'therosehill-plus'); ?></label>
        <input type="text" name="rhp_youtube_url" />
        <p><?php _e('A URL to the promoter\'s YouTube page.', 'therosehill-plus'); ?></p>
    </div>
    <div class="form-field">
        <label><?php _e('More Info URL', 'therosehill-plus'); ?></label>
        <input type="text" name="rhp_more_info_url" />
        <p><?php _e('A URL the user can click to learn more about this promoter.', 'therosehill-plus'); ?></p>
    </div>

    <?php
    // Add nonce field for validation
    wp_nonce_field('rhp_save_promoter_meta_action', 'rhp_meta_nonce');
    ?>
    <?php
}

function rhp_promoter_edit_form_fields($term) {
    
    $instagram_url = get_term_meta(
        $term->term_id, 'instagram_url', true
    );

    $facebook_url = get_term_meta(
        $term->term_id, 'facebook_url', true
    );

    $youtube_url = get_term_meta(
        $term->term_id, 'youtube_url', true
    );

    $more_info_url = get_term_meta(
        $term->term_id, 'more_info_url', true
    );

    wp_nonce_field('rhp_save_promoter_meta_action', 'rhp_meta_nonce');

    ?>
     <tr class="form-field">
        <th>
        <label><?php _e('Instagram', 'therosehill-plus'); ?></label>
        </th>
        <td>
            <input type="text" name="rhp_instagram_url" 
                value="<?php echo $instagram_url; ?>"/>
            <p class="description"><?php _e('A URL to the promoter\'s Instagram page.', 'therosehill-plus'); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th>
        <label><?php _e('Facebook', 'therosehill-plus'); ?></label>
        </th>
        <td>
            <input type="text" name="rhp_facebook_url" 
                value="<?php echo $facebook_url; ?>"/>
            <p class="description"><?php _e('A URL to the promoter\'s Facebook page.', 'therosehill-plus'); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th>
        <label><?php _e('YouTube', 'therosehill-plus'); ?></label>
        </th>
        <td>
            <input type="text" name="rhp_youtube_url" 
                value="<?php echo $youtube_url; ?>"/>
            <p class="description"><?php _e('A URL to the promoter\'s YouTube page.', 'therosehill-plus'); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th>
        <label><?php _e('More Info URL', 'therosehill-plus'); ?></label>
        </th>
        <td>
            <input type="text" name="rhp_more_info_url" 
                value="<?php echo $more_info_url; ?>"/>
            <p class="description"><?php _e('A URL the user can click to learn more about this promoter.', 'therosehill-plus'); ?></p>
        </td>
    </tr>

    <?php
}