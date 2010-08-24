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

namespace XLite\View\OrderList;

/**
 * Abstract order list
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class AOrderList extends \XLite\View\Dialog
{
    /**
     * Orders list (cache)
     * 
     * @var    array of \XLite\Model\Order
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
     * @since  3.0.0
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
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'order/list';
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    protected function isVisible()
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
            'widgetTarget' => \XLite\Core\Request::getInstance()->target,
            'widgetAction' => \XLite\Core\Request::getInstance()->action,
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
     * @return array of \XLite\Model\Order
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

        $list[] = 'js/jquery.blockUI.js';
        $list[] = 'order/list/list.js';

        return $list;
    }

    /**
     * Register CSS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'order/list/list.css';

        return $list;
    }

}

