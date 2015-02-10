<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class ZT_LayersliderModelSetting extends JModelAdmin
{
    protected $_context = 'com_zt_virtuemarter';

    /**
     * @var		string	The prefix to use with controller messages.
     * @since	1.6
     */
    protected $text_prefix = 'COM_ZT_VIRTUEMARTER';


    public function getForm($data = array(), $loadData = true)
    {

        echo 'lol';
        // Get the form.
        $form = $this->loadForm('com_zt_virtuemarter', 'setting', array('control' => 'jform', 'load_data' => $loadData));


        if (empty($form)) {
            return false;
        }
        return $form;
    }

    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_zt_virtuemarter.edit.setting.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }



    protected function canSave($data = array(), $key = 'id')
    {
        return JFactory::getUser()->authorise('core.edit', $this->option);
    }


    public function save($data)
    {
        $items = $data;


        $data['attribs'] = array_merge($data['attribs'], $items);

        return parent::save($data);
    }

}