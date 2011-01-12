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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Customer\Base;

/**
 * Order 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class Order extends \XLite\Controller\Customer\ACustomer
{
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
	 * Return current order ID
	 * 
	 * @return integer
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function getOrderId()
	{
		return intval(\XLite\Core\Request::getInstance()->order_id);
	}

	/**
	 * Return current order
	 * 
	 * @return \XLite\Model\Order
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function getOrder()
	{
		if (!isset($this->order)) {
			$this->order = \XLite\Core\Database::getRepo('XLite\Model\Order')->find($this->getOrderId());
		}

		return $this->order;
	}

	/**
	 * Check if currently logged user is an admin
	 * 
	 * @return boolean
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function isAdmin()
	{
		return \XLite\Core\Auth::getInstance()->getProfile()->isAdmin();
	}

	/**
	 * Check if order corresponds to current user
	 * 
	 * @return boolean
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function checkOrderProfile()
	{
		return \XLite\Core\Auth::getInstance()->getProfileId() == $this->getOrder()->getOrigProfile()->getProfileId();
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
		return \XLite\Core\Auth::getInstance()->isLogged() && ($this->isAdmin() || $this->checkOrderProfile());
    }

	/**
     * Check if current page is accessible
     *
     * @return boolean
     * @access protected
     * @since  3.0.0
     */
    protected function checkAccess()
    {
        return parent::checkAccess() && $this->getOrder() && $this->checkOrderAccess();
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
     * Get secure controller status
	 * FIXME: remove if it's not needed
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
