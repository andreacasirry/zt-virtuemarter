<?php
/**
 * @package    ZT VirtueMarter
 * @author       ZooTemplate.com
 * @link http://www.zootemplate.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * Setting table class
 */
class ZtvirtuemarterTableSetting extends JTable
{

    public $id = null;
    public $setting = null;

    /**
     * Constructor
     *
     * @param object Database connector object
     */
    public function __construct(&$db)
    {
        parent::__construct('#__ztvirtuemarter', 'id', $db);
    }

    /**
     * Table fields checking
     * @return type
     */
    public function check()
    {
        if (trim((string)$this->setting) == '') {
            return false;
        }
        $this->setting = json_encode($this->setting);
        return parent::check();
    }

}
