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