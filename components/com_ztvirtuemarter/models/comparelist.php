<?php

defined('_JEXEC') or die;

class ZtvirtuemarterModelComparelist extends JModelLegacy
{
    private $input;

    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->input = JFactory::getApplication()->input;
    }
}
