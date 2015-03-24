/**
 * Zena ajax cart
 * @param {type} w Windows pointer
 * @param {type} $ jQuery pointer
 * @returns {undefined}
 */
(function (w, $) {

    /**
     * Opencart local object
     * @type object
     */
    var _cart = {
        /**
         * Generate
         * @returns {undefined}
         */
        generate: function () {
            var mod = $(".vmCartModule");
            $.getJSON(window.vmSiteurl + "index.php?option=com_virtuemart&nosef=1&view=cart&task=viewJS&format=json" + window.vmLang,
                    function (datas, textStatus) {
                        mod.find(".vm_cart_products .content-top").html("");

                        $('#zt_top_cart .total-price').html(datas.billTotal.replace('Total', ''));
                        $('#cart .total-item').html('(' + datas.totalProduct + ')items - ');

                        var cartItem = '<p class="add-product">Recently added item(s)</p>';
                        $('.vm_cart_products .content-top').append(cartItem);
                        $.each(datas.products, function (key, product) {
                            var item_quantity = (product.quantity > 1) ? '(x' + product.quantity + ')' : '';
                            cartItem = '<div class="ajax-cart-product cart-row cart-row-item-' + key + ' clearfix">';
                            cartItem += '<div class="cart-product-img">';
                            cartItem += product.image;
                            cartItem += '</div>';
                            cartItem += '<div class="cart-product-detail">';
                            cartItem += '<h4>' + product.product_name + item_quantity + '</h4>';
                            cartItem += '<p class="product-price">' + product.subtotal_with_tax + '</p>';
                            cartItem += '<a href="#" onclick="zo2.cart.remove(' + key + ')" class="cart-ajax-del" data-type="cart-view"><i class="fa fa-times"></i>Remove</a>';
                            cartItem += '</div>';
                            cartItem += '</div>';
                            $('.vm_cart_products .content-top').append(cartItem);

                        });
                        if (datas.totalProduct > 0) {
                            $('.cart-header-info').show();
                        } else {
                            mod.find(".vm_cart_products .content-top").html("<p>You have no items in your shopping cart.</p>");
                            $('.cart-header-info').hide();
                        }

                        mod.find(".show_cart").html(datas.cart_show);
                        mod.find(".total_products").html(datas.totalProductTxt);
                        mod.find(".total").html(datas.billTotal);
                    }
            );
        },
        /**
         * Remove item from cart
         * @param {type} cart_id
         * @returns {undefined}
         */
        remove: function (cart_id) {
            var base_url = $('#base_url').val();
            var container = $('#zt_top_cart .zt-cart-inner .cart-row-item-' + cart_id);
            container.addClass('add-position');
            $.ajax({
                type: 'post',
                url: base_url + 'index.php?option=com_virtuemart&view=cart&task=delete',
                data: 'cart_virtuemart_product_id=' + cart_id,
                success: function (html) {
                    zo2.cart.generate();
                    container.removeClass('add-position');
                }
            });
        }
    };

    /* Append cart to zo2 */
    $(document).ready(function () {
        w.zo2.cart = _cart;
    });

    /* add item to cart header */
    $.fn.updateVirtueMartCartModule = function (arg) {
        var options = $.extend({}, $.fn.updateVirtueMartCartModule.defaults, arg);
        return this.each(function () {
            $.ajaxSetup({ cache: false })
            zo2.cart.generate();
        });
    };

    /* Definition Of Defaults */
    $.fn.updateVirtueMartCartModule.defaults = {
        name1: 'value1'
    };
})(window, window.jQuery);
