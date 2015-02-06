<?php
defined( '_JEXEC' ) or die;

class ComparelistViewComparelist extends JViewLegacy
{
	function display($tpl = null) 
	{
		$app = JFactory::getApplication();
		$pathway = $app->getPathway();
		$pathway->addItem(JText::_('COM_COMPARE_COMPARE_PRODUCT'),JRoute::_('index.php?option=com_zt_virtuemarter&view=comparelist'));

		parent::display($tpl);
	}
}
