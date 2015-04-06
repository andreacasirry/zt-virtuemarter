<?php

/**
 * @package    ZT VirtueMarter
 * @subpackage ZT VirtueMarter Plugins
 * @author       ZooTemplate.com
 * @link http://zootemplate.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */
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
    }

    public function onBeforeRender()
    {
        if (!class_exists('ZtvirtuemarterModelWishlist')) require(JPATH_SITE . '/components/com_ztvirtuemarter/models/wishlist.php');
        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();
        if (!($app->isAdmin())) {
            $wishlistModel = new ZtvirtuemarterModelWishlist();
            $wishlistModel->updateCurrentWishlist();
            $doc->addScript(JURI::root() . '/plugins/system/ztvirtuemarter/assets/js/ajax-cart.js');
            if (self::getZtvirtuemarterSetting()->enable_quickview == '1') {
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
                $doc->addScript(JURI::root() . '/plugins/system/ztvirtuemarter/assets/js/quickview.js');
                $doc->addStyleSheet(JURI::root() . '/plugins/system/ztvirtuemarter/assets/css/quickview.css');
            }
            if (self::getZtvirtuemarterSetting()->enable_countdown == '1')
                $doc->addScript(JURI::root() . '/plugins/system/ztvirtuemarter/assets/js/jquery.countdown.min.js');
            if (self::getZtvirtuemarterSetting()->enable_photozoom == '1')
                $doc->addScript(JURI::root() . '/plugins/system/ztvirtuemarter/assets/js/jquery.elevateZoom-3.0.8.min.js');
        }
    }


    public static function addCompareButton($product, $type = null)
    {
        $mainframe = JFactory::getApplication();
        $compareIds = $mainframe->getUserState("com_ztvirtuemarter.site.compareIds", array());
        if (self::getZtvirtuemarterSetting()->enable_compare == '1'): ?>
            <div class="compare_cat list_compare<?php echo $product->virtuemart_product_id; ?>">
                <a class="compare-label add_compare hasTooltip <?php echo in_array($product->virtuemart_product_id, $compareIds) ? 'go_to_compare active' : ''; ?>"
                   title="<?php echo JText::_('DR_ADD_TO_COMPARE'); ?>"
                   onclick="zo2.compare.add('<?php echo $product->virtuemart_product_id; ?>');">
                    <i class="fa fa-files-o"></i>
                    <span><?php echo JText::_("DR_ADD_TO_COMPARE"); ?></span>
                </a>
            </div>
        <?php endif;
    }

    public static function addWishlistButton($product)
    {
        if (!class_exists('ZtvirtuemarterModelWishlist')) require(JPATH_SITE . '/components/com_ztvirtuemarter/models/wishlist.php');
        $mainframe = JFactory::getApplication();
        $wishlistIds = $mainframe->getUserState("com_ztvirtuemarter.site.wishlistIds", array());
        if (self::getZtvirtuemarterSetting()->enable_wishlist == '1'): ?>

            <div class="wishlist list_wishlists<?php echo $product->virtuemart_product_id; ?>">
                <?php
                $user = JFactory::getUser();

                if ($user->guest) :
                    ?>
                    <a class="add_wishlist hasTooltip <?php echo in_array($product->virtuemart_product_id, $wishlistIds) ? 'go_to_whishlist active' : ''; ?>"
                       title="<?php echo JText::_('ADD_TO_WHISHLIST'); ?>"
                       onclick="zo2.wishlist.add('<?php echo $product->virtuemart_product_id; ?>');">
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
                       onclick="zo2.wishlist.add('<?php echo $product->virtuemart_product_id; ?>');">
                        <i class="fa fa-heart-o"></i>
                        <span><?php echo JText::_("ADD_TO_WHISHLIST"); ?></span>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif;
    }

    public static function getZtvirtuemarterSetting()
    {
        $application = JFactory::getApplication();
        $setting = $application->getUserState("com_ztvirtuemarter.site.setting");
        if(!empty($setting)) {
            return json_decode($setting);
        } else {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from($db->quoteName('#__ztvirtuemarter'));
            $query->where($db->quoteName('id') . ' = 1');
            $db->setQuery($query);
            $results = $db->loadObjectList();

            if (isset($results[0]) && !empty($results[0]->setting)) {
                $application->setUserState("com_ztvirtuemarter.site.setting", $results[0]->setting);
                return json_decode($results[0]->setting);
            }
        }
    }

    public static function getCountdown($product)
    {
        if (self::getZtvirtuemarterSetting()->enable_countdown == '1' && ($product->prices['product_price_publish_down'] > 0)): ?>
            <div class="countdown-<?php echo $product->virtuemart_product_id; ?>">
                <?php $time = strtotime($product->prices['product_price_publish_down']); ?>
                <div class="count_holder">
                    <div id="product-countdown-<?php echo $product->virtuemart_product_id ?>"></div>
                    <script type="text/javascript">
                        (function ($) {
                            $('#product-countdown-<?php echo $product->virtuemart_product_id ?>').countdown("<?php echo date('Y/m/d', $time)?>", function (event) {
                                $(this).text(event.strftime('%D days %H:%M:%S')
                                );
                            });
                        })(jQuery)
                    </script>
                </div>
            </div>
        <?php
        endif;
    }
}

?>