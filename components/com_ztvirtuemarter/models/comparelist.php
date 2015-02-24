<?php

defined('_JEXEC') or die;

class ZtvirtuemarterModelComparelist extends JModelLegacy
{
    private $input;

    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->input = JFactory::getApplication()->input;
    }

    public function getProducts()
    {
        $session = JFactory::getSession();
        $compare_ids = $session->get('compare_ids', array(), 'compare_product');
        $product_model = VmModel::getModel('product');

        $prods = $product_model->getProducts($compare_ids);
        $product_model->addImages($prods, 1);

        return $prods;
    }
}
