<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
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
        return $includeCurrent || $this->getCategoryId() !== $category->category_id;
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
            ? $this->buildURL('category', '', array('category_id' => $category->category_id))
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
        return new XLite_Model_Location($category->name, $this->getCategoryURL($category, $includeCurrent));
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

        $categoryPath = XLite_Core_Database::getRepo('XLite_Model_Category')->getCategoryPath(XLite_Core_Request::getInstance()->category_id);

        foreach ($categoryPath as $category) {
            if (0 < $category->category_id) {
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
        return $this->getCategory()->category_id && $this->getCategory()->enabled;
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
        $metaTitle = $this->getModelObject()->meta_title;

        return $metaTitle ? $metaTitle : $this->getModelObject()->name;
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
        return $this->getModelObject()->description;
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
        $metaDesc = $this->getModelObject()->meta_desc;

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
        return $this->getModelObject()->meta_tags;
    }
}
