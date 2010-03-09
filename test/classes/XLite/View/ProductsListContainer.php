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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Products list abstract container
 *
 * @package    XLite
 * @subpackage View
 * @since      3.0
 */
abstract class XLite_View_ProductsListContainer extends XLite_View_Dialog
{
    /**
     * Check if widget is visible
     *
     * @return bool
     * @access protected
     * @since  3.0.0 EE
     */
    public function isVisible()
    {
        return parent::isVisible()
            && $this->getProducts();
    }

    /**
     * Get products 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract public function getProducts($sortCriterion = 'name', $sortOrder = 'asc');

    /**
     * Get list factory
     * 
     * @return array (callback - object + method name)
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getListFactory()
    {
        return array($this, 'getProducts');
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += XLite_View_ProductsList::getWidgetParamsList();
    }

    /**
     * Export widget arguments 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function exportWidgetArguments()
    {
        $data = array();

        foreach ($this->getWidgetParams() as $key => $param) {
            $data[$key] = isset($this->attributes[$key]) ? $this->attributes[$key] : $param->value;
        }

        return $data;
    }
}

