(function ($) {
    var old_style;

    $(document).ready(function () {

        $('.wrap-items .bar a').click(function () {
            var $addClass = $(this).data('style') == 'grid' ? 'grid' : 'list';
            var $removeClass = $(this).data('style') != 'grid' ? 'grid' : 'list';

            $(this).toggleClass('active');
            $(this).siblings('a').removeClass('active');
            $(this).parents('.wrap-items').addClass($addClass).removeClass($removeClass);

            d_orc_change_list_style($addClass);
        });

        // Add Product
        $('.dorc-product-form').submit(function (e) {
            e.preventDefault();
            var form = new FormData(e.target);
            form.set('action', 'dorc_add_to_cart');

            d_orc_submit_form(form, function (res) {
                try {
                    var res = JSON.parse(res)
                } catch (error) {
                    console.error(error)
                }

                if (res.message) {
                    alert(res.message);
                }

                if (res.product_list.variations_html) {
                    $('#dorc-list-variations-cart').html(res.product_list.variations_html);
                }

                if (res.product_list.quantity_total) {
                    $('#quantity-total span').html(res.product_list.quantity_total)
                }
            });
        });

        // Alter Quantity Variation Product
        $('body').on('click', '.dorc-input-quantity', function (e) {
            $(this).prev('button').fadeIn();
        })

        $('body').on('click', '.dorc-change-quant-variation', function (e) {
            e.preventDefault();

            var _self = $(this);
            var input_value = $(this).next('input')
            input_value.val(input_value.val() < 1 ? 1 : input_value.val());

            var data = {
                action: 'dorc_change_variation_from_cart',
                product: $(this).data('prodid'),
                value: $(this).next('input').val()
            }

            if (typeof $(this).data('key') != undefined) {
                data.key = $(this).data('key');
            }

            d_orc_post_ajax(data, function (res) {
                try {
                    var data = JSON.parse(res);
                } catch (error) {
                    return false;
                }

                if ($('#quantity-total span').length) {
                    $('#quantity-total span').html(data.prod_quantity_total);
                } else {
                    _self.parents('.list-group-item').find('.quantity-total').html(data.prod_quantity_total);
                }

                _self.fadeOut();

            });
        })

        // Remove Product
        $('body').on('click', '.dorc-remove-product', function (e) {
            e.preventDefault();
            var data = {
                action: 'dorc_remove_from_cart',
                product: $(this).data('prodid')
            }

            if (typeof $(this).data('key') != undefined) {
                data.key = $(this).data('key');
            }

            d_orc_post_ajax(data, function (res) {
                try {
                    var data = JSON.parse(res);
                } catch (error) {

                }

                if (data.message) {
                    alert(data.message);
                    window.location.reload();
                }

            });
        })

        $('[data-zoom]').each(function (index, ele) {
            $(ele).zoom({
                url: $(ele).data('zoom')
            });
        })


        // Init single slide show
        $('[data-dorc=slick]').slick({
            dots: true,
            arrows: false,
            adaptiveHeight: true,
        });
    });

    function d_orc_change_list_style(style) {
        if (old_style == style) {
            return;
        }
        var data = {
            'action': 'dorc_change_list_style',
            'style': style
        }
        $.post(ajax_object.ajax_url, data, function (response) { });
        old_style = style;
    }

    function d_orc_submit_form(data, callback) {
        $.ajax({
            url: ajax_object.ajax_url,
            data: data,
            processData: false,
            contentType: false,
            type: 'POST'
        }).done(function (res) {
            callback(res)
        });
    }

    function d_orc_post_ajax(data, callback) {
        $.post(ajax_object.ajax_url, data, function (response) {
            callback(response);
        });
    }

})(jQuery);
