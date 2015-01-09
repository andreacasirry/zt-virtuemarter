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

if (!class_exists('mod_comparelist')) require('helper.php');

$ratingModel = VmModel::getModel('ratings');
$product_model = VmModel::getModel('product');
if (!empty($_SESSION['compare_ids'])) {
    $products = $_SESSION['compare_ids'];
    $prods = $product_model->getProducts($products);
    $product_model->addImages($prods, 1);
    $currency = CurrencyDisplay::getInstance();
}
require JModuleHelper::getLayoutPath('mod_comparelist', $params->get('layout', 'default'));
?>