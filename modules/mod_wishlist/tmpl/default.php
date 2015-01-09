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
JFactory::getLanguage()->load('com_virtuemartzooex');

$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::root(). 'modules/mod_wishlist/assets/fancybox/jquery.fancybox-1.3.4.css' );
$doc->addStyleSheet(JURI::root(). 'modules/mod_wishlist/assets/css/style.wishlist.css' );
$doc->addScript(JURI::root(). 'modules/mod_wishlist/assets/fancybox/jquery.fancybox-1.3.4.pack.js' );
$doc->addScript(JURI::root(). 'modules/mod_wishlist/assets/js/wishlist.js' );
$items = JFactory::getApplication()->getMenu( 'site' )->getItems( 'component', 'com_virtuemartzooex' );
foreach ( $items as $item ) {
    if($item->query['view'] === 'wishlists'){
        $itemid= $item->id;
    }
}
?>
<div class="vmgroup<?php echo $params->get('moduleclass_sfx') ?>" id="mod_wishlists">
    <div class="seldcomp" id="butseldwish" >
        <a class="btn_wishlist" href="<?php echo JRoute::_('index.php?option=com_virtuemartzooex&view=wishlists&Itemid='.$itemid.''); ?>">
            <i class="fa fa-heart-o"></i>
        </a>
    </div>
    <div class="zt-cart-inner">
        <div class="vmproduct">
            <?php
            if(count($prods) > 0) {
                foreach ($prods as $product) {
                    ?>
                    <div id="wishlists_prod_<?php echo $product->virtuemart_product_id; ?>" class="modwishlistsprod clearfix">
                        <div class="image fleft">
                            <a href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product->virtuemart_product_id.'&virtuemart_category_id='.$product->virtuemart_category_id); ?>">
                                <img src="<?php if (!empty($product->file_url_thumb)){ echo JURI::base().$product->file_url_thumb;}else {echo JURI::base().'images/stories/virtuemart/noimage.gif';} ?>" alt="<?php echo $product->product_name; ?>" title="<?php echo $product->product_name; ?>" />
                            </a>
                        </div>
                        <div class="extra-wrap">
                            <div class="name">
                                <?php echo JHTML::link($product->link, $product->product_name); ?>
                            </div>
                            <div class="remwishlists">
                                <a class="tooltip-1" title="remove"  onclick="removeWishlists('<?php echo $product->virtuemart_product_id ;?>');">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                <?php
                }
            } else { ?>
                <div class="not_text wishlists"><?php echo JText::_('YOU_HAVE_NO_PRODUCT_TO_WISHLISTS');?></div>
            <?php
             }
            ?>
        </div>
    </div>
    <div class="clear"></div>
</div>
