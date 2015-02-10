<?php
/**
 * @package ZT LayerSlider
 * @author    ZooTemplate.com
 * @copyright(C) 2015 - ZooTemplate.com
 * @license PHP files are GNU/GPL
 **/
// No direct access.
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
// Create shortcut to parameters.
?>
            <div class="tab-content">
                <div class="tab-pane active" id="general">

                    <div class="row-fluid">

                        <div class="span10">
                            <div class="control-group">
                                <div class="control-label">
                                    <?php echo $this->form->getLabel('title'); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $this->form->getInput('title'); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <div class="control-label">
                                    <?php echo $this->form->getLabel('alias'); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $this->form->getInput('alias'); ?>
                                </div>

                            </div>
                            <h4><?php echo JText::_('COM_ZT_LAYERSLIDER_ATTRIBS_FIELDSET_SLIDER_BASIC_LABEL');?></h4>

                            <div class="control-group">
                                <div class="control-label">
                                    <?php echo $this->form->getLabel('slider_type'); ?>
                                </div>
                                <div class="controls">
                                    <?php echo $this->form->getInput('slider_type','', @$this->item->attribs['slider_type']); ?>
                                </div>
                            </div>