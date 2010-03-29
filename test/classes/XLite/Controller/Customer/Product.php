<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * ____file_title____
 *  
 * @category   Lite Commerce
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version    SVN: $Id$
 * @link       http://www.qtmsoft.com/
 * @since      3.0.0
 */

/**
 * XLite_Controller_Customer_Product 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Controller_Customer_Product extends XLite_Controller_Customer_Catalog
{
    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams[self::PARAM_PRODUCT_ID]->setVisibility(true);
    }

    /**
     * getCategoryId
     *
     * @return int
     * @access protected
     * @since  3.0.0
     */
    protected function getCategoryId()
    {
        return ($categoryId = parent::getCategoryId()) ? $categoryId : $this->getProductCategory()->get('category_id');
    }

    /**
     * Return random product category 
     * 
     * @return XLite_Model_Category
     * @access protected
     * @since  3.0.0
     */
    protected function getProductCategory()
    {
        $list = $this->getProduct()->getCategories();

        return array_shift($list);
    }

    /**
     * Add the base part of the location path
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function addBaseLocation($includeCurrent = false)
    {
        parent::addBaseLocation(true);
    }

    /**
     * getModelObject
     *
     * @return XLite_Model_Abstract
     * @access protected
     * @since  3.0.0
     */
    protected function getModelObject()
    {
        return $this->getProduct();
    }

    /**
     * Common method to determine current location 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return $this->getProduct()->get('name');
    }


    /**
     * getDescription 
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getDescription()
    {
        return ($descr = parent::getDescription()) ? $descr : $this->getProduct()->get('brief_description');
    }

    /**
     * handleRequest 
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
	public function handleRequest()
	{
        if ($this->getProduct()->is('exists')) {
            parent::handleRequest();
        } elseif ($this->isCategoryAvailable()) {
            $this->set('returnUrl', $this->buildURL('category', '', array('category_id' => $this->getCategoryId())));
        } else {
            $this->set('returnUrl', $this->buildURL());
        }
    }

    /**
     * Check - available product for sale or not 
     * TODO - check if it's need to be revised
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isAvailableForSale()
    {
        return true;
    }
}

