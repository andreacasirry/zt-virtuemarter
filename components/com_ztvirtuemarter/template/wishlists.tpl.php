<?php
$user = JFactory::getUser();

if ($user->guest) :
    ?>
    <a class="add_wishlist hasTooltip <?php echo in_array($product->virtuemart_product_id, $wishlistIds) ? 'go_to_whishlist active' : ''; ?>"
       title="<?php echo JText::_('ADD_TO_WHISHLIST'); ?>"
       onclick="addToWishlists('<?php echo $product->virtuemart_product_id; ?>');">
        <i class="fa fa-heart-o"></i>
        <span><?php echo JText::_("ADD_TO_WHISHLIST"); ?></span>
    </a>
<?php
else :
    JPluginHelper::importPlugin('System');
    $dispatcher = JDispatcher::getInstance();
    $results = $dispatcher->trigger('onBeforeRender');

    if ($results[0] == 'true') {
        $wishlistModel = new ZtvirtuemarterModelWishlist();
        $allproducts = $wishlistModel->getProducts();
        foreach ($allproducts as $productbd) {
            $allprod['id'][] = $productbd['virtuemart_product_id'];
        }
    }
    ?>
    <a class="add_wishlist hasTooltip <?php echo in_array($product->virtuemart_product_id, $allprod['id']) ? 'go_to_whishlist active' : ''; ?>"
       title="<?php echo JText::_('ADD_TO_WHISHLIST'); ?>"
       data-toggle="tooltip"
       onclick="addToWishlists('<?php echo $product->virtuemart_product_id; ?>');">
        <i class="fa fa-heart-o"></i>
        <span><?php echo JText::_("ADD_TO_WHISHLIST"); ?></span>
    </a>

<?php

endif; ?>


