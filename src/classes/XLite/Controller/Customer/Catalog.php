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

namespace XLite\Controller\Customer;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class Catalog extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Determines if we need to return categoty link or not 
     * 
     * @param \XLite\Model\Category $category       category model object to use
     * @param bool                  $includeCurrent flag
     *  
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function checkCategoryLink(\XLite\Model\Category $category, $includeCurrent)
    {
        return $includeCurrent || $this->getCategoryId() !== $category->category_id;
    }

    /**
     * Return link to category page 
     * 
     * @param \XLite\Model\Category $category       category model object to use
     * @param bool                  $includeCurrent flag
     *  
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getCategoryURL(\XLite\Model\Category $category, $includeCurrent)
    {
        return $this->checkCategoryLink($category, $includeCurrent) 
            ? $this->buildURL('category', '', array('category_id' => $category->category_id))
            : null;
    }

    /**
     * Return category name and link 
     * 
     * @param \XLite\Model\Category $category       category model object to use_
     * @param bool                  $includeCurrent flag
     *  
     * @return \XLite\Model\Location
     * @access protected
     * @since  3.0.0
     */
    protected function getCategoryLocation(\XLite\Model\Category $category, $includeCurrent)
    {
        return new \XLite\Model\Location($category->name, $this->getCategoryURL($category, $includeCurrent));
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

        $categoryPath = \XLite\Core\Database::getRepo('\XLite\Model\Category')
            ->getCategoryPath(\XLite\Core\Request::getInstance()->category_id);

        foreach ($categoryPath as $category) {
            if (0 < $category->category_id) {
                $this->locationPath->addNode($this->getCategoryLocation($category, $includeCurrent));
            }
        }
    }

    /**
     * Return current category Id
     *
     * @return int
     * @access protected
     * @since  3.0.0
     */
    protected function getCategoryId()
    {
        return \XLite\Core\Request::getInstance()->category_id;
    }


    /**
     * getModelObject
     *
     * @return \XLite\Model\AModel
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getModelObject();

    /**
     * Return current (or default) category object
     *
     * @return \XLite\Model\Category
     * @access public
     * @since  3.0.0 EE
     */
    public function getCategory()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Category')
            ->getCategory($this->getCategoryId());
    }

    /**
     * Returns the page title (for the content area)
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getTitle()
    {
        $object = $this->getModelObject();

        return $object ? $object->getName() : parent::getTitle();
    }

    /**
     * Returns the page title (for the <title> tag)
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getPageTitle()
    {
        $object = $this->getModelObject();

        return $object
            ? ($object->getMetaTitle() ?: $this->getTitle())
            : parent::getTitle();
 
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
        return $this->getModelObject()
            ? $this->getModelObject()->getDescription()
            : null;
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
        $object = $this->getModelObject();

        return $object
            ? ($object->getMetaDesc() ?: $this->getDescription())
            : null;
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
        return $this->getModelObject()
            ? $this->getModelObject()->getMetaTags()
            : null;
    }
}
