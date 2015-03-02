<?php

/**
 * Zt Virtuemarter
 * 
 * @package     Joomla
 * @subpackage  Component
 * @version     1.0.0
 * @author      ZooTemplate 
 * @email       support@zootemplate.com 
 * @link        http://www.zootemplate.com 
 * @copyright   Copyright (c) 2015 ZooTemplate
 * @license     GPL v2
 */
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * Setting controller class
 */
class ZtvirtuemarterControllerSetting extends JControllerAdmin
{

    /**
     * 
     * @param array $config
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->input = JFactory::getApplication()->input;
    }

    /**
     * 
     */
    public function apply()
    {
        $settings = $this->input->post->get('jform', array(), 'array');
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        if (count($settings) > 0)
        {
            /**
             * @todo Move query to model
             */
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from($db->quoteName('#__ztvirtuemarter'));
            $query->where($db->quoteName('id') . ' = 1');
            $db->setQuery($query);
            $results = $db->loadObjectList();
            if (isset($results[0]))
            {
                $results[0]->setting = json_encode($settings);
                //print_r($settings);
                var_dump(JFactory::getDbo()->updateObject('#__ztvirtuemarter', $results[0], 'id'));
                // die;
            } else
            {
                $settingModel = new stdClass();
                $settingModel->id = 1;
                $settingModel->setting = json_encode($settings);
                $db->insertObject('#__ztvirtuemarter', $settingModel);
            }
        }
        $this->setRedirect(JRoute::_('index.php?option=' . $this->option, false));
    }

}
