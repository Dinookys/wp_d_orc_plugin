<?php 
/**
*@package Wordpress
*@subpackage Produtos & Orçamentos
*/

// Redirecionando para a template de visualização de categoria do plugin
function dorc_filter_category_template($template){

    $theme_archive_file = !file_exists(get_template_directory() . '/archive-dorc-category.php');
    $theme_tax_file = !file_exists(get_template_directory() . '/taxonomy-dorc-category.php');

    $taxonomy = get_query_var( 'taxonomy' );

    if($taxonomy == 'dorc-product-categories' && $theme_archive_file && $theme_tax_file){
        $template = DORC_DIR_PATH . '/templates/dorc-category.php';
    }
    return $template;
}
add_filter( 'archive_template', 'dorc_filter_category_template' );
add_filter( 'taxonomy_template', 'dorc_filter_category_template' );

// Redirecionando para a template de visualização de item do plugin
function dorc_filter_single_template($template){
    
    $theme_file = !file_exists(get_template_directory() . '/single-dorc-products.php');

    if(get_post_type( ) == 'dorc-products' && $theme_file){
        $template = DORC_DIR_PATH . '/templates/dorc-single.php';
    }
    return $template;
}
add_filter( 'single_template', 'dorc_filter_single_template' );