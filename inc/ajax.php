<?php 
/**
*@package Wordpress
*@subpackage Produtos & Orçamentos
*/
function dorc_change_list_style(){
    $_SESSION['dorc-list-style'] = 
        filter_input(INPUT_POST, 'style') 
            ? filter_input(INPUT_POST, 'style') 
            : 'grid';    
    wp_die();
}
add_action( 'wp_ajax_dorc_change_list_style', 'dorc_change_list_style');
add_action( 'wp_ajax_nopriv_dorc_change_list_style', 'dorc_change_list_style');


function dorc_add_to_cart(){
    $quant = filter_input(INPUT_POST, 'quant');
    $id = filter_input(INPUT_POST, 'item');
    $title = filter_input(INPUT_POST, 'title');

    $itens = $_SESSION['dorc-products'] ? $_SESSION['dorc-products'] : array();

    if(array_key_exists($id, $itens) && $quant != $itens[$id]['quant']){
        echo json_encode(['message' => sprintf('Quantidade alterada de %s para %s', $itens[$id]['quant'], $quant)], JSON_UNESCAPED_UNICODE);
    }elseif(array_key_exists($id, $itens)){
        echo json_encode(['message' => sprintf('%s, já adicionado.', $title)], JSON_UNESCAPED_UNICODE);
    }else{
        echo json_encode(['message' => sprintf('%s, adicionado com sucesso.', $title)], JSON_UNESCAPED_UNICODE);
    }

    $itens[$id]['id'] = $id;
    $itens[$id]['title'] = $title;
    $itens[$id]['quant'] = $quant;

    $_SESSION['dorc-products'] = $itens;
    
    $has = false;

    wp_die();
}
add_action( 'wp_ajax_dorc_add_to_cart', 'dorc_add_to_cart');
add_action( 'wp_ajax_nopriv_dorc_add_to_cart', 'dorc_add_to_cart');


function dorc_remove_to_cart(){    
    $id = filter_input(INPUT_POST, 'item');   
    $return = array();

    if(array_key_exists($id, $_SESSION['dorc-products'])){
        unset($_SESSION['dorc-products'][$id]);
        $return['message'] = 'Removido com sucesso!';
    }
    $return['count'] = count($_SESSION['dorc-products']);

    echo json_encode($return, JSON_UNESCAPED_UNICODE);
    
    $has = false;

    wp_die();
}
add_action( 'wp_ajax_dorc_remove_to_cart', 'dorc_remove_to_cart');
add_action( 'wp_ajax_nopriv_dorc_remove_to_cart', 'dorc_remove_to_cart');