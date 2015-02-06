<?php
/**
 * @package    VirtueMart Zooex
 * @subpackage VirtueMart Zooex Components
 * @author       ZooTemplate.com
 * @link http://zootemplate.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */
//error_reporting('E_ALL');
defined('_JEXEC') or die;
function virtuemartzooexBuildRoute(&$query)
{
    //var_dump ($query);
    $segments = array();
    if (isset($query['view'])) {
        $segments[] = $query['view'];
        unset($query['view']);
    }
    return $segments;
}

function virtuemartzooexParseRoute($segments)
{
    $vars = array();
    $count = count($segments);
    if ($count) {
        $count--;
        $segment = array_shift($segments);
        $vars['view'] = $segment;
    }
    if ($count) {
        $count--;
        $segment = array_shift($segments);
        $vars['id'] = $segment;
    }
    return $vars;
}
