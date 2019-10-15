<?php

/**
 *@package Wordpress
 *@subpackage Produtos & Orçamentos
 */

// SLIDE
add_action('add_meta_boxes', 'do_add_meta_box_slide_product_images');
function do_add_meta_box_slide_product_images()
{
    add_meta_box('slide', 'Imagens', 'do_meta_box_slide_product_images_callback', 'dorc-products', 'normal', 'high');
}

function do_meta_box_slide_product_images_callback($post)
{

    wp_nonce_field('slide_meta_box_nonce', 'meta_box_nonce');

    $galeria = get_post_meta($post->ID, 'dorc_slide', true);

    ?>

    <div><i>Arraste para ordenar</i></div>

    <ul id="slide-sortable" class="slide-sortable" >
        <?php if ($galeria) { ?>
            <?php foreach ($galeria as $key => $url) { ?>
            <li data-id="<?php echo $key ?>" style="background-image: url('<?php echo $url ?>')" >
                <a class="removeitem" href="#">
                    <span class="dashicons dashicons-trash"></span>
                </a>
                <input type="hidden" name="dorc_slide[<?php echo $key ?>]" value="<?php echo $url ?>" />
            </li>
        <?php 
    }
} ?>
        <li class="additem nosort" >
            <a href="#" >
                <span class="dashicons dashicons-plus"></span>
            </a>
        </li>
    </ul>
    <hr>
    <style>
        #slide-sortable {
            display: flex;
            flex-wrap: wrap;
            text-align: center;
        }
        #slide-sortable li {
            border: 1px solid #eee;
            width: 100px;
            height: 100px;
            margin: 5px;
            background-color: #eee;
            overflow: hidden;
            position: relative;
            background-repeat: no-repeat;
            background-position: center;
        }
        #slide-sortable li:not(.additem):hover {
            border: 1px dashed #000;
            background-size: contain;
        }
        #slide-sortable .ui-sortable-helper {
            border: 1px dashed #eee;
            cursor: move;
        }
        #slide-sortable .additem a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            text-decoration: none;
        }
        #slide-sortable .removeitem {
            position: absolute;
            right: 5px;
            top: 5px;
            color: #900;
            text-decoration: none;
        }
    </style>
    <script>
        (function($){
            $('document').ready(function() {
                var nextID = 0;
                var custom_uploader;

                $( 'body' ).on('click', '.slide-sortable .removeitem', function(e){
                    e.preventDefault();
                    $(this).parent('li').fadeOut('fast', function(){
                        $(this).delay(1000).remove();
                    })
                });

                $( ".slide-sortable" ).sortable({ items: "> li:not(.nosort)" });
                $( ".slide-sortable" ).disableSelection();

                $( "#slide-sortable .additem a" ).click(function(e){
                    e.preventDefault();

                    var _parent = $('#slide-sortable');

                    $(this).parent().siblings().each(function(index, item) {
                        nextID =
                            nextID < Number($(item).data('id'))
                            ? Number($(item).data('id'))
                            : nextID;
                    });

                    var id = Number(nextID+1);

                    //If the uploader object has already been created, reopen the dialog
                    if (custom_uploader) {
                        custom_uploader.open();
                        return;
                    }

                    //Extend the wp.media object
                    custom_uploader = wp.media.frames.file_frame = wp.media({
                        title: 'Escolher imagem',
                        button: {
                            text: 'Escolher imagem'
                        },
                        multiple: true
                    });

                    custom_uploader.on('select', function () {
                        custom_uploader.state().get('selection').map(function (attachment) {
                            attachment = attachment.toJSON();

                            var $li = '<li data-id="'+ id +'" style="background-image: url('+ attachment.url +')" >'
                                + '<a class="removeitem" href="#">'
                                    + '<span class="dashicons dashicons-trash"></span>'
                                + '</a>'
                                + '<input type="hidden" name="dorc_slide['+ id +']" value="'+ attachment.url +'" />'
                            + '</li>';

                            _parent.prepend($li);
                            //Incrementando
                            id++
                        });
                    });

                    custom_uploader.open();
                    nextID = 0;
                })
            })
        })(jQuery)
    </script>
<?php 
}

// LIST
add_action('add_meta_boxes', 'dorc_add_meta_box_products_list');
function dorc_add_meta_box_products_list()
{
    add_meta_box('products-list', 'Lista de produtos', 'dorc_product_list_meta_callback', 'dorc', 'normal', 'high');
}

function dorc_product_list_meta_callback($post)
{
    $products_ids = get_post_meta($post->ID, 'dorc_products_ids', true);
    $products_quant = json_decode(get_post_meta($post->ID, 'dorc_products_quant', true), true);
    $_products = get_post_meta($post->ID, 'dorc_products', true);

    if(empty($_products)) {
        $products = new WP_Query(array(
            'post_type' => 'dorc-products',
            'post__in' => $products_ids,
            'order_by' => 'title',
            'order' => 'ASC',
        ));
    }

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
            .list .item,
            .list .variation-item{
                padding: 8px;
                margin: 0;
            }
            .list .item,
            .list .variation-item{
                border: 1px solid #ccc;
                border-top: none;
                background: #eee;
            }

            .variation-item {
                background-color: white !important;                
            }

            .variation-item > div{
                display: flex;
                justify-content: space-between;
                overflow: auto;
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
        <?php 
            /**
             * @todo compatibilizando com os DB antigo, remover na procimas versão
             */
        ?>
        <ul class="list" >
            <li class="item-header" ><span>#</span><span>Produto</span><span>Quantidade</span></li>
            <?php if (empty($_products) && $products->have_posts()) : ?>

                <?php while ($products->have_posts()) : $products->the_post(); ?>
                    <li class="item" >
                        <span><?php the_ID(); ?></span><a href="<?php echo get_edit_post_link(); ?>" class="title"><?php the_title() ?></a><span><?php echo $products_quant[get_the_ID()]; ?></span>                        
                    </li>
                <?php endwhile; wp_reset_postdata() ?>

            <?php else: ?>

                <?php foreach ($_products as $id => $product) : ?>

                <li class="item" >
                    <span><?php echo $id; ?></span><a href="<?php echo $product['url']; ?>" class="title"><?php echo $product['title'] ?></a><span><?php echo $product['quant']; ?></span>                    
                </li>
                <li class="variation-item">
                    <?php if(isset($product['variations'])) : 
                        foreach($product['variations'] as $key => $variation):
                        $html = '<div>';
                            foreach($variation as $name => $value) {
                                if($name !== '_quantity') {
                                    $html .= '<span><b>'. $name .': </b> '. $value .'</span>';
                                } else {
                                    $html .= '<span><b>Quantidade: </b> '. $value .'</span>';
                                }
                            }
                        $html .= '</div>';
                        echo $html;

                    endforeach;
                    else:
                        _e('O produto sem variações.');
                    endif;
                    ?>
                </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
<?php 
}

// CLIENT
add_action('add_meta_boxes', 'dorc_add_meta_box_client');
function dorc_add_meta_box_client()
{
    add_meta_box('client-list', 'Dados do cliente', 'dorc_client_list_meta_callback', 'dorc', 'normal', 'high');
}

function dorc_client_list_meta_callback($post)
{
    $client = get_post_meta($post->ID, 'dorc_client', false);
    ?>
    <ul class="list" >
        <li class="item-header" ></li>
        <li class="item" ><b>Nome:</b> <?php echo get_post_meta($post->ID, 'dorc_name', true) ?></li>
        <li class="item" ><b>E-mail:</b> <?php echo get_post_meta($post->ID, 'dorc_email', true) ?></li>
        <li class="item" ><b>CPF/CNPJ:</b> <?php echo get_post_meta( $post->ID, 'dorc_cpf_cnpj', true ) ?></li>
        <li class="item" ><b>Telefone:</b> <?php echo get_post_meta($post->ID, 'dorc_phone', true) ?></li>
        <li class="item" ><b>Endereço:</b> <?php echo get_post_meta($post->ID, 'dorc_addres', true) ?></li>
        <li class="item" ><b>Cidade:</b> <?php echo get_post_meta( $post->ID, 'dorc_city', true ) ?></li>        
    </ul>
<?php 
}

// INFO
add_action('add_meta_boxes', 'dorc_add_meta_box_post_info');
function dorc_add_meta_box_post_info()
{
    add_meta_box('post-info', 'Informações gerais', 'dorc_post_info_meta_callback', 'dorc', 'side', 'high');
}

function dorc_post_info_meta_callback($post)
{ ?>
    <ul class="list-info" >
        <li class="item" ><b>ID:</b> <?php echo $post->ID; ?> </li>
        <li class="item" ><b>Data:</b> <?php echo date_i18n('d \d\e F \d\e Y', strtotime($post->post_date)) ?></li>
        <li class="item" ><b>Horário:</b> <?php echo date_i18n('h:i:s', strtotime($post->post_date)) ?></li>
    </ul>
<?php 
}

// VARIATIONS
add_action('add_meta_boxes', 'dorc_add_meta_box_variations');
function dorc_add_meta_box_variations()
{
    add_meta_box('dorc-variations', 'Variações', 'dorc_add_meta_box_variations_callback', 'dorc-products', 'normal', 'high');
}

function dorc_add_meta_box_variations_callback($post)
{
    $data = dorc_get_product_variations($post->ID);
    $variations = dorc_get_products_variations($post->ID);

    wp_nonce_field('product_variation_meta_box_nonce', 'product_variation_meta_box_nonce'); ?>

    <div id="import-variations">
        <?php if( !empty($variations) ) :?>
        <label>
            <?php _e('Importar variações de outro produto:'); ?>
            <p><i><?php _e('Para selecionar mais de um item segure a tecla CTRL e clique no item desejado. Clique sobre o título do produto para importa todas as variações dele.') ?></i></p>
            <select id="variations" name="variations" multiple>                
                <?php foreach($variations as $value) :
                    $vars = maybe_unserialize($value->meta_value); ?>
                    <option class="option-parent" value="<?php echo htmlentities(json_encode($vars, JSON_UNESCAPED_UNICODE)) ?>">
                        <?php echo $value->ID .' - '. $value->post_title; ?>
                    </option>                    
                    <?php foreach($vars as $k => $v): ?>
                        <option class="option-children" value="<?php echo htmlentities(json_encode($v, JSON_UNESCAPED_UNICODE)) ?>" >
                            <?php echo $v['name'] . ' ( ' . preg_replace('/\r\n/', ',', $v['variations']) .' ) '?>
                        </option>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </select>
        </label>        
        <a href="#" class="button button-secondary" >
            <?php _e('Import'); ?>
        </a>        
        <?php endif; ?>
    </div>

    <div id="wrapper-variations">
        <?php dorc_products_variations_header(); ?>

        <div class="variation-items variation-rows variation-sortable">
            <?php if( $data ) {
                foreach ($data as $key => $value) {
                   printf(dorc_products_variation_row($value), $key, $key, $key);
                }
            } ?>                
        </div>

        <?php dorc_products_variations_header('append'); ?>        

    </div>

    <style>
        #wrapper-variations{}
        
        #wrapper-variations .variation-row-header { background-color: #eee;}

        #wrapper-variations .variation-row { display: flex; padding: 5px 0; align-items: center;}        
        #wrapper-variations .variation-row > div { flex: 1; padding: 5px;}        
        
        #wrapper-variations .variation-row .variation-action,
        #wrapper-variations .variation-row .variation-sortable-area {width: 40px; flex: 0 0 40px; text-align: center; }
        #wrapper-variations .variation-item .variation-sortable-area:hover {
            cursor: move;
        }

        #wrapper-variations .variation-row {align-items: center; border: 1px solid #eee; margin-top: 10px;}        
        #wrapper-variations .variation-row input,
        #wrapper-variations .variation-row textarea { width: 100%; min-height: 60px; background-color: #eee; }
        #wrapper-variations .variation-row input:focus,
        #wrapper-variations .variation-row textarea:focus { background-color: white; }
        .variation-remove,
        .variation-remove:hover { color: #900; text-decoration: none; }

        #import-variations .option-parent {font-weight: bold !important;}
        #import-variations .option-children { padding-left: 15px !important; font-style: italic !important; }

        #variations { width: 100%; }
    </style>

    <script>
        $(document).ready(function(){

            var layout = '<?php echo dorc_products_variation_row(); ?>';
            var container_variations = $('.variation-items');

            $('.variation-sortable').sortable({
                axis: "y",
                cancel: '.variation-name,.variation-values,.variation-action'
            });

            //REMOVE ITEM
            $('body').on('click', '.variation-remove', function(e){
                e.preventDefault();
                $(this).parents('.variation-item').slideUp('fast', function(){
                    $(this).remove();
                });
            })

            //ADD NEW ITEM
            $('.variation-add').on('click', function(e){
                e.preventDefault();
                var append = $(this).data('position');                

                var ly = layout.replace(/\%s/g, variation_next_id()).replace('%name', '').replace('%variations', '');                

                if( append == 'append' ) {
                    container_variations.append(ly);
                } else {
                    container_variations.prepend(ly);
                }
            });

            //IMPORT VARIATIONS
            $('#import-variations a').click(function(e){
                e.preventDefault();
                var $values = $('select#variations').val();
                $values.map(function(value, index){
                    var item = JSON.parse(value);
                    if( 'name' in item ) {
                        insert_new_variation(item, layout, container_variations);
                    } else {
                        Object.keys(item).map(function(k) {
                            insert_new_variation(item[k], layout, container_variations);
                        });
                    }
                });
            })
        })

        function insert_new_variation(item, layout, container) {            
            var ly = layout;
            Object.keys(item).map(function(k){                
                var reg = new RegExp('(\%'+k+')','g');   
                //console.log(reg);
                ly = ly.replace(reg, item[k]);
            });

            container.prepend(ly.replace(/%s/g, variation_next_id()));
        }

        function variation_next_id() {
            var next_id = 0;                
            $('.variation_key').each(function(i, e){
                var value = Number(e.value);
                if( value > next_id) {
                    next_id = value
                }                    
            });

            next_id++;

            return next_id;
        }
        
    </script>

<?php }

/**
 * SAVE CUSTOM METABOXES DATA
 */
add_action('save_post', 'dorc_meta_box_slide_product_images_save');
function dorc_meta_box_slide_product_images_save($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (!isset($_POST['meta_box_nonce']) || !wp_verify_nonce($_POST['meta_box_nonce'], 'slide_meta_box_nonce')) return;

    if (!current_user_can('edit_post')) return;

    $slide = filter_input(INPUT_POST, 'dorc_slide', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

    update_post_meta($post_id, 'dorc_slide', $slide);
}

add_action('save_post', 'dorc_meta_box_product_variation_save');
function dorc_meta_box_product_variation_save($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (!isset($_POST['meta_box_nonce']) || !wp_verify_nonce($_POST['product_variation_meta_box_nonce'], 'product_variation_meta_box_nonce')) return;

    if (!current_user_can('edit_post')) return;

    $dorc_product_variations = filter_input(INPUT_POST, 'dorc_product_variations', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

    update_post_meta($post_id, 'dorc_product_variations', $dorc_product_variations);
}

// Template fragments
function dorc_products_variations_header($action = 'prepend'){ ?> 
<div class="variation-row-header variation-row">
    <div class="variation-sortable-area" title="<?php _e('Arraste as variações para reordenar', 'dorc'); ?>" >
        <span class="dashicons dashicons-menu"></span>
    </div>
    <div class="variation-name" >
        <b><?php _e('Nome') ?></b><br>
        <i><?php _e('Nome da variação.') ?></i>
    </div>
    <div class="variation-values">
        <b><?php _e('Valores', 'dorc') ?></b><br>
        <i><?php _e('Um por linha') ?></i>
    </div>
    <div class="variation-action">
        <a href="#" class="variation-add" data-position="<?php echo $action; ?>" >
            <i class="dashicons dashicons-plus"></i>
        </a>
    </div>
</div>
<?php }

function dorc_products_variation_row($data = array())
{
$html = '<div class="variation-row variation-item">';
    $html .= '<div class="variation-sortable-area" title="'.__('Arraste para reordenar', 'dorc').'" >';
        $html .= '<span class="dashicons dashicons-menu"></span>';
    $html .= '</div>';
    $html .= '<div class="variation-name">';
        $html .= '<input name="dorc_product_variations[%s][name]" type="text" value="'. (isset($data['name']) ? $data['name'] : '%name') .'" placeholder="Nome da variação" />';
        $html .= '<input type="hidden" class="variation_key" value="%s" >';
    $html .= '</div>';
    $html .= '<div class="variation-values">';
        $html .= '<textarea name="dorc_product_variations[%s][variations]" type="text" placeholder="Valores da variação" >'. (isset($data['variations']) ? $data['variations'] : '%variations') .'</textarea>';
    $html .= '</div>';
    $html .= '<div class="variation-action">';
        $html .= '<a href="#" class="variation-remove">';
            $html .= '<i class="dashicons dashicons-trash"></i>';
        $html .= '</a>';
    $html .= '</div>';
$html .= '</div>';

return $html;
}