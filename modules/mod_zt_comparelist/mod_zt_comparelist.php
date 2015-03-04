<?php
/**
 * @package    ZT VirtueMarter
 * @subpackage ZT VirtueMarter Comparelist Module
 * @author       ZooTemplate.com
 * @link http://zootemplate.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */

// No direct access.
defined('_JEXEC') or die('Restricted access');
VmConfig::loadConfig();
// Load the language file of com_virtuemart.
JFactory::getLanguage()->load('com_virtuemart');
$prods = array();
$ratingModel = VmModel::getModel('ratings');
$product_model = VmModel::getModel('product');

$session = JFactory::getSession();
$compareIds = $session->get('compare_ids', array(), 'compare_product');
if (!empty($compareIds)) {
    $products = $compareIds;
    $prods = $product_model->getProducts($products);
    $product_model->addImages($prods, 1);
    $currency = CurrencyDisplay::getInstance();
}
require JModuleHelper::getLayoutPath('mod_zt_comparelist', $params->get('layout', 'default'));
?>