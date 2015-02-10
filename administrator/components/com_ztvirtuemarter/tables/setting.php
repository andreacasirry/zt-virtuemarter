<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * Hello Table class
 */
class ZtvirtuemarterTableSetting extends JTable
{
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db)
    {
        parent::__construct('#__ztvirtuemarter', 'id', $db);
    }

    function apply() {
        echo 'lol';
        die;
    }

    function save() {
        echo 'lol2';
        die;
    }


}