function genderCartItem() {
    var mod = jQuery(".vmCartModule");
    jQuery.getJSON(window.vmSiteurl + "index.php?option=com_virtuemart&nosef=1&view=cart&task=viewJS&format=json" + window.vmLang,
        function (datas, textStatus) {
            mod.find(".vm_cart_products .content-top").html("");

            jQuery('#zt_top_cart .total-price').html(datas.billTotal.replace('Total', ''));
            jQuery('#cart .total-item').html('(' + datas.totalProduct + ')items - ');

            var cart_item = '<p class="add-product">Recently added item(s)</p>';
            jQuery('.vm_cart_products .content-top').append(cart_item);
            jQuery.each(datas.products, function (key, product) {
                var item_quantity = '';
                if (product.quantity > 1) {
                    item_quantity = '(x' + product.quantity + ')';
                }

                var cart_item = '<div class="ajax-cart-product cart-row cart-row-item-' + key + ' clearfix">';
                cart_item += '<div class="cart-product-img">';
                cart_item += product.image;
                cart_item += '</div>';
                cart_item += '<div class="cart-product-detail">';
                cart_item += '<h4>' + product.product_name + item_quantity + '</h4>';
                cart_item += '<p class="product-price">' + product.subtotal_with_tax + '</p>';
                cart_item += '<a href="#" onclick="ajaxRemoveItemCart(' + key + ')" class="cart-ajax-del" data-type="cart-view"><i class="fa fa-times"></i>Remove</a>';
                cart_item += '</div>';
                cart_item += '</div>';
                jQuery('.vm_cart_products .content-top').append(cart_item);

            });
            if (datas.totalProduct > 0) {
                jQuery('.cart-header-info').show();
            } else {
                mod.find(".vm_cart_products .content-top").html("<p>You have no items in your shopping cart.</p>");
                jQuery('.cart-header-info').hide();
            }

            mod.find(".show_cart").html(datas.cart_show);
            mod.find(".total_products").html(datas.totalProductTxt);
            mod.find(".total").html(datas.billTotal);
        }
    );
}

function ajaxRemoveItemCart(cart_id) {
    var base_url = jQuery('#base_url').val();
    var container = jQuery('#zt_top_cart .zt-cart-inner .cart-row-item-' + cart_id);
    container.addClass('add-position');
    jQuery.ajax({
        type: 'post',
        url: base_url + 'index.php?option=com_virtuemart&view=cart&task=delete',
        data: 'cart_virtuemart_product_id=' + cart_id,
        success: function (html) {
            genderCartItem();
            container.removeClass('add-position');
        }
    });
}