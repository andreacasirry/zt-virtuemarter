function quick_btn(product_id) {
    jQuery  .fancybox.showActivity();
    jQuery.ajax({
        url: 'index.php?option=com_ztvirtuemarter&view=quickview',
        type: 'get',
        data: 'virtuemart_product_id=' + product_id,
        success: function (data) {
            jQuery.fancybox({
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
