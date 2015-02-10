<?php // no direct access
defined ('_JEXEC') or die('Restricted access');
// add javascript for price and cart, need even for quantity buttons, so we need it almost anywhere
vmJsApi::jPrice();

$col = 1;
$pwidth = ' width' . floor (100 / $products_per_row);
if ($products_per_row > 1) {
	$float = "floatleft";
} else {
	$float = "center";
}
?>
<div class="vmgroup<?php echo $params->get ('moduleclass_sfx') ?>">

	<?php if ($headerText) { ?>
	<div class="vmheader"><?php echo $headerText ?></div>
	<?php
}
	if ($display_style == "div") {
		?>
		<div id="vmproduct" class="vmproduct<?php echo $params->get ('moduleclass_sfx'); ?> productdetails ">
			<?php foreach ($productss as $product) { ?>
			<div class="col-md-3 col-sm-3">
				<div class="spacer ">
					<h3><a href="<?php echo $url ?>"><?php echo $product->product_name ?></a></h3>

					<?php
						echo '<div class="vm-product-media-container">';
							if (!empty($product->images[0])) {
								$image = $product->images[0]->displayMediaThumb ('class="featuredProductImage" border="0"', FALSE);
							} else {
								$image = '';
							}
							echo JHTML::_ ('link', JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id), $image, array('title' => $product->product_name));
							echo '<div class="clear"></div>';
							$url = JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' .
								$product->virtuemart_category_id);

						echo '</div>';
					?>

					      <?php    echo '<div class="clear"></div>';
					echo '<div class="product-bottom"><div class="price">';
					if ($show_price) {
						//echo '<span class="prices">'. $currency->priceDisplay( $product->prices['product_price']). '</span>';
						 //echo '<span class="regua-prices">'. $currency->priceDisplay( $product->prices['product_price']).'</span>' ;
						if (!empty($product->prices['salesPrice'])) {
							echo $currency->createPriceDiv ('salesPrice', '', $product->prices, FALSE, FALSE, 1.0, TRUE);
						}
						 		if ($product->prices['salesPriceWithDiscount']>0) echo $currency->priceDisplay($product->prices['salesPriceWithDiscount']);
						if (!empty($product->prices['salesPriceWithDiscount'])) {
							echo $currency->createPriceDiv ('salesPriceWithDiscount', '', $product->prices, FALSE, FALSE, 1.0, TRUE);
						}


					}
					echo '</div>';
					if ($show_addtocart) {
						$oder = 0;
						for($i=0; $i<strlen($url);$i++){
							if($url[$i] == '/'){
								$oder = $i;
							}
						}
						$abc = substr($url, $oder);
						$url_link = str_replace($abc,"", $url);


						echo mod_zt_vm3_product::addtocart ($product);
					}
					echo '</div>';
					?>
					<div class="product_hover zt-product-content">
						<div class="mod_wishlist"><i class="fa fa-heart-o"></i></div>
						<input class="quick_ids" type="hidden" value="<?php echo $product->virtuemart_product_id; ?>">
						<div class="mod_compare"><i class="fa fa-files-o"></i></div>

					</div>
				</div>
			</div>
			<?php
			if ($col == $products_per_row && $products_per_row && $col < $totalProd) {
				echo "	</div><div style='clear:both;'>";
				$col = 1;
			} else {
				$col++;
			}
		} ?>
		</div>
		<br style='clear:both;'/>

		<?php
	} else {
		$last = count ($productss) - 1;
		?>

		<div id="vmproduct" class="vmproduct<?php echo $params->get ('moduleclass_sfx'); ?> productdetails ">
			<?php foreach ($productss as $product) : ?>
			<div class="col-md-3 col-sm-3">
				<div class="spacer zt-product-content">
					<?php
						if (!empty($product->images[0])) {
							$image = $product->images[0]->displayMediaThumb ('class="featuredProductImage" border="0"', FALSE);
						} else {
							$image = '';
						}
						echo JHTML::_ ('link', JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id), $image, array('title' => $product->product_name));
						echo '<div class="clear"></div>';
						$url = JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' .
						$product->virtuemart_category_id);
					?>
					<a href="<?php echo $url ?>"><?php echo $product->product_name ?></a>
					 <?php    echo '<div class="clear"></div>';
						// $product->prices is not set when show_prices in config is unchecked
						if ($show_price and  isset($product->prices)) {
							echo '<div class="product-price">'.$currency->createPriceDiv ('salesPrice', '', $product->prices, FALSE, FALSE, 1.0, TRUE);
							if ($product->prices['salesPriceWithDiscount'] > 0) {
								echo $currency->createPriceDiv ('salesPriceWithDiscount', '', $product->prices, FALSE, FALSE, 1.0, TRUE);
							}
							echo '</div>';
						}
						if ($show_addtocart) {
							echo mod_zt_vm3_product::addtocart ($product);
						}
					?>
				</div>
			</div>
			<?php
			if ($col == $products_per_row && $products_per_row && $last) {
				echo '
		</div><div class="clear"></div>
		<ul  class="vmproduct' . $params->get ('moduleclass_sfx') . ' productdetails">';
				$col = 1;
			} else {
				$col++;
			}
			$last--;
		endforeach; ?>
		</div>>
		<div class="clear"></div>

		<?php
	}
	if ($footerText) : ?>
		<div class="vmfooter<?php echo $params->get ('moduleclass_sfx') ?>">
			<?php echo $footerText ?>
		</div>
		<?php endif; ?>
</div>

<!--ajax-->
