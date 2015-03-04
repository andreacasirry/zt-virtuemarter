<?php

defined('_JEXEC') or die;

class ZtvirtuemarterModelComparelist extends JModelLegacy
{
    private $input;

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    public function getProducts()
    {
        $session = JFactory::getSession();
        $compareIds = $session->get('compare_ids', array(), 'compare_product');
        $productModel = VmModel::getModel('product');

        $prods = $productModel->getProducts($compareIds);
        $productModel->addImages($prods, 1);

        return $prods;
    }
}
