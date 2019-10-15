<?php
/*
    @package Wordpress
    @subpackage plugin dorc-form
*/
function dorc_orc_form_shortcode($atts , $content = null){

    if(!wp_style_is( 'dorc' )){
        wp_enqueue_style( 'dorc', DORC_DIR_URL . '/inc/css/dorc-site.css' );
    }

    if(!wp_script_is( 'dorc-script' )){
        wp_enqueue_script( 'dorc-script', DORC_DIR_URL . '/inc/js/dorc-script.js' );
    }

    if(get_option( 'dorc_google_site_key') && !wp_script_is( 'recaptcha' )){
        wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js' );
        wp_enqueue_script( 'dorc-script', DORC_DIR_URL . '/inc/js/dorc-recaptcha.js' );
    }

    global $wp;
    $current_url = home_url(add_query_arg(array(),$wp->request));    
    
    include_once DORC_DIR_PATH_SHORTCODES . '/class/valid-data.php';
    include_once DORC_DIR_PATH_SHORTCODES . '/helpers.php';    

    include DORC_DIR_PATH_SHORTCODES . '/forms/dorc-submit.php';

    ob_start();    
    include DORC_DIR_PATH_SHORTCODES . '/forms/dorc-form.php';
    return ob_get_clean();
}

add_shortcode( 'dorc-form', 'dorc_orc_form_shortcode' );

function dorc_list_products($attrs, $content)
{
    $attrs = (object) shortcode_atts(array(
        'order' => 'ASC',
        'posts_per_page' => 10,
        'cats' => '',
        'view' => 'grid'
    ), $attrs, 'dorc-list-products');

    $query = new WP_Query(array(
        'post_type' => 'dorc-products',
        'posts_per_page' => $attrs->posts_per_page,       
        'orderby' => $attrs->order == 'RAND' ? strtolower($attrs->order) : array('post_title' => $attrs->order),
        'tax_query' => array(
            array(
                'taxonomy' => 'dorc-product-categories',
                'field' => 'term_id',
                'terms' => explode(',', $attrs->cats),
                'operator' => count(explode(',', $attrs->cats)) <= 1 ? '=' : 'IN'
            )
        )
    ));

    ob_start();

    if($query->have_posts()) {

        echo '<div class="dorc-products-list items '. $attrs->view .'">';

        while( $query->have_posts() ) {
            $query->the_post();

            $template_theme = get_template_directory() . '/dorc-item-list.php';

            if(file_exists($template_theme)) {
                require $template_theme;
            } else {
                require DORC_DIR_PATH . '/templates/dorc-item-list.php';
            }

        }

        echo '</div>';

        wp_reset_postdata();
        wp_reset_query();
    }

    return ob_get_clean();
}
add_shortcode('dorc-list-products', 'dorc_list_products');