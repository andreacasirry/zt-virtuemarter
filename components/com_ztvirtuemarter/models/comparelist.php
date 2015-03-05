<?php

defined('_JEXEC') or die;

class ZtvirtuemarterModelComparelist extends JModelLegacy
{
    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    public function getProducts()
    {
        $mainframe = JFactory::getApplication();
        $compareIds = $mainframe->getUserState( "com_ztvirtuemarter.site.compareIds", array() );

        $productModel = VmModel::getModel('product');

        $prods = $productModel->getProducts($compareIds);
        $productModel->addImages($prods, 1);

        return $prods;
    }
}
