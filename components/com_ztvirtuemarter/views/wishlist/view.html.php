<?php
/**
 * @package    ZT VirtueMarter
 * @subpackage Components
 * @author       ZooTemplate.com
 * @link http://zootemplate.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */


// No direct access
defined('_JEXEC') or die;


/**
 * HTML View class for the HelloWorld Component
 *
 * @package    HelloWorld
 */
class ZtvirtuemarterViewWishlist extends JViewLegacy
{
    function display($tpl = null)
    {
        $app = JFactory::getApplication();
        $pathway = $app->getPathway();
        $pathway->addItem(JText::_('COM_WISHLISTS_PRODUCT'), JRoute::_('index.php?option=com_ztvirtuemarter&view=wishlist'));
        parent::display($tpl);
    }
}