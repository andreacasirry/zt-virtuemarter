<?php

/**
 * @package    ZT VirtueMarter
 * @author       ZooTemplate.com
 * @link http://www.zootemplate.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */
defined('_JEXEC') or die('Restricted access');

// Get controller instance
$controller = JControllerLegacy::getInstance('Ztvirtuemarter');

// Perform the Request task
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
