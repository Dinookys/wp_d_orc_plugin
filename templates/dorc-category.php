<?php 
/**
*@package Wordpress
*@subpackage Produtos & Orçamentos
*/

$grid_style = $_SESSION['dorc-list-style'] ? $_SESSION['dorc-list-style'] : 'list';
$btn_grid_active = $_SESSION['dorc-list-style'] == 'grid' || !$_SESSION['dorc-list-style'] ? 'active' : '';
$btn_list_active = $_SESSION['dorc-list-style'] == 'list' ? 'active' : '';

//ORDER
global $wp_query;
$args = array(        
    'orderby' => 'post_title',
    'order' => 'ASC'
);

query_posts(array_merge($wp_query->query, $args));

$pagination = get_the_posts_pagination( 
    array(
        'screen_reader_text' => ' ',
        'mid_size' => 2
    )
);

$pagination = strip_tags($pagination,'<a><span><div>');

get_header(); ?>
    <div id="dorc-category" class="container">
        <?php the_archive_title('<h2 class="page-header" >', '</h2>'); ?>

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
        <div class="wrap-items <?php echo $grid_style; ?>">
            <div class="bar">
                <div class="btn-group">
                    <a class="btn btn-default <?php echo $btn_grid_active; ?>" href="javaScript:void(0)" data-style="grid" class="mode">
                        <i class="fa fa-th-large" aria-hidden="true"></i>
                    </a>
                    <a class="btn btn-default <?php echo $btn_list_active; ?>" href="javaScript:void(0)" data-style="list" class="mode">
                        <i class="fa fa-th-list" aria-hidden="true"></i>                        
                    </a>
                </div>
            </div>
            <div class="items">
                <?php if(have_posts()): 
                    while(have_posts()) : the_post();?>
                    <?php require DORC_DIR_PATH . '/templates/dorc-item-list.php'; ?>
                <?php endwhile; ?>                
                <?php else: ?>
                    <h4 class="jumbotron text-center">Nenhum produto aqui ainda... :)</h4>
                <?php endif; ?>
            </div>
        </div>
        <?php echo $pagination; ?>       
    </div>
<?php if( has_nav_menu( 'd_orc_menu_sidebar' ) ) : ?>
    </div>
</div>
<?php endif; ?>
<?php
get_footer();