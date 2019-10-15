<?php 

class Dorc_Orc_Products_Widget extends WP_Widget
{
    public function __construct()
    {
        $args = array(
            'classname' => 'dorc-products-widget',
            'description' => __('Lista de produto de "Produtos e Orçamentos"', 'dorc')
        );
        parent::__construct('dorc-products-widget', __('Lista de Produtos', 'dorc'), $args);
    }

    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $categories = !empty($instance['categories']) ? $instance['categories'] : [];
        $products_per_page = !empty($instance['products_per_page']) ? $instance['products_per_page'] : 10;
        $order = !empty($instance['order']) ? $instance['order'] : 'ASC';
        $terms = get_terms('dorc-product-categories', array('hide_empty' => false));

        $output = '<p>';
        $output .= '<label for="' . esc_attr($this->get_field_id('title')) . '">'. __('Title') .'</label>';
        $output .= '<input class="widefat" id="' . esc_attr($this->get_field_id('title')) . '" name="' . esc_attr($this->get_field_name('title')) . '" type="text" value="' . esc_attr($title) . '">';
        $output .= '</p>';

        $output .= '<p>';
        $output .= '<label title="'. __('Categories') .'" for="' . esc_attr($this->get_field_id('categories')) . '">'. __('Categories') .'</label>';
        $output .= '<select class="widefat" id="' . esc_attr($this->get_field_id('categories')) . '" name="' . esc_attr($this->get_field_name('categories')) . '[]" size="10" multiple="" autocomplete="off">';
            foreach( $terms as $term ) {
                //echo $term->term_id;
                $output .= '<option value="'. $term->term_id .'" '. ( in_array($term->term_id, $categories) ? "selected" : '' ) .' >'. $term->name .'</option>';
            }
        $output .= '</select>';             
        $output .= '<i>'. __('Deixe vazio para mostrar produtos de todas as categorias.', 'dorc') .'</i>';
        $output .= '</p>';
        $output .= '<p>';
        $output .= '<i>'. __('Para selecionar varias categorias matenha a tecla CTRL pressionada e clique sobre a categoria desejada.', 'dorc') .'</i>';        
        $output .= '</p>';
        
        $output .= '<p>';
        $output .= '<label for="' . esc_attr($this->get_field_id('products_per_page')) . '">'. __('Quantidade', 'dorc') .'</label>';
        $output .= '<input class="widefat" id="' . esc_attr($this->get_field_id('products_per_page')) . '" name="' . esc_attr($this->get_field_name('products_per_page')) . '" min="-1" type="number" value="' . esc_attr($products_per_page) . '" autocomplete="off">';
        $output .= '<i>'. __('Para mostrar todos os produtos deixei o valor com: -1', 'dorc') .'</i>';
        $output .= '</p>';

        $output .= '<p>';
        $output .= '<label title="'. __('Ordenação', 'dorc') .'" for="' . esc_attr($this->get_field_id('order')) . '">'. __('Ordenação', 'dorc') .'</label>';
        $output .= '<select class="widefat" id="' . esc_attr($this->get_field_id('order')) . '" name="' . esc_attr($this->get_field_name('order')) . '" autocomplete="off">';
            $output .= '<option value="ASC" '. ( 'ASC' == $order ? "selected" : '' ) .' >'. __('Ascending') .'</option>';
            $output .= '<option value="DESC" '. ( 'DESC' == $order ? "selected" : '' ) .' >'. __('Descending') .'</option>';   
            $output .= '<option value="RAND" '. ( 'RAND' == $order ? "selected" : '' ) .' >'. __('Random') .'</option>';
        $output .= '</select>'; 
        $output .= '</p>';

        $output .= '<p>';
        $output .= '<label title="'. __('Visualização','dorc') .'" for="' . esc_attr($this->get_field_id('view')) . '">'. __('Visualização', 'dorc') .'</label>';
        $output .= '<select class="widefat" id="' . esc_attr($this->get_field_id('view')) . '" name="' . esc_attr($this->get_field_name('view')) . '" autocomplete="off">';
            $output .= '<option value="grid" '. ( 'grid' == $order ? "selected" : '' ) .' >'. __('Grade', 'dorc') .'</option>';
            $output .= '<option value="list" '. ( 'list' == $order ? "selected" : '' ) .' >'. __('List') .'</option>';               
        $output .= '</select>'; 
        $output .= '</p>';

        echo $output;
    }

    public function widget($args, $instance)
    {
        $categories = $instance['categories'];
        $posts_per_page = $instance['products_per_page'];
        $order = $instance['order'];
        $view = $instance['view'];

        echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
        echo do_shortcode('[dorc-list-products cats="'. implode(',', $categories) .'" posts_per_page="'. $posts_per_page .'" order="'. $order .'" view="'. $view .'"]');
        echo $args['after_widget'];
    }
}
add_action('widgets_init', function(){
    register_widget('Dorc_Orc_Products_Widget');
});