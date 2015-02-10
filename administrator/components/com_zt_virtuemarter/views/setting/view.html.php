<?php



class ZT_VirtuemarterViewSetting extends JViewLegacy
{

    protected $form;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $version = new JVersion;

        $this->form = $this->loadForm('com_zt_virtuemarter', 'setting', array('control' => 'jform', 'load_data' => $loadData));
        var_dump($this->form	);
        $this->addToolbar();

        parent::display($tpl);
    }


    protected function addToolbar()
    {
        defined('_JEXEC') or die();
        JToolBarHelper::title(JText::_('Component ZT VirtueMarter '));
        JToolBarHelper::apply('slide.apply');
        JToolBarHelper::save('slide.save');
        JToolBarHelper::cancel('slide.cancel');
        //echo JText::_('Description of Component ZT VirtueMarter');


    }
}
$ZT_Virtuemarter = new ZT_VirtuemarterViewSetting();
$ZT_Virtuemarter->display();

?>