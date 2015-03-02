<?php



class ZtvirtuemarterViewSetting extends JViewLegacy
{

    protected $form;
    protected $params = null;


    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $this->form	= $this->get('Form');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__ztvirtuemarter'));
        $query->where($db->quoteName('id') . ' = 1');
        $db->setQuery($query);
        $results = $db->loadObjectList();
        if(isset($results[0])) {
            if(!empty($results[0]->setting))
                $this->params = json_decode($results[0]->setting);
        }

        $this->addToolbar();

        parent::display($tpl);
    }


    protected function addToolbar()
    {
        defined('_JEXEC') or die();
        JToolBarHelper::title(JText::_('Component ZT VirtueMarter '));
        JToolBarHelper::apply('setting.apply');
    }
}


?>