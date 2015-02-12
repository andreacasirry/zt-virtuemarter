<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class ZtvirtuemarterModelSetting extends JModelAdmin
{

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param	type	The table type to instantiate
     * @param	string	A prefix for the table class name. Optional.
     * @param	array	Configuration array for model. Optional.
     * @return	JTable	A database object
     * @since	2.5
     */
    public function getTable($type = 'Setting', $prefix = 'ZtvirtuemarterTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_ztvirtuemarter.edit', 'setting',
            array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form))
        {
            return false;
        }
        return $form;
    }
    /**
     * Method to get the data that should be injected in the form.
     *
     * @return	mixed	The data for the form.
     * @since	2.5
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_ztvirtuemarter.edit.ztvirtuemarter.data', array());

        if (empty($data))
        {
            $data = $this->getItem();
        }
        return $data;
    }
}