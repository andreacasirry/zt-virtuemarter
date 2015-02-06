<?php
/**
 * @package    VirtueMart Zooex
 * @subpackage VirtueMart Zooex Comparelist Module
 * @author       ZooTemplate.com
 * @link http://zootemplate.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */
//error_reporting('E_ALL');
defined('_JEXEC') or die('Restricted access');

$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::root() . 'modules/mod_zt_comparelist/assets/css/compare.css');
$doc->addScript(JURI::root() . 'modules/mod_zt_comparelist/assets/js/compare.js');

JFactory::getLanguage()->load('com_zt_virtuemarter');
$items = JFactory::getApplication()->getMenu('site')->getItems('component', 'com_zt_virtuemarter');
foreach ($items as $item) {
    if ($item->query['view'] === 'comparelist') {
        $itemid = $item->id;

    }
}
?>

<div class="mod-compare">
    <div id="cur-lang" class="header-button-compare">
        <div id="compare_total">
            <a class="compare_total heading" href="<?php echo JRoute::_('index.php?option=com_zt_virtuemarter&view=comparelist&Itemid=' . $itemid . ''); ?>">
                <i class="fa fa-files-o"></i>
            <span>
		   <?php
           echo count($_SESSION['compare_ids']);
           ?></span>
            </a>
        </div>
    </div>
</div>