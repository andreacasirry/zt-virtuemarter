function removeCompare(remove_id) {
    jQuery('#compare_cat'+remove_id+' a').removeClass('go_to_compare active');
    jQuery.ajax({
        url: 'index.php?option=com_zt_virtuemarter&view=comparelist&task=removed',
        type: 'post',
        data: 'remove_id=' + remove_id,
        dataType: 'json',
        success: function(json){
            jQuery('.compare_prod_'+remove_id).remove();
            jQuery('#compare_prod_'+remove_id).remove();
            jQuery('.success .successprod_'+remove_id).remove();
            jQuery('.success_compare span').remove();
            jQuery('#system_view .success .success_compare').append('<span class="warning">'+json.rem+'</span>');
            jQuery('.list_compare'+remove_id+' a').removeClass('go_to_compare active');
            if(json.totalrem<1){
                jQuery("#mod_compare .not_text").removeClass('displayNone');
                jQuery("#butseldcomp").addClass('displayNone');
                jQuery(".module-title.compare.no-products").addClass('displayBlock');
                jQuery(".browscompare_list").remove();

            }
            if(json.totalrem){
                jQuery('.btn-compare span').html(json.totalrem);
            }
            if(json.totalrem <1){
                jQuery('.btn-compare span').html('0');
                jQuery('#mod_compare .zt-cart-inner .vmproduct').html('<div class="not_text compare">You have no product to compare.</div>');
            }
            jQuery.fancybox.close();
        }
    });
}

function addToCompare(product_id) {
    jQuery.fancybox.showActivity();
    jQuery.ajax({
        url: 'index.php?option=com_zt_virtuemarter&view=comparelist&task=add',
        type: 'post',
        data: 'product_id=' + product_id,
        dataType: 'json',
        beforeSend: function() {

        },
        success: function(json){
            if(json.img_prod != '') {
                var item_thumb = json.img_prod;
            }else {
                var item_thumb = json.img_prod2;
            }
            var content = item_thumb+json.title+json.btnrem+json.message+json.btncompare+json.btncompareback;
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
            if(json.totalcompare != '') {
                jQuery('.btn-compare span').html(json.totalcompare);
                jQuery('#mod_compare .zt-cart-inner .vmproduct .not_text').remove();
            }
            
            var compare_item = '<div id="compare_prod_'+json.product_ids+'" class="modcompareprod clearfix">'+item_thumb+json.prod_name+'</div><div class="clear"></div>';
            jQuery('#mod_compare .zt-cart-inner .vmproduct').append(compare_item);

            jQuery('#compare_continue').click( jQuery.fancybox.close);

        },
        error: function(jqXHR, textStatus, errorThrown)
        {
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

