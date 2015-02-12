<?php
/**
 * @package    VirtueMart Zooex
 * @subpackage VirtueMart Zooex Comparelist Module
 * @author       ZooTemplate.com
 * @link http://zootemplate.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */
//error_reporting('E_ALL');
// No direct access.
defined('_JEXEC') or die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
if (!class_exists('mod_zt_wishlist')) require('helper.php');
$user = JFactory::getUser();
$ratingModel = VmModel::getModel('ratings');
$product_model = VmModel::getModel('product');

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('*');
$query->from($db->quoteName('#__ztvirtuemarter'));
$query->where($db->quoteName('id') . ' = 1');
$db->setQuery($query);
$results = $db->loadObjectList();
$ztvirtuemarter_params = array(
    'enable_wishlist' => '1',
    'enable_compare' => '1',
    'enable_quickview' => '1'
);
if(isset($results[0]) && !empty($results[0]->setting)) {
    $ztvirtuemarter_params = json_decode($results[0]->setting);
}

$prods = array();
if ($user->guest) {
    if (!empty($_SESSION['wishlist_ids'])) {
        $products = $_SESSION['wishlist_ids'];
        $prods = $product_model->getProducts($products);
        $product_model->addImages($prods, 1);
        $currency = CurrencyDisplay::getInstance();

    } else {
        $_SESSION['wishlist_ids'] = null;
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