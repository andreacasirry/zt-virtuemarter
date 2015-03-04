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

class ZtvirtuemarterControllerWishlist extends JControllerLegacy
{
    public function __construct()
    {
        ZtvituemarterHelper::loadVMLibrary();
        parent::__construct();
    }

    public function add()
    {
        $recent = '';
        $jinput = JFactory::getApplication()->input;
        $session = JFactory::getSession();
        $wishlistIds = $session->get('wishlist_ids', array(), 'wishlist_product');

        $itemID = ZtvituemarterHelper::getItemId('wishlist');

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
                        $imgUrl = JURI::base() . 'images/stories/virtuemart/noimage.gif';
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
                            $imgUrl = JURI::base() . 'images/stories/virtuemart/noimage.gif';
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
            $wishlistModel = new ZtvirtuemarterModelWishlist();
            $allProducts = $wishlistModel->getProducts();
            foreach ($allProducts as $productbd) {
                $allprod['ids'][] = $productbd['virtuemart_product_id'];
            }
            if ((!in_array($jinput->get('product_id', null, 'INT'), $allprod['ids']))) {
                //Insert new wishlist item
                $wishlistModel->insert($jinput->get('product_id', null, 'INT'));

                if ((!in_array($jinput->get('product_id', null, 'INT'), $allprod['id']))) {

                    $prods = $wishlistModel->getProducts();
                    foreach ($prods as $product) {

                        $title = '<div class="title">' . JHTML::link($product->link, $product->product_name) . '</div>';
                        $prodUrl = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id);
                        if (!empty($product->file_url_thumb)) {
                            $imgUrl = $product->file_url_thumb;
                        } else {
                            $imgUrl = JURI::base() . 'images/stories/virtuemart/noimage.gif';
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
                        $imgUrl = JURI::base() . 'images/stories/virtuemart/noimage.gif';
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
        echo json_encode(array('message' => $message, 'title' => $title, 'totalwishlists' => $totalwishlists, 'recent' => $recent, 'img_prod' => $imgProd, 'img_prod2' => $imgProd2, 'btnrem' => $btnrem, 'prod_name' => $prodName, 'product_ids' => $productIds, 'btnwishlists' => $btnwishlists, 'btnwishlistsback' => $btnwishlistsback));

    }

    public function removed()
    {
        error_reporting(E_ALL);
        ini_set("display_errors", "On");

        VmConfig::loadConfig();
        VmConfig::loadJLang('com_ztvirtuemarter', true);
        $session = JFactory::getSession();
        $wishlistIds = $session->get('wishlist_ids', array(), 'wishlist_product');
        $jinput = JFactory::getApplication()->input;

        $productModel = VmModel::getModel('product');

        $user = JFactory::getUser();
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
            $wishlistModel = new ZtvirtuemarterModelWishlist();
            $wishlistModel->remove($jinput->get('remove_id', null, 'INT'));

            $allProducts = $wishlistModel->getProducts();
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