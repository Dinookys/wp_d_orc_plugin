<?php 
/**
*@package Wordpress
*@subpackage Produtos & Orçamentos
*/
?>
<h1 class="aligncenter" >Opções</h1>
<hr>
<?php settings_errors() ?>
<form action="options.php" method="post" >    
    <?php settings_fields( 'dorc-settings' ); ?>
    <?php do_settings_sections( 'dorc_plugin_config' ); ?>    
    <?php submit_button(  ) ?>
</form>