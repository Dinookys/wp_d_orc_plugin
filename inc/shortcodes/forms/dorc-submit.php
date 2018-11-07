<?php
/*
    @package portal-chapada
    @subpackage plugin form newpost
*/

$valid = new validData();
$no_products = false;
$dorc_google_site_key = get_option( 'dorc_google_site_key');

if(isset($_POST['submit']) && isset($_POST['dorc-form']) && $_POST['dorc-form'] = 'newdorc-form' ){            

    //Dados do solicitante
    $email = isset($_POST['email']) ? sanitize_email( $_POST['email'] ) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field( $_POST['phone'] ) : '';
    $name = isset($_POST['client_name']) ? sanitize_text_field( $_POST['client_name'] ) : ''; 
    $addres = isset($_POST['addres']) ? wp_strip_all_tags( $_POST['addres'] ) : '';
    $title = '#' . date('d-m-Y h:i:s') . ' | ' . $name;

    $products = array();
    $products_ids = array();
    $products_quant = array();
    foreach($_SESSION['dorc-products'] as $product){
        $products[$product['id']]['title'] = $product['title'];
        $products[$product['id']]['quant'] = $product['quant'];
        $products[$product['id']]['url'] = get_edit_post_link( $product['id'] );
        $products_ids[] = $product['id'];
        $products_quant[$product['id']] = $product['quant'];
    }

    if(empty($products_ids)){
        $no_products = true;
    }

    $inputs = [
        'addres',        
        'email',
        'phone',
        'client_name'
    ];    

    $validations = [
        'addres' => 'required',        
        'email' => function( $data ){
            if(!empty($data) && strpos($data,'@') > 2 && strpos($data,'.')){
                return true;
            }

            return false;
        },
        'phone' => 'required',
        'client_name' => 'required'
    ];

    if($dorc_google_site_key){
        $inputs[] = 'g-recaptcha-response';
        $validations['g-recaptcha-response'] = 'required';
    }

    $valid->setInputs($inputs);

    $valid->setValidations($validations);

    $valid->setMessages([
        'addres' => 'O endereço deve ser preenchido.',        
        'email' => 'O email deve ser preenchido corretamente.',
        'phone' => 'O campo telefone deve ser preenchido',
        'client_name' => 'O nome deve ser preenchido',
        'g-recaptcha-response' => 'Verificação não realizada.'
    ]);

    $valid->getPostData($_POST)->valid();

    if($valid->isValidPost()){

        $args = array(
            'post_title' => $title,            
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'dorc',
            'meta_input' => array(
                'dorc_phone' => $phone,
                'dorc_name'  => $name,
                'dorc_addres' => $addres,
                'dorc_email' => $email,
                'dorc_products_ids' => $products_ids,
                'dorc_products_quant' => json_encode($products_quant)
            )
        );
        
        $postID = wp_insert_post($args);

        // Configurações para o envio do email para o administrador
        $domain = $_SERVER['SERVER_NAME'];
        //$headers[] = 'From: no-reply@' . $domain;
        $headers[] = 'Content-Type: text/html; charset=UTF-8';

        $subject = 'Novo pedido de orçamento do site: ' . get_site_option( 'blogname' );

        // Gerando html do contéudo do email do administrador
        $message = dorc_mail_body(array('products' =>  $products,'post_url' => get_edit_post_link( $postID ), 'addres' => $addres, 'name' => $name, 'email' => $email, 'phone' => $phone));

        // Admin Email
        wp_mail( get_option( 'dorc_admin_email', get_site_option( 'admin_email') ) , $subject, $message, $headers);        

        if(!empty($email)){            
            // Gerando html do contéudo do email client
            $message = 'Seu pedido de orçamento foi submetido com sucesso, entraremos em contato em breve. <br> <b>ID do pedido: ' . $postID . '</b>';
            // Client Email
            wp_mail( $email, $subject, $message, $headers);        
        }        

        // Resetando os campos do formulario.
        if(is_numeric($postID)){
            
            $addres='';            
            $phone='';
            $name='';
            $email='';
            unset($_POST);
            unset($_SESSION['dorc-products']);
            $no_products = false;            
        }

    }else{

        $postID = false;
    }

}