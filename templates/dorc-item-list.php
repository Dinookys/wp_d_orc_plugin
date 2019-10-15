<?php 
/**
*@package Wordpress
*@subpackage Produtos & Orçamentos
*/
?>
<div class="dorc-product">
<?php if(has_post_thumbnail()): ?>
    <div class="image-content">        
        <a href="<?php echo get_the_permalink( ) ?>">
            <?php the_post_thumbnail( 'medium', array('alt' => get_the_title())); ?>
        </a>            
    </div>
<?php endif; ?>
    <div class="content">
        <div class="title-content">
            <?php the_title( '<h4><a href="'. get_the_permalink( ) .'" >', '</a></h4>' ); ?>
        </div>        
        <div class="text-content">
            <?php the_excerpt(); ?>
        </div>
    </div>
    <div class="dorc-product-action">
        <div class="actions">            
            <a href="<?php echo get_the_permalink( ); ?>" class="btn btn-default"><?php echo __('Detalhes', 'dorc'); ?></a>
        </div>
    </div>
</div>