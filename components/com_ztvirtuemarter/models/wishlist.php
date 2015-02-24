<?php
/**
 * Hello Model for Hello World Component
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:modules/
 * @license    GNU/GPL
 */

// No direct access
defined('_JEXEC') or die;

class ZtvirtuemarterModelWishlist extends JModelLegacy
{
    /**
     * @var JInput|null
     */
    private $input;

    /**
     * Class constructor
     * @param array $config
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->input = JFactory::getApplication()->input;
    }

    public function updateCurrentWishlist( ) {
        $session        = JFactory::getSession();
        $wishlistIds   = $session->get('wishlist_ids', array(), 'wishlist_product');
        if(count($wishlistIds) > 0) {
            $user = JFactory::getUser();
            if (!$user->guest) {
                $db             = JFactory::getDBO();
                $query          = $db->getQuery(true);

                $query->select($db->quoteName('virtuemart_product_id') )
                    ->from($db->quoteName('#__wishlists'))
                    ->where($db->quoteName('userid') . '=' . $db->quote($user->id));

                $db->setQuery($query);
                $allProducts = $db->loadAssocList();
                foreach ($allProducts as $productbd) {
                    $prodIds['ids'][] = $productbd['virtuemart_product_id'];
                }

                foreach($wishlistIds as $id) {
                    if (!in_array($id, $prodIds['ids'])) {
                        $query = $db->getQuery(true);
                        $query->insert($db->quoteName('#__wishlists'))
                            ->columns($db->quoteName('virtuemart_product_id'))
                            ->values( $id)
                            ->columns($db->quoteName('userid'))
                            ->values( $user->id);

                        $db->setQuery($query);
                        $db->execute();
                    }
                }
            }
        }
    }

    public function getProducts() {
        $user = JFactory::getUser();
        $prodIds = array();
        if (!$user->guest) {
            $db             = JFactory::getDBO();
            $query          = $db->getQuery(true);

            $query->select($db->quoteName('virtuemart_product_id') )
                ->from($db->quoteName('#__wishlists'))
                ->where($db->quoteName('userid') . '=' . $db->quote($user->id));

            $db->setQuery($query);
            $allProducts = $db->loadAssocList();
            foreach ($allProducts as $productbd) {
                $prodIds[] = $productbd['virtuemart_product_id'];
            }
        } else {
            $session        = JFactory::getSession();
            $wishlistIds   = $session->get('wishlist_ids', array(), 'wishlist_product');
            $prodIds = $wishlistIds;
        }
        $productModel = VmModel::getModel('product');

        $products = $productModel->getProducts($prodIds);
        $productModel->addImages($products, 1);
        
        return $products;
    }
}