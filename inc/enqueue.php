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

    if(wp_script_is( 'jquery' )) {
        wp_enqueue_script( 'zoom', DORC_DIR_URL . '/inc/js/jquery.zoom.min.js' );
    }

    if(!wp_style_is( 'dorc' )){
        wp_enqueue_style( 'dorc', DORC_DIR_URL . '/inc/css/dorc-site.css' );
    }

    if(!wp_script_is( 'dorc-script' )){
        wp_enqueue_script( 'dorc-script', DORC_DIR_URL . '/inc/js/dorc-script.js' );
    }

    wp_localize_script( 'dorc-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action( 'wp_enqueue_scripts', 'dorc_enqueu_scripts');