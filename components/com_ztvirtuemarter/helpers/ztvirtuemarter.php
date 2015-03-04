<?php

class ZtvituemarterHelper
{

    public static function loadVMLibrary()
    {
        if (!class_exists('VmConfig')) require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php');
        if (!class_exists('calculationHelper')) require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/calculationh.php');
        if (!class_exists('CurrencyDisplay')) require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/currencydisplay.php');
        if (!class_exists('VirtueMartModelVendor')) require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/models/vendor.php');
        if (!class_exists('VmImage')) require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/image.php');
        if (!class_exists('shopFunctionsF')) require(JPATH_SITE . '/components/com_virtuemart/helpers/shopfunctionsf.php');
        if (!class_exists('calculationHelper')) require(JPATH_COMPONENT_SITE . '/helpers/cart.php');
        if (!class_exists('VirtueMartModelProduct')) {
            JLoader::import('product', JPATH_ADMINISTRATOR . '/components/com_virtuemart/models');
        }
        if (!class_exists('VirtueMartModelRatings')) {
            JLoader::import('ratings', JPATH_ADMINISTRATOR . '/components/com_virtuemart/models');
        }
    }

    public static function getItemId($view)
    {
        $itemID = '';
        $component = JComponentHelper::getComponent('com_ztvirtuemarter');
        $lang = JFactory::getLanguage()->getTag();
        if (empty($lang))
            $lang = '*';

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('menu.*')
            ->from($db->quoteName('#__menu', 'menu'))
            ->where($db->quoteName('component_id') . '=' . $db->quote($component->id))
            ->where($db->quoteName('language') . '=' . $db->quote($lang));

        $db->setQuery($query);
        $items = $db->loadObjectList();
        if (empty($items)) {
            $query = $db->getQuery(true);

            $query->select('menu.*')
                ->from($db->quoteName('#__menu', 'menu'))
                ->where($db->quoteName('component_id') . '=' . $db->quote($component->id))
                ->where($db->quoteName('language') . '=' . $db->quote('*'));

            $items = $db->loadObjectList();
        }

        foreach ($items as $item) {
            if (strstr($item->link, 'view=' . $view)) {
                $itemID = $item->id;
                break;
            }
        }

        if (empty($itemID) && !empty($items[0]->id)) {
            $itemID = $items[0]->id;
        }
        return $itemID;
    }
}