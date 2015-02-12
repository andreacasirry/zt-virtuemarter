<?php
/**
 * @package    VirtueMart Zooex
 * @subpackage VirtueMart Zooex Comparelist Module
 * @author       ZooTemplate.com
 * @link http://zootemplate.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */
//error_reporting('E_ALL');
defined('_JEXEC') or die('Restricted access');

$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::root() . 'modules/mod_zt_comparelist/assets/css/compare.css');
$doc->addScript(JURI::root() . 'modules/mod_zt_comparelist/assets/js/compare.js');

JFactory::getLanguage()->load('com_ztvirtuemarter');
$items = JFactory::getApplication()->getMenu('site')->getItems('component', 'com_ztvirtuemarter');
$itemid = '';
foreach ($items as $item) {
    if ($item->query['view'] === 'comparelist') {
        $itemid = $item->id;
    }
}
?>

<div class="ajax-dropdown vmgroup<?php echo $params->get('moduleclass_sfx') ?>" id="mod_compare">
    <div class="seldcomp" id="butseldcomp">
        <?php if(plgSystemZtvirtuemarter::getZtvirtuemarterSetting()->enable_compare == '1') {?>
        <a class="btn-compare"
           href="<?php echo JRoute::_('index.php?option=com_ztvirtuemarter&view=comparelist&Itemid=' . $itemid . ''); ?>">
            <i class="fa fa-files-o hover-dropdown"></i>
            <span>
		   <?php
           echo count($_SESSION['compare_ids']);
           ?></span>
        </a>
        <?php } ?>
    </div>
    <div class="zt-cart-inner">
        <div class="vmproduct">
            <?php
            if (count($prods) > 0) {
                ?>
                <?php
                foreach ($prods as $product) {
                    ?>
                    <div id="compare_prod_<?php echo $product->virtuemart_product_id; ?>"
                         class="modcompareprod clearfix">
                        <div class="compare-product-img">
                            <a href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id); ?>">
                                <img src="<?php if (!empty($product->file_url_thumb)) {
                                    echo JURI::base() . $product->file_url_thumb;
                                } else {
                                    echo JURI::base() . 'images/stories/virtuemart/noimage.gif';
                                } ?>" alt="<?php echo $product->product_name; ?>"
                                     title="<?php echo $product->product_name; ?>"/>
                            </a>
                        </div>
                        <div class="compare-product-detail">
                            <div class="name">
                                <?php echo JHTML::link($product->link, $product->product_name); ?>
                            </div>
                            <div class="remcompare">
                                <a class="tooltip-1" title="remove"
                                   onclick="removeCompare('<?php echo $product->virtuemart_product_id; ?>');">
                                    <i class="fa fa-times"></i><?php echo JText::_('REMOVE'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            <?php
            } else {
                ?>
                <div class="not_text compare"><?php echo JText::_('YOU_HAVE_NO_PRODUCT_TO_COMPARE'); ?></div>
            <?php
            }
            ?>
        </div>
    </div>
</div>
	
