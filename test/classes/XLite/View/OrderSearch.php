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
 * Orders search widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_View_OrderSearch extends XLite_View_Dialog
{
    /**
     * Conditions (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $conditions = null;

    /**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0 EE
     */
    protected $allowedTargets = array('order_list');

    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getHead()
    {
        return 'Search orders';
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
        return 'order/search';
    }

    /**
     * Get condition 
     * 
     * @param string $name Condition name
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCondition($name)
    {
        if (is_null($this->conditions)) {
            $this->conditions = $this->session->get('orders_search');
            if (!is_array($this->conditions)) {
                $this->conditions = XLite_Model_Order::getDefaultSearchConditions();
                $this->session->set('orders_search', $this->conditions);
            }
        }

        return isset($this->conditions[$name]) ? $this->conditions[$name] : null;
    }
}

