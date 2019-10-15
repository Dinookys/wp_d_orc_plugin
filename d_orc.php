<?php
/**
 *Plugin Name: Produtos & Orçamentos
 *Author: Media Virtual
 *Author URI: http://mediavirtual.com.br
 *Version: 1.6
 *Description: Um plugin para cadastro de produtos para orçamento. Com categorias e galeria de fotos  e com variação de produto.
 *
 */
if (!session_id()) {
    session_start();
    !isset($_SESSION['dorc-products']) ? $_SESSION['dorc-products'] = array() : '';
    !isset($_SESSION['dorc-list-style']) ? $_SESSION['dorc-list-style'] = 'list' : '';
}

define('DORC_DIR_PATH', plugin_dir_path(__FILE__));
define('DORC_DIR_PATH_SHORTCODES', DORC_DIR_PATH . '/inc/shortcodes');
define('DORC_DIR_URL', plugin_dir_url(__FILE__));

require_once plugin_dir_path(__FILE__) . '/inc/functions.php';
require_once plugin_dir_path(__FILE__) . '/inc/enqueue.php';
require_once plugin_dir_path(__FILE__) . '/inc/post_types.php';
require_once plugin_dir_path(__FILE__) . '/inc/ajax.php';

//ShortCodes
require_once plugin_dir_path(__FILE__) . '/inc/shortcodes/dorc-orc.php';

//Widgets
require_once plugin_dir_path(__FILE__) . '/inc/widgets/widgets.php';

if (is_admin()) {
    require_once plugin_dir_path(__FILE__) . '/inc/admin_function.php';
    require_once plugin_dir_path(__FILE__) . '/inc/meta_boxes.php';
}

if (!is_admin()) {
    require_once plugin_dir_path(__FILE__) . '/inc/add_filter_templates.php';
}

function dorc_pagination()
{
    $links = paginate_links(array(
        'type' => 'array',
        'mid_size' => 5,
    ));

    if (empty($links)) {
        return false;
    }

    $out = '<ul class="pagination pagination-sm" style="display: table; margin: 15px auto 15px 0;" >';

    foreach ($links as $link) {
        if (strpos($link, 'current')) {
            $out .= '<li class="active" >' . $link . '</li>';
        } else {
            $out .= '<li>' . $link . '</li>';
        }

    }

    $out .= '</ul>';

    return $out;
}

function d_orc_init()
{
    register_nav_menus(array(
        'd_orc_menu_sidebar' => 'Menu lateral produtos',
    ));
}
add_action('init', 'd_orc_init');

function d_orc_on_activation_hook()
{  
    d_orc_register_products_post_type();
    d_orc_register_products_taxonomy();
    d_orc_register_orc_post_type();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'd_orc_on_activation_hook');