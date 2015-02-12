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
defined('_JEXEC') or die('Restricted access');

if (!class_exists('mod_zt_comparelist')) require('helper.php');
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
$ratingModel = VmModel::getModel('ratings');
$product_model = VmModel::getModel('product');
if (!empty($_SESSION['compare_ids'])) {
    $products = $_SESSION['compare_ids'];
    $prods = $product_model->getProducts($products);
    $product_model->addImages($prods, 1);
    $currency = CurrencyDisplay::getInstance();
}else {
    $_SESSION['compare_ids'] = null;
}
require JModuleHelper::getLayoutPath('mod_zt_comparelist', $params->get('layout', 'default'));
?>