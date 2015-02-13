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
$prods = array();
$ratingModel = VmModel::getModel('ratings');
$product_model = VmModel::getModel('product');

$session = JFactory::getSession();
$compare_ids = $session->get('compare_ids', array(), 'compare_product');
if (!empty($compare_ids)) {
    $products = $compare_ids;
    $prods = $product_model->getProducts($products);
    $product_model->addImages($prods, 1);
    $currency = CurrencyDisplay::getInstance();
}
require JModuleHelper::getLayoutPath('mod_zt_comparelist', $params->get('layout', 'default'));
?>