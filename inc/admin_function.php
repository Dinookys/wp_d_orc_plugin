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
    add_submenu_page('plugins.php', 'Configurações Plugin Orçamentos DO', 'Config. Orc DO', 'manage_options', 'dorc_plugin_config', 'dorc_create_admin_page');

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
    $html = '<input class="regular-text" type="text" value="[dorc-form]" onclick="this.select()" readonly=""/><br><i>Copie o código acima e cole dentro de uma página para gerar o formulário</i>';
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