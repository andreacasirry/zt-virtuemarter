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


if (!empty($this->product->images)) : ?>

<div class="gallery_image-product owl-carousel">

<?php
  for ($i = 0; $i < count($this->product->images); $i++) {
    $image = $this->product->images[$i];
    echo $image->displayMediaFull('class="product-image" style="cursor: pointer"',false);
  }
?>

</div>
<script>
  jQuery('.gallery_image-product').owlCarousel({
    items: 1
  });
</script>
<?php
endif; ?>
