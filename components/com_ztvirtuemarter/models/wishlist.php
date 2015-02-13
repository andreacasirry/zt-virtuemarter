<?php
/**
 * Hello Model for Hello World Component
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:modules/
 * @license    GNU/GPL
 */

// No direct access
defined('_JEXEC') or die;

class ZtvirtuemarterModelWishlist extends JModelLegacy
{
    /**
     * @var JInput|null
     */
    private $input;

    /**
     * Class constructor
     * @param array $config
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->input = JFactory::getApplication()->input;
    }

}