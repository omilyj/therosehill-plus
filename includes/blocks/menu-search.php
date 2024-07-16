<?php

function rhp_menu_search_render_cb($atts) {
    $bgColor = esc_attr($atts['bgColor']);
    $iconColor = esc_attr($atts['iconColor']);
    $borderColor = esc_attr($atts['borderColor']);
    $styleAttr = "background-color:{$bgColor};color:{$iconColor};";

    ob_start();
    ?>
        <div class="wp-block-therosehill-plus-menu-search">
            <form action="<?php echo esc_url(home_url('/')); ?>">
            <button type="submit" style="<?php echo $styleAttr; ?>">
                <i class="bi bi-search"></i>
            </button>
                <input type="search"
                    style="<?php echo $borderColor; ?>"
                    placeholder="<?php esc_html_e('Search', 'therosehill-plus'); ?>"
                    name="s" 
                    value="<?php the_search_query(); ?>" />
            </form>
        </div>
    <?php

    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}