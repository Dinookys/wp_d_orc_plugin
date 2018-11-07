<?php
/**
*@subpackage d_orc
*
*/

function dorc_mail_body($args = []){

    $html = '<h3>Pedido</h3>';
    $html = '<h5>Dados do solicitante:</h5>';
    $html .= '<p>Nome: ' . $args['name'] . '</p>';
    $html .= '<p>Email: ' . $args['email'] . '</p>';
    $html .= '<p>Telefone: ' . $args['phone'] . '</p>';
    $html .= '<hr>';
    $html .= '<p><a href="'. $args['post_url'] .'" target="_blank" >Visualizar Pedido</a></p>';
    $html .= '<hr>';
    foreach($args['products'] as $product){
        $html .= '<p><a href="'. $product['url'] .'" target="_blank" >'. $product['title'] .' </a> Quantidade: '. $product['quant'] .'</p>';
    }

    return $html;

}