<?php
/**
 * @package    ZT VirtueMarter
 * @subpackage ZT VirtueMarter Components
 * @author       ZooTemplate.com
 * @link http://zootemplate.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */

defined('_JEXEC') or die;
if (!class_exists('VmConfig')) require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php');
if (!class_exists('calculationHelper')) require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/calculationh.php');
if (!class_exists('CurrencyDisplay')) require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/currencydisplay.php');
if (!class_exists('VirtueMartModelVendor')) require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/models/vendor.php');
if (!class_exists('VmImage')) require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/image.php');
if (!class_exists('shopFunctionsF')) require(JPATH_SITE . '/components/com_virtuemart/helpers/shopfunctionsf.php');
if (!class_exists('calculationHelper')) require(JPATH_COMPONENT_SITE . '/helpers/cart.php');
if (!class_exists('VirtueMartModelProduct')) {
    JLoader::import('product', JPATH_ADMINISTRATOR . '/components/com_virtuemart/models');
}
if (!class_exists('VirtueMartModelRatings')) {
    JLoader::import('ratings', JPATH_ADMINISTRATOR . '/components/com_virtuemart/models');
}


class ZtvirtuemarterControllerComparelist extends JControllerLegacy
{

    public function add()
    {

        $itemID = '';

        $lang = JFactory::getLanguage()->getTag();
        $session = JFactory::getSession();
        $compareIds = $session->get('compare_ids', array(), 'compare_product');
        $jinput = JFactory::getApplication()->input;
        JFactory::getLanguage()->load('com_ztvirtuemarter');
        VmConfig::loadConfig();
        VmConfig::loadJLang('com_ztvirtuemarter', true);

        if (empty($lang))
            $lang = '*';

        $component = JComponentHelper::getComponent('com_ztvirtuemarter');

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select('menu.*')
            ->from($db->quoteName('#__menu', 'menu'))
            ->where($db->quoteName('component_id') . '=' . $db->quote($component->id))
            ->where($db->quoteName('language') . '=' . $db->quote($lang));

        $db->setQuery($query);
        $items = $db->loadObjectList();
        if (empty($items)) {
            $query = $db->getQuery(true);

            $query->select('menu.*')
                ->from($db->quoteName('#__menu', 'menu'))
                ->where($db->quoteName('component_id') . '=' . $db->quote($component->id))
                ->where($db->quoteName('language') . '=' . $db->quote('*'));

            $items = $db->loadObjectList();
        }

        foreach ($items as $item) {
            if (strstr($item->link, 'view=comparelist')) {
                $itemID = $item->id;
                break;
            }
        }

        if (empty($itemID) && !empty($items[0]->id)) {
            $itemID = $items[0]->id;
        }

        $productModel = VmModel::getModel('product');

        if (isset($compareIds) && (!in_array($jinput->get('product_id', null, 'INT'), $compareIds)) && (count($compareIds) <= 3)) {

            $product = array($jinput->get('product_id', null, 'INT'));
            $prods = $productModel->getProducts($product);
            $productModel->addImages($prods, 1);
            $compareIds[] = $jinput->get('product_id', null, 'INT');
            foreach ($prods as $product) {
                $title = '<div class="title">' . JHTML::link($product->link, $product->product_name) . '</div>';
                $prodUrl = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id);
                if (!empty($product->file_url_thumb)) {
                    $imgUrl = $product->file_url_thumb;
                } else {
                    $imgUrl =  JURI::base().'images/stories/virtuemart/noimage.gif';
                }

                $imgProd = '<div class="compare-product-img"><a href="' . $prodUrl . '"><img src="' . JURI::base() . $imgUrl . '" alt="' . $product->product_name . '" title="' . $product->product_name . '" /></a></div>';
                $imgProd2 = '<div class="compare-product-img"><a href="' . $prodUrl . '"><img src="' . JURI::base() . $imgUrl . '" alt="' . $product->product_name . '" title="' . $product->product_name . '" /></a></div>';

                $prod_name = '<div class="compare-product-detail"><div class="name">' . JHTML::link($product->link, $product->product_name) . '</div><div class="remcompare"><a class="tooltip-1" title="remove"  onclick="removeCompare(' . $product->virtuemart_product_id . ');"><i class="fa fa-times"></i>' . JText::_('REMOVE') . '</a></div></div>';
                $link = JRoute::_('index.php?option=com_ztvirtuemarter&view=comparelist&Itemid=' . $itemID . '');
                $btncompare = '<a id="compare_go" class="button" rel="nofollow" href="' . $link . '">' . JText::_('GO_TO_COMPARE') . '</a>';
                $btncompareback = '<a id="compare_continue" class="continue button reset2" rel="nofollow" href="javascript:;">' . JText::_('CONTINUE_SHOPPING') . '</a>';
                $btnrem = '<div class="remcompare"><a class="tooltip-1" title="remove"  onclick="removeCompare(' . $product->virtuemart_product_id . ');"><i class="fa fa-times"></i>' . JText::_('REMOVE') . '</a></div>';
                $productIds = $product->virtuemart_product_id;
                if (!empty($compareIds)) {
                    $totalcompare = count($compareIds);
                }
            }
            $this->showJSON('<span class="successfully">' . JText::_('COM_COMPARE_MASSEDGE_ADDED_NOTREG') . '</span>', $title, $imgProd2, $btnrem, $btncompare, $btncompareback, $totalcompare, '', $imgProd, $prod_name, $productIds);

        } else {
            if ( !in_array($jinput->get('product_id', null, 'INT'), $compareIds)) {
                $product = array($jinput->get('product_id', null, 'INT'));
                $prods = $productModel->getProducts($product);
                $productModel->addImages($prods, 1);
                foreach ($prods as $product) {
                    $title = '<div class="title">' . JHTML::link($product->link, $product->product_name) . '</div>';
                    $prodUrl = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id);
                    if (!empty($product->file_url_thumb)) {
                        $imgUrl = $product->file_url_thumb;
                    } else {
                        $imgUrl = JURI::base().'images/stories/virtuemart/noimage.gif';
                    }
                    $imgProd2 = '<div class="compare-product-img"><a href="' . $prodUrl . '"><img src="' . JURI::base() . $imgUrl . '" alt="' . $product->product_name . '" title="' . $product->product_name . '" /></a></div>';
                    $link = JRoute::_('index.php?option=com_ztvirtuemarter&view=comparelist&Itemid=' . $itemID . '');
                    $btncompare = '<a id="compare_go" class="button" rel="nofollow" href="' . $link . '">' . JText::_('GO_TO_COMPARE') . '</a>';
                    $btncompareback = '<a id="compare_continue" class="continue button reset2" rel="nofollow" href="javascript:;">' . JText::_('CONTINUE_SHOPPING') . '</a>';
                    $btnrem = '<div class="remcompare"><a class="tooltip-1" title="remove"  onclick="removeCompare(' . $product->virtuemart_product_id . ');"><i class="fa fa-times"></i>' . JText::_('REMOVE') . '</a></div>';
                    if (!empty($compareIds)) {
                        $totalcompare = count($compareIds);
                    }
                }
                $this->showJSON('<span class="warning">' . JText::_('COM_COMPARE_MASSEDGE_MORE') . '</span>', '', '', '', $btncompare, $btncompareback, $totalcompare);
            } else {
                $product = array($jinput->get('product_id', null, 'INT'));
                $prods = $productModel->getProducts($product);
                $productModel->addImages($prods, 1);
                //
                foreach ($prods as $product) {
                    //
                    $title = '<div class="title">' . JHTML::link($product->link, $product->product_name) . '</div>';
                    $prodUrl = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id);
                    if (!empty($product->file_url_thumb)) {
                        $imgUrl = $product->file_url_thumb;
                    } else {
                        $imgUrl = JURI::base().'images/stories/virtuemart/noimage.gif';
                    }
                    $imgProd2 = '<div class="compare-product-img"><a href="' . $prodUrl . '"><img src="' . JURI::base() . $imgUrl . '" alt="' . $product->product_name . '" title="' . $product->product_name . '" /></a></div>';
                    $link = JRoute::_('index.php?option=com_ztvirtuemarter&view=comparelist&Itemid=' . $itemID . '');
                    $btncompare = '<a id="compare_go" class="button" rel="nofollow" href="' . $link . '">' . JText::_('GO_TO_COMPARE') . '</a>';
                    $btncompareback = '<a id="compare_continue" class="continue button reset2" rel="nofollow" href="javascript:;">' . JText::_('CONTINUE_SHOPPING') . '</a>';
                    $btnrem = '<div class="remcompare"><a class="tooltip-1" title="remove"  onclick="removeCompare(' . $product->virtuemart_product_id . ');"><i class="fa fa-times"></i>' . JText::_('REMOVE') . '</a></div>';
                    if (!empty($compareIds)) {
                        $totalcompare = count($compareIds);
                    }

                }
                $this->showJSON('<span class="notification">' . JText::_('COM_COMPARE_MASSEDGE_ALLREADY_NOTREG') . '</span>', $title, $imgProd2, $btnrem, $btncompare, $btncompareback, $totalcompare);
            }
        }
        $session->set('compare_ids', $compareIds, 'compare_product');
        exit;
    }

    public function showJSON($message = '', $title = '', $imgProd2 = '', $btnrem = '', $btncompare = '', $btncompareback = '', $totalcompare = '', $recent = '', $imgProd = '', $prod_name = '', $productIds = '')
    {
        echo json_encode(array('message' => $message, 'title' => $title, 'totalcompare' => $totalcompare, 'recent' => $recent, 'img_prod' => $imgProd, 'img_prod2' => $imgProd2, 'btnrem' => $btnrem, 'prod_name' => $prod_name, 'product_ids' => $productIds, 'btncompare' => $btncompare, 'btncompareback' => $btncompareback));
    }

    public function removed()
    {

        VmConfig::loadConfig();
        VmConfig::loadJLang('com_ztvirtuemarter', true);
        $session = JFactory::getSession();
        $compareIds = $session->get('compare_ids', array(), 'compare_product');
        $jinput = JFactory::getApplication()->input;

        $productModel = VmModel::getModel('product');

        if ($jinput->get('remove_id', null, 'INT')) {
            foreach ($compareIds as $k => $v) {
                if ($jinput->get('remove_id', null, 'INT') == $v) {
                    unset($compareIds[$k]);
                }
            }
            $prod = array($jinput->get('remove_id', null, 'INT'));
            $prods = $productModel->getProducts($prod);
            foreach ($prods as $product) {
                $title = '<span>' . JHTML::link($product->link, $product->product_name) . '</span>';
            }
            $totalrem = count($compareIds);
        }
        $session->set('compare_ids', $compareIds, 'compare_product');
        $this->removeJSON('' . JText::_('COM_COMPARE_MASSEDGE_REM') . ' ' . $title . ' ' . JText::_('COM_COMPARE_MASSEDGE_REM2') . '', $totalrem);
        exit;
    }


    public function removeJSON($rem = '', $totalrem = '', $recentrem = '')
    {
        echo json_encode(array('rem' => $rem, 'totalrem' => $totalrem, 'recentrem' => $recentrem));
        exit;
    }
}