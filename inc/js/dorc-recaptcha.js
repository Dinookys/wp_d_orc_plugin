(function($){
    $(document).ready(function(){

        $('form').submit(function(e){

            if($(this).find('.g-recaptcha')){

                var verified = grecaptcha.getResponse();                   

                if(verified.length === 0){                
                    e.preventDefault();
                    if($(this).parent().find('.not-checked').length == 0){
                        $(this).parent().prepend('<p class="alert alert-warning not-checked" >É necessário marcar o reCapatcha para continuar.</p>');
                    }
                    return;
                }else{
                   $(this).parent().find('.not-checked').remove();
                }     
            }
        });
    });
})(jQuery)