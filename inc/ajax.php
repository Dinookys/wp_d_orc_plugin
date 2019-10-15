<?php 
/**
 *@package Wordpress
 *@subpackage Produtos & Orçamentos
 */

function dorc_change_list_style()
{
    $_SESSION['dorc-list-style'] =
        filter_input(INPUT_POST, 'style')
        ? filter_input(INPUT_POST, 'style')
        : 'grid';
    wp_die();
}
add_action('wp_ajax_dorc_change_list_style', 'dorc_change_list_style');
add_action('wp_ajax_nopriv_dorc_change_list_style', 'dorc_change_list_style');

/**
 * @todo Alterar
 */
function dorc_add_to_cart_ajax()
{

    $product = filter_input(INPUT_POST, 'product', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

    $return = dorc_add_to_cart($product);
    $variation_html = '';

    if (!empty($return['product_list']['variations'])) {
        foreach ($return['product_list']['variations'] as $key => $variation) {
            $variation_html .= dorc_loop_variation_cart_html($variation, $key, $product['_ID']);
        }
        $return['product_list']['variations_html'] = $variation_html;
    }


    echo json_encode($return, JSON_UNESCAPED_UNICODE);

    wp_die();
}
add_action('wp_ajax_dorc_add_to_cart', 'dorc_add_to_cart_ajax');
add_action('wp_ajax_nopriv_dorc_add_to_cart', 'dorc_add_to_cart_ajax');

function dorc_remove_from_cart()
{
    $return = array();
    $product_id = $_POST['product'];

    if (isset($_POST['key'])) {
        $key = $_POST['key'];
    }


    if (array_key_exists($product_id, $_SESSION['dorc-products'])) {
        
        if(isset($key)) {
            $quant = $_SESSION['dorc-products'][$product_id]['variations'][$key]['_quantity'];
            unset($_SESSION['dorc-products'][$product_id]['variations'][$key]);
            $_SESSION['dorc-products'][$product_id]['quantity_total'] -= $quant;
            
        } else {
            unset($_SESSION['dorc-products'][$product_id]);
        }
        
        if(empty($_SESSION['dorc-products'][$product_id]['variations'])) {
            unset($_SESSION['dorc-products'][$product_id]); 
        }

        $return['message'] = 'Removido com sucesso!';
    }
    $return['count'] = count($_SESSION['dorc-products']);

    echo json_encode($return, JSON_UNESCAPED_UNICODE);

    $has = false;

    wp_die();
}
add_action('wp_ajax_dorc_remove_from_cart', 'dorc_remove_from_cart');
add_action('wp_ajax_nopriv_dorc_remove_from_cart', 'dorc_remove_from_cart');

function dorc_change_variation_from_cart()
{
    $return = array();
    $product_id = $_POST['product'];
    $key = $_POST['key'];
    $new_value = $_POST['value'];


    if (array_key_exists($product_id, $_SESSION['dorc-products'])) {
        
        $quant = $_SESSION['dorc-products'][$product_id]['variations'][$key]['_quantity'];            
        $_SESSION['dorc-products'][$product_id]['variations'][$key]['_quantity'] = $new_value;
        $_SESSION['dorc-products'][$product_id]['quantity_total'] -= $quant;
        $_SESSION['dorc-products'][$product_id]['quantity_total'] += $new_value;

        $return['message'] = 'Alterado com sucesso!';
        $return['prod_quantity_total'] = $_SESSION['dorc-products'][$product_id]['quantity_total'];
    }

    echo json_encode($return, JSON_UNESCAPED_UNICODE);

    $has = false;

    wp_die();
}
add_action('wp_ajax_dorc_change_variation_from_cart', 'dorc_change_variation_from_cart');
add_action('wp_ajax_nopriv_dorc_change_variation_from_cart', 'dorc_change_variation_from_cart');