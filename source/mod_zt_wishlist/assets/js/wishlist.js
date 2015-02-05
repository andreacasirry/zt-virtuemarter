function removeWishlists(remove_id) {
    jQuery.ajax({
        url: 'index.php?option=com_virtuemartzooex&view=wishlists&task=removed',
        type: 'post',
        data: 'remove_id=' + remove_id,
        dataType: 'json',
        success: function(json){
            jQuery('.count_holder_small').remove();
            jQuery('#wishlists_prod_'+remove_id).remove();
            jQuery('.wishlists_prods_'+remove_id).remove();
            jQuery('.success .successprod_'+remove_id).remove();
            jQuery('.success_wishlists span').remove();
            jQuery('#system_view .success .success_wishlists').append('<span class="warning">'+json.rem+'</span>');
            jQuery('.list_wishlists'+remove_id+' a').removeClass('go_to_compare active');
            if(json.totalrem<1){
                jQuery("#mod_wishlists .not_text").removeClass('displayNone');
                jQuery("#butseldwish").addClass('displayNone');
                jQuery(".module-title.wishlists.no-products").addClass('displayBlock');
                jQuery(".category-wishlist").remove();

            }
            if(json.totalrem){
                jQuery('.btn-wishlist span').html(json.totalrem);
            }
            if(json.totalrem<1){
                jQuery('.btn-wishlist span').html('0');
                jQuery('#mod_wishlists .vmproduct').html('<div class="not_text wishlists">You have no product to wishlist.</div>');
            }
            jQuery.fancybox.close();
        }
    });
}

function addToWishlists(product_id) {
    jQuery.fancybox.showActivity();
    jQuery('body > :first-child').prepend('<div class="overlay"></div><div class="loading"></div>');
    jQuery.ajax({
        url: 'index.php?option=com_virtuemartzooex&view=wishlists&task=add',
        type: 'post',
        data: 'product_id=' + product_id,
        dataType: 'json',
        success: function(json){
            if(json.img_prod != '') {
                var image = json.img_prod;
            }else {
                var image = json.img_prod2;
            }
            var content =
                 image
                +json.title
                +json.btnrem
                +json.message
                +json.btnwishlists

                +json.btnwishlistsback;
            jQuery.fancybox({
                    "titlePosition" : 	"inside",
                    "transitionIn"	:	"fade",
                    "transitionOut"	:	"fade",
                    "changeFade"    :   "fast",
                    "type"			:	"html",
                    "autoCenter"    :   true,
                    "closeBtn"      :   false,
                    "closeClick"    :   false,
                    "content"       :   content
                }
            );
            if(json.totalwishlists != '') {
                jQuery('.wishlist_total span').html(json.totalwishlists);
                jQuery('#mod_wishlists .vmproduct .not_text').remove();
            }

            if(json.img_prod != '' && json.prod_name != ''){
                var wishlist_item = '<div class="modwishlistsprod clearfix" id="wishlists_prod_'+json.product_ids+'">'+json.img_prod+json.prod_name+'</div><div class="clear"></div>';
                jQuery('#mod_wishlists .vmproduct').append(wishlist_item);
            }

            jQuery('.btn-wishlist span').html(json.totalwishlists);
            jQuery('#wishlists_continue').click( jQuery.fancybox.close);
        }
    });
}

jQuery(document).ready(function(){
    jQuery('#zo2-left').find('.hover-dropdown').removeClass('hover-dropdown');
    var iconDropdown = jQuery('body').find('.hover-dropdown').parent().parent().parent();
    jQuery(iconDropdown).hover(
        function (){
            jQuery(this).find('.zt-cart-inner').delay(500).slideDown(500);
        }, function (){
            jQuery(this).find('.zt-cart-inner').slideUp(500);
        }
    );
});