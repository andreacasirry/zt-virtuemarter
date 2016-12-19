<?php

/**
 * @package    ZT VirtueMarter
 * @author       ZooTemplate.com
 * @link http://www.zootemplate.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * General controller class
 */
class ZtvirtuemarterController extends JControllerLegacy
{

    protected $default_view = 'setting';

    /**
     * display task
     *
     * @return void
     */
    public function display($cachable = false, $urlparams = false)
    {
        // set default view if not set
        $input = JFactory::getApplication()->input;
        $input->set('view', $input->getCmd('view', 'setting'));

        // call parent behavior
        parent::display($cachable);
    }

}
