<?php
$user = JFactory::getUser();
if ($user->guest) {
    ?>
    <a class="add_wishlist hasTooltip <?php if ( isset($_SESSION['wishlist_ids']) && is_array($_SESSION['wishlist_ids']) && in_array($product->virtuemart_product_id, $_SESSION['wishlist_ids'])) {echo 'go_to_whishlist active';} ?>"
        title="<?php echo JText::_('ADD_TO_WHISHLIST'); ?>"
        onclick="addToWishlists('<?php echo $product->virtuemart_product_id; ?>');">
        <i class="fa fa-heart-o"></i>
        <span><?php echo JText::_("ADD_TO_WHISHLIST"); ?></span>
    </a>
<?php
} else {
    JPluginHelper::importPlugin('System');
    $dispatcher =& JDispatcher::getInstance();
    $results = $dispatcher->trigger('onBeforeRender');

    if ($results[0] == 'true') {
        $db =& JFactory::getDBO();
        $q = "SELECT virtuemart_product_id FROM #__wishlists WHERE userid =" . $user->id . " AND virtuemart_product_id=" . $product->virtuemart_product_id;
        $db->setQuery($q);
        $allproducts = $db->loadAssocList();
        foreach ($allproducts as $productbd) {
            $allprod['id'][] = $productbd['virtuemart_product_id'];
        }
        //var_dump($allproducts);
    }
    ?>
    <a class="add_wishlist hasTooltip <?php if (in_array($product->virtuemart_product_id, $allprod['id'])) {echo 'go_to_whishlist active';} ?>"
        title="<?php echo JText::_('ADD_TO_WHISHLIST'); ?>"
        data-toggle="tooltip"
        onclick="addToWishlists('<?php echo $product->virtuemart_product_id; ?>');">
        <i class="fa fa-heart-o"></i>
        <span><?php echo JText::_("ADD_TO_WHISHLIST"); ?></span>
    </a>

<?php

} ?> 


