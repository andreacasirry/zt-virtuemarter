<?php
/**
 * @package    ZT VirtueMarter
 * @author       ZooTemplate.com
 * @link http://www.zootemplate.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */
defined('_JEXEC') or die;

class ZtvirtuemarterControllerAjaxcart extends JControllerLegacy
{
    public function __construct()
    {
        parent::__construct();
        ZtvituemarterHelper::loadVMLibrary();
    }
}