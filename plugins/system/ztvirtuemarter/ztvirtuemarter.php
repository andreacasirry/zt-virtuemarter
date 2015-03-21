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
        }
    }


    public static function addCompareButton($product, $type = null)
    {
        $mainframe = JFactory::getApplication();
        $compareIds = $mainframe->getUserState("com_ztvirtuemarter.site.compareIds", array());
        if (self::getZtvirtuemarterSetting()->enable_compare == '1')
            if (is_file(JPATH_BASE . "/components/com_ztvirtuemarter/template/comparelist.tpl" . $type . ".php")) :?>
                <div class="compare_cat list_compare<?php echo $product->virtuemart_product_id; ?>">
                    <?php require(JPATH_BASE . "/components/com_ztvirtuemarter/template/comparelist.tpl" . $type . ".php"); ?>
                </div>
            <?php
            endif;
    }

    public static function addWishlistButton($product, $type = null)
    {
        if (!class_exists('ZtvirtuemarterModelWishlist')) require(JPATH_SITE . '/components/com_ztvirtuemarter/models/wishlist.php');
        $mainframe = JFactory::getApplication();
        $wishlistIds = $mainframe->getUserState("com_ztvirtuemarter.site.wishlistIds", array());
        if (self::getZtvirtuemarterSetting()->enable_wishlist == '1')
            if (is_file(JPATH_BASE . "/components/com_ztvirtuemarter/template/wishlists.tpl" . $type . ".php")) : ?>
                <div class="wishlist list_wishlists<?php echo $product->virtuemart_product_id; ?>">
                    <?php require(JPATH_BASE . "/components/com_ztvirtuemarter/template/wishlists.tpl" . $type . ".php"); ?>
                </div>
            <?php
            endif;
    }

    public static function getZtvirtuemarterSetting()
    {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__ztvirtuemarter'));
        $query->where($db->quoteName('id') . ' = 1');
        $db->setQuery($query);
        $results = $db->loadObjectList();

        if (isset($results[0]) && !empty($results[0]->setting)) {
            return json_decode($results[0]->setting);
        }
        return json_decode('{"enable_wishlist":"1","enable_compare":"1","enable_quickview":"1"}');
    }
}

?>