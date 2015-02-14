<?php
/**
 * @package    ZT VirtueMarter
 * @subpackage ZT VirtueMarter Comparelist Module
 * @author       ZooTemplate.com
 * @link http://zootemplate.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */

// No direct access.
defined('_JEXEC') or die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
if (!class_exists('mod_zt_wishlist')) require('helper.php');
$user = JFactory::getUser();
$ratingModel = VmModel::getModel('ratings');
$product_model = VmModel::getModel('product');

$session = JFactory::getSession();
$wishlist_ids = $session->get('wishlist_ids', array(), 'wishlist_product');

$prods = array();
if ($user->guest) {
    if (!empty($wishlist_ids)) {
        $products = $wishlist_ids;
        $prods = $product_model->getProducts($products);
        $product_model->addImages($prods, 1);
        $currency = CurrencyDisplay::getInstance();

    } else {
        $wishlist_ids = null;
    }

} else {

    $db = JFactory::getDBO();
    $q = "SELECT virtuemart_product_id FROM #__wishlists WHERE userid =" . $user->id;
    $db->setQuery($q);
    $allproducts = $db->loadAssocList();
    foreach ($allproducts as $productbd) {
        $allprod['id'][] = $productbd['virtuemart_product_id'];
    }
    $product = $allprod['id'];
    $prods = $product_model->getProducts($product);
    $product_model->addImages($prods, 1);
    $currency = CurrencyDisplay::getInstance();
}
require JModuleHelper::getLayoutPath('mod_zt_wishlist', $params->get('layout', 'default'));
?>