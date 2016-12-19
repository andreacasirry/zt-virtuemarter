<?php
/**
 * @package    ZT VirtueMarter
 * @author       ZooTemplate.com
 * @link http://www.zootemplate.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */
defined('_JEXEC') or die('Restricted access');

class ZtvirtuemarterViewSetting extends JViewLegacy
{

    protected $form;
    protected $item;
    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->_addToolbar();
        parent::display($tpl);
    }

    protected function _addToolbar()
    {
        JToolBarHelper::title(JText::_('COM_ZTVIRTUEMARTER_ADMIN_TITLE'));
        JToolBarHelper::apply('setting.apply');
        JToolBarHelper::cancel();
//        JToolbarHelper::preferences('com_ztvirtuemarter');
    }

}
