<?php 

/**
 * Retrive all products variations,cols retrive: post ID, post_title and meta_value
 * @param int $post_id if specified the product will be excluded from the query
 */
function dorc_get_products_variations($post_id = null)
{
    global $wpdb;
    $result = $wpdb->get_results( $wpdb->prepare("SELECT p.ID, p.post_title, m.meta_value from {$wpdb->posts} as p 
    LEFT JOIN {$wpdb->postmeta} as m on p.ID=m.post_id where p.post_type='dorc-products'
    AND m.meta_key='dorc_product_variations' AND p.ID != %s GROUP BY p.ID ORDER BY p.post_title ASC", $post_id),
    OBJECT);

    return $result;
}

/**
 * Retrive variations for current product or specific post_id
 */
function dorc_get_product_variations($post_id = null) 
{
    if( $post_id == null ) {
        global $post;
        $post_id = $post->ID;
    }

    return get_post_meta($post_id, 'dorc_product_variations', true);

}

/**
 * Retrieve Query with all products relations from specific product
 * @return WP_Query|WP_Error
 */
function dorc_get_product_relations($post_id = null, $posts_per_page = 3)
{
    if( $post_id == null ) {
        global $post;
        $post_id = $post->ID;
    }

    $tax = 'dorc-product-categories';

    $terms = wp_get_post_terms($post_id, $tax, array('fields' => 'ids'));

    $query = array(
        'post_type' => 'dorc-products',
        'posts_per_page' => $posts_per_page,        
        'tax_query' => array(
            array(
                'taxonomy' => $tax,
                'field' => 'term_id',
                'terms' => $terms
            )
        )
    );

    return new WP_Query($query);
}

/**
 * Get all products from cart
 * @return mixed
 */
function dorc_get_cart_products() {
    return isset($_SESSION['dorc-products']) ? $_SESSION['dorc-products'] : array();
}

function dorc_set_cart_product($product_id, $product_data)
{
    $_SESSION['dorc-products'][$product_id] = $product_data;
}

/**
 * Get single product by ID from cart
 * @return mixed
 */
function dorc_get_cart_product($product_id = null) {
    return isset($_SESSION['dorc-products'][$product_id]) ? $_SESSION['dorc-products'][$product_id] : array();
}

function dorc_add_to_cart($product = array())
{
    if(empty($product)) {
        return false;
    }

    $sucess_message = __('Adicionado com sucesso!', 'dorc');
    $modified_message = __('Alteração realizada com sucesso!');

    $product_ID = $product['_ID'];
    $product_title = $product['_title'];
    $product['_quantity'] = $product['_quantity'] ?? 1;
    unset($product['_ID']);
    unset($product['_title']);

    //$_SESSION['dorc-products'] = [];   

    // Recuperando o produto da sessão
    $session_product = dorc_get_cart_product($product_ID);    

    if(empty($session_product)) {        

        $_product = array(
            'title' => $product_title,            
            'quantity_total' => $product['_quantity'],
        );

        if(count($product) > 1) {
            $_product['variations'] = array( $product );
        }

        dorc_set_cart_product($product_ID, $_product);

        return [
            'message' => $sucess_message,
            'product_list' => $_SESSION['dorc-products'][$product_ID]
        ];
    }
    
    $count_quantify = 0;
    $modify = false;

    // Verificando se ja existe a variação dentro do produto
    if(isset($session_product['variations'])) {
        foreach( $session_product['variations'] as $k => $v ) {
            $diff = array_diff_assoc($product, $v);
    
            // Verificando se a variação é igual a nova variação se sim ele substitui
            if( count($diff) == 1 && isset($diff['_quantity']) || count($diff) == 0) {
                $session_product['variations'][$k] = $product;
                $count_quantify += (int) $diff['_quantity'];
                $sucess_message = $modified_message;
                $modify = true;
                continue;
            }
    
            $count_quantify += (int) $v['_quantity'];
        }
    }

    if( !$modify ) {        
        $count_quantify += (int) $product['_quantity'];        
    }

    if(isset($session_product['variations']) && !$modify) {
        $session_product['variations'][] = $product;        
    }

    $session_product['quantity_total'] = $count_quantify;
    dorc_set_cart_product($product_ID, $session_product);
    
    return array(
        'message' => $sucess_message,
        'product_list' => $session_product
    );
}

function dorc_loop_variation_cart_html($variation, $key, $product_id) 
{
    $html = '<li>';

    foreach ($variation as $k => $v) {
        if($k !== '_quantity') {
            $html .= '<span><b>'. $k .': </b> '. $v .'</span>';
        } else {
            $html .= '<button class="dorc-change-quant-variation btn button btn-default button-primary" style="display: none" data-prodid="'. $product_id .'" data-key="'. $key .'" title="'. __('Save') .'" ><i class="dashicons dashicons-yes"></i></button>';
            $html .= '<input class="dorc-input-quantity" type="number" title="'. __('Quantidade') .'" value="'. $v .'" min="1" autocomplete="off"/>';
        }
    }
    $html .= '<a href="#" class="dorc-remove-variation" data-prodid="'. $product_id .'" data-key="'. $key .'" ><span class="dashicons dashicons-trash"></span></a>';
    $html .= '</li>';

    return $html;
}

//////////// TEMPLATE HELPERS
function dorc_get_product_html_actions($post_id = null)
{
    $post_id = $post_id ?? get_the_ID();

    $product_variations = dorc_get_product_variations();
    
?>
<div class="dorc-product-action">
    <?php 
        $from_cart = dorc_get_cart_product(get_the_ID());
        $variations_from_cart = isset($from_cart['variations']) ? $from_cart['variations'] : [];
        $total_quant = isset($from_cart['quantity_total']) ? $from_cart['quantity_total'] : []; 
        
    if( $product_variations ): ?>
    <h4>Variações</h4>
    <ul id="dorc-list-variations-cart" class="dorc-list-variations-cart" >
    <?php foreach ($variations_from_cart as $key => $variation) : ?>
        <?php echo dorc_loop_variation_cart_html($variation, $key, $post_id, $product_variations) ?>
    <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <div id="quantity-total">
        <?php _e('Quantidade total: ') ?><span><?php echo $total_quant ?: 0; ?></span>
    </div>
    
    <form class="dorc-product-form">        
    <?php if( !empty( $product_variations ) ) : 
        foreach ($product_variations as $key => $variation) : $variations_from_cart = explode(PHP_EOL, $variation['variations']) ?>
        <div>
            <label>
                <b><?php echo $variation['name']; ?>: </b>
                <select name="product[<?php echo $variation['name'] ?>]" id="var_<?php echo $key; ?>">
                <?php foreach($variations_from_cart as $k => $v): $v = trim($v); ?>
                    <option value="<?php echo $v; ?>"><?php echo $v ?></option>
                <?php endforeach; ?>
                </select>
            </label>
        </div>
    <?php endforeach; ?>
    <?php endif; ?>
        <div>
            <label>
                <b><?php _e('Quantidade:', 'dorc') ?></b>
                <input name="product[_quantity]" type="number" value="1" min="1" >
            </label>
        </div>
        <div>
            <input type="hidden" name="product[_title]" value="<?php echo get_the_title(); ?>" >         
            <input type="hidden" name="product[_ID]" value="<?php echo $post_id; ?>" >         
            <button type="submit" class="button button-secondary btn-default btn" >
                <i class="fa fa-cart-plus"></i> <?php _e('Adicionar ao Orçamento'); ?>
            </button>          
        </div>
    </form>
</div>  
<?php }

function dorc_get_product_html_images()
{ ?>
    <div class="dorc-images" data-dorc="slick" >

    <?php if(has_post_thumbnail() ) : ?>
        <?php the_post_thumbnail( 'medium' ); ?>
    <?php endif; ?>

    <?php 
        $slide = get_post_meta(get_the_Id(), 'dorc_slide', true);
        if( $slide ) : ?>

        <?php foreach ($slide as $key => $url): ?>
            <img src="<?php echo $url; ?>" alt="">
        <?php endforeach; ?>       
        
        <?php endif; ?>
    </div> 
<?php }