<?php // no direct access
defined('_JEXEC') or die('Restricted access');

//dump ($cart,'mod cart');
// Ajax is displayed in vm_cart_products
// ALL THE DISPLAY IS Done by Ajax using "hiddencontainer" 

?>

<!-- Virtuemart 2 Ajax Card -->
<div id="zt_top_cart">
	<div id="cart"><a href="<?php echo JURI::base().'index.php?option=com_virtuemart&view=cart'; ?>"><i class="fa fa-shopping-cart"></i><span class="total-item">(<?php echo count($cart->products); ?>)items - </span></a>
		<?php
			$total = '0.00';
            if(count($cart->products) > 0 ) {
            	$total = 0;
				foreach ($cart->products as $index => $product){
	                $total += $product->allPrices[0]['product_price'];
	                
				}
       		}
       		if ($show_price) 
                echo '<span class="total-price">$'.$total.'</span>';
		?>
	</div>
	<div class="zt-cart-inner">
		<div class="vmCartModule <?php echo $params->get('moduleclass_sfx'); ?>" id="vmCartModule">
		<?php
		if ($show_product_list) {
			?>
			<div id="hiddencontainer" style=" display: none; ">
				<div class="content-top">
					<?php if ($show_price) { ?>
					  <div class="prices" style="float: right;"></div>
					<?php } ?>
					<div class="product_row">
						<span class="quantity"></span>&nbsp;x&nbsp;<span class="product_name"></span>
					</div>

					<div class="product_attributes"></div>
				</div>
			</div>
			<div class="vm_cart_products">
				<div class="content-top">

				<?php

                if(count($cart->products) > 0 ) {
                	?>
                	<p class="add-product">Recently added item(s)</p>
                	<?php
					foreach ($cart->products as $index => $product){

                        if ($show_price) { ?>

                        <div class="cart-row cart-row-item-<?php echo $product->cart_item_id; ?> clearfix" data-id="<?php echo $product->virtuemart_product_id; ?>">
                        	<div class="item">
	                            <div class="cart-product-img">
	                                <?php echo $product->images[0]->displayMediaThumb ('class="featuredProductImage" border="0"', FALSE); ?>
	                            </div>
	                            <div class="cart-product-detail">
	                                <?php
	                                $item_quantity = '';
	                                if($product->quantity > 1){
	                                    $item_quantity = '(x'+$product->quantity+')';
	                                }
	                                ?>
	                                <h4><?php echo $product->product_name.' '.$item_quantity; ?> </h4>
	                                <p class="product-price">$<?php echo number_format($product->allPrices[0]['product_price'], 2); ?></p>
	                                <a href="#" onclick="zo2.cart.remove(<?php echo $product->cart_item_id; ?>);" class="cart-ajax-del" data-type="cart-view"><i class="fa fa-times"></i>Remove</a>
	                            </div>
	                        </div>
                        </div>
                        <?php } ?>
                        <?php
                    }
                } else {
                    echo 'You have no items in your shopping cart.';
                }
				?>
				</div>
			</div>

            <div class="cart-header-info" <?php if(count($cart->products) == 0 ) echo 'style="display:none;"';?>>
                <div class="total">
                    <span>Total</span></span><span style="float: right;">$<?php echo number_format($total, 2); ?></span>
                </div>
                <div style="clear:both;"></div>
                <div class="cart-btn">
                    <a href="<?php echo JURI::base().'index.php?option=com_virtuemart&view=cart'; ?>">Show cart</a>
                    <a href="<?php echo JURI::base().'index.php?option=com_virtuemart&view=user&task=editaddresscart'; ?>">Check out</a>
                    <input type="hidden" id="base_url" value="<?php echo JURI::root()?>">
                </div>
            </div>
        <?php } ?>
		<noscript>
		<?php echo JText::_('MOD_VIRTUEMART_CART_AJAX_CART_PLZ_JAVASCRIPT') ?>
		</noscript>
		</div>
	</div>
</div>
