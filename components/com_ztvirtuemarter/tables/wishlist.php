<?php
/**
 * @package    ZT VirtueMarter
 * @author       ZooTemplate.com
 * @link http://www.zootemplate.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Wishlist Table class
 *
 * @since  0.0.1
 */
class ZtvirtuemarterTableWishlist extends JTable
{
    public $id = null;
    public $virtuemart_product_id = null;
    public $userid = null;

    /**
     * Constructor
     *
     * @param   JDatabaseDriver &$db A database connector object
     */
    public function __construct(&$db)
    {
        parent::__construct('#__wishlists', 'id', $db);
    }

    /**
     * Table fields checking
     * @return type
     */
    public function check()
    {
        if (empty($this->userid) || empty($this->virtuemart_product_id))
            return false;
        return parent::check();
    }
}