<?php
defined( '_JEXEC' ) or die;

class ZtvirtuemarterViewComparelist extends JViewLegacy
{
    public $products;

	public function display($tpl = null)
	{
        VmConfig::loadConfig();
        VmConfig::loadJLang('com_ztvirtuemarter', true);
        vmJsApi::jPrice();
        JHtml::_('behavior.modal');
		$app = JFactory::getApplication();
		$pathway = $app->getPathway();
		$pathway->addItem(JText::_('COM_COMPARE_COMPARE_PRODUCT'),JRoute::_('index.php?option=com_ztvirtuemarter&view=comparelist'));

        $this->products = $this->get('Products');

		parent::display($tpl);
	}
}
