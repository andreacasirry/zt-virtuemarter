<?php
/**
 * ZT Layerslider
 * 
 * @package     Joomla
 * @subpackage  Component
 * @version     1.0.0
 * @author      ZooTemplate 
 * @email       support@zootemplate.com 
 * @link        http://www.zootemplate.com 
 * @copyright   Copyright (c) 2015 ZooTemplate
 * @license     GPL v2
 */
defined('_JEXEC') or die('Restricted access');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('jquery.framework');
JHtml::_('formbehavior.chosen', 'select');
?>

<script type="text/javascript" xmlns="http://www.w3.org/1999/html">
    Joomla.submitbutton = function (task) {
        if (task == 'slider.cancel' || document.formvalidator.isValid(document.id('item-form'))) {

            Joomla.submitform(task, document.getElementById('item-form'));
        } else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
        }
    }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_ztvirtuemarter') ?>" method="post" name="adminForm" id="item-form" class="form-validate"">

    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#general" data-toggle="tab">Details</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="general">
                    <div class="row-fluid">
                        <div class="span12">
                            <?php
                            if ($this->params == null)
                            {
                                ?>
                                <div class="control-group">
                                    <div class="control-label">
                                        <?php echo $this->form->getLabel('enable_wishlist'); ?>
                                    </div>
                                    <div class="controls">
                                        <?php echo $this->form->getInput('enable_wishlist'); ?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="control-label">
                                        <?php echo $this->form->getLabel('enable_compare'); ?>
                                    </div>
                                    <div class="controls">
                                        <?php echo $this->form->getInput('enable_compare'); ?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="control-label">
                                        <?php echo $this->form->getLabel('enable_quickview'); ?>
                                    </div>
                                    <div class="controls">
                                        <?php echo $this->form->getInput('enable_quickview'); ?>
                                    </div>
                                </div>
                                <?php
                            } else
                            {
                                ?>
                                <div class="control-group">
                                    <div class="control-label">
                                        <?php echo $this->form->getLabel('enable_wishlist'); ?>
                                    </div>
                                    <div class="controls">
                                        <fieldset id="jform_enable_wishlist" class="radio inputbox btn-group">
                                            <input type="radio" id="jform_enable_wishlist0" name="jform[enable_wishlist]" value="0" <?php
                                            if ($this->params->enable_wishlist == '0')
                                            {
                                                echo 'checked="checked"';
                                            }
                                            ?>>
                                                <label for="jform_enable_wishlist0" class="btn <?php
                                                if ($this->params->enable_wishlist == '0')
                                                {
                                                    echo 'active btn-danger';
                                                }
                                                ?>">No</label>
                                                <input type="radio" id="jform_enable_wishlist1" name="jform[enable_wishlist]" value="1" <?php
                                                if ($this->params->enable_wishlist == '1')
                                                {
                                                    echo 'checked="checked"';
                                                }
                                                ?>>
                                                    <label for="jform_enable_wishlist1" class="btn <?php
                                                    if ($this->params->enable_wishlist == '1')
                                                    {
                                                        echo 'active btn-success';
                                                    }
                                                    ?>">Yes</label>
                                                    </fieldset>
                                                    </div>
                                                    </div>
                                                    <div class="control-group">
                                                        <div class="control-label">
                                                            <?php echo $this->form->getLabel('enable_compare'); ?>
                                                        </div>
                                                        <div class="controls">
                                                            <fieldset id="jform_enable_compare" class="radio inputbox btn-group">
                                                                <input type="radio" id="jform_enable_compare0" name="jform[enable_compare]" value="0" <?php
                                                                if ($this->params->enable_compare == '0')
                                                                {
                                                                    echo 'checked="checked"';
                                                                }
                                                                ?>>
                                                                    <label for="jform_enable_compare0" class="btn <?php
                                                                    if ($this->params->enable_compare == '0')
                                                                    {
                                                                        echo 'active btn-danger';
                                                                    }
                                                                    ?>">No</label>
                                                                    <input type="radio" id="jform_enable_compare1" name="jform[enable_compare]" value="1" <?php
                                                                    if ($this->params->enable_compare == '1')
                                                                    {
                                                                        echo 'checked="checked"';
                                                                    }
                                                                    ?>>
                                                                        <label for="jform_enable_compare1" class="btn <?php
                                                                        if ($this->params->enable_compare == '1')
                                                                        {
                                                                            echo 'active btn-success';
                                                                        }
                                                                        ?>">Yes</label>
                                                                        </fieldset>
                                                                        </div>
                                                                        </div>
                                                                        <div class="control-group">
                                                                            <div class="control-label">
                                                                                <?php echo $this->form->getLabel('enable_quickview'); ?>
                                                                            </div>
                                                                            <div class="controls">
                                                                                <fieldset id="jform_enable_quickview" class="radio inputbox btn-group">
                                                                                    <input type="radio" id="jform_enable_quickview0" name="jform[enable_quickview]" value="0" <?php
                                                                                    if ($this->params->enable_quickview == '0')
                                                                                    {
                                                                                        echo 'checked="checked"';
                                                                                    }
                                                                                    ?>>
                                                                                        <label for="jform_enable_quickview0" class="btn <?php
                                                                                        if ($this->params->enable_quickview == '0')
                                                                                        {
                                                                                            echo 'active btn-danger';
                                                                                        }
                                                                                        ?>">No</label>
                                                                                        <input type="radio" id="jform_enablequickview1" name="jform[enable_quickview]" value="1" <?php
                                                                                        if ($this->params->enable_quickview == '1')
                                                                                        {
                                                                                            echo 'checked="checked"';
                                                                                        }
                                                                                        ?>>
                                                                                            <label for="jform_enable_quickview1" class="btn <?php
                                                                                            if ($this->params->enable_quickview == '1')
                                                                                            {
                                                                                                echo 'active btn-success';
                                                                                            }
                                                                                            ?>">Yes</label>
                                                                                            </fieldset>
                                                                                            </div>
                                                                                            </div>
                                                                                        <?php } ?>
                                                                                        </div>
                                                                                        </div>
                                                                                        </div>
                                                                                        </div>
                                                                                        </div>
                                                                                        </div>
                                                                                        <div class="clr"></div>

                                                                                        <div>
                                                                                            <input type="hidden" name="task" value=""/>
                                                                                            <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return'); ?>"/>
                                                                                            <?php echo JHtml::_('form.token'); ?>
                                                                                        </div>
                                                                                        <script>
                                                                                            jQuery(document).ready(function () {
                                                                                                jQuery('.btn-group label').on("click", function () {
                                                                                                    var label = jQuery(this);
                                                                                                    var input = label.prev();
                                                                                                    label.closest('.btn-group').find('label').removeClass('btn-success').removeClass('btn-danger').removeClass('btn-primary').removeClass('active');
                                                                                                    if (input.val() == '') {
                                                                                                        label.addClass('active btn-primary');
                                                                                                    } else if (input.val() == 0) {
                                                                                                        label.addClass('active btn-danger');
                                                                                                    } else {
                                                                                                        label.addClass('active btn-success');
                                                                                                    }
                                                                                                    jQuery(this).parent().find('input').removeAttr("checked");
                                                                                                    input.attr("checked", true);
                                                                                                });
                                                                                            });

                                                                                        </script>
                                                                                        </form>