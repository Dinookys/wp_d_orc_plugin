<?php 
/**
*@package Wordpress
*@subpackage Produtos & Orçamentos
*/
get_header(); ?>
<?php while(have_posts()) : the_post(); ?>
<?php 
    $has_quant_product = isset($_SESSION['dorc-products'][get_the_ID()]['quant']) ?
    $_SESSION['dorc-products'][get_the_ID()]['quant'] : 
    '1';
?>
<div class="dorc-product details">
    <div id="dorc-single" class="container">
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
        <div class="clearfix">
            <a href="#" class="thumbnail pull-left" data-zoom="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" >
                <?php the_post_thumbnail( 'medium' ); ?>                
            </a>
            <div class="dorc-product-action">
                <div class="actions pull-right">            
                    <p class="message text-primary" style="display: none;" ></p>
                    <input type="number" name="quant" min="1" class="form-control" value="<?php echo $has_quant_product; ?>" >
                    <br>
                    <div class="btn-group pull-right">
                        <button class="btn btn-default minus">-</button>
                        <button class="btn btn-default plus">+</button>
                        <button class="btn btn-primary add"><?php echo __('Add'); ?></button>
                    </div>
                    <input type="hidden" name="title" value="<?php echo get_the_title(); ?>" >
                    <input type="hidden" name="item" value="<?php echo the_ID(); ?>" >            
                </div>                
            </div>        
        </div>        
        <div class="description">
            <h3>Descrição</h3>
            <?php if( has_excerpt( ) ) : ?>
                <?php the_excerpt(); ?>
            <?php else: ?>
                <?php the_content(); ?>
            <?php endif; ?>
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