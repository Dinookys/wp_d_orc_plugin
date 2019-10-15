<?php 
/**
*@package Wordpress
*@subpackage Produtos & Orçamentos
*/
function dorc_enqueu_scripts()
{
    if(!wp_script_is( 'jquery' )){
        wp_enqueue_script( 'jquery' );        //wp_enqueue_script( 'bootbox', DORC_DIR_URL . '/inc/vendors/bootbox/bootbox.min.js' );
    }
    
    wp_enqueue_script( 'zoom', DORC_DIR_URL . 'inc/vendors/jquery.zoom.min.js' );
    wp_enqueue_script( 'slick', DORC_DIR_URL . 'inc/vendors/slick/slick.min.js' );
    wp_enqueue_style( 'slick', DORC_DIR_URL . 'inc/vendors/slick/slick.css' );
    wp_enqueue_style( 'slick-theme', DORC_DIR_URL . 'inc/vendors/slick/slick-theme.css' );

    if(!wp_style_is('dashicons')) {
        wp_enqueue_style('dashicons');
    }

    if(!wp_style_is( 'dorc' )){
        wp_enqueue_style( 'dorc', DORC_DIR_URL . 'inc/css/dorc-site.css' );
    }

    if(!wp_script_is( 'dorc-script' )){
        wp_enqueue_script( 'dorc-script', DORC_DIR_URL . 'inc/js/dorc-script.js' );
    }

    wp_localize_script( 'dorc-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action( 'wp_enqueue_scripts', 'dorc_enqueu_scripts');