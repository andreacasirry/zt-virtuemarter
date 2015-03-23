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


if (!empty($this->product->images)) :
    $image = $this->product->images[0];
    ?>
    <div class="main-image">
        <?php echo $image->displayMediaFull("", true, "rel='vm-additional-images'"); ?>
        <div class="clear"></div>
    </div>
<?php endif; ?>

