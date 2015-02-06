<?php
/**
 * @package    VirtueMart Zooex
 * @subpackage VirtueMart Zooex Components
 * @author       ZooTemplate.com
 * @link http://zootemplate.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */
//error_reporting('E_ALL');
// No direct access

defined('_JEXEC') or die;
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
define('VZ_BASE_PATH', JPATH_BASE . DS . 'components' . DS . 'com_zt_virtuemarter');

// Require the base controller
$input = JFactory::getApplication()->input;
$_controller = $input->getCmd('view');
$_class = ucfirst($_controller) . 'Controller';
if (file_exists(VZ_BASE_PATH . DS . 'controllers' . DS . $_class . '.php')) {
    if (!class_exists($_class)) {
        require(VZ_BASE_PATH . DS . 'controllers' . DS . $_class . '.php');
    }
}
$task = $input->getCmd('task');
if (class_exists($_class)) {
    $controller = new $_class();
    $controller->execute($task);
    $controller->redirect();
}

