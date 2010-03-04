<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Category products list widget
 *  
 * @category  Litecommerce
 * @package   View
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0 EE
 */

/**
 * Category products list widget
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_View_CategoryProducts extends XLite_View_Dialog
{
    /**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0 EE
     */
    protected $allowedTargets = array('category');

    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getHead()
    {
        return 'Catalog';
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getDir()
    {
        return 'category_products';
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access protected
     * @since  3.0.0 EE
     */
    public function isVisible()
    {
        return parent::isVisible();
    }

    /**
     * Get products 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProducts($sortCriterion = 'name', $sortOrder = 'asc')
    {
        return $this->getCategory()->getProducts(null, $sortCriterion . ' ' . strtoupper($sortOrder));
    }

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
            if (isset($this->attributes[$key])) {
                $data[$key] = $this->attributes[$key];
            } else {
                $data[$key] = $param->value;
            }
        }

        return $data;
    }
}

