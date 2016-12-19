<?php
/**
 * @package    ZT VirtueMarter
 * @author       ZooTemplate.com
 * @link http://www.zootemplate.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */
defined('_JEXEC') or die;

class ZtvirtuemarterViewComparelist extends JViewLegacy
{
    public $products;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        VmConfig::loadConfig();
        VmConfig::loadJLang('com_ztvirtuemarter', true);
        vmJsApi::jPrice();
        JHtml::_('behavior.modal');
        $app = JFactory::getApplication();
        $pathway = $app->getPathway();
        $pathway->addItem(JText::_('COM_COMPARE_COMPARE_PRODUCT'), JRoute::_('index.php?option=com_ztvirtuemarter&view=comparelist'));

        $this->products = $this->get('Products');

        parent::display($tpl);
    }
}
