(function($){    
    var old_style;

    $(document).ready(function(){

        $('.wrap-items .bar a').click(function(){            
            var $addClass = $(this).data('style') == 'grid' ? 'grid' : 'list';
            var $removeClass = $(this).data('style') != 'grid' ? 'grid' : 'list';

            $(this).toggleClass('active');
            $(this).siblings('a').removeClass('active');                        
            $(this).parents('.wrap-items').addClass($addClass).removeClass($removeClass);            

            d_orc_change_list_style($addClass);
        });

        $('.dorc-product-action .actions .dropdown-toggle').click(function(e){
            e.preventDefault();
            $(this).siblings('.dropdown-action').toggleClass('open');
        });

        $('.dorc-product-action .plus').click(function(e){
            e.preventDefault();
             var quant = parseInt($(this).parents('.actions').find('[name=quant]').val());             
             $(this).parents('.actions').find('[name=quant]').val(quant+1);
        });

        $('.dorc-product-action .minus').click(function(e){
             e.preventDefault();
             var quant = parseInt($(this).parents('.actions').find('[name=quant]').val());
             if(quant > 1){
                $(this).parents('.actions').find('[name=quant]').val(quant-1);
             }
        });

        $('.dorc-product-action .remove').click(function(e){
             e.preventDefault();
             var self = $(this);
             var data = {
                'action' : 'dorc_remove_to_cart',
                'item' : $(this).parents('.actions').find('[name=item]').val()                
            };

            d_orc_remove_to_cart(data, function(res){
                var response = JSON.parse(res);
                var content_message = self.parents('.actions').find('.message');
                if(content_message.is(':visible')){
                    content_message.fadeOut();
                }
                content_message.fadeIn('fast', function(){
                    $(this).html(response.message).delay(1500).fadeOut('fast', function(){
                        $(this).html('');
                        if(!response.count){
                            self.parents('form').find('[type=submit]').attr('disabled','disabled');
                        }
                        self.parents('.actions').parent().remove();
                    });
                });
            });
        });

        $('.dorc-product-action .add').click(function(e){
            e.preventDefault();

            $(this).text('Alterar');            

            var self = $(this);            
            var data = {
                'action' : 'dorc_add_to_cart',
                'item' : $(this).parents('.actions').find('[name=item]').val(),
                'title' : $(this).parents('.actions').find('[name=title]').val(),
                'quant' : $(this).parents('.actions').find('[name=quant]').val(),
            };
            
            d_orc_add_to_cart(data, function(res){
                var response = JSON.parse(res);
                var content_message = self.parents('.actions').find('.message');
                if(content_message.is(':visible')){
                    content_message.fadeOut();
                }
                content_message.fadeIn('fast', function(){
                    $(this).html(response.message).delay(3000).fadeOut('fast', function(){
                        $(this).html('');
                    });
                });
            });
        });

        $('[data-zoom]').each(function(index, ele) {            
            $(ele).zoom({
                url: $(ele).data('zoom')
            });
        })
    });

    function d_orc_change_list_style(style){
        if(old_style == style){
            return;
        }
        var data = {
            'action' : 'dorc_change_list_style',
            'style' : style
        }
        $.post(ajax_object.ajax_url, data, function(response){});
        old_style = style;
    }

    function d_orc_add_to_cart(data, callback){               
        $.post(ajax_object.ajax_url, data, function(response){
            callback(response);
        });
    }   
    
    function d_orc_remove_to_cart(data, callback){               
        $.post(ajax_object.ajax_url, data, function(response){
            callback(response);
        });
    }  

})(jQuery);
