<?php



class ZT_VirtuemarterViewSetting extends JViewLegacy
{

    protected $form;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $this->form	= $this->get('Form');
        //var_dump($this->form	);
        $this->addToolbar();

        parent::display($tpl);
    }


    protected function addToolbar()
    {
        defined('_JEXEC') or die();
        JToolBarHelper::title(JText::_('Component ZT VirtueMarter '));
        JToolBarHelper::apply('setting.apply');
        JToolBarHelper::save('setting.save');
        JToolBarHelper::cancel('setting.cancel');
    }
}


?>