(function (w, $) {
    if (typeof w.ZtVirtuemarter === 'undefined') {
        var _ZtVirtuemarter = {
            /* Internal jQuery */
            jQuery: $,

            _elements: {
                vm3Product: '.product .spacer, .product-item .spacer'
            },

            /**
             * Init function
             * @returns {undefined}
             */
            _init: function () {
                if($( window ).width() < 768 ) {
                    $('body').addClass('zt-mobile');
                }
                var $iconDropdown = $('#mod_wishlists, #zt_top_cart, #mod_compare ');
                $iconDropdown.find('.zt-cart-inner').hide();
                $iconDropdown.hover(
                    function () {
                        if(!$('body').hasClass('zt-mobile'))
                            $(this).find('.zt-cart-inner').delay(200).slideDown(500);
                    },
                    function () {
                        if(!$('body').hasClass('zt-mobile'))
                            $(this).find('.zt-cart-inner').slideUp(500);
                    }
                );
            },

            actionButtons: function (quickviewEnable, compareEnable, wishlistEnable) {
                var $this = this;
                $($this._elements.vm3Product).each(function () {
                    var priceId = $(this).find('.product-price').attr('id');
                    var productId = priceId.replace('productPrice', '');
                    var html = '<div class="zt-product-action">';
                    if (quickviewEnable == 1)
                        html += $this.buttonQuickView(productId);
                    if (compareEnable == 1)
                        html += $this.buttonCompare(productId);
                    if (wishlistEnable == 1)
                        html += $this.buttonWishlist(productId);
                    html += '</div>';
                    $(this).append(html);
                });
            },

            buttonWishlist: function (id) {
                return '<div class="wishlist wishlists'+id+'">'
                    + '<a class="wishlist-label add-wishlist hasTooltip " title="Add To Wishlist" onclick="ZtVirtuemarter.wishlist.add('+id+');">'
                    + '    <i class="fa fa-heart-o"></i>'
                    + '</a>'
                    + '</div>';
            },

            buttonCompare: function (id) {
                return '<div class="compare compare'+id+'">'
                    + '<a class="compare-label add-compare hasTooltip " title="Add To Compare" onclick="ZtVirtuemarter.compare.add('+id+');">'
                    + '<i class="fa fa-files-o"></i>'
                    + '</a>'
                    + '</div>';
            },

            buttonQuickView: function (id) {
                return '<div class="quickview quickview'+id+'">'
                    + '<a class="quickview-label add-quickview hasTooltip " title="Show Quickview" onclick="ZtVirtuemarter.quickview.show('+id+');">'
                    + '<i class="fa fa-search"></i>'
                    + '</a>'
                    + '</div>';
            },

            countdown: function(enable) {
                if(enable != 1)
                    return false;

                var $this = this;
                var ids = [];
                $($this._elements.vm3Product).each(function (index) {
                    var priceId = $(this).find('.product-price').attr('id');
                    ids[index] = priceId.replace('productPrice', '');
                });
                jQuery.ajax({
                    url: 'index.php?action=countdown',
                    type: 'POST',
                    cache: false,
                    data: 'countdownProductIds=' + ids.join('-'),
                    success: function (data) {
                        var countdownData = JSON.parse(data);
                        $($this._elements.vm3Product).each(function () {
                            var priceId = $(this).find('.product-price').attr('id');
                            var id = priceId.replace('productPrice', '');
                            if(typeof countdownData[id] != 'undefined') {
                                var countdownHtml = '<div class="product-countdown-wrap countdown-'+id+'">'
                                    +'<div class="count_holder">'
                                    +'<div id="product-countdown-'+id+'"></div>'
                                    +'</div>'
                                    +'</div>';
                                $(this).append(countdownHtml);

                                $('#product-countdown-'+id).countdown(countdownData[id], function (event) {
                                    $(this).text(event.strftime('%D days %H:%M:%S')
                                    );
                                });
                            }
                        });
                    }
                });
            }
        };

        w.ZtVirtuemarter = _ZtVirtuemarter;


        $(document).ready(function(){
            ZtVirtuemarter._init();
        });

    }
})(window, jQuery.noConflict());