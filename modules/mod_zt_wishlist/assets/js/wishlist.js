/**
 * Zena whishlist
 * @param {type} w Windows pointer
 * @param {type} $ jQuery pointer
 * @returns {undefined}
 */
(function (w, $) {

    /**
     * Whishlist local object
     * @type object
     */
    var _whishlist = {
        /* Element selector */
        _elements: {
            itemCount: '.btn-wishlist span'
        },
        /**
         * Init function
         * @returns {undefined}
         */
        _init: function () {
            $('#zo2-left').find('.hover-dropdown').removeClass('hover-dropdown');
            var $iconDropdown = $('body').find('.hover-dropdown').closest('.ajax-dropdown');
            $iconDropdown.hover(
                    function () {
                        $(this).find('.zt-cart-inner').delay(500).slideDown(500);
                    },
                    function () {
                        $(this).find('.zt-cart-inner').slideUp(500);
                    }
            );
        },
        /**
         * Add item to whishlist
         * @param {type} id
         * @returns {undefined}
         */
        add: function (id) {
            var _self = this;
            $.fancybox.showActivity();
            $('body > :first-child').prepend('<div class="overlay"></div><div class="loading"></div>');
            $.ajax({
                url: 'index.php?option=com_ztvirtuemarter&view=wishlist&task=add&product_id=' + id,
                type: 'post',
                data: 'product_id=' + id,
                dataType: 'json',
                success: function (json) {
                    $.fancybox({
                        "titlePosition": "inside",
                        "transitionIn": "fade",
                        "transitionOut": "fade",
                        "changeFade": "fast",
                        "type": "html",
                        "autoCenter": true,
                        "closeBtn": false,
                        "closeClick": false,
                        "content":
                                '<div class="wishlist-product-detail">'
                                + json.img_prod
                                + json.title
                                + json.btnrem
                                + json.message
                                + json.btnwishlists
                                + json.btnwishlistsback
                                + '</div>'
                    });
                    if (json.totalwishlists !== '') {
                        _self.updateItemCount(json.totalwishlists);
                        $('#mod_wishlists .vmproduct .not_text').remove();
                    }
                    if (json.exists != 1) {
                        var wishlist_item = '<div class="modwishlistsprod clearfix" id="wishlists_prod_' + json.product_ids + '">' + json.img_prod + json.prod_name + '</div><div class="clear"></div>';
                        $('#mod_wishlists .vmproduct').append(wishlist_item);
                    }
                    $('#wishlists_continue').click($.fancybox.close);
                }
            });
        },
        /**
         * Remove item from whishlist
         * @param {type} id
         * @returns {undefined}
         */
        remove: function (id) {
            var _self = this;
            $.ajax({
                url: 'index.php?option=com_ztvirtuemarter&view=wishlist&task=removed',
                type: 'post',
                data: 'remove_id=' + id,
                dataType: 'json',
                success: function (json) {
                    $('.count_holder_small'
                            + ', #wishlists_prod_' + id
                            + ', .wishlists_prods_' + id
                            + ', .success .successprod_' + id
                            + ', .success_wishlists span').remove();
                    $('#system_view .success .success_wishlists').append('<span class="warning">' + json.rem + '</span>');
                    $('.list_wishlists' + id + ' a').removeClass('go_to_compare active');
                    if (json.totalrem < 1) {
                        $("#mod_wishlists .not_text").removeClass('displayNone');
                        $("#butseldwish").addClass('displayNone');
                        $(".module-title.wishlists.no-products").addClass('displayBlock');
                        $(".category-wishlist").remove();
                        $('#mod_wishlists .vmproduct').html('<div class="not_text wishlists">You have no product to wishlist.</div>');
                    }
                    _self.updateItemCount(json.totalrem);
                    $.fancybox.close();
                }
            });
        },
        /**
         * Update item count
         * @param {type} totalItem
         * @returns {undefined}
         */
        updateItemCount: function (totalItem) {
            $(this._elements.itemCount).html((totalItem < 1) ? '0' : totalItem);
        }
    };

    /* Append wishlist to zo2 */
    $(document).ready(function () {
        w.zo2.wishlist = _whishlist;
        w.zo2.wishlist._init();
    });

})(window, window.jQuery);