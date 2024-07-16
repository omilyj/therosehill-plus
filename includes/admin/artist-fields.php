<?php

function rhp_artist_add_form_fields() {
    ?>
    <div class="form-field">
        <label><?php _e('Instagram', 'therosehill-plus'); ?></label>
        <input type="text" name="rhp_instagram_url" />
        <p><?php _e('A URL to the artist\'s Instagram page.', 'therosehill-plus'); ?></p>
    </div>
    <div class="form-field">
        <label><?php _e('Facebook', 'therosehill-plus'); ?></label>
        <input type="text" name="rhp_facebook_url" />
        <p><?php _e('A URL to the artist\'s Facebook page.', 'therosehill-plus'); ?></p>
    </div>
    <div class="form-field">
        <label><?php _e('YouTube', 'therosehill-plus'); ?></label>
        <input type="text" name="rhp_youtube_url" />
        <p><?php _e('A URL to the artist\'s YouTube page.', 'therosehill-plus'); ?></p>
    </div>
    <div class="form-field">
        <label><?php _e('Music', 'therosehill-plus'); ?></label>
        <input type="text" name="rhp_music_url" />
        <p><?php _e('A URL to where the user can listen to the artist\'s music.', 'therosehill-plus'); ?></p>
    </div>
    <div class="form-field">
        <label><?php _e('More Info URL', 'therosehill-plus'); ?></label>
        <input type="text" name="rhp_more_info_url" />
        <p><?php _e('A URL the user can click to learn more about this artist.', 'therosehill-plus'); ?></p>
    </div>
    <div class="form-field">
        <label><?php _e('Artist Image', 'therosehill-plus'); ?></label>
        <input type="button" id="upload_artist_image_button" class="button" value="<?php _e('Select Image', 'therosehill-plus'); ?>" />
        <input type="hidden" name="rhp_artist_image" id="rhp_artist_image_field" value="<?php echo esc_attr( $artist_image ); ?>" />
        <div id="artist_image_preview"></div>
        <p class="description"><?php _e('An image of the artist.', 'therosehill-plus'); ?></p>
    </div>

    <?php
    // Add nonce field for validation
    wp_nonce_field('rhp_save_artist_meta_action', 'rhp_meta_nonce');
    ?>

    <script>
        jQuery(document).ready(function($) {
            function initializeMediaUploader(buttonId, inputFieldId, previewDivId) {
                $(buttonId).click(function(e) {
                    e.preventDefault();
                    var customUploader = wp.media({
                        title: '<?php _e("Choose Artist Image", "therosehill-plus"); ?>',
                        button: {
                            text: '<?php _e("Select Image", "therosehill-plus"); ?>',
                        },
                        multiple: false
                    }).on('select', function() {
                        var selectImage = customUploader.state().get('selection').first().toJSON();
                        $(inputFieldId).val(selectImage.id);
                        $(previewDivId).html('<img src="' + selectImage.url + '" style="max-width: 200px;" />');
                    }).open();
                });
            }

            // Initialize media uploader for the add form
            initializeMediaUploader('#upload_artist_image_button', '#rhp_artist_image_field', '#artist_image_preview');

        });
    </script>
    <?php
}

function rhp_artist_edit_form_fields($term) {
    
    $instagram_url = get_term_meta(
        $term->term_id, 'instagram_url', true
    );

    $facebook_url = get_term_meta(
        $term->term_id, 'facebook_url', true
    );

    $youtube_url = get_term_meta(
        $term->term_id, 'youtube_url', true
    );

    $music_url = get_term_meta(
        $term->term_id, 'music_url', true
    );

    $more_info_url = get_term_meta(
        $term->term_id, 'more_info_url', true
    );

    $artist_image = get_term_meta(
        $term->term_id, 'artist_image', true
    );
    if (is_wp_error($artist_image) || empty($artist_image)) {
        // Handle error or absence of meta data appropriately
        $artist_image_url = ''; // Set a default or handle the error
    } else {
        $artist_image_url = wp_get_attachment_url($artist_image);
    }

    wp_nonce_field('rhp_save_artist_meta_action', 'rhp_meta_nonce');

    ?>
     <tr class="form-field">
        <th>
        <label><?php _e('Instagram', 'therosehill-plus'); ?></label>
        </th>
        <td>
            <input type="text" name="rhp_instagram_url" 
                value="<?php echo $instagram_url; ?>"/>
            <p class="description"><?php _e('A URL to the artist\'s Instagram page.', 'therosehill-plus'); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th>
        <label><?php _e('Facebook', 'therosehill-plus'); ?></label>
        </th>
        <td>
            <input type="text" name="rhp_facebook_url" 
                value="<?php echo $facebook_url; ?>"/>
            <p class="description"><?php _e('A URL to the artist\'s Facebook page.', 'therosehill-plus'); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th>
        <label><?php _e('YouTube', 'therosehill-plus'); ?></label>
        </th>
        <td>
            <input type="text" name="rhp_youtube_url" 
                value="<?php echo $youtube_url; ?>"/>
            <p class="description"><?php _e('A URL to the artist\'s YouTube page.', 'therosehill-plus'); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th>
        <label><?php _e('Music', 'therosehill-plus'); ?></label>
        </th>
        <td>
            <input type="text" name="rhp_music_url" 
                value="<?php echo $music_url; ?>"/>
            <p class="description"><?php _e('A URL to where the user can listen to the artist\'s music.', 'therosehill-plus'); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th>
        <label><?php _e('More Info URL', 'therosehill-plus'); ?></label>
        </th>
        <td>
            <input type="text" name="rhp_more_info_url" 
                value="<?php echo $more_info_url; ?>"/>
            <p class="description"><?php _e('A URL the user can click to learn more about this artist.', 'therosehill-plus'); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row">
            <label for="rhp_artist_image"><?php _e('Artist Image', 'therosehill-plus'); ?></label>
        </th>
        <td>
            <?php if ($artist_image_url): ?>
                <img src="<?php echo $artist_image_url; ?>" style="max-width: 200px; margin-bottom: 10px;" /><br />
            <?php endif; ?>
            <input type="button" id="edit_upload_artist_image_button" class="button" value="<?php _e('Upload Image', 'therosehill-plus'); ?>" data-uploader_title="<?php _e('Choose Artist Image', 'therosehill-plus'); ?>" data-uploader_button_text="<?php _e('Select Image', 'therosehill-plus'); ?>" />
            <input type="hidden" name="rhp_artist_image" id="edit_rhp_artist_image" value="<?php echo esc_attr( $artist_image ); ?>" />
            <div id="edit_artist_image_preview" style="margin-top: 10px;"></div>
            <script>
                jQuery(document).ready(function($) {
                    function initializeMediaUploader(buttonId, inputFieldId, previewDivId) {
                        $(buttonId).click(function(e) {
                            e.preventDefault();
                            var customUploader = wp.media({
                                title: '<?php _e("Choose Artist Image", "therosehill-plus"); ?>',
                                button: {
                                    text: '<?php _e("Select Image", "therosehill-plus"); ?>',
                                },
                                multiple: false
                            }).on('select', function() {
                                var editImage = customUploader.state().get('selection').first().toJSON();
                                $(inputFieldId).val(editImage.id);
                                $(previewDivId).html('<img src="' + editImage.url + '" style="max-width: 200px;" />');
                            }).open();
                        });
                    }

                    initializeMediaUploader('#edit_upload_artist_image_button', '#edit_rhp_artist_image', '#edit_artist_image_preview');
                });
            </script>
            <p class="description"><?php _e('An image of the artist.', 'therosehill-plus'); ?></p>
        </td>
    </tr>
    <?php
}