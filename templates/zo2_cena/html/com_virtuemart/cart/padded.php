<?php
/**
 *
 * Layout for the add to cart popup
 *
 * @package	VirtueMart
 * @subpackage Cart
 * @author Max Milbers
 *
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2013 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: cart.php 2551 2010-09-30 18:52:40Z milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
?>

<div class="cart-ajax-title">
    <?php
    $this->cart->prepareCartData();
    if($this->products){
        foreach($this->products as $product){
            $productModel = VmModel::getModel('Product');

            $productModel->addImages($product, 1);

            echo $product->images[0]->displayMediaThumb ('class="featuredProductImage" border="0"', FALSE);


            echo '<h4>'.vmText::sprintf('COM_VIRTUEMART_CART_PRODUCT_ADDED',$product->product_name,$product->quantity).'</h4>';
        }
    }
    echo '</div></div>';
    echo '<div class="add-cart-notice">The product has been added to your shopping cart</div>';

    if ($this->errorMsg) echo '<div>'.$this->errorMsg.'</div>';

    if(VmConfig::get('popup_rel',1)){
        if($this->products and !empty($this->products[0])){
            $customFieldsModel = VmModel::getModel('customfields');
            $customfields = $customFieldsModel->getCustomEmbeddedProductCustomFields($this->products[0]->allIds,'R');

            $customFieldsModel->displayProductCustomfieldFE($this->products[0], $customfields);
            if(!empty($customfields)){
                ?>
                <div class="product-related-products">
                <h4><?php echo vmText::_('COM_VIRTUEMART_RELATED_PRODUCTS'); ?></h4>
            <?php
            }
            foreach($customfields as $rFields){

                if(!empty($rFields->display)){
                    ?><div class="product-field product-field-type-<?php echo $rFields->field_type ?>">
                    <span class="product-field-display"><?php echo $rFields->display ?></span>
                    </div>
                <?php }
            } ?>
            </div>
        <?php
        }
    }

    echo '<a class="continue q" href="' . $this->continue_link . '" >' . vmText::_('COM_VIRTUEMART_CONTINUE_SHOPPING') . '</a>';
    echo '<a class="showcart floatright" href="' . $this->cart_link . '">' . vmText::_('COM_VIRTUEMART_CART_SHOW') . '</a>';
    ?><br style="clear:both">

    <script type="text/javascript">
        jQuery('#cart span').html(<?php echo count($this->cart->products)?>);
        /*
        var cart_item = '<div class="cart-row cart-row-item-<?php echo $this->products[0]->cart_item_id; ?> clearfix" data-id="<?php echo $this->products[0]->virtuemart_product_id; ?>">';
        cart_item += '<div class="cart-product-img">';
        cart_item += '<?php echo $this->products[0]->images[0]->displayMediaThumb ('class="featuredProductImage" border="0"', FALSE); ?>';
        cart_item += '</div>';
        cart_item += '<div class="cart-product-detail">';
        cart_item += '<h4><?php echo $this->products[0]->product_name; ?> (<?php echo $this->products[0]->quantity; ?>)</h4>';
        cart_item += '<p class="product-price">$<?php echo number_format($this->products[0]->allPrices[0]['product_price'], 2); ?></p>';
        cart_item += '<a href="#" onclick="zo2.cart.remove(<?php echo $this->products[0]->cart_item_id; ?>);" class="cart-ajax-del" data-type="cart-view"><i class="fa fa-times"></i>Remove</a>';
        cart_item += '</div>';
        cart_item += '</div>';
        jQuery('.vm_cart_products .vmcontainer').append(cart_item);
        */
    </script>
