<?php



class ZT_VirtuemarterViewZt_virtuemarter extends JViewLegacy
{

    protected $form;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $this->form		= $this->get('Form');
        //var_dump($this->form	);
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


?>