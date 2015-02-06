jQuery(document).ready(function () {
    jQuery('body').append('<div id="quick_view_overlay" style="display:none"></div><div class="AjaxPreloader" style="display:none"></div><div id="quick_view_popup"></div>');
});
function quick_btn(product_id) {
    jQuery('#quick_view_overlay').show();
    jQuery('.AjaxPreloader').show();
    jQuery.ajax({
        url: 'index.php?action=test',
        type: 'get',
        data: 'product_id=' + product_id,
        success: function (data) {
            jQuery('#quick_view_popup').html(data);
            jQuery('.AjaxPreloader').hide();
            jQuery('#quick-view').show(500);
            jQuery("#Img_zoom").elevateZoom({
                gallery: 'gallery_01',
                cursor: 'pointer',
                showLens: false,
                zoomType: 'lens',
                containLensZoom: false,
                easing: false,
                zoomLens: false,
                galleryActiveClass: 'zoomThumbActive active'
            });
            RESPONSIVEUIQUICK.responsiveTabsQuick();
            jQuery(function () {
                jQuery('.product-custom select').styler().trigger('refresh');
            });
            jQuery('#carousel').jcarousel({
                scroll: 1
            });
            jQuery('.productdetails-view .addtocart-bar2 .hasTooltip').tooltip();
            jQuery('#quick-view').css({
                position: 'fixed',
            });
            jQuery("#quick_view_popup .productdetails-view.quick").mCustomScrollbar({
                advanced: {
                    updateOnContentResize: true
                },
                scrollButtons: {
                    enable: true
                }
            });
            jQuery("#quick_view_popup .productdetails-view.quick").mCustomScrollbar("update");
            function setproducttype(form, id) {
                form.view = null;
                var $ = jQuery, datas = form.serialize();
                var prices = form.parent(".productdetails").find(".product-price");
                if (0 == prices.length) {
                    prices = jQuery("#productPrice" + id);
                }
                datas = datas.replace("&view=cart", "");
                prices.fadeTo("fast", 0.75);
                //encodeURIComponent(datas);
                jQuery.getJSON(window.vmSiteurl + 'index.php?option=com_virtuemart&nosef=1&view=productdetails&task=recalculate&virtuemart_product_id=' + id + '&format=json' + window.vmLang, datas,
                    function (datas, textStatus) {
                        prices.fadeTo("fast", 1);
                        // refresh price
                        for (var key in datas) {
                            var value = datas[key];
                            if (value != 0) prices.find("span.Price" + key).show().html(value);
                            else prices.find(".Price" + key).html(0).hide();
                        }
                    });
                return false; // prevent reload
            }

            function productUpdate() {
                mod = jQuery(".vmCartModule");
                var $ = jQuery;
                $.ajaxSetup({ cache: false })
                $.getJSON(window.vmSiteurl + "index.php?option=com_virtuemart&nosef=1&view=cart&task=viewJS&format=json" + window.vmLang,
                    function (datas, textStatus) {
                        if (datas.totalProduct > 0) {
                            mod.find(".vm_cart_products").html("");
                            datas.products.reverse();
                            $.each(datas.products, function (key, val) {
                                if (key < limitcount) {
                                    $("#hiddencontainer .container").clone().appendTo(".vmCartModule .vm_cart_products");
                                    $.each(val, function (key, val) {
                                        if ($("#hiddencontainer .container ." + key)) mod.find(".vm_cart_products ." + key + ":last").html(val);
                                    });
                                    if (key == 0) {
                                        jQuery("#cart_list div#vm_cart_products").addClass('height').removeClass('heightnone').css('height', 'auto');
                                    } else {
                                        jQuery("#cart_list div#vm_cart_products").addClass('heightnone').removeClass('height');
                                    }
                                }
                            });
                            mod.find(".text-art").html(datas.cart_recent_text);
                            mod.find(".total").html(datas.billTotal);
                            mod.find(".tot3").html(datas.taxTotal);
                            mod.find(".tot4").html(datas.discTotal);
                            //mod.find(".total2").html(datas.billTotal);
                            mod.find(".show_cart").html(datas.cart_show);
                            mod.find("#cart_list .vmicon i").html(datas.cart_remove);
                        }
                        mod.find(".total_products").html(datas.totalProductTxt);
                        customScrollbar();

                    }
                );
            }

            function sendtocart(form) {
                cartEffect(form);
            }

            function cartEffect(form) {
                jQuery('.AjaxPreloaderC').show();
                var $ = jQuery;
                $.ajaxSetup({ cache: false });
                var dat = form.serialize();

                if (usefancy) {
                    $.fancybox.showActivity();
                }

                $.getJSON(vmSiteurl + 'index.php?option=com_virtuemart&nosef=1&view=cart&task=addJS&format=json' + vmLang, dat,
                    function (datas, textStatus) {
                        jQuery('.AjaxPreloaderC').hide();
                        if (datas.stat == 1) {
                            var this_prod = form.find(".item_id").val();
                            var img_url = document.getElementById('Img_to_Js_' + this_prod).src;
                            var txt = datas.msg + "<img width='80' height='auto' src='" + img_url + "' />";
                        } else if (datas.stat == 2) {
                            var this_prod = form.find(".item_id").val();
                            var img_url = document.getElementById('Img_to_Js_' + this_prod).src;
                            var txt = datas.msg + "<H4>" + form.find(".pname").val() + "</H4><img width='80' height='auto' src='" + img_url + "' />";
                        } else {
                            var txt = "<H4>" + vmCartError + "</H4>" + datas.msg;
                        }
                        if (usefancy) {
                            $.fancybox({
                                    "titlePosition": "inside",
                                    "transitionIn": "fade",
                                    "transitionOut": "fade",
                                    "changeFade": "fast",
                                    "type": "html",
                                    "autoCenter": true,
                                    "closeBtn": false,
                                    "closeClick": false,
                                    "content": txt
                                }
                            );
                        } else {
                            $.facebox.settings.closeImage = closeImage;
                            $.facebox.settings.loadingImage = loadingImage;
                            //$.facebox.settings.faceboxHtml = faceboxHtml;
                            $.facebox({ text: txt }, 'my-groovy-style2');
                        }


                        Virtuemart.productUpdate();
                    });

                $.ajaxSetup({ cache: true });
            }

            function product(carts) {
                carts.each(function () {
                    var cart = jQuery(this),
                        step = cart.find('input[name="quantity"]'),
                        addtocart = cart.find('button.addtocart-button'),
                        plus = cart.find('.quantity-plus'),
                        minus = cart.find('.quantity-minus'),
                        select = cart.find('select'),
                        radio = cart.find('input:radio'),
                        virtuemart_product_id = cart.find('input[name="virtuemart_product_id[]"]').val(),
                        quantity = cart.find('.quantity-input');
                    var Ste = parseInt(step.val());
                    //Fallback for layouts lower than 2.0.18b
                    if (isNaN(Ste)) {
                        Ste = 1;
                    }
                    addtocart.die("click");
                    addtocart.live('click', function (e) {
                        sendtocart(cart);
                        return false;
                    });
                    plus.die("click");
                    plus.live('click', function () {
                        var Qtt = parseInt(quantity.val());
                        if (!isNaN(Qtt)) {
                            quantity.val(Qtt + Ste);
                            setproducttype(cart, virtuemart_product_id);
                        }

                    });
                    minus.die("click");
                    minus.live('click', function () {
                        var Qtt = parseInt(quantity.val());
                        if (!isNaN(Qtt) && Qtt > Ste) {
                            quantity.val(Qtt - Ste);
                        } else quantity.val(Ste);
                        setproducttype(cart, virtuemart_product_id);
                    });
                    select.change(function () {
                        setproducttype(cart, virtuemart_product_id);
                    });
                    radio.change(function () {
                        setproducttype(cart, virtuemart_product_id);
                    });
                    quantity.keyup(function () {
                        setproducttype(cart, virtuemart_product_id);
                    });
                });

            }

            product(jQuery(".product"));
            jQuery("form.js-recalculate").each(function () {
                if (jQuery(this).find(".product-fields").length) {
                    var id = jQuery(this).find('input[name="virtuemart_product_id[]"]').val();
                    setproducttype(jQuery(this), id);

                }
            });
            jQuery('#quick_view_overlay, #quick_view_close').click(function () {
                jQuery('#quick-view').remove();
                jQuery('#quick_view_overlay').hide();
                //jQuery('.AjaxPreloader').hide();
            });
            jQuery(document).keyup(function (e) {
                if (e.keyCode == 27) {
                    jQuery('#quick_view_close').click();
                }   // esc
            });
            return false;
        }
    });
}
