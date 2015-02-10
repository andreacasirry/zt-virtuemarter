<?php

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

?>

<script type="text/javascript" xmlns="http://www.w3.org/1999/html">
    Joomla.submitbutton = function (task) {
        if (task == 'slider.cancel' || document.formvalidator.isValid(document.id('item-form'))) {

            Joomla.submitform(task, document.getElementById('item-form'));
        } else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
        }
    }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_zt_virtuemarter') ?>" method="post" name="adminForm" id="item-form" class="form-validate"">

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
                                <label id="jform_title-lbl" for="jform_title" class="hasTooltip required" title="" data-original-title="<strong>Title</strong>">
                                    Title<span class="star">&nbsp;*</span>
                                </label>
                            </div>
                            <div class="controls">
                                <input type="text" name="jform[title]" id="jform_title" value="5" class="inputbox required" size="30" required="required" aria-required="true"></div>
                        </div>
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

</form>