<?php 
/**
*@package Wordpress
*@subpackage Produtos & Orçamentos
*/

/*************************
    ADMIN FUNCTIONS
*************************/
function dorc_add_admin_page(){
    //add_submenu_page( parent_slug, page_title, menu_title, capability, menu_slug, function ) 
    add_submenu_page('plugins.php', 'Configurações Plugin Orçamentos DO', 'Config. Orçamento', 'manage_options', 'dorc_plugin_config', 'dorc_create_admin_page');

    register_setting( 'dorc-settings', 'dorc_google_site_key' );
    register_setting( 'dorc-settings', 'dorc_google_private_key' );
    register_setting( 'dorc-settings', 'dorc_admin_email' );

    //register_setting( 'dorc-settings', 'dorc_func_pagination' );
    //register_setting( 'dorc-settings', 'dorc_posts_per_page' );

    add_settings_section( 'dorc-form', 'Configurações Orçamento', 'dorc_form_settings_section_callback', 'dorc_plugin_config' );
    add_settings_field( 'admin_email', 'Email', 'dorc_admin_email_callback', 'dorc_plugin_config', 'dorc-form' );

    add_settings_section( 'dorc-google-recaptcha', 'Google reCaptcha2', 'dorc_google_settings_section_callback', 'dorc_plugin_config' );

    add_settings_field( 'google_site_key', 'Chave pública', 'dorc_field_google_site_key_callback', 'dorc_plugin_config', 'dorc-google-recaptcha' );
    add_settings_field( 'google_private_key', 'Chave privada', 'dorc_field_google_private_key_callback', 'dorc_plugin_config', 'dorc-google-recaptcha' );

    //add_settings_section( 'dorc-options', 'Opções', 'dorc_options_settings_section_callback', 'dorc_plugin_config' );
    //add_settings_field( 'posts_per_page', 'Produtos por página', 'dorc_field_posts_per_page_callback', 'dorc_plugin_config', 'dorc-options' );
    //add_settings_field( 'func_pagination', 'Páginação', 'dorc_field_func_pagination_callback', 'dorc_plugin_config', 'dorc-options' );

}
add_action( 'admin_menu', 'dorc_add_admin_page');

function dorc_options_settings_section_callback(){}

function dorc_create_admin_page(){
    require_once(DORC_DIR_PATH . 'inc/admin_page.php');
}

function dorc_admin_email_callback(){
    $value = get_option( 'dorc_admin_email', null);
    echo '<input type="text" class="regular-text" name="dorc_admin_email" placeholder="Email" value="'. $value .'" > <br>';
    echo '<i>Email que receberá os pedidos de orçamento.</i>';
}

// Section Form
function dorc_form_settings_section_callback(){
    $html = '<p>';
    $html .= '<input class="large-text" type="text" value="'. htmlentities2('[dorc-form]') .'" onclick="this.select()" readonly=""/><br><i>Copie o código acima e cole dentro de uma página para gerar o formulário</i>';    
    $html .= '</p>';
    $html .= '<p>';
    $html .= '<input class="large-text" type="text" value="'. htmlentities2('[dorc-list-products cats="1,2,3" posts_per_page="10" order="ASC|DESC|RAND" view="grid|list"]') .'" onclick="this.select()" readonly=""/><br><i>Copie o código acima e cole dentro de uma página para gerar uma lista de produtos basedo no id da categoria.</i>';
    $html .= '<br><i>Atributos do shortcode: </i>';
    $html .=  '<br><i><b>posts_per_page</b> = Quantidade de produtos a ser mostrado; deixe <b>-1</b> para listar todos.</i>';
    $html .=  '<br><i><b>cats</b> = ID das categorias de produtos separados por <b>vírgula (,)</b>. Deixei vazio para listar produtos de todas as categorias.</i>';
    $html .=  '<br><i><b>order</b> = Ordenação valores: ASC, DESC ou RAND.</i>';
    $html .=  '<br><i><b>view</b> = Tipo de visualização valores: grid ou list.</i>';
    $html .= '</p>';
    echo $html;
}

function dorc_google_settings_section_callback(){
    echo '<p>Adicione a chave publica e privada nos campos abaixo para habilitar o reCaptcha2.</p>';
}

function dorc_field_google_site_key_callback(){
    $value = get_option( 'dorc_google_site_key');
    echo '<input type="text" class="regular-text" name="dorc_google_site_key" placeholder="Site Key" value="'. $value .'" >';
}

function dorc_field_google_private_key_callback(){
    $value = get_option( 'dorc_google_private_key');
    echo '<input type="text" class="regular-text" name="dorc_google_private_key" placeholder="Private Key" value="'. $value .'" >';
}