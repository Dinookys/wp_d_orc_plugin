<?php 
/**
*@package Wordpress
*@subpackage Produtos & Orçamentos
*/

/*--------------------------------------

 Add Manage Post Types for this plugin

---------------------------------------*/

function d_orc_register_products_post_type()
{
    $labels = array(
        'name' => 'Produtos DO',
        'singular_name' => 'Produto',
        'add_new' => 'Adicionar novo',
        'add_new_item' => 'Adicionando novo produto',
        'edit_item' => 'Editar',
        'new_item' => 'Adicionar',
        'view_item' => 'Visualizar produto',
        'view_items' => 'Visuzalizr produtos',
        'search_items' => 'Pesquisar',
        'not_found' => 'Produto não encontrado',
        'not_found_in_trash' => 'Produto não encontrado na lixeira',
    );

    register_post_type( 'dorc-products', array(
        'labels' => $labels,
        'public' => true,
        'menu_position' => 10,
        'menu_icon' => 'dashicons-store',
        'supports' => array('title','editor','thumbnail','revisions'),
        'has_archive' => true,                
        'rewrite' => array('slug' => 'produtos/item'),
        'show_in_nav_menus' => false,
        'hierarchical' => false
    ) );    
}
add_action( 'init', 'd_orc_register_products_post_type');

function d_orc_register_products_taxonomy()
{
    $labels = array(
        'name' => 'Categorias Produtos DO',
        'singular_name' => 'Categoria',        
        'menu_name' => 'Categorias',
        'all_items' => 'Todas as categorias',
        'edit_item' => 'Editar categoria',
        'add_new_item' => 'Adicionar Novo',
        'view_item' => 'Visualizar Categoria',
        'update_item' => 'Atualizar Categoria'
    );

    register_taxonomy( 'dorc-product-categories', 'dorc-products', array(
        'labels' => $labels,
        'hierarchical' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'produtos/categoria')
    ));
}
add_action( 'init', 'd_orc_register_products_taxonomy');

function d_orc_register_orc_post_type()
{
    $labels = array(
        'name' => 'Orçamentos DO',
        'singular_name' => 'Orçamento',                
        'view_item' => 'Visualizar orçamento',
        'edit_item' => 'Visualizando orçamento',
        'view_items' => 'Visuzalizr orçamentos',
        'search_items' => 'Pesquisar',
        'not_found' => 'Nenhum orçamento encontrado',
        'not_found_in_trash' => 'Orçamento não encontrado na lixeira',
    );

    register_post_type( 'dorc', array(
        'labels' => $labels,        
        'public' => true,
        'menu_position' => 10,
        'menu_icon' => 'dashicons-list-view',
        'supports' => false,       
        'publicly_queryable' => false,
        'exclude_from_search' => true,
        'show_in_nav_menus' => false,
        'capabilities' => array(            
            'edit_post'  => 'edit_dorc',            
            'read_post' => 'read_dorc',
            'delete_post' => 'delete_dorc',            
            'create_posts' => false,
            'publish_posts' => false,            
        ),
        'map_meta_cap' => true,
        'register_meta_box_cb' => 'd_orc_metaboxes_change_dorc'
    ) );  
}
add_action( 'init', 'd_orc_register_orc_post_type' );

/*************Remove meta boxes dorc**************/
function d_orc_metaboxes_change_dorc(){
    remove_meta_box( 'submitdiv', 'dorc', 'side' );
}

/***** Remove edit and quick edit for dor*******/
function d_orc_manage_edit_from_dorc($actions){
    $screen = get_current_screen();
    if($screen->post_type == 'dorc'){
        return array_slice($actions,2);
    }
    return $actions;
}
add_filter( 'post_row_actions', 'd_orc_manage_edit_from_dorc');

/***** Add collumns in dorc page list*******/
function d_orc_add_products_columns_dorc($columns)
{   
    return array(
        'cb' => '<input type="checkbox">',
        'title' => __( 'Title' ),
        'client_info' => 'Info. Cliente',        
        'date' => __( 'Date' )
    );
}
add_filter( 'manage_dorc_posts_columns', 'd_orc_add_products_columns_dorc' );

function d_orc_add_products_custom_column_dorc($column, $post_id){

    if($column == 'client_info'){    
        echo 'Nome: ' . get_post_meta( $post_id, 'dorc_name', true ) . ' <br> ';
        echo 'Email: ' . get_post_meta( $post_id, 'dorc_email', true ) . ' <br> ';
        echo 'Telefone: ' . get_post_meta( $post_id, 'dorc_phone', true );
    }
}
add_filter( 'manage_dorc_posts_custom_column', 'd_orc_add_products_custom_column_dorc', 10, 2 );

/********* Add filter category in custom post type**********/
function d_orc_add_filter_category_filter()
{
    global $typenow;
    $post_type = 'dorc-products';
    $taxonomy = 'dorc-product-categories';

    if ($typenow == $post_type) {
		$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'show_option_all' => __("Todas as categorias"),
			'taxonomy'        => $taxonomy,
			'name'            => $taxonomy,
			'orderby'         => 'name',
			'selected'        => $selected,
			'show_count'      => true,
			'hide_empty'      => true,
		));
	};

}
add_filter( 'restrict_manage_posts', 'd_orc_add_filter_category_filter');

function d_orc_convert_term_id_in_term_query($query)
{
    global $pagenow;
	$post_type = 'dorc-products';
    $taxonomy = 'dorc-product-categories';
	$q_vars    = &$query->query_vars;
	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
}
add_filter('parse_query', 'd_orc_convert_term_id_in_term_query');