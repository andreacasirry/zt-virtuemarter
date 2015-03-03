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
$wishlistIds = $session->get('wishlist_ids', array(), 'wishlist_product');

$prods = array();
if ($user->guest) {
    if (!empty($wishlistIds)) {
        $products = $wishlistIds;
        $prods = $product_model->getProducts($products);
        $product_model->addImages($prods, 1);
        $currency = CurrencyDisplay::getInstance();

    } else {
        $wishlistIds = null;
    }

} else {

    $db = JFactory::getDBO();
    $db             = JFactory::getDBO();
    $query          = $db->getQuery(true);

    $query->select($db->quoteName('virtuemart_product_id') )
        ->from($db->quoteName('#__wishlists'))
        ->where($db->quoteName('userid') . '=' . $db->quote($user->id));

    $db->setQuery($query);

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