<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Category navigation dialog
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
 * Category navigation dialog 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0 EE
 */
class XLite_Controller_Customer_Category extends XLite_Controller_Customer_Catalog
{
    /**
     * getTitle 
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getTitle()
    {
        $metaTitle = $this->getCategory()->get('meta_title');

        return $metaTitle ? $metaTitle : $this->getCategory()->get('name');
    }
    

    public $params = array("target", "category_id");

    function init()
    {
        parent::init();
		if (isset($this->category_id) && empty($this->category_id)) {
            return $this->redirect("cart.php");
		}
        $this->setComplex("pager.itemsPerPage", $this->getComplex('config.General.products_per_page'));
        if (!isset($_REQUEST["action"])) {
            $this->session->set("productListURL", $this->getUrl());
        }
    }
    
    /**
    * 'description' meta-tag value.
    */
    function getDescription()
    {
        $description = $this->getComplex('category.description');
        if (empty($description)) {
            $description = null;
        }
		return $description;
    }

    function getMetaDescription()
    {
        $description = $this->getDescription();
        return ($this->getComplex('category.meta_desc') ? $this->getComplex('category.meta_desc') : $description);
    }

    /**
    * 'keywords' meta-tag value.
    */
    function getKeywords()
    {
        return $this->getComplex('category.meta_tags');
    }

    function handleRequest()
    {
        if (!$this->isComplex('category.exists') || !$this->isComplex('category.enabled')) {
            return $this->redirect("cart.php");
        }
        parent::handleRequest();
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

        $this->pageTypeParams['category_id'] = new XLite_Model_WidgetParam_ObjectId_Category('Category Id', 0);
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
		$category = new XLite_Model_Category($this->category_id);

        return $category->isPersistent;
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
		$category = new XLite_Model_Category($this->category_id);

		$this->target = 'category';

        return array(
			$category->get('name'),
			$this->getUrl(),
		);
    }

}

