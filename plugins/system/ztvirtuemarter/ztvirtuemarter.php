<?php
/**
 * @package    VirtueMart Zooex
 * @subpackage VirtueMart Zooex Plugins
 * @author       ZooTemplate.com
 * @link http://zootemplate.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */
//error_reporting('E_ALL');
class plgSystemZtvirtuemarter extends JPlugin
{
    /**
     * Class Constructor
     * @param object $subject
     * @param array $config
     */
    public function __construct(& $subject, $config)
    {
        parent::__construct($subject, $config);
        $this->document = JFactory::getDocument();
        $this->loadLanguage();
        $jlang = JFactory::getLanguage();
        $jlang->load('com_virtuemart', JPATH_SITE, $jlang->getDefault(), true);
        $jlang->load('com_virtuemart', JPATH_SITE, null, true);

    }

    function onBeforeRender()
    {
        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();
        $user = JFactory::getUser();
        if (!($app->isAdmin())) {
            if (!$user->guest) {
                if (isset($_SESSION['wishlist_ids'])) {
                    $dbIds = $_SESSION['wishlist_ids'];
                    $db = JFactory::getDBO();
                    $q = "SELECT virtuemart_product_id FROM #__wishlists WHERE userid =" . $user->id;
                    $db->setQuery($q);
                    $allproducts = $db->loadAssocList();
                    foreach ($allproducts as $productbd) {
                        $allprod['ids'][] = $productbd['virtuemart_product_id'];
                    }
                    for ($r = 0; $r < count($dbIds); $r++) {
                        if (!in_array($dbIds[$r], $allprod['ids'])) {
                            $q = "INSERT INTO `#__wishlists`
								(virtuemart_product_id,userid )
								VALUES
								('" . $dbIds[$r] . "','" . $user->id . "') ";
                            //var_dump ($dbIds[$r]);
                            $db->setQuery($q);
                            $db->queryBatch();
                        }
                    }
                    unset($_SESSION['wishlist_ids']);
                }
            }

            if(self::getZtvirtuemarterSetting()->enable_quickview == '1') {
                $show_quicktext = JText::_('COM_VIRTUEMART_QUICK');
                $jsq = 'jQuery(document).ready(function () {
                        var show_quicktext="' . $show_quicktext . '";
                        //jQuery("ul.layout .product-box , ul.layout2 .product-box").each(function(indx, element){
                        jQuery(".zt-product-content").each(function(indx, element){
                            var my_product_id = jQuery(this).find(".quick_ids").val();
                            if(my_product_id){
                                if(jQuery(this).find(".quick_btn").length < 1) {
                                    jQuery(this).append("<div class=\'quick_btn\' onClick =\'quick_btn("+my_product_id+")\'><i class=\'fa fa-search\'></i><span>"+show_quicktext+"</span></div>");
                                }
                            }
                            jQuery(this).find(".quick_id").remove();
                        });
                    });';
                $doc->addScriptDeclaration($jsq);
                $doc->addScript(JURI::root(true) . '/plugins/system/ztvirtuemarter/assets/js/custom.js');
                $doc->addStyleSheet(JURI::root(true) . '/plugins/system/ztvirtuemarter/assets/css/more_custom.css');
            }
        }
    }

    function onAfterInitialise()
    {
        if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        $input = JFactory::getApplication()->input;
        if ($input->getCmd('action') !== 'test') {
            return;
        }
        $region = $input->getInt('product_id', 0);
        if ($region) {
            if (!class_exists('VmConfig')) require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php');
            if (!class_exists('calculationHelper')) require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/calculationh.php');
            if (!class_exists('CurrencyDisplay')) require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/currencydisplay.php');
            if (!class_exists('VirtueMartModelVendor')) require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/models/vendor.php');
            if (!class_exists('VmImage')) require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/image.php');
            if (!class_exists('shopFunctionsF')) require(JPATH_SITE . '/components/com_virtuemart/helpers/shopfunctionsf.php');
            if (!class_exists('calculationHelper')) require(JPATH_COMPONENT_SITE . '/helpers/cart.php');
            if (!class_exists('vmCustomPlugin')) require(JPATH_VM_PLUGINS . '/vmcustomplugin.php');

            if (!class_exists('VirtueMartModelProduct')) {
                JLoader::import('product', JPATH_ADMINISTRATOR . '/components/com_virtuemart/models');
            }
            if (!class_exists('VirtueMartModelRatings')) {
                JLoader::import('ratings', JPATH_ADMINISTRATOR . '/components/com_virtuemart/models');
            }
            VmConfig::loadConfig();
            VmConfig::loadJLang('com_virtuemart', true);
            $product_model = VmModel::getModel('product');
            $prods = array($_GET['product_id']);

            $product = $product_model->getProduct($prods);
            $product_model->addImages($product);
            $ratingModel = VmModel::getModel('ratings');
            $customfieldsModel = VmModel::getModel('Customfields');
            $product->customfields = $customfieldsModel->getCustomEmbeddedProductCustomFields($product->allIds);
            if ($product->customfields) {
                if (!class_exists('vmCustomPlugin')) {
                    require(JPATH_VM_PLUGINS . DS . 'vmcustomplugin.php');
                }
                $customfieldsModel->displayProductCustomfieldFE($product, $product->customfields);
            }

            $mainframe = Jfactory::getApplication();
            $pathway = $mainframe->getPathway();
            $task = JRequest::getCmd('task');
            $virtuemart_currency_id = $mainframe->getUserStateFromRequest("virtuemart_currency_id", 'virtuemart_currency_id', JRequest::getInt('virtuemart_currency_id', 0));
            $currency = CurrencyDisplay::getInstance();
            $product->event = new stdClass();
            $product->event->afterDisplayTitle = '';
            $product->event->beforeDisplayContent = '';
            $product->event->afterDisplayContent = '';
            if (VmConfig::get('enable_content_plugin', 0)) {
                shopFunctionsF::triggerContentPlugin($product, 'productdetails', 'product_desc');
            }
            ?>

            <div id="quick-view" style="display:none;">
            <div id="quick_view_close">
                <i class="fa fa-times"></i>
            </div>

            <?php
            $discont = $product->prices['discountAmount'];
            $discont = abs($discont);
            $show_price = $currency->createPriceDiv('salesPrice', '', $product->prices);
            ?>
            <div id="quick-view-scroll">

            <div id="productdetailsview" class="productdetails-view quick">
            <script type="text/javascript" src="<?php echo JUri::base(); ?>plugins/system/ztvirtuemarter/assets/js/more_custom.js"></script>
            <script type="text/javascript" src="<?php echo JUri::base(); ?>plugins/system/ztvirtuemarter/assets/js/shortcodes.js"></script>
            <div class="wrapper2">
            <div class="fleft">
                <div class="image_show_quick">
                    <?php
                    $images = $product->images;
                    $main_image_url = JURI::root() . '' . $images[0]->file_url; // [file_title][file_description][file_meta]
                    $main_image_url2 = JURI::root() . '' . $images[0]->file_url_thumb;
                    if ($images[0]->published !== 0) {
                        $main_image_url = JURI::root(true) . '/' . $images[0]->file_url;
                    } else {
                        $main_image_url = JURI::root(true) . '/images/stories/virtuemart/img/noimage.gif';
                    }

                    if (!empty($images[0]->file_url_thumb)) {
                        $main_image_url2 = JURI::root(true) . '/' . $images[0]->file_url_thumb;
                    } else {
                        $main_image_url2 = JURI::root(true) . '/images/stories/virtuemart/img/noimage.gif';
                    }

                    $main_image_title = $images[0]->file_title; // [file_title][file_description][file_meta]
                    $main_image_description = $images[0]->file_description; // [file_title][file_description][file_meta]
                    $main_image_alt = $images[0]->file_meta; // [file_title][file_description][file_meta]
                    $vm_id = $product->virtuemart_product_id;
                    $discont = $product->prices['discountAmount'];
                    $discont = abs($discont);
                    if (!empty($product->images[0])) {
                        ?>
                        <img style="display:none!important;" src="<?php echo $main_image_url ?>" class="big_img" id="Img_to_Js_<?php echo $vm_id ?>"/>
                        <div class="main-image-quick">
                            <div class="lbl-box2">
                                <?php
                                $stockhandle = VmConfig::get('stockhandle', 'none');
                                if (($stockhandle == 'disableit' or $stockhandle == 'disableadd') and (($product->product_in_stock - $product->product_ordered) < 1) ||
                                    (($product->product_in_stock - $product->product_ordered) < $product->min_order_level)
                                ) {
                                    ?>
                                    <div class="soldafter"></div>
                                    <div class="soldbefore"></div>
                                    <div class="sold"><?php echo JText::_('DR_SOLD'); ?></div>
                                <?php } ?>
                            </div>
                            <div class='lbl-box'>
                                <?php if ($product->prices['override'] == 1 && ($product->prices['product_price_publish_down'] > 0)) { ?>
                                    <div class='offafter'></div>
                                    <div class='offbefore'></div>
                                    <div class='discount limited'><?php echo JText::_('DR_LIMITED_OFFER'); ?></div>
                                <?php } elseif ($discont > 0 && $product->product_sales < 20) { ?>
                                    <div class='discafter'></div>
                                    <div class='discbefore'></div>
                                    <div class='discount'><?php echo JText::_('DR_SALE'); ?></div>
                                <?php } elseif ($product->product_sales > 20) { ?>
                                    <div class='hitafter'></div>
                                    <div class='hitbefore'></div>
                                    <div class='hit'><?php echo JText::_('DR_HOT'); ?></div>
                                <?php } ?>
                            </div>
                            <img src="<?php echo $main_image_url ?>" data-zoom-image="<?php echo $main_image_url ?>" title="<?php echo $main_image_title ?>" alt="<?php echo $main_image_alt ?>" class="big_img" id="Img_zoom"/>

                            <?php
                            $j = count($images);
                            //add HTML
                            if ($j <= 3) {
                                $class = 'none';
                            } else {
                                $class = '';
                            }
                            if ($j > 1) {
                                ?>
                                <div id="gallery_01" class="additional-images <?php echo $class; ?>">
                                    <ul id="carousel" class=" quickview_carousel paginat jcarousel-skin-tango">
                                        <?php
                                        for ($i = 0; $i < $j; $i++) {
                                            ?>
                                            <li class="floatleft">
                                                <a href="#" data-image="<?php echo JURI::root() . '' . $images[$i]->file_url; ?>" data-zoom-image="<?php echo JURI::root() . '' . $images[$i]->file_url; ?>">
                                                    <img src="<?php echo JURI::root() . '' . $images[$i]->file_url_thumb; ?>"/>
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } // Product Main Image END ?>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="fright">
            <?php // Product Title  ?>
            <h1 class="title"><?php echo $product->product_name ?></h1>
            <?php // Product Title END  ?>
            <div class="rating">
                <?php
                $showRating = $ratingModel->showRating($product->virtuemart_product_id);
                if ($showRating == 'true') {
                    $rating = $ratingModel->getRatingByProduct($product->virtuemart_product_id);
                    if (!empty($rating)) {
                        $r = $rating->rating;
                    } else {
                        $r = 0;
                    }
                    $maxrating = VmConfig::get('vm_maximum_rating_scale', 5);
                    $ratingwidth = ($r * 100) / $maxrating; //I don't use round as percetntage with works perfect, as for me
                    ?>
                    <?php if (!empty($rating)) { ?>
                        <span class="vote">
                            <span title="" class="vmicon ratingbox" style="display:inline-block;">
                                <span class="stars-orange" style="width:<?php echo $ratingwidth; ?>%"></span>
                            </span>
                            <span class="rating-title"><?php echo JText::_('COM_VIRTUEMART_RATING') . ' ' . round($rating->rating, 2) . '/' . $maxrating; ?></span>
                        </span>
                    <?php } else { ?>
                        <span class="vote">
                            <span title="" class="vmicon ratingbox" style="display:inline-block;">
                                <span class="stars-orange" style="width:<?php echo $ratingwidth; ?>%"></span>
                            </span>
                            <span class="rating-title"><?php echo JText::_('COM_VIRTUEMART_RATING') . ' ' . JText::_('COM_VIRTUEMART_UNRATED') ?></span>
                        </span>
                    <?php }
                } ?>
            </div>
            <?php
            // Manufacturer of the Product
            if (!empty($product->virtuemart_manufacturer_id)) {
                ?>
                <div class="manufacturer">
                    <?php
                    $text = $product->mf_name;
                    ?>
                    <span class="bold"><?php echo JText::_('COM_VIRTUEMART_PRODUCT_DETAILS_MANUFACTURER_LBL') ?></span><?php echo $text ?>
                </div>
            <?php } ?>
            <?php
            if ($product->product_in_stock >= 1) {
                echo '<div class="stock"><span class="bold">' . JText::_('DR_AVAILABILITY_NEW') . ':</span><i class="green">' . JText::_('DR_IN_STOCK_NEW') . '</i>&nbsp;<b>' . $product->product_in_stock . '&nbsp;' . JText::_('DR_ITEMS_NEW') . '</b></div>';
            } else {
                echo '<div class="stock"><span class="bold">' . JText::_('DR_AVAILABILITY_NEW') . ':</span><i class="red">' . JText::_('DR_OUT_STOCK_NEW') . '</i>&nbsp;<b>' . $product->product_in_stock . '&nbsp;' . JText::_('DR_ITEMS_NEW') . '</b></div>';
            }
            echo '<div class="code"><span class="bold">' . JText::_('DR_PRODUCT_CODE_NEW') . ':</span>' . $product->product_sku . '</div>';
            ?>
            <?php if (!($product->prices['product_price_publish_down'] > 0)) { ?>
                <?php if ((!empty($product->prices['salesPrice'])) && !$product->images[0]->file_is_downloadable) { ?>
                    <div class="Price">
                    <div class="product-price" id="productPrice<?php echo $product->virtuemart_product_id ?>">
                        <?php

                        if ($product->product_unit && VmConfig::get('vm_price_show_packaging_pricelabel')) {
                            echo "<strong>" . JText::_('COM_VIRTUEMART_CART_PRICE_PER_UNIT') . ' (' . $product->product_unit . "):</strong>";
                        }
                        if ($discont > 0) {
                            echo $currency->createPriceDiv('basePriceWithTax', '', $product->prices);
                        }
                        echo $currency->createPriceDiv('salesPrice', '', $product->prices);
                        ?>
                    </div>

                    </div><?php
                } else {
                    if ($product->prices['salesPrice'] <= 0 and VmConfig::get('askprice', 1)) {
                        ?>
                        <div class="call-a-question">
                            <a class="call modal" rel="{handler: 'iframe', size: {x: 460, y: 550}}" href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=productdetails&task=askquestion&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id . '&tmpl=component'); ?>">
                                <?php echo JText::_('COM_VIRTUEMART_PRODUCT_ASKPRICE') ?>
                            </a>
                        </div>
                    <?php }
                } ?>
            <?php
            } // Product Packaging END
            ?>
            <?php if ($product->prices['override'] == 1 && ($product->prices['product_price_publish_down'] > 0)) { ?>
                <div class="time-box">
                    <div class="indent">
                        <?php if ((!empty($product->prices['salesPrice'])) && !$product->images[0]->file_is_downloadable) { ?>
                            <div class="price">
                                <div class="product-price"
                                     id="productPrice<?php echo $product->virtuemart_product_id ?>">
                                    <?php
                                    if ($product->prices['salesPrice'] > 0) {
                                        ?>
                                        <span class="price-sale">
                                    <span class="text"><?php echo JText::_('DR_SPECIAL_DEAL_PRICE'); ?>:</span>
                                            <?php echo '<span class="sales">' . $currency->createPriceDiv('salesPrice', '', $product->prices) . '</span>'; ?>
                                    </span>
                                    <?php } ?>
                                    <?php
                                    if ($product->prices['basePriceWithTax'] > 0) {
                                        ?>
                                        <span class="price-old">
                                    <span class="text"><?php echo JText::_('DR_OLD_PRICE'); ?>:</span>
                                            <?php echo '<span class="WithoutTax">' . $currency->createPriceDiv('basePriceWithTax', '', $product->prices) . '</span>'; ?>
                                    </span>
                                    <?php } ?>
                                    <span class="price_save">
                                     <span class="text"><?php echo JText::_('DR_YOU_ARE_SAVING'); ?>:</span>
                                        <?php echo '<span class="discount">' . $currency->createPriceDiv('discountAmount', '', $product->prices) . '</span>'; ?>
                                    </span>

                                    <div class="clear"></div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="bzSaleTimer">
                            <?php
                            $data = $product->prices['product_price_publish_down'];
                            $data = explode(' ', $data);
                            $time = explode(':', $data[1]);
                            $data = explode('-', $data[0]);
                            //var_dump($data);
                            $year = $data[0];
                            $month = $data[1];
                            $data = $data[2];
                            //var_dump($time);
                            ?>
                            <div class="count_holder">
                                <div class="count_info"><?php echo JText::_('DR_LIMITED2') ?></div>
                                <div id="CountSmall<?php echo $product->virtuemart_product_id ?>" class="count_border">
                                    <script type="text/javascript">
                                        jQuery(function () {
                                            jQuery('.count_border').countdown({
                                                until: new Date(<?php echo $year; ?>, <?php echo $month; ?> -1, <?php echo $data; ?>),
                                                labels: ['Years', 'Months', 'Weeks', '<?php echo JText::_('DR_DAYS')?>', '<?php echo JText::_('DR_HOURS')?>', '<?php echo JText::_('DR_MINUTES')?>', '<?php echo JText::_('DR_SECONDS')?>'],
                                                labels1: ['Years', 'Months', 'Weeks', '<?php echo JText::_('DR_DAYS')?>', '<?php echo JText::_('DR_HOURS')?>', '<?php echo JText::_('DR_MINUTES')?>', '<?php echo JText::_('DR_SECONDS')?>'],
                                                compact: false});

                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bzSaleTimerDesc"><?php echo JText::_('DR_HURRY'); ?>
                        <div>&nbsp;&nbsp;<?php echo $product->product_in_stock ?>&nbsp;<?php echo JText::_('DR_HURRY_ITEMS'); ?></div>
                    </div>
                    <div class="bzSaleTimerDesc2">
                        <div><?php echo $product->product_ordered ?></div>
                        &nbsp;&nbsp;<?php echo JText::_('DR_BOOKED'); ?>
                    </div>
                    <div class="clear"></div>
                </div>
            <?php } ?>

            <div class="product-box2">
                <?php
                if (isset($product->step_order_level))
                    $step = $product->step_order_level;
                else
                    $step = 1;
                if ($step == 0)
                    $step = 1;
                $alert = JText::sprintf('COM_VIRTUEMART_WRONG_AMOUNT_ADDED', $step);
                if (!VmConfig::get('use_as_catalog', 0) and !empty($product->prices['salesPrice'])) {
                    ?>
                    <div class="addtocart-area2 proddet">
                        <form method="post" class="product js-recalculate" action="<?php echo JURI::getInstance()->toString(); ?>">
                            <input name="quantity" type="hidden" value="<?php echo $step ?>"/>
                            <div class="product-custom<?php if (empty($product->customfields)) { echo ' none'; } ?>">
                                <?php
                                foreach ($product->customfields as $field) {
                                    if ($field->layout_pos == 'addtocart') { ?>
                                        <div class="product-fields">
                                            <div class="product-field product-field-type-<?php echo $field->field_type ?>">
                                                <div class="wrapper2">
                                                    <span class="product-fields-title"><b><?php echo JText::_($field->custom_title) ?></b></span>
                                                    <span class="product-field-display"><?php echo $field->display ?></span>
                                                </div>
                                                <span class="product-field-desc"><?php echo $field->custom_field_desc ?></span>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                }
                                ?>
                            </div>
                            <div class="addtocart-bar2">
                                <script type="text/javascript">
                                    function check(obj) {
                                        // use the modulus operator '%' to see if there is a remainder
                                        remainder = obj.value % <?php echo $step?>;
                                        quantity = obj.value;
                                        if (remainder != 0) {
                                            alert('<?php echo $alert?>!');
                                            obj.value = quantity - remainder;
                                            return false;
                                        }
                                        return true;
                                    }
                                </script>

                                <?php // Display the quantity box
                                $stockhandle = VmConfig::get('stockhandle', 'none');
                                if (($stockhandle == 'disableit' or $stockhandle == 'disableadd') and ($product->product_in_stock - $product->product_ordered) < 1) {
                                    ?>
                                    <a class="addtocart-button hasTooltip"
                                       title="<?php echo JText::_('COM_VIRTUEMART_CART_NOTIFY') ?>"
                                       href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=productdetails&layout=notify&virtuemart_product_id=' . $product->virtuemart_product_id); ?>"><?php echo JText::_('COM_VIRTUEMART_CART_NOTIFY') ?>
                                        <span></span>
                                    </a>
                                <?php } else { ?>
                                    <div class="wrapper">
                                        <div class="controls">
                                            <label for="quantity<?php echo $product->virtuemart_product_id; ?>"
                                                   class="quantity_box"><?php echo JText::_('COM_VIRTUEMART_CART_QUANTITY'); ?>
                                                : </label>
                                            <span class="box-quantity">
                                                <span class="quantity-box">
                                                    <input type="text" class="quantity-input js-recalculate" name="quantity[]"
                                                           value="<?php if (isset($product->step_order_level) && (int)$product->step_order_level > 0) {
                                                               echo $product->step_order_level;
                                                           } else if (!empty($product->min_order_level)) {
                                                               echo $product->min_order_level;
                                                           } else {
                                                               echo '1';
                                                           } ?>"/>
                                                </span>
                                            <span class="quantity-controls js-recalculate">
                                            <i class="quantity-controls quantity-plus">+</i>
                                            <i class="quantity-controls quantity-minus">-</i>
                                            </span>
                                            </span>
                                            <?php // Display the quantity box END ?>
                                        </div>
                                        <?php
                                        // Display the add to cart button
                                        ?>
                                        <span class="addtocart_button2">
                                        <?php if ($product->orderable) { ?>
                                            <button type="submit" value="<?php echo $button_lbl ?>" title="<?php echo JText::_('COM_VIRTUEMART_CART_ADD_TO'); ?>" class="addtocart-button cart-click"><?php echo JText::_('COM_VIRTUEMART_CART_ADD_TO'); ?><span>&nbsp;</span></button>
                                        <?php } else { ?>
                                            <span title="<?php echo JText::_('COM_VIRTUEMART_ADDTOCART_CHOOSE_VARIANT'); ?>" class="addtocart-button addtocart-button-disabled cart-click"><?php echo JText::_('COM_VIRTUEMART_ADDTOCART_CHOOSE_VARIANT'); ?></span>
                                        <?php } ?>
                                        </span>
                                        <?php if (is_file(JPATH_BASE . DS . "components/com_virtuemartzooex/template/wishlists.tpl.php")) { ?>
                                            <div class="wishlist list_wishlists<?php echo $product->virtuemart_product_id; ?>">
                                                <?php require(JPATH_BASE . DS . "components/com_virtuemartzooex/template/wishlists.tpl.php"); ?>
                                            </div>
                                        <?php } ?>
                                        <?php if (is_file(JPATH_BASE . DS . "components/com_virtuemartzooex/template/comparelist.tpl.php")) { ?>
                                            <div class="jClever compare_cat list_compare<?php echo $product->virtuemart_product_id; ?>">
                                                <?php require(JPATH_BASE . DS . "components/com_virtuemartzooex/template/comparelist.tpl.php"); ?>
                                            </div>
                                        <?php } ?>
                                    </div>

                                <?php } ?>

                                <div class="clear"></div>
                                <input type="hidden" class="pname" value="<?php echo htmlentities($product->product_name, ENT_QUOTES, 'utf-8') ?>"/>
                                <input type="hidden" name="option" value="com_virtuemart"/>
                                <input type="hidden" name="view" value="cart"/>
                                <noscript><input type="hidden" name="task" value="add"/></noscript>
                                <input type="hidden" class="item_id" name="virtuemart_product_id[]" value="<?php echo $product->virtuemart_product_id ?>"/>
                                <input type="hidden" name="virtuemart_category_id[]" value="<?php echo $product->virtuemart_category_id ?>"/>
                            </div>
                        </form>
                        <div class="clear"></div>
                    </div>
                <?php } ?>
                <div class="clear"></div>
            </div>
            </div>
            <div class="clear"></div>
            </div>

            <div class="example2_quick">
                <div class="tabs_show">
                    <div class="responsive-tabs2">
                        <?php if ($product->product_desc) { ?>
                            <h2> <?php echo JText::_('COM_VIRTUEMART_PRODUCT_DESC_TITLE'); ?></h2>
                            <div>
                                <?php echo '<div class="desc">' . $product->product_desc . '</div>'; ?>
                            </div>
                        <?php }
                        foreach ($product->customfields as $field) {
                            if ($field->layout_pos == 'custom') {
                                ?>
                                <h2> <?php echo JText::_('COM_VIRTUEMART_CUSTOM_TAB'); ?></h2>
                                <div>
                                    <div class="custom"><?php echo $field->display ?></div>
                                    ';
                                </div>
                            <?php
                            }
                        }
                        ?>

                    </div>
                </div>
            </div>
            </div>
            </div>

            <?php
            die();
            ?>
            </div>
        <?php }
    }

    public static function addCompareButton($product, $type = null)
    {
        if(self::getZtvirtuemarterSetting()->enable_compare == '1')
            if (is_file(JPATH_BASE . DS . "components/com_ztvirtuemarter/template/comparelist.tpl" . $type . ".php")) {
                ?>
                <div class="compare_cat list_compare<?php echo $product->virtuemart_product_id; ?>">
                    <?php require(JPATH_BASE . DS . "components/com_ztvirtuemarter/template/comparelist.tpl" . $type . ".php"); ?>
                </div>
            <?php
            }
    }

    public static function addWishlistButton($product, $type = null)
    {

            if (is_file(JPATH_BASE . DS . "components/com_ztvirtuemarter/template/wishlists.tpl" . $type . ".php")) {
                ?>
                <div class="wishlist list_wishlists<?php echo $product->virtuemart_product_id; ?>">
                    <?php require(JPATH_BASE . DS . "components/com_ztvirtuemarter/template/wishlists.tpl" . $type . ".php"); ?>
                </div>
            <?php
            }
    }

    public static function getZtvirtuemarterSetting() {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__ztvirtuemarter'));
        $query->where($db->quoteName('id') . ' = 1');
        $db->setQuery($query);
        $results = $db->loadObjectList();

        if(isset($results[0]) && !empty($results[0]->setting)) {
            return json_decode($results[0]->setting);
        }
        return json_decode('{"enable_wishlist":"1","enable_compare":"1","enable_quickview":"1"}');
    }
}

?>