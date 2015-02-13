<?php
/**
 * @package    VirtueMart Zooex
 * @subpackage VirtueMart Zooex Components
 * @author       ZooTemplate.com
 * @link http://zootemplate.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */
//error_reporting('E_ALL');
defined('_JEXEC') or die;
$mainframe = Jfactory::getApplication();
$virtuemart_currency_id = $mainframe->getUserStateFromRequest("virtuemart_currency_id", 'virtuemart_currency_id', JRequest::getInt('virtuemart_currency_id', 0));
VmConfig::loadConfig();
VmConfig::loadJLang('com_ztvirtuemarter', true);
$document = JFactory::getDocument();
vmJsApi::jPrice();

$ratingModel = VmModel::getModel('ratings');
$product_model = VmModel::getModel('product');

$session = JFactory::getSession();
$compare_ids = $session->get('compare_ids', array(), 'compare_product');

$prods = $product_model->getProducts($compare_ids);
$product_model->addImages($prods, 1);
$currency = CurrencyDisplay::getInstance();
?>
<div class="compare_box">
<h3 class="module-title">
    <?php echo JText::_('COM_COMPARE_COMPARE_PRODUCT') ?>
</h3>

<?php // Back To Category Button
if ( isset($product->virtuemart_category_id)) {
    $catURL = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $product->virtuemart_category_id);
    $categoryName = $product->category_name;
} else {
    $catURL = JRoute::_('index.php?option=com_virtuemart&view=virtuemart');
    $categoryName = jText::_('COM_VIRTUEMART_SHOP_HOME');
}
?>

<div class="back-to-category">
    <a href="<?php echo $catURL ?>" class="button_back button reset2" title="<?php echo $categoryName ?>">
        <i class="fa fa-reply"></i><?php echo JText::sprintf('COM_VIRTUEMART_CATEGORY_BACK_TO', $categoryName) ?>
        <span></span></a>
</div>
<div class="clear"></div>
<?php
if (!empty($prods)) {
    ?>
    <div class="browseview browscompare_list">
    <?php
    foreach ($prods as $product) {

        if (isset($product->customfields)) {
            foreach ($product->customfields as $field) {
                if (($field->field_type == 'E' && $field->custom_value !== 'youtube') || $field->field_type == 'P' || $field->field_type == 'S' || $field->field_type == 'I' || $field->field_type == 'B') {
                    $compare_fields[$field->virtuemart_custom_id] = $field->custom_title;
                }
                if (isset($compare_fields[$field->virtuemart_custom_id]) && $field->display) {
                    $compare_fields_product[$product->virtuemart_product_id][$field->virtuemart_custom_id] = $field->display;
                } else {
                    $compare_fields_product[$product->virtuemart_product_id][$field->virtuemart_custom_id] = null;
                }
            }
        }
    }
    $table[0][0] = '<div class="comare_name">' . JText::_('DR_PRODUCT_NAME') . '</div>';
    $table[1][0] = '<div class="comare_image">' . JText::_('DR_PRODUCT_IMAGE') . '</div>';
    $table[2][0] = '<div class="comare_rating">' . JText::_('DR_PRODUCT_RATING') . '</div>';
    $table[3][0] = '<div class="comare_price">' . JText::_('DR_PRODUCT_PRICE') . '</div>';
    $table[4][0] = '<div class="comare_desc">' . JText::_('DR_PRODUCT_DESCRIPTION') . '</div>';
    $table[5][0] = '<div class="comare_brand">' . JText::_('DR_PRODUCT_MANUFACTURER') . '</div>';
    $table[6][0] = '<div class="comare_stock">' . JText::_('DR_PRODUCT_AVAILABILITY') . '</div>';
    $table[7][0] = '<div class="comare_code">' . JText::_('DR_PRODUCT_CODE') . '</div>';
    $table[8][0] = '<div class="comare_weight">' . JText::_('DR_PRODUCT_WEIGHT') . '</div>';
    $table[9][0] = '<div class="comare_dim">' . JText::_('DR_PRODUCT_DIMENSIONS') . '</div>';
    $table[10][0] = '<div class="comare_pack">' . JText::_('DR_PRODUCT_PACKAGING') . '</div>';
    $table[11][0] = '<div class="comare_unit">' . JText::_('DR_PRODUCT_UNITS_BOX') . '</div>';
    $table[12][0] = '<div class="comare_action">' . JText::_('DR_PRODUCT_ACTION') . '</div>';
    $row = 13;
    if (isset($compare_fields)) {
        foreach ($compare_fields as $field_no => $field_name) {
            $table[$row][0] = $field_name;
            $row++;
        }
    }
    $rowall = $row;
    $col = 1;
    $tdclass[0] = '';
    foreach ($prods as $product) {

        $rating = $ratingModel->getRatingByProduct($product->virtuemart_product_id);
        if (!empty($rating)) {
            $r = $rating->rating;
        } else {
            $r = 0;
        }
        $maxrating = VmConfig::get('vm_maximum_rating_scale', 5);
        $ratingwidth = ($r * 100) / $maxrating;
        $text = $product->mf_name;
        if (!empty($compare_ids)) {

            $row = 0;
            $tdclass[$col] = 'compare_prod_' . $product->virtuemart_product_id;
            $table[$row][$col] = '';
            $table[$row][$col] .= '<div class="comare_name"><h5>' . JHTML::link($product->link, shopFunctionsF::limitStringByWord($product->product_name, '40', '...')) . '</h5></div>';
            $row = 1;
            $table[$row][$col] = '';
            $table[$row][$col] .= '<div class="comare_image">';
            $table[$row][$col] .= '<div class="browseImage ">';
            if (isset($product->override) && $product->override == 1 && ($product->product_price_publish_down > 0)) {
                $table[$row][$col] .= '<div class="discount limited">' . JText::_('DR_LIMITED_OFFER') . '</div>';
            } elseif (abs($product->prices['discountAmount']) > 0 && $product->product_sales < 5) {
                $table[$row][$col] .= '<div class="discount">' . JText::_('DR_SALE') . '</div>';
            } elseif ($product->product_sales > 0) {
                $table[$row][$col] .= '<div class="hit">' . JText::_('DR_HOT') . '</div>';
            }
            $image = $product->images[0]->displayMediaThumb('class="browseProductImage" id="Img_to_Js_' . $product->virtuemart_product_id . '" border="0" title="' . $product->product_name . '" ', false, 'class="vm2_modal"');
            $table[$row][$col] .= JHTML::_('link', JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id), $image);
            $table[$row][$col] .= '</div>';
            $table[$row][$col] .= '</div>';
            $showRating = $ratingModel->showRating($product->virtuemart_product_id);
            if ($showRating == 'true') {
                $row = 2;
                $table[$row][$col] = '';

                $table[$row][$col] .= '<div class="comare_rating">';
                $table[$row][$col] .= '<div class="rating">';
                $table[$row][$col] .= '<span class="vote">';
                $table[$row][$col] .= '<span title="" class="vmicon ratingbox" style="display:inline-block;">';
                $table[$row][$col] .= '<span class="stars-orange" style="width:' . $ratingwidth . '%">';
                $table[$row][$col] .= '</span>';
                $table[$row][$col] .= '</span>';
                $table[$row][$col] .= '</span>';
                $table[$row][$col] .= '</div>';
                $table[$row][$col] .= '</div>';
            }
            $row = 3;
            $table[$row][$col] = '';
            $table[$row][$col] .= '<div class="comare_price">';
            if ((!empty($product->prices['salesPrice'])) && !$product->images[0]->file_is_downloadable) {
                $table[$row][$col] .= '<div class="price"><div id="productPrice' . $product->virtuemart_product_id . '" class="product-price">';
                if ($product->product_unit && VmConfig::get('vm_price_show_packaging_pricelabel')) {
                    $table[$row][$col] .= "<strong>" . JText::_('COM_VIRTUEMART_CART_PRICE_PER_UNIT') . ' (' . $product->product_unit . "):</strong>";
                }
                if (abs($product->prices['discountAmount']) > 0) {
                    $table[$row][$col] .= '<span class="WithoutTax">' . $currency->createPriceDiv('basePriceWithTax', '', $product->prices) . '</span>';
                }
                $table[$row][$col] .= $currency->createPriceDiv('salesPrice', '', $product->prices);
                $table[$row][$col] .= '</div></div>';
                $table[$row][$col] .= '</div>';
            } else {
                if ($product->prices['salesPrice'] <= 0 and VmConfig::get('askprice', 1)) {
                    $call_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&task=askquestion&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id . '&tmpl=component');
                    $table[$row][$col] .= '<div class="call-a-question">';
                    $table[$row][$col] .= "<a class='call modal' rel=\"{handler: 'iframe',size: {x: 460, y: 550}}\"  href='" . $call_link . "' >" . JText::_('COM_VIRTUEMART_PRODUCT_ASKPRICE') . "</a>";
                    $table[$row][$col] .= ' </div>';
                }
            }

            $row = 4;
            $table[$row][$col] = '';
            $table[$row][$col] .= '<div class="comare_desc">';
            if (!empty($product->product_s_desc)) {
                $table[$row][$col] .= '<div class="product_s_desc">' . shopFunctionsF::limitStringByWord($product->product_s_desc, 150, '...') . '</div>';
            }
            $table[$row][$col] .= '</div>';
            $row = 5;
            $table[$row][$col] = '';
            $table[$row][$col] .= '<div class="comare_brand">';
            if (!empty($text)) {
                $table[$row][$col] .= $text;
            } else {
                $table[$row][$col] .= JText::_('EMPTY');
            }
            $table[$row][$col] .= '</div>';

            $row = 6;
            $table[$row][$col] = '';
            $table[$row][$col] .= '<div class="comare_stock">';
            if ($product->product_in_stock >= 1) {
                $table[$row][$col] .= '<div class="stock"></span><span class="green">' . JText::_('DR_IN_STOCK') . '</span> ' . $product->product_in_stock . ' ' . JText::_('DR_ITEMS') . '</div>';
            } else {
                $table[$row][$col] .= '<div class="stock"></span><span class="red">' . JText::_('DR_OUT_STOCK') . '</span></div>';
            }
            $table[$row][$col] .= '</div>';

            $row = 7;
            $table[$row][$col] = '';
            $table[$row][$col] .= '<div class="comare_code">';
            $table[$row][$col] .= '<div class="code"></span>' . $product->product_sku . '</div>';
            $table[$row][$col] .= '</div>';

            $row = 8;
            $table[$row][$col] = '';
            $table[$row][$col] .= '<div class="comare_weight">';
            if ($product->product_weight > 0) {
                $table[$row][$col] .= '<div>' . $product->product_weight . $product->product_weight_uom . '</div>';
            } else {
                $table[$row][$col] .= '<div>empty</div>';
            }
            $table[$row][$col] .= '</div>';
            $row = 9;
            $table[$row][$col] = '';
            $table[$row][$col] .= '<div class="comare_dim">';
            if (($product->product_length > 0) || ($product->product_width > 0) || ($product->product_height > 0)) {
                $table[$row][$col] .= '<div>' . $product->product_length . $product->product_lwh_uom . ' x ' . $product->product_width . $product->product_lwh_uom . ' x ' . $product->product_height . $product->product_lwh_uom . '</div>';
            } else {
                $table[$row][$col] .= '<div>' . JText::_('EMPTY') . '</div>';
            }
            $row = 10;
            $table[$row][$col] = '';
            $table[$row][$col] .= '</div>';
            $table[$row][$col] .= '<div class="comare_pack">';
            if ($product->product_packaging > 0) {
                $table[$row][$col] .= '<div>' . $product->product_packaging . $product->product_unit . '</div>';
            } else {
                $table[$row][$col] .= '<div>' . JText::_('EMPTY') . '</div>';
            }
            $table[$row][$col] .= '</div>';
            $row = 11;
            $table[$row][$col] = '';
            $table[$row][$col] .= '<div class="comare_unit">';
            if ($product->product_box) {
                $table[$row][$col] .= '<div>' . $product->product_box . '</div>';
            } else {
                $table[$row][$col] .= JText::_('EMPTY');
            }
            $table[$row][$col] .= '</div>';

            $row = 12;
            $table[$row][$col] = '';
            $table[$row][$col] .= '<div class="comare_action">';


            if (!VmConfig::get('use_as_catalog', 0) and !empty($product->prices['salesPrice'])) {
                $table[$row][$col] .= '<div class="addtocart-area2">';
                if (($product->product_in_stock < 1) || ($product->product_in_stock < $product->min_order_level) || ($product->product_in_stock - $product->product_ordered) < $product->min_order_level) {
                    $table[$row][$col] .= '<a class="addtocart-button" title="' . JText::_('COM_VIRTUEMART_CART_NOTIFY') . '" href="' . JRoute::_('index.php?option=com_virtuemart&view=productdetails&layout=notify&virtuemart_product_id=' . $product->virtuemart_product_id) . '">' . JText::_('COM_VIRTUEMART_CART_NOTIFY') . '<span></span></a>';
                } else {

                    $table[$row][$col] .= '<form method="post" class="product" action="index.php" id="addtocartproduct' . $product->virtuemart_product_id . '">';
                    $table[$row][$col] .= '<div class="addtocart-bar2">';
                    if ((!empty($product->customsChilds)) || (!empty($product->customfieldsCart))) {
                        $table[$row][$col] .= '<span class="attributes"><b>*</b> Product has attributes</span>';
                        $table[$row][$col] .= '<div class="addtocart_button2">';
                        $table[$row][$col] .= '<a class="addtocart-button" title="' . JText::_('DR_VIRTUEMART_SELECT_OPTION') . '" href="' . JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id) . '">' . JText::_('DR_VIRTUEMART_SELECT_OPTION') . '<span></span></a>';
                        $table[$row][$col] .= '</div>';
                    } else {
                        $table[$row][$col] .= '<span class="box-quantity">';
                        $table[$row][$col] .= '<span class="quantity-box">';
                        if (isset($product->step_order_level) && (int)$product->step_order_level > 0) {
                            $minorder = $product->step_order_level;
                        } else if (!empty($product->min_order_level)) {
                            $minorder = $product->min_order_level;
                        } else {
                            $minorder = '1';
                        }
                        $table[$row][$col] .= '<input type="text" class="quantity-input js-recalculate" name="quantity[]" value="' . $minorder . '"/>';
                        $table[$row][$col] .= '</span>';
                        $table[$row][$col] .= '<span class="quantity-controls">';
                        $table[$row][$col] .= '<i class="quantity-controls quantity-plus">+</i>';
                        $table[$row][$col] .= '<i class="quantity-controls quantity-minus">-</i>';
                        $table[$row][$col] .= '</span>';
                        $table[$row][$col] .= '</span>';
                        $table[$row][$col] .= '<div class="clear"></div>';
                        $table[$row][$col] .= '<span class="addtocart_button2">';
                        $table[$row][$col] .= '<input type="submit" value="' . JText::_('COM_VIRTUEMART_CART_ADD_TO') . '" title="' . JText::_('COM_VIRTUEMART_CART_ADD_TO') . '" class="addtocart-button cart-click">';
                        $table[$row][$col] .= '</span>';

                        $table[$row][$col] .= '<input type="hidden" class="pname" value="' . $product->product_name . '"/>';
                        $table[$row][$col] .= '<input type="hidden" name="option" value="com_virtuemart" />';
                        $table[$row][$col] .= '<input type="hidden" name="view" value="cart" />';
                        $table[$row][$col] .= '<noscript><input type="hidden" name="task" value="add" /></noscript>';
                        $table[$row][$col] .= ' <input type="hidden" class="item_id" name="virtuemart_product_id[]" value="' . $product->virtuemart_product_id . '"/>';
                        $table[$row][$col] .= '<input type="hidden" name="virtuemart_category_id[]" value="' . $product->virtuemart_category_id . '" />';

                        $table[$row][$col] .= '</div>';
                    }

                    $table[$row][$col] .= '</form>';
                }
                $table[$row][$col] .= ' </div>';
            }


            $table[$row][$col] .= '<div class="clear"></div>';
            $table[$row][$col] .= '<div class="remcompare"><a class="compare_del" title="remove"  onclick="removeCompare(' . $product->virtuemart_product_id . ');"><i class="fa fa-times"></i>remove</a></div>';
            $table[$row][$col] .= '</div>';

            $row = 13;
            if (isset($compare_fields)) {
                $pfld = "@";
                foreach ($compare_fields as $field_no => $field_name) {
                    if (isset($compare_fields_product[$product->virtuemart_product_id][$field_no])) {
                        $fld = $compare_fields_product[$product->virtuemart_product_id][$field_no];
                        $table[$row][$col] = $fld;
                    } else {
                        $table[$row][$col] = '';
                    }
                    $row++;
                }
            }
            $col++;
        }

    }
    for ($r = 0; $r < $rowall; $r++) {
        $trclass[$r] = '';
        for ($c = 2; $c < $col; $c++) {
            if ($table[$r][$c] != $table[$r][$c - 1]) {
                $trclass[$r] = ' tr_diff';
            }

        }

    }
    ?>
    <table id="compare_list_prod">
        <tbody>
        <?php
        for ($r = 0; $r < $rowall; $r++) {
            echo '<tr class="items' . $r . '">';
            for ($c = 0; $c < $col; $c++) {
                $class = $tdclass[$c];
                if ($c > 0) $class .= $trclass[$r];
                echo '<td class="cp ' . $class . '" >';
                if (isset($table[$r][$c]))
                    echo $table[$r][$c];
                else
                    echo '';
                echo '</td>';
            }
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
    </div>
    <h3 class="module-title compare no-products" style="display:none;">
        <i class="fa fa-info-circle"></i><?php echo JText::_('COM_VIRTUEMART_ITEMS_NO_PRODUCTS_COMPARE'); ?>
    </h3>
<?php
} else {
    echo '<h3 class="module-title compare no-products" ><i class="fa fa-info-circle"></i>' . JText::_('COM_VIRTUEMART_ITEMS_NO_PRODUCTS_COMPARE') . '</h3>';
}

?>
</div>
    