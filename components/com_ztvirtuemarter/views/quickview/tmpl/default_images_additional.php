<?php
/**
 * @package    ZT VirtueMarter
 * @subpackage Components
 * @author       ZooTemplate.com
 * @link http://zootemplate.com
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
?>
<div class="additional-images" id="zt_list_product">
    <?php
    $start_image = VmConfig::get('add_img_main', 0) ? 0 : 1;

    for ($i = $start_image - 1; $i < count($this->product->images); $i++) :
        $image = $this->product->images[$i];
        ?>
        <div class="floatleft">
            <?php
            if (VmConfig::get('add_img_main', 1)) :
                echo $image->displayMediaThumb('class="product-image" style="cursor: pointer"', false, "");
            else :
                echo $image->displayMediaThumb("", true, "rel='vm-additional-images'");
            endif;
            ?>
        </div>
    <?php
    endfor;
    ?>
    <div class="clear"></div>
</div>
