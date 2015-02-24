<a class="compare-label add_compare hasTooltip <?php if ( in_array($product->virtuemart_product_id, $compareIds)) { echo 'go_to_compare active';} ?>"
   title="<?php echo JText::_('DR_ADD_TO_COMPARE'); ?>"
   onclick="addToCompare('<?php echo $product->virtuemart_product_id; ?>');">
    <i class="fa fa-files-o"></i>
    <span><?php echo JText::_("DR_ADD_TO_COMPARE"); ?></span>
</a>
