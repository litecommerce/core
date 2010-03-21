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
 * @since      3.0.0 EE
 */

/**
 * XLite_Controller_Customer_Product 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0 EE
 */
class XLite_Controller_Customer_Product extends XLite_Controller_Customer_Catalog
{
    /**
     * Return random product category 
     * 
     * @return XLite_Model_Category
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getProductCategory()
    {
        $list = $this->getProduct()->getCategories();

        return array_shift($list);
    }

    /**
     * Return link to product page 
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getProductURL()
    {
        return $this->buildURL('product', '', array('product_id' => $this->getProduct()->get('product_id')));
    }


    /**
     * Return current (or default) category object
     * 
     * @return XLite_Model_Category
     * @access public
     * @since  3.0.0 EE
     */
    public function getCategory()
    {
        // Cache category ID in the request
        if (!isset(XLite_Core_Request::getInstance()->category_id)) {
            XLite_Core_Request::getInstance()->category_id = $this->getProductCategory()->get('category_id');
        }

        return parent::getCategory();
    }

    /**
     * Add the base part of the location path
     * 
     * @return void
     * @access protected
     * @since  3.0.0 EE
     */
    protected function addBaseLocation($includeCurrent = false)
    {
        parent::addBaseLocation(true);
    }

    /**
     * Common method to determine current location 
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getLocation()
    {
        return $this->getProduct()->get('name');
    }


    /**
     * getTitle 
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getTitle()
    {
        $metaTitle = $this->getProduct()->get('meta_title');

        return $metaTitle ? $metaTitle : $this->getProduct()->get('name');
    }



    // TODO - all of the above should be revised


    public $params = array("target", "product_id", "category_id");

	function handleRequest()
	{
		$result = null;

		if ($this->getProduct()->is('exists')) {
			$result = parent::handleRequest();

		} elseif ($this->getCategory()->is('exists') && $this->getCategory()->is('enabled')) {
			$result = $this->redirect($this->buildURL('category', '', array('category_id' => $this->getCategory()->get('category_id'))));

		} else {
			$result = $this->redirect($this->buildURL('main'));
		}

		return $result;
	}

    function getDescription()
    {
        $description = $this->getProduct()->get('description');

		return $description ? $description : $this->getProduct()->get('brief_description');
    }

	function getMetaDescription()
	{
		$metaDesc = $this->getProduct()->get('meta_desc');

		return $metaDesc ? $metaDesc : $this->getDescription();
	}

    function getKeywords()
    {
		return $this->getProduct()->get('meta_tags');
    }

    /**
     * Check - available product for sale or not 
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isAvailableForSale()
    {
        return true;
    }

    /**
     * Define page type parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function definePageTypeParams()
    {
		parent::definePageTypeParams();

        $this->pageTypeParams['product_id'] = new XLite_Model_WidgetParam_ObjectId_Product('Product Id', 0);
    }

    /**
     * Check - page instance visible or not
     *
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isPageInstanceVisible()
    {
		$product = new XLite_Model_Product($this->product_id);

        return $product->isPersistent;
    }

    /**
     * Get page instance data (name and URL)
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageInstanceData()
    {
		$product = new XLite_Model_Product($this->product_id);

		$this->target = 'product';

        return array(
			$product->get('name'),
			$this->getUrl(),
		);
    }

}

