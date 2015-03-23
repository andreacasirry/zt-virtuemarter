/**
 * Zena compare
 * @param {type} w Windows pointer
 * @param {type} $ jQuery pointer
 * @returns {undefined}
 */
(function (w, $) {

    /**
     * Compare local object
     * @type object
     */
    var _compare = {
        /* Element selector */
        _elements: {
            itemCount: '.btn-compare span'
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
         * Add item to compare
         * @param {type} id
         * @returns {undefined}
         */
        add: function (id) {
            var _self = this;
            $.fancybox.showActivity();
            $.ajax({
                url: 'index.php?option=com_ztvirtuemarter&view=comparelist&task=add&product_id=' + id,
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
                            '<div class="compare-product-detail">'
                                + json.img_prod
                                + json.title
                                + json.btnrem
                                + json.message
                                + json.btncompare
                                + json.btncompareback
                                + '</div>'
                    });
                    if (json.totalcompare !== '') {
                        $('#mod_compare .zt-cart-inner .vmproduct .not_text').remove();
                    }
                    if (json.exists != 1) {
                        var compare_item = '<div id="compare_prod_' + json.product_ids + '" class="modcompareprod clearfix">' + json.img_prod + json.prod_name + '</div><div class="clear"></div>';
                        $('#mod_compare .zt-cart-inner .vmproduct').append(compare_item);
                    }
                    _self.updateItemCount(json.totalcompare);
                    $('#compare_continue').click($.fancybox.close);
                }

            });
        },
        /**
         * Remove item from compare
         * @param {type} id
         * @returns {undefined}
         */
        remove: function (id) {
            var _self = this;
            $('#compare_cat' + id + ' a').removeClass('go_to_compare active');
            $.ajax({
                url: 'index.php?option=com_ztvirtuemarter&view=comparelist&task=removed',
                type: 'post',
                data: 'remove_id=' + id,
                dataType: 'json',
                success: function (json) {
                    $('.compare_prod_' + id
                            + ', #compare_prod_' + id
                            + ', .success .successprod_' + id
                            + ', .success_compare span').remove();
                    $('#system_view .success .success_compare').append('<span class="warning">' + json.rem + '</span>');
                    $('.list_compare' + id + ' a').removeClass('go_to_compare active');
                    if (json.totalrem < 1) {
                        $("#mod_compare .not_text").removeClass('displayNone');
                        $("#butseldcomp").addClass('displayNone');
                        $(".module-title.compare.no-products").addClass('displayBlock');
                        $(".browscompare_list").remove();
                        $('#mod_compare .zt-cart-inner .vmproduct').html('<div class="not_text compare">You have no product to compare.</div>');
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
        w.zo2.compare = _compare;
        w.zo2.compare._init();
    });

})(window, window.jQuery);
