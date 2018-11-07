<?php 
/**
*@package Wordpress
*@subpackage Produtos & Orçamentos
*/
$has_quant_product = isset($_SESSION['dorc-products'][get_the_ID()]['quant']) ?
                $_SESSION['dorc-products'][get_the_ID()]['quant'] : 
                '1';

$has_product = isset($_SESSION['dorc-products'][get_the_ID()]['quant']) ? true : false;

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
            <div class="controls dropdown">
                <a href="#" class="btn btn-primary dropdown-toggle" aria-haspopup="true" aria-expanded="false" >
                    <?php echo __('Add'); ?>
                </a>
                <div class="dropdown-action">
                    <p class="message text-primary" style="display: none;" ></p>
                    <input type="number" name="quant" min="1" class="form-control" value="<?php echo $has_quant_product; ?>" >
                    <br>
                    <div class="btn-group pull-right">
                        <button class="btn btn-default minus">-</button>
                        <button class="btn btn-default plus">+</button>
                        <button class="btn btn-primary add"><?php 
                            if($has_product) :
                                echo 'Alterar';
                            else:
                                echo __('Add'); 
                            endif;
                        ?></button>
                    </div>
                    <input type="hidden" name="title" value="<?php echo get_the_title(); ?>" >
                    <input type="hidden" name="item" value="<?php echo the_ID(); ?>" >
                </div>
            </div>
            <a href="<?php echo get_the_permalink( ); ?>" class="btn btn-default"><?php echo __('Detalhes'); ?></a>
        </div>
    </div>
</div>