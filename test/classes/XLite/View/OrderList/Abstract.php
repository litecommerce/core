<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Orders list widget
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
 * Abstract orders list widget
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
abstract class XLite_View_OrderList_Abstract extends XLite_View_Dialog
{
    /**
     * Orders list (cache)
     * 
     * @var    array of XLite_Model_Order
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $orders = null;

    /**
     * Widget class name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $widgetClass = '';

    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getHead()
    {
        return 'Search result';
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
        return 'order/list';
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    public function isVisible()
    {
        return parent::isVisible()
            && $this->getOrders();
    }

    /**
     * Get class identifier as CSS class name
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getClassIdentifier()
    {
        return strtolower(str_replace('_', '-', $this->widgetClass));
    }

    /**
     * Get AJAX request parameters
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAJAXRequestParams()
    {
        $params = array(
            'widgetTarget' => XLite_Core_Request::getInstance()->target,
            'widgetAction' => XLite_Core_Request::getInstance()->action,
            'widgetClass'  => $this->widgetClass,
        );

        return $params + $this->getWidgetKeys();
    }

    /**
     * Get AJAX request parameters as javascript object definition
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAJAXRequestParamsAsJSObject()
    {
        $params = $this->getAJAXRequestParams();

        $result = array();
        $forbidden = array('widgetTarget', 'widgetAction', 'widgetClass');

        foreach ($this->getAJAXRequestParams() as $key => $value) {
            if (!in_array($key, $forbidden)) {
                $result[] = $key . ': \'' . $value . '\'';
            }
        }

        return '{ '
            . 'widgetTarget: \'' . $params['widgetTarget'] . '\', '
            . 'widgetAction: \'' . $params['widgetAction'] . '\', '
            . 'widgetClass: \'' . $params['widgetClass'] . '\', '
            . 'widgetParams: { ' . implode(', ', $result) . ' }'
            . ' }';
    }

    /**
     * Get orders 
     * 
     * @return array of XLite_Model_Order
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract public function getOrders();

    /**
     * Get widget keys 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract protected function getWidgetKeys();

    /**
     * Register JS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = 'popup/jquery.blockUI.js';
        $list[] = 'order/list/list.js';

        return $list;
    }
}

