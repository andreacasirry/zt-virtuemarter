function quick_btn(product_id) {

}
/**
 * Zt Virtuemarter quickview
 * @param {type} w Windows pointer
 * @param {type} $ jQuery pointer
 * @returns {undefined}
 */
(function (w, $) {

    /**
     * quickview local object
     * @type object
     */
    var _quickview = {
        show: function(id) {
            $.fancybox.showActivity();
            $.ajax({
                url: 'index.php?option=com_ztvirtuemarter&view=quickview',
                type: 'get',
                data: 'virtuemart_product_id=' + id,
                success: function (data) {
                    $.fancybox({
                        "titlePosition": "inside",
                        "transitionIn": "fade",
                        "transitionOut": "fade",
                        "changeFade": "fast",
                        "type": "html",
                        "autoCenter": true,
                        "closeBtn": false,
                        "closeClick": false,
                        "content": data
                    });
                }
            });
        }

    };

    /* Append wishlist to ZtVirtuemarter */
    $(document).ready(function () {
        w.ZtVirtuemarter.quickview = _quickview;
    });

})(window, window.jQuery);
