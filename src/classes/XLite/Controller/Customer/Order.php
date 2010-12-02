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
 * Order controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Order extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Controller parameters
     * FIXME - to remove
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $params = array('target', 'order_id');

    /**
     * Order (cache)
     * 
     * @var    \XLite\Model\Order
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $order;


    /**
     * Return the current page title (for the content area)
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getTitle()
    {
        return 'Order #' . $this->getOrder()->getOrderId()
            . ', ' . date('M d, Y, H:i', $this->getOrder()->getDate());
    }

    /**
     * Add the base part of the location path
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        $this->addLocationNode('Search orders', $this->buildURL('order_list'));
    }

    /**
     * Common method to determine current location 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return 'Order details';
    }

    /**
     * Check if current page is accessible
     * 
     * @return boolean 
     * @access public
     * @since  3.0.0
     */
    public function checkAccess()
    {
        return parent::checkAccess() && $this->checkOrderAccess();
    }

    /**
     * Check controller visibility
     *
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isVisible()
    {
        return !is_null($this->getOrder());
    }

    /**
     * Check order access 
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkOrderAccess()
    {
        $access = false;
        $auth = \XLite\Core\Auth::getInstance();
        if ($auth->isLogged()) {
            // Not valid order is processed in isValid() method
            $access = !$this->getOrder()
                || $auth->getProfile()->getProfileId() == $this->getOrder()->getOrigProfileId();
        }

        return \XLite\Core\Session::getInstance()->last_order_id == \XLite\Core\Request::getInstance()->order_id
            || $access;
    }

    /**
     * Get order 
     * 
     * @return \XLite\Model\Order
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOrder()
    {
        if (!isset($this->order)) {

            $this->order = \XLite\Core\Database::getRepo('XLite\Model\Order')
                ->find(intval(\XLite\Core\Request::getInstance()->order_id));

        }

        return $this->order;
    }

    /**
     * Get controller charset 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCharset()
    {
        $charset = $this->getOrder()->getProfile()->getBillingAddress()->getCountry()->getCharset();

        return $charset ? $charset : parent::getCharset();
    }

    /**
     * Get secure controller status
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSecure()
    {
        return $this->config->Security->customer_security;
    }
}
