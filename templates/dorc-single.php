<?php 
/**
*@package Wordpress
*@subpackage Produtos & Orçamentos
*/
get_header(); ?>
<?php while(have_posts()) : the_post(); ?>
<div class="dorc-product container details">
    <div id="dorc-single">
        <?php the_title( '<h2 class="page-header" >', '</h2>' ); ?>
    <?php if( has_nav_menu( 'd_orc_menu_sidebar' ) ) : ?>
    <div class="row">            
        <div class="col-xs-12 col-md-3">            
            <?php wp_nav_menu( array(
                'theme_location' => 'd_orc_menu_sidebar',
                'menu_class' => 'navbar nav',
                'container' => false,
                'menu_id' => 'd_orc_menu_sidebar',
                'walker' => class_exists('Walker_Menu_Bootstrap') ? new Walker_Menu_Bootstrap() : ''
                ) ); ?>
        </div>
        <div class="col-xs-12 col-md-9">            
    <?php endif; ?>
        <div class="flex">                        
            <?php 
                dorc_get_product_html_images(); 
                dorc_get_product_html_actions(); 
            ?>        
        </div>               

        <div class="description">
            <h3>Descrição</h3>
            <?php the_content(); ?>
        </div>

    </div>

<?php if( has_nav_menu( 'd_orc_menu_sidebar' ) ) : ?>
    </div>
</div>
<?php endif; ?>

</div> 
<?php endwhile; ?>
<?php
get_footer();