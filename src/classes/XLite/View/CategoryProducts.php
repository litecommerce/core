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
 * Category products list widget
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0
 */
class XLite_View_CategoryProducts extends XLite_View_ProductsList
{
    /**
     * Widget parameter names
     */

    const PARAM_CATEGORY_ID = 'category_id';


    /**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $allowedTargets = array('category');


    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'Catalog';
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

        $this->widgetParams += array(
            self::PARAM_CATEGORY_ID => new XLite_Model_WidgetParam_ObjectId_Category('Category ID', 0),
        );

        $this->requestParams[] = self::PARAM_CATEGORY_ID;
    }

    /**
     * getOrderByCondition 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getOrderByCondition()
    {
        return $this->getParam(self::PARAM_SORT_BY) . ' ' . strtoupper($this->getParam(self::PARAM_SORT_ORDER));
    }

    /**
     * Get products 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getData()
    {
        return $this->getCategory()->getProducts(null, $this->getOrderByCondition());
    }

    /**
     * Fetch param value from current session
     * FIXME - need a common approach to manage such situations
     *
     * @param string $param parameter name
     *
     * @return mixed
     * @access protected
     * @since  3.0.0
     */
    protected function getSavedRequestParam($param)
    {
        return self::PARAM_CATEGORY_ID == $param
            ? XLite_Core_Request::getInstance()->$param
            : parent::getSavedRequestParam($param);
    }
}

