<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * HelloWorld Controller
 */
class ZtvirtuemarterControllerSetting extends JControllerAdmin
{
    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->input = JFactory::getApplication()->input;
    }


    function apply(){
        $settings = $this->input->post->get('jform', array(), 'array');
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        if(count($settings) > 0 ){
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from($db->quoteName('#__ztvirtuemarter'));
            $query->where($db->quoteName('id') . ' = 1');
            $db->setQuery($query);
            $results = $db->loadObjectList();
            if(isset($results[0])) {
                $results[0]->setting = json_encode($settings);
                //print_r($settings);
                var_dump(JFactory::getDbo()->updateObject('#__ztvirtuemarter', $results[0], 'id'));
               // die;
            } else {
                $settingModel = new stdClass();
                $settingModel->id = 1;
                $settingModel->setting = json_encode($settings);
                $db->insertObject('#__ztvirtuemarter', $settingModel);
            }
        }
        $this->setRedirect(JRoute::_('index.php?option=' . $this->option, false));
    }
}