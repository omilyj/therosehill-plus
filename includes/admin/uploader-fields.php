<?php

function rhp_add_image_attachment_fields_to_edit($form_fields, $post) {
    // Add photo credit field
    $form_fields['rhp-photo-credit'] = array(
        'label' => 'Photo Credit Name',
        'input' => 'text',
        'value' => get_post_meta($post->ID, 'rhp_photo_credit', true),
        'helps' => 'Enter the name of the photographer/source of the image.',
    );

    // Add photo source URL field
    $form_fields['rhp-photo-source-url'] = array(
        'label' => 'Photo Credit URL',
        'input' => 'text',
        'value' => get_post_meta($post->ID, 'rhp_photo_source_url', true),
        'helps' => 'Enter the URL of the photographer or photograph\'s source..',
    );

    return $form_fields;
}

function rhp_save_image_attachment_fields($post, $attachment) {
    if (isset($attachment['rhp-photo-credit'])) {
        update_post_meta($post['ID'], 'rhp_photo_credit', sanitize_text_field($attachment['rhp-photo-credit']));
    }
    if (isset($attachment['rhp-photo-source-url'])) {
        update_post_meta($post['ID'], 'rhp_photo_source_url', esc_url_raw($attachment['rhp-photo-source-url']));
    }
    return $post;
}