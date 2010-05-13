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
 * Product
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Controller_Customer_Product extends XLite_Controller_Customer_Catalog
{
    protected $params = array('product_id');

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
     * Get product category id
     *
     * @return int
     * @access protected
     * @since  3.0.0
     */
    protected function getCategoryId()
    {
        $categoryId = parent::getCategoryId();

        if (!$categoryId) {
            $productCategory = $this->getProductCategory();
            if ($productCategory) {
                $categoryId = $productCategory->get('category_id');
            }
        }

        return $categoryId;
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
        $descr = parent::getDescription();

        return $descr
            ? $descr
            : $this->getProduct()->get('brief_description');
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
        if ($this->getProduct()->isExists()) {
            parent::handleRequest();

        } elseif ($this->isCategoryAvailable()) {
            $this->set(
                'returnUrl',
                $this->buildURL('category', '', array('category_id' => $this->getCategoryId()))
            );

        } else {
            $this->set('returnUrl', $this->buildURL());
        }
    }
}
