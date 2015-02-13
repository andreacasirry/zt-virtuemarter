<?php // no direct access
defined('_JEXEC') or die('Restricted access');
// add javascript for price and cart, need even for quantity buttons, so we need it almost anywhere
vmJsApi::jPrice();

$col = 1;
$pwidth = ' width' . floor(100 / $products_per_row);
if ($products_per_row > 1) {
    $float = "floatleft";
} else {
    $float = "center";
}
?>
<div class="vmgroup<?php echo $params->get('moduleclass_sfx') ?>">

    <?php if ($headerText) { ?>
        <div class="vmheader"><?php echo $headerText ?></div>
    <?php
    }
    if ($display_style == "div") {
        ?>
        <div id="vmproduct" class="vmproduct<?php echo $params->get('moduleclass_sfx'); ?> productdetails ">
            <?php foreach ($productss as $product) {
                $url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id);
                ?>
                <div class="col-md-3 col-sm-3 product-item product-grid-item">
                    <div class="spacer ">
                        <h3><a href="<?php echo $url ?>"><?php echo $product->product_name ?></a></h3>
                        <?php
                            $createddate = $product->created_on;
                            $sale = $product->prices['product_override_price'];
                            $timeCreateddate = strtotime($createddate);
                            $date = strtotime("now");

                            $htmlLabel = '';
                            $saleClass = '';
                            if ($sale > 0) {
                                $saleClass = ' product-sale';
                            }

                            $dateDiff = date_diff(date_create(), date_create($product->product_available_date));

                            if ($dateDiff->days < $new_product_from) {
                                $htmlLabel .= '<div class="label-product label-new">New</div>';
                            }
                            if ($sale > 0) {
                                $htmlLabel .= '<div class="label-product label-sale">Sale</div>';
                            }

                            echo $htmlLabel;
                        ?>
                        <?php
                        $ratingModel = VmModel::getModel('ratings');
                        $product->showRating = $ratingModel->showRating($product->virtuemart_product_id);
                        if ($product->showRating) {
                            $rating = $ratingModel->getRatingByProduct($product->virtuemart_product_id);
                            if (!empty($rating)) {
                                $r = $rating->rating;
                            } else {
                                $r = 0;
                            }
                            $maxrating = VmConfig::get('vm_maximum_rating_scale', 5);
                            $ratingwidth = ($r * 100) / $maxrating;
                            $rate_star = '';
                            $rate_star .= '<div class="comare_rating">';
                            $rate_star .= '<div class="rating">';
                            $rate_star .= '<span class="vote">';
                            $rate_star .= '<span title="" class="vmicon ratingbox" style="display:inline-block;">';
                            $rate_star .= '<span class="stars-orange" style="width:' . $ratingwidth . '%">';
                            $rate_star .= '</span>';
                            $rate_star .= '</span>';
                            $rate_star .= '</span>';
                            $rate_star .= '</div>';
                            $rate_star .= '</div>';
                            echo $rate_star;

                            //$rating = $ratingModel->getRatingByProduct($product->virtuemart_product_id);
                            //$product->assignRef('rating', $rating);
                            //vmdebug('Should show rating vote and rating',$vote,$rating);
                        }

                        echo '<div class="vm-product-media-container">';
                        if (!empty($product->images[0])) {
                            $image = $product->images[0]->displayMediaThumb('class="featuredProductImage" border="0"', FALSE);
                        } else {
                            $image = '';
                        }
                        echo JHTML::_('link', JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id), $image, array('title' => $product->product_name));
                        echo '<div class="clear"></div>';
                        echo '</div>';
                        echo '<div class="clear"></div>';
                        echo '<div class="product-bottom"><div class="price">';
                         echo '<div class="product-price' . $saleClass . '">' . shopFunctionsF::renderVmSubLayout('prices', array('product' => $product, 'currency' => $currency)) . '</div>';

                        echo '</div>';
                        if ($show_addtocart) {
                            $oder = 0;
                            for ($i = 0; $i < strlen($url); $i++) {
                                if ($url[$i] == '/') {
                                    $oder = $i;
                                }
                            }
                            $abc = substr($url, $oder);
                            $url_link = str_replace($abc, "", $url);


                            echo mod_ztvirtuemarter_product::addtocart($product);
                        }
                        echo '</div>';
                        ?>
                        <div class="product_hover zt-product-content">
                            <?php plgSystemZtvirtuemarter::addWishlistButton($product); ?>
                            <?php plgSystemZtvirtuemarter::addCompareButton($product); ?>
                            <input class="quick_ids" type="hidden" value="<?php echo $product->virtuemart_product_id; ?>">
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
        $last = count($productss) - 1;
        ?>
        <div id="vmproduct" class="vmproduct<?php echo $params->get('moduleclass_sfx'); ?> productdetails ">
            <?php foreach ($productss as $product) : ?>
                <div class="col-md-3 col-sm-3 product-item">
                    <div class="spacer zt-product-content">
                        <?php
                            $createddate = $product->created_on;
                            $sale = $product->prices['product_override_price'];
                            $timeCreateddate = strtotime($createddate);
                            $date = strtotime("now");

                            $htmlLabel = '';
                            $saleClass = '';
                            if ($sale > 0) {
                                $saleClass = ' product-sale';
                            }

                            if ($date - $createddate <= 3) {
                                $htmlLabel .= '<div class="label-product label-new">New</div>';
                            }
                            if ($sale > 0) {
                                $htmlLabel .= '<div class="label-product label-sale">Sale</div>';
                            }

                            echo $htmlLabel;
                        ?>
                        <?php
                        if (!empty($product->images[0])) {
                            $image = $product->images[0]->displayMediaThumb('class="featuredProductImage" border="0"', FALSE);
                        } else {
                            $image = '';
                        }
                        echo JHTML::_('link', JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id), $image, array('title' => $product->product_name));
                        echo '<div class="clear"></div>';
                        $url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' .
                            $product->virtuemart_category_id);
                        ?>
                        <a href="<?php echo $url ?>"><?php echo $product->product_name ?></a>
                        <?php    echo '<div class="clear"></div>';
                        // $product->prices is not set when show_prices in config is unchecked
                        if ($show_price and isset($product->prices)) {
                            echo '<div class="product-price">' . $currency->createPriceDiv('salesPrice', '', $product->prices, FALSE, FALSE, 1.0, TRUE);
                            if ($product->prices['salesPriceWithDiscount'] > 0) {
                                echo $currency->createPriceDiv('salesPriceWithDiscount', '', $product->prices, FALSE, FALSE, 1.0, TRUE);
                            }
                            echo '</div>';
                        }
                        if ($show_addtocart) {
                            echo mod_ztvirtuemarter_product::addtocart($product);
                        }
                        ?>
                    </div>
                    <div class="product_hover zt-product-content">
                        <?php plgSystemZtvirtuemarter::addWishlistButton($product); ?>
                        <?php plgSystemZtvirtuemarter::addCompareButton($product); ?>
                        <input class="quick_ids" type="hidden" value="<?php echo $product->virtuemart_product_id; ?>">
                    </div>
                </div>
                <?php
                if ($col == $products_per_row && $products_per_row && $last) {
                    echo '
                    </div><div class="clear"></div>
                    <ul  class="vmproduct' . $params->get('moduleclass_sfx') . ' productdetails">';
                    $col = 1;
                } else {
                    $col++;
                }
                $last--;
            endforeach; ?>
        </div>
        <div class="clear"></div>

    <?php
    }
    if ($footerText) : ?>
        <div class="vmfooter<?php echo $params->get('moduleclass_sfx') ?>">
            <?php echo $footerText ?>
        </div>
    <?php endif; ?>
</div>

<!--ajax-->
