<?php
/**
 * @package    VirtueMart Zooex
 * @subpackage VirtueMart Zooex Plugins
 * @author       ZooTemplate.com
 * @link http://zootemplate.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */
//error_reporting('E_ALL');
class PlgSystemVirtuemartzooex extends JPlugin
{
    /**
     * Class Constructor
     * @param object $subject
     * @param array $config
     */
    public function __construct(& $subject, $config)
    {
        parent::__construct($subject, $config);
        $this->document = JFactory::getDocument();

    }

    function onBeforeRender()
    {
        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();
        $user = JFactory::getUser();
        if (!($app->isAdmin())) {
            if (!$user->guest) {
                if (isset($_SESSION['id'])) {
                    $dbIds = $_SESSION['id'];
                    $db = JFactory::getDBO();
                    $q = "SELECT virtuemart_product_id FROM #__wishlists WHERE userid =" . $user->id;
                    $db->setQuery($q);
                    $allproducts = $db->loadAssocList();
                    foreach ($allproducts as $productbd) {
                        $allprod['ids'][] = $productbd['virtuemart_product_id'];
                    }
                    for ($r = 0; $r < count($dbIds); $r++) {
                        if (!in_array($dbIds[$r], $allprod['ids'])) {
                            $q = "INSERT INTO `#__wishlists`
								(virtuemart_product_id,userid )
								VALUES
								('" . $dbIds[$r] . "','" . $user->id . "') ";
                            //var_dump ($dbIds[$r]);
                            $db->setQuery($q);
                            $db->queryBatch();
                        }
                    }
                    unset($_SESSION['id']);
                }
            }
        }

    }

    public static function addCompareButton($product, $type = null)
    {
        if (is_file(JPATH_BASE . DS . "components/com_zt_virtuemartzooex/template/comparelist.tpl" . $type . ".php")) {
            ?>
            <div class="compare_cat list_compare<?php echo $product->virtuemart_product_id; ?>">
                <?php require(JPATH_BASE . DS . "components/com_zt_virtuemartzooex/template/comparelist.tpl" . $type . ".php"); ?>
            </div>
        <?php
        }
    }

    public static function addWishlistButton($product, $type = null)
    {
        if (is_file(JPATH_BASE . DS . "components/com_zt_virtuemartzooex/template/wishlists.tpl" . $type . ".php")) {
            ?>
            <div class="wishlist list_wishlists<?php echo $product->virtuemart_product_id; ?>">
                <?php require(JPATH_BASE . DS . "components/com_zt_virtuemartzooex/template/wishlists.tpl" . $type . ".php"); ?>
            </div>
        <?php
        }
    }
}

?>