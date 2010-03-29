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
 * XLite_Controller_Customer_Catalog 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
abstract class XLite_Controller_Customer_Catalog extends XLite_Controller_Customer_Abstract
{
    /**
     * Determines if we need to return categoty link or not 
     * 
     * @param XLite_Model_Category $category category model object to use
     * @param bool                 $includeCurrent flag
     *  
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function checkCategoryLink(XLite_Model_Category $category, $includeCurrent)
    {
        return $includeCurrent || $this->getCategoryId() !== $category->get('category_id');
    }

    /**
     * Return link to category page 
     * 
     * @param XLite_Model_Category $category category model object to use
     * @param bool                 $includeCurrent flag
     *  
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getCategoryURL(XLite_Model_Category $category, $includeCurrent)
    {
        return $this->checkCategoryLink($category, $includeCurrent) 
            ? $this->buildURL('category', '', array('category_id' => $category->get('category_id')))
            : null;
    }

    /**
     * Return category name and link 
     * 
     * @param XLite_Model_Category $category       category model object to use_
     * @param bool                 $includeCurrent flag
     *  
     * @return XLite_Model_Location
     * @access protected
     * @since  3.0.0
     */
    protected function getCategoryLocation(XLite_Model_Category $category, $includeCurrent)
    {
        return new XLite_Model_Location($category->get('name'), $this->getCategoryURL($category, $includeCurrent));
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
        parent::addBaseLocation();

        foreach ($this->getCategory()->getPath() as $category) {
            if (0 < $category->get('category_id')) {
                $this->locationPath->addNode($this->getCategoryLocation($category, $includeCurrent));
            }
        }
    }

    /**
     * isCategoryAvailable 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isCategoryAvailable()
    {
        return $this->getCategory()->is('exists') && $this->getCategory()->is('enabled');
    }


    /**
     * getModelObject 
     * 
     * @return XLite_Model_Abstract
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getModelObject();


    /**
     * getTitle
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getTitle()
    {
        $metaTitle = $this->getModelObject()->get('meta_title');

        return $metaTitle ? $metaTitle : $this->getModelObject()->get('name');
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
        return $this->getModelObject()->get('description');
    }

    /**
     * getMetaDescription
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getMetaDescription()
    {
        $metaDesc = $this->getModelObject()->get('meta_desc');

        return $metaDesc ? $metaDesc : $this->getDescription();
    }

    /**
     * getKeywords
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getKeywords()
    {
        return $this->getModelObject()->get('meta_tags');
    }
}

