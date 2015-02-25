<?php
/**
 * @package    ZT VirtueMarter
 * @subpackage ZT VirtueMarter Components
 * @author       ZooTemplate.com
 * @link http://zootemplate.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */

defined('_JEXEC') or die;
JFactory::getLanguage()->load('com_wishlists');

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

class ZtvirtuemarterControllerWishlist extends JControllerLegacy
{

    public function add()
    {

        $itemID = '';
        $recent = '';
        if (empty($lang)) $lang = '*';

        $jinput = JFactory::getApplication()->input;
        $component = JComponentHelper::getComponent('com_ztvirtuemarter');
        $session = JFactory::getSession();
        $wishlistIds = $session->get('wishlist_ids', array(), 'wishlist_product');

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
            if (strstr($item->link, 'view=wishlist')) {
                $itemID = $item->id;
                break;
            }
        }

        if (empty($itemID) && !empty($items[0]->id)) {
            $itemID = $items[0]->id;
        }

        VmConfig::loadConfig();
        VmConfig::loadJLang('com_ztvirtuemarter', true);
        $productModel = VmModel::getModel('product');

        $user = JFactory::getUser();
        if ($user->guest) {
            if (!in_array($jinput->get('product_id', null, 'INT'), $wishlistIds)) {
                $product = array($jinput->get('product_id', null, 'INT'));

                $prods = $productModel->getProducts($product);
                $productModel->addImages($prods, 1);
                $wishlistIds[] = $jinput->get('product_id', null, 'INT');
                foreach ($prods as $product) {
                    //var_dump($product);
                    $title = '<div class="title">' . JHTML::link($product->link, $product->product_name) . '</div>';
                    $prodUrl = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id);
                    if (!empty($product->file_url_thumb)) {
                        $imgUrl = $product->file_url_thumb;
                    } else {
                        $imgUrl = JURI::base().'images/stories/virtuemart/noimage.gif';
                    }
                    $prodId = $product->virtuemart_product_id;
                    $imgProd = '<div class="wishlist-product-img"><a href="' . $prodUrl . '"><img src="' . JURI::base() . $imgUrl . '" alt="' . $product->product_name . '" title="' . $product->product_name . '" /></a></div>';
                    $imgProd2 = '<div class="wishlist-product-img"><a href="' . $prodUrl . '"><img src="' . JURI::base() . $imgUrl . '" alt="' . $product->product_name . '" title="' . $product->product_name . '" /></a></div>';

                    $prodName = '<div class="wishlist-product-detail"><div class="name">' . JHTML::link($product->link, $product->product_name) . '</div><div class="remwishlists"><a class="tooltip-1" title="remove"  onclick="removeWishlists(' . $product->virtuemart_product_id . ');"><i class="fa fa-times"></i>' . JText::_('REMOVE') . '</a></div></div>';
                    $link = JRoute::_('index.php?option=com_ztvirtuemarter&view=wishlist&Itemid=' . $itemID . '');
                    $btnwishlists = '<a id="wishlists_go" class="button" rel="nofollow" href="' . $link . '">' . JText::_('GO_TO_WISHLISTS') . '</a>';
                    $btnwishlistsback = '<a id="wishlists_continue" class="continue button reset2" rel="nofollow" href="javascript:;">' . JText::_('CONTINUE_SHOPPING') . '</a>';
                    $btnrem = '<div class="remwishlists"><a class="tooltip-1" title="remove"  onclick="removeWishlists(' . $product->virtuemart_product_id . ');"><i class="fa fa-times"></i>' . JText::_('REMOVE') . '</a></div>';
                    $productIds = $product->virtuemart_product_id;
                    $totalwishlists = count($wishlistIds);
                }
                $this->showJSON('<span class="successfully">' . JText::_('COM_WHISHLISTS_MASSEDGE_ADDED_NOTREG') . '</span>', $title, $imgProd2, $btnrem, $btnwishlists, $btnwishlistsback, $totalwishlists, $recent, $imgProd, $prodName, $productIds);

            } else {
                if (in_array($jinput->get('product_id', null, 'INT'), $wishlistIds)) {
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
                        $imgProd2 = '<div class="wishlist-product-img"><a href="' . $prodUrl . '"><img src="' . JURI::base() . $imgUrl . '" alt="' . $product->product_name . '" title="' . $product->product_name . '" /></a></div>';
                        $link = JRoute::_('index.php?option=com_ztvirtuemarter&view=wishlist&Itemid=' . $itemID . '');
                        $btnwishlists = '<a id="wishlists_go" class="button" rel="nofollow" href="' . $link . '">' . JText::_('GO_TO_WISHLISTS') . '</a>';
                        $btnwishlistsback = '<a id="wishlists_continue" class="continue button reset2" rel="nofollow" href="javascript:;">' . JText::_('CONTINUE_SHOPPING') . '</a>';
                        $btnrem = '<div class="remwishlists"><a class="tooltip-1" title="remove"  onclick="removeWishlists(' . $product->virtuemart_product_id . ');"><i class="fa fa-times"></i>' . JText::_('REMOVE') . '</a></div>';
                    }
                    $this->showJSON('<span class="notification">' . JText::_('COM_WHISHLISTS_MASSEDGE_ALLREADY_NOTREG') . '</span>', $title, $imgProd2, $btnrem, $btnwishlists, $btnwishlistsback);

                }
            }

        } else {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query->select($db->quoteName('virtuemart_product_id'))
                ->from($db->quoteName('#__wishlists'))
                ->where($db->quoteName('userid') . '=' . $db->quote($user->id));

            $db->setQuery($query);
            $allProducts = $db->loadAssocList();
            foreach ($allProducts as $productbd) {
                $allprod['ids'][] = $productbd['virtuemart_product_id'];
            }
            if ((!in_array($jinput->get('product_id', null, 'INT'), $allprod['ids']))) {
                $query = $db->getQuery(true);
                $query->insert($db->quoteName('#__wishlists'))
                    ->columns($db->quoteName('virtuemart_product_id'))
                    ->values( $jinput->get('product_id', null, 'INT'))
                    ->columns($db->quoteName('userid'))
                    ->values( $user->id);

                $db->setQuery($query);
                $db->execute();

                if ((!in_array($jinput->get('product_id', null, 'INT'), $allprod['id']))) {
                    $query = $db->getQuery(true);

                    $query->select($db->quoteName('virtuemart_product_id'))
                        ->from($db->quoteName('#__wishlists'))
                        ->where($db->quoteName('userid') . '=' . $db->quote($user->id));

                    $db->setQuery($query);
                    $allProducts = $db->loadAssocList();
                    foreach ($allProducts as $productbd) {
                        $allprod['id'][] = $productbd['virtuemart_product_id'];
                    }

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
                        $prodId = $product->virtuemart_product_id;
                        $imgProd = '<div class="wishlist-product-img"><a href="' . $prodUrl . '"><img src="' . JURI::base() . $imgUrl . '" alt="' . $product->product_name . '" title="' . $product->product_name . '" /></a></div>';
                        $imgProd2 = '<div class="wishlist-product-img"><a href="' . $prodUrl . '"><img src="' . JURI::base() . $imgUrl . '" alt="' . $product->product_name . '" title="' . $product->product_name . '" /></a></div>';

                        $prodName = '<div class="wishlist-product-detail"><div class="name">' . JHTML::link($product->link, $product->product_name) . '</div><div class="remwishlists"><a class="tooltip-1" title="remove"  onclick="removeWishlists(' . $product->virtuemart_product_id . ');">' . JText::_('REMOVE') . '</a></div></div>';
                        $link = JRoute::_('index.php?option=com_ztvirtuemarter&view=wishlist&Itemid=' . $itemID . '');
                        $btnwishlists = '<a id="wishlists_go" class="button" rel="nofollow" href="' . $link . '">' . JText::_('GO_TO_WISHLISTS') . '</a>';
                        $btnwishlistsback = '<a id="wishlists_continue" class="continue button reset2" rel="nofollow" href="javascript:;">' . JText::_('CONTINUE_SHOPPING') . '</a>';
                        $btnrem = '<div class="remwishlists"><a class="tooltip-1" title="remove"  onclick="removeWishlists(' . $product->virtuemart_product_id . ');"><i class="fa fa-times"></i>' . JText::_('REMOVE') . '</a></div>';
                        $productIds = $product->virtuemart_product_id;
                        $totalwishlists = count($allprod['id']);
                    }
                    $this->showJSON('<span class="successfully">' . JText::_('COM_WHISHLISTS_MASSEDGE_ADDED_REG') . '</span>', $title, $imgProd2, $btnrem, $btnwishlists, $btnwishlistsback, $totalwishlists, $recent, $imgProd, $prodName, $productIds);
                }
            } else {
                $product = array($jinput->get('product_id', null, 'INT'));
                $prods = $productModel->getProducts($product);
                $productModel->addImages($prods, 1);
                //var_dump($prods);
                foreach ($prods as $product) {
                    //var_dump($product);
                    $title = '<div class="title">' . JHTML::link($product->link, $product->product_name) . '</div>';
                    $prodUrl = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id);
                    if (!empty($product->file_url_thumb)) {
                        $imgUrl = $product->file_url_thumb;
                    } else {
                        $imgUrl = JURI::base().'images/stories/virtuemart/noimage.gif';
                    }
                    $imgProd2 = '<div class="image fleft"><a href="' . $prodUrl . '"><img src="' . JURI::base() . $imgUrl . '" alt="' . $product->product_name . '" title="' . $product->product_name . '" /></a></div>';
                    $link = JRoute::_('index.php?option=com_ztvirtuemarter&view=wishlist&Itemid=' . $itemID . '');
                    $btnwishlists = '<a id="wishlists_go" class="button" rel="nofollow" href="' . $link . '">' . JText::_('GO_TO_WISHLISTS') . '</a>';
                    $btnwishlistsback = '<a id="wishlists_continue" class="continue button reset2" rel="nofollow" href="javascript:;">' . JText::_('CONTINUE_SHOPPING') . '</a>';
                    $btnrem = '<div class="remwishlists"><a class="tooltip-1" title="remove"  onclick="removeWishlists(' . $product->virtuemart_product_id . ');"><i class="fa fa-times"></i></a></div>';
                }
                $this->showJSON('<span class="notification">' . JText::_('COM_WHISHLISTS_MASSEDGE_ALLREADY_REG') . '</span>', $title, $imgProd2, $btnrem, $btnwishlists, $btnwishlistsback);

            }
        }
        $session->set('wishlist_ids', $wishlistIds, 'wishlist_product');
        exit;
    }

    public function showJSON($message = '', $title = '', $imgProd2 = '', $btnrem = '', $btnwishlists = '', $btnwishlistsback = '', $totalwishlists = '', $recent = '', $imgProd = '', $prodName = '', $productIds = '')
    {
        echo json_encode(array('message' => $message, 'title' => $title, 'totalwishlists' => $totalwishlists, 'recent' => $recent, 'imgProd' => $imgProd, 'imgProd2' => $imgProd2, 'btnrem' => $btnrem, 'prod_name' => $prodName, 'product_ids' => $productIds, 'btnwishlists' => $btnwishlists, 'btnwishlistsback' => $btnwishlistsback));

    }

    public function removed()
    {

        VmConfig::loadConfig();
        VmConfig::loadJLang('com_ztvirtuemarter', true);
        $session = JFactory::getSession();
        $wishlistIds = $session->get('wishlist_ids', array(), 'wishlist_product');
        $jinput = JFactory::getApplication()->input;

        if (isset($wishlistIds)) ;
        $productModel = VmModel::getModel('product');

        $user =& JFactory::getUser();
        if ($user->guest) {

            if ($jinput->get('remove_id', null, 'INT')) {
                foreach ($wishlistIds as $k => $v) {
                    if ($jinput->get('remove_id', null, 'INT') == $v) {
                        unset($wishlistIds[$k]);
                    }
                }
                $prod = array($jinput->get('remove_id', null, 'INT'));
                $prods = $productModel->getProducts($prod);
                foreach ($prods as $product) {
                    $title = '<span>' . JHTML::link($product->link, $product->product_name) . '</span>';
                }
                $totalrem = count($wishlistIds);
            }
            $this->removeJSON('' . JText::_('COM_WHISHLISTS_MASSEDGE_REM') . ' ' . $title . ' ' . JText::_('COM_WHISHLISTS_MASSEDGE_REM2') . '', $totalrem);
        } else {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $conditions = array(
                $db->quoteName('virtuemart_product_id') . '=' . $jinput->get('remove_id', null, 'INT'),
                $db->quoteName('userid') . '=' . $user->id
            );

            $query->delete($db->quoteName('#__user_profiles'));
            $query->where($conditions);
            $db->setQuery($query);
            $db->execute();

            $query = $db->getQuery(true);
            $query->select($db->quoteName('virtuemart_product_id'))
                ->from($db->quoteName('#__wishlists'))
                ->where($db->quoteName('userid') . '=' . $db->quote($user->id));

            $db->setQuery($query);
            $allProducts = $db->loadAssocList();
            foreach ($allProducts as $productbd) {
                $allprod['ids'][] = $productbd['virtuemart_product_id'];
            }
            //var_dump($allprod['ids']);
            $prod = array($jinput->get('remove_id', null, 'INT'));
            $prods = $productModel->getProducts($prod);
            foreach ($prods as $product) {
                $title = '<span>' . JHTML::link($product->link, $product->product_name) . '</span>';
            }
            $totalrem = count($allprod['ids']);

            $this->removeJSON('' . JText::_('COM_WHISHLISTS_MASSEDGE_REM') . ' ' . $title . ' ' . JText::_('COM_WHISHLISTS_MASSEDGE_REM2') . '', $totalrem);
        }
        $session->set('wishlist_ids', $wishlistIds, 'wishlist_product');
        exit;
    }

    public function removeJSON($rem = '', $totalrem = '')
    {
        echo json_encode(array('rem' => $rem, 'totalrem' => $totalrem));
    }
}