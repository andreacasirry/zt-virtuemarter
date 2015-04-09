<?php
/**
 * @copyright	Copyright (c) 2015 ztonestepcheckout. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * system - ZTONESTEPCHECKOUT Plugin
 *
 * @package		Joomla.Plugin
 * @subpakage	ztonestepcheckout.ZTONESTEPCHECKOUT
 */
class plgsystemZTONESTEPCHECKOUT extends JPlugin {

	/**
	 * Constructor.
	 *
	 * @param 	$subject
	 * @param	array $config
	 */
	function __construct(&$subject, $config = array()) {
		// call parent constructor
		parent::__construct($subject, $config);
	}
	
}