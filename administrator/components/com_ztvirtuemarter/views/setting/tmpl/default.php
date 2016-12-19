<?php
/**
 * @package    ZT VirtueMarter
 * @author       ZooTemplate.com
 * @link http://www.zootemplate.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */

defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidation');
$input = JFactory::getApplication()->input;
$settings = json_decode($this->item->setting);
?>
<script type="text/javascript" xmlns="http://www.w3.org/1999/html">
    Joomla.submitbutton = function (task) {
        if (task == 'setting.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
            Joomla.submitform(task, document.getElementById('item-form'));
        } else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
        }
    }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_ztvirtuemarter') ?>" method="post" name="adminForm"
      id="item-form" class="form-validate"">

<div class="row-fluid">
    <div class="span10 form-horizontal">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#general" data-toggle="tab">Details</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="general">
                <div class="row-fluid">
                    <div class="span12">
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('enable_wishlist'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('enable_wishlist', '', (isset($settings->enable_wishlist) ? $settings->enable_wishlist : 1 )); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('enable_compare'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('enable_compare', '', (isset($settings->enable_compare) ? $settings->enable_compare : 1)); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('enable_quickview'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('enable_quickview', '', (isset($settings->enable_quickview)?$settings->enable_quickview : 1)); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('enable_countdown'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('enable_countdown', '', (isset($settings->enable_countdown)? $settings->enable_countdown : 1)); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('enable_photozoom'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('enable_photozoom', '', (isset($settings->enable_photozoom))? $settings->enable_photozoom : 1); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('enable_auto_insert'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('enable_auto_insert', '', (isset($settings->enable_auto_insert)?$settings->enable_auto_insert : 1)); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div>
    <input type="hidden" name="task" value="<?php echo $input->get('task'); ?>"/>
    <input type="hidden" name="return" value="<?php echo $input->getCmd('return'); ?>"/>
    <?php echo JHtml::_('form.token'); ?>
</div>
</form>