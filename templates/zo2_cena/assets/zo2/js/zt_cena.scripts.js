

//add item to cart header
;(function (jQuery) {
    jQuery.fn.updateVirtueMartCartModule = function (arg) {
        var options = jQuery.extend({}, jQuery.fn.updateVirtueMartCartModule.defaults, arg);
        return this.each(function () {
            jQuery.ajaxSetup({ cache: false })
            zo2.cart.generate();
        });
    };

    // Definition Of Defaults
    jQuery.fn.updateVirtueMartCartModule.defaults = {
        name1: 'value1'
    };

})(jQuery);

jQuery(document).ready(function($){
    jQuery('.faq_block .panel-heading').eq(0).addClass('active');
    jQuery('.faq_block .panel-heading').click(function(){
        if(jQuery(this).hasClass('active')){
            jQuery(this).removeClass('active');
        }
        else{
            jQuery('.faq_block .panel-heading').removeClass('active');
            jQuery(this).toggleClass('active');
        }
    });

    var contact_form  = jQuery('#contact-form');
    contact_form.bootstrapValidator({
        message: 'This value is not valid',
        fields: {
            "jform[contact_name]": {
                validators: {
                    notEmpty: {
                        message: 'The name is required and can\'t be empty'
                    }
                }
            },
            "jform[contact_email]": {
                validators: {
                    emailAddress: {
                        message: 'The input is not a valid email address'
                    },
                    notEmpty: {
                        message: 'The email is required and can\'t be empty'
                    }
                }
            },
            "jform[contact_subject]": {
                validators: {
                    notEmpty: {
                        message: 'The subject is required and can\'t be empty'
                    }
                }
            },
            "jform[contact_message]": {
               validators: {
                    stringLength: {
                        max: 300,
                        min: 10,
                        message: 'The review must be more than 10 characters and less than 300 characters long'
                    },
                    notEmpty: {
                        message: 'The message is required and can\'t be empty'
                    }
                }

            }
        }
    }).find('button').on('click', function() {
        contact_form.bootstrapValidator('validate' );
        if(jQuery('small[data-bv-result="INVALID"]').length == 0) {
            document.getElementById("contact-form").submit();
        }
    });

    jQuery('#zo2-position-0 .icon-search').click(function(){
        jQuery('.search .search-form').fadeIn('300');
        jQuery('#zo2-top-wrap .search .search-form .inputbox').focus().css("color","#000");
    });
        jQuery('#zo2-position-0 .search-close').click(function(){
        jQuery('.search .search-form').fadeOut('300');
    });

    jQuery('#masonry').imagesLoaded(function() {
        jQuery(this).masonry({
            itemSelector: '.masonry-item',
            columnWidth: 2,
            isAnimated: true,
            layoutPriorities: {
                shelfOrder: 1.21
            }
        });
    });

    $("#zt_list_product").owlCarousel({
        items : 3,
        navigation : true
    });

    $('#myTab a:last').tab('show');

    var id = jQuery('.sticky-wrapper').attr('id');
    if( typeof id == 'undefined'){
        jQuery('#zo2-top-wrap').addClass('no-sticky');
    }

    $('#masonry .items-leading-masonry').imagesLoaded(function() {
        this.masonry({
            itemSelector: '.imasonry',
            columnWidth: 10,
            isAnimated: true,
            layoutPriorities: {
                shelfOrder: 1.21
            }
        });
    });



    jQuery('#zo2-position-0 .zt-cart-inner').hide();
    jQuery("#zo2-position-0 #zt_top_cart, #zo2-position-0 #mod_compare, #zo2-position-0 #mod_wishlists").hover(
        function(){
            jQuery(this).addClass("control-active").find(".zt-cart-inner").slideDown(200);
        },
        function(){
            jQuery(this).removeClass("control-active").find(".zt-cart-inner").slideUp(200);
        }
    );

    if(jQuery('#containt').find('.col-right').length>0){
        jQuery('#containt .col-md-9.containt').addClass('add-position');
    }

   jQuery('#zt_top_cart .cart-ajax-del').click(function(){
        jQuery('#zt_top_cart .cart-row').addClass('add-position');
    });
}(jQuery));

