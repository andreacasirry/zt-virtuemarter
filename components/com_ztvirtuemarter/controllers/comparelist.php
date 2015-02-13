<?php
/**
 * @package    ZT VirtueMarter
 * @subpackage ZT VirtueMarter Components
 * @author       ZooTemplate.com
 * @link http://zootemplate.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */
//error_reporting('E_ALL');
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
        error_reporting('E_ALL');
        $itemID = '';

        $lang = JFactory::getLanguage()->getTag();
        $session = JFactory::getSession();
        $compare_ids = $session->get('compare_ids', array(), 'compare_product');
        $jinput = JFactory::getApplication()->input;
        JFactory::getLanguage()->load('com_ztvirtuemarter');
        VmConfig::loadConfig();
        VmConfig::loadJLang('com_ztvirtuemarter', true);

        if (empty($lang))
            $lang = '*';

        $component = JComponentHelper::getComponent('com_ztvirtuemarter');

        $db = JFactory::getDbo();
        $q = 'SELECT * FROM `#__menu` WHERE `component_id` = "' . $component->id . '" and `language` = "' . $lang . '"';
        $db->setQuery($q);
        $items = $db->loadObjectList();
        if (empty($items)) {
            $q = 'SELECT * FROM `#__menu` WHERE `component_id` = "' . $component->id . '" and `language` = "*"';
            $db->setQuery($q);
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

        $product_model = VmModel::getModel('product');

        if (isset($compare_ids) && (!in_array($jinput->get('product_id', null, 'INT'), $compare_ids)) && (count($compare_ids) <= 3)) {

            $product = array($jinput->get('product_id', null, 'INT'));
            $prods = $product_model->getProducts($product);
            $product_model->addImages($prods, 1);
            //var_dump($prods);
            $compare_ids[] = $jinput->get('product_id', null, 'INT');
            foreach ($prods as $product) {
                //var_dump($product);
                $title = '<div class="title">' . JHTML::link($product->link, $product->product_name) . '</div>';
                $prod_url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id);
                if (!empty($product->file_url_thumb)) {
                    $img_url = $product->file_url_thumb;
                } else {
                    $img_url =  JURI::base().'images/stories/virtuemart/noimage.gif';
                }
                $prod_id = $product->virtuemart_product_id;
                $img_prod = '<div class="compare-product-img"><a href="' . $prod_url . '"><img src="' . JURI::base() . $img_url . '" alt="' . $product->product_name . '" title="' . $product->product_name . '" /></a></div>';
                $img_prod2 = '<div class="compare-product-img"><a href="' . $prod_url . '"><img src="' . JURI::base() . $img_url . '" alt="' . $product->product_name . '" title="' . $product->product_name . '" /></a></div>';

                $prod_name = '<div class="compare-product-detail"><div class="name">' . JHTML::link($product->link, $product->product_name) . '</div><div class="remcompare"><a class="tooltip-1" title="remove"  onclick="removeCompare(' . $product->virtuemart_product_id . ');"><i class="fa fa-times"></i>' . JText::_('REMOVE') . '</a></div></div>';
                $link = JRoute::_('index.php?option=com_ztvirtuemarter&view=comparelist&Itemid=' . $itemID . '');
                $btncompare = '<a id="compare_go" class="button" rel="nofollow" href="' . $link . '">' . JText::_('GO_TO_COMPARE') . '</a>';
                $btncompareback = '<a id="compare_continue" class="continue button reset2" rel="nofollow" href="javascript:;">' . JText::_('CONTINUE_SHOPPING') . '</a>';
                $btnrem = '<div class="remcompare"><a class="tooltip-1" title="remove"  onclick="removeCompare(' . $product->virtuemart_product_id . ');"><i class="fa fa-times"></i>' . JText::_('REMOVE') . '</a></div>';
                $product_ids = $product->virtuemart_product_id;
                if (!empty($compare_ids)) {
                    $totalcompare = count($compare_ids);
                }
            }
            $this->showJSON('<span class="successfully">' . JText::_('COM_COMPARE_MASSEDGE_ADDED_NOTREG') . '</span>', $title, $img_prod2, $btnrem, $btncompare, $btncompareback, $totalcompare, '', $img_prod, $prod_name, $product_ids);

        } else {
            if ( !in_array($jinput->get('product_id', null, 'INT'), $compare_ids)) {
                $product = array($jinput->get('product_id', null, 'INT'));
                $prods = $product_model->getProducts($product);
                $product_model->addImages($prods, 1);
                //var_dump($prods);
                foreach ($prods as $product) {
                    //var_dump($product);
                    $title = '<div class="title">' . JHTML::link($product->link, $product->product_name) . '</div>';
                    $prod_url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id);
                    if (!empty($product->file_url_thumb)) {
                        $img_url = $product->file_url_thumb;
                    } else {
                        $img_url = JURI::base().'images/stories/virtuemart/noimage.gif';
                    }
                    $img_prod2 = '<div class="compare-product-img"><a href="' . $prod_url . '"><img src="' . JURI::base() . $img_url . '" alt="' . $product->product_name . '" title="' . $product->product_name . '" /></a></div>';
                    $link = JRoute::_('index.php?option=com_ztvirtuemarter&view=comparelist&Itemid=' . $itemID . '');
                    $btncompare = '<a id="compare_go" class="button" rel="nofollow" href="' . $link . '">' . JText::_('GO_TO_COMPARE') . '</a>';
                    $btncompareback = '<a id="compare_continue" class="continue button reset2" rel="nofollow" href="javascript:;">' . JText::_('CONTINUE_SHOPPING') . '</a>';
                    $btnrem = '<div class="remcompare"><a class="tooltip-1" title="remove"  onclick="removeCompare(' . $product->virtuemart_product_id . ');"><i class="fa fa-times"></i>' . JText::_('REMOVE') . '</a></div>';
                    if (!empty($compare_ids)) {
                        $totalcompare = count($compare_ids);
                    }
                }
                $this->showJSON('<span class="warning">' . JText::_('COM_COMPARE_MASSEDGE_MORE') . '</span>', '', '', '', $btncompare, $btncompareback, $totalcompare);
            } else {
                $product = array($jinput->get('product_id', null, 'INT'));
                $prods = $product_model->getProducts($product);
                $product_model->addImages($prods, 1);
                //var_dump($prods);
                foreach ($prods as $product) {
                    //var_dump($product);
                    $title = '<div class="title">' . JHTML::link($product->link, $product->product_name) . '</div>';
                    $prod_url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id);
                    if (!empty($product->file_url_thumb)) {
                        $img_url = $product->file_url_thumb;
                    } else {
                        $img_url = JURI::base().'images/stories/virtuemart/noimage.gif';
                    }
                    $img_prod2 = '<div class="compare-product-img"><a href="' . $prod_url . '"><img src="' . JURI::base() . $img_url . '" alt="' . $product->product_name . '" title="' . $product->product_name . '" /></a></div>';
                    $link = JRoute::_('index.php?option=com_ztvirtuemarter&view=comparelist&Itemid=' . $itemID . '');
                    $btncompare = '<a id="compare_go" class="button" rel="nofollow" href="' . $link . '">' . JText::_('GO_TO_COMPARE') . '</a>';
                    $btncompareback = '<a id="compare_continue" class="continue button reset2" rel="nofollow" href="javascript:;">' . JText::_('CONTINUE_SHOPPING') . '</a>';
                    $btnrem = '<div class="remcompare"><a class="tooltip-1" title="remove"  onclick="removeCompare(' . $product->virtuemart_product_id . ');"><i class="fa fa-times"></i>' . JText::_('REMOVE') . '</a></div>';
                    if (!empty($compare_ids)) {
                        $totalcompare = count($compare_ids);
                    }

                }
                $this->showJSON('<span class="notification">' . JText::_('COM_COMPARE_MASSEDGE_ALLREADY_NOTREG') . '</span>', $title, $img_prod2, $btnrem, $btncompare, $btncompareback, $totalcompare);
            }
        }
        $session->set('compare_ids', $compare_ids, 'compare_product');
        exit;
    }

    public function showJSON($message = '', $title = '', $img_prod2 = '', $btnrem = '', $btncompare = '', $btncompareback = '', $totalcompare = '', $recent = '', $img_prod = '', $prod_name = '', $product_ids = '')
    {
        echo json_encode(array('message' => $message, 'title' => $title, 'totalcompare' => $totalcompare, 'recent' => $recent, 'img_prod' => $img_prod, 'img_prod2' => $img_prod2, 'btnrem' => $btnrem, 'prod_name' => $prod_name, 'product_ids' => $product_ids, 'btncompare' => $btncompare, 'btncompareback' => $btncompareback));
    }

    public function removed()
    {
        //error_reporting('E_ALL');
        VmConfig::loadConfig();
        VmConfig::loadJLang('com_ztvirtuemarter', true);
        $session = JFactory::getSession();
        $compare_ids = $session->get('compare_ids', array(), 'compare_product');
        $jinput = JFactory::getApplication()->input;

        $product_model = VmModel::getModel('product');

        if ($jinput->get('remove_id', null, 'INT')) {
            foreach ($compare_ids as $k => $v) {
                if ($jinput->get('remove_id', null, 'INT') == $v) {
                    unset($compare_ids[$k]);
                }
            }
            $prod = array($jinput->get('remove_id', null, 'INT'));
            $prods = $product_model->getProducts($prod);
            foreach ($prods as $product) {
                $title = '<span>' . JHTML::link($product->link, $product->product_name) . '</span>';
            }
            $totalrem = count($compare_ids);
        }
        $session->set('compare_ids', $compare_ids, 'compare_product');
        $this->removeJSON('' . JText::_('COM_COMPARE_MASSEDGE_REM') . ' ' . $title . ' ' . JText::_('COM_COMPARE_MASSEDGE_REM2') . '', $totalrem);
        exit;
    }


    public function removeJSON($rem = '', $totalrem = '', $recentrem = '')
    {
        echo json_encode(array('rem' => $rem, 'totalrem' => $totalrem, 'recentrem' => $recentrem));
        exit;
    }
}