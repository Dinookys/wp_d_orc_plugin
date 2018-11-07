<?php 
/**
*@package Wordpress
*@subpackage Produtos & Orçamentos
*/
function dorc_add_meta_box_products_list()
{
    add_meta_box( 'products-list', 'Lista de produtos', 'dorc_product_list_meta_callback', 'dorc', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'dorc_add_meta_box_products_list');

function dorc_product_list_meta_callback( $post )
{
    $products_ids = explode(',', get_post_meta( $post->ID, 'dorc_products_ids', true ));
    $products_quant =  json_decode(get_post_meta( $post->ID, 'dorc_products_quant', true ),true);    

    $products = new WP_Query( array(
        'post_type' => 'dorc-products',
        'post__in' => $products_ids,
        'order_by' => 'title',
        'order' => 'ASC'
    ) );   

?>
        <style>
            .list{
                width: 100%;
                padding: 5px;
                list-style: none;
            }
            .list .item-header{                
                border-bottom: 1px solid #ccc;
                color: 444;
            }
            .list .item-header,
            .list .item{
                padding: 8px;
                margin: 0;
            }
            .list .item{
                border: 1px solid #ccc;
                border-top: none;
                background: #eee;                
            }
            .list .item:hover{
                background: #e4e4e4;
            }            
            .list .item-header span,
            .list .item-header a,
            .list .item span,
            .list .item a{
                display: inline-block;
                overflow: hidden;
            }
            .list .item-header *:first-child,
            .list .item *:first-child{
                width: 10%;
            }

            .list .item-header *:nth-child(2),
            .list .item *:nth-child(2){
                width: 75%;
            }

            .list .item-header *:last-child,
            .list .item *:last-child{
                width: 15%;
                text-align: center;
            }

        </style>
        <ul class="list" >
            <li class="item-header" ><span>#</span><span>Produto</span><span>Quantidade</span></li>
            <?php if($products->have_posts()) : ?>            
                <?php while($products->have_posts()) : $products->the_post(); ?>
                    <li class="item" ><span><?php the_ID(); ?></span><a href="<?php echo get_edit_post_link(); ?>" class="title"><?php the_title() ?></a><span><?php echo $products_quant[get_the_ID()]; ?></span></li>
                <?php endwhile; ?>
            <?php endif; ?>
        </ul>
<?php }

function dorc_add_meta_box_client()
{
    add_meta_box( 'client-list', 'Dados do cliente', 'dorc_client_list_meta_callback', 'dorc', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'dorc_add_meta_box_client');

function dorc_client_list_meta_callback($post)
{ 
    $client = get_post_meta( $post->ID, 'dorc_client', false );
?>
    <ul class="list" >       
        <li class="item-header" ></li> 
        <li class="item" ><b>Nome:</b> <?php echo get_post_meta( $post->ID, 'dorc_name', true ) ?></li>
        <li class="item" ><b>E-mail:</b> <?php echo get_post_meta( $post->ID, 'dorc_email', true ) ?></li>
        <li class="item" ><b>Telefone:</b> <?php echo get_post_meta( $post->ID, 'dorc_phone', true ) ?></li>        
        <li class="item" ><b>Endereço:</b> <?php echo get_post_meta( $post->ID, 'dorc_addres', true ) ?></li>        
    </ul>
<?php }

function dorc_add_meta_box_post_info(){
    add_meta_box( 'post-info', 'Informações gerais', 'dorc_post_info_meta_callback', 'dorc', 'side', 'high' );
}
add_action( 'add_meta_boxes', 'dorc_add_meta_box_post_info' );

function dorc_post_info_meta_callback($post)
{ ?>    
    <ul class="list-info" >        
        <li class="item" ><b>ID:</b> <?php echo $post->ID; ?> </li>
        <li class="item" ><b>Data:</b> <?php echo date_i18n( 'd \d\e F \d\e Y', strtotime($post->post_date) ) ?></li>
        <li class="item" ><b>Horário:</b> <?php echo date_i18n( 'h:i:s', strtotime($post->post_date) ) ?></li>
    </ul>
<?php }