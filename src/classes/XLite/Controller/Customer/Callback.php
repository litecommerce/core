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
 * Payment method callback
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Callback extends \XLite\Controller\Customer\ACustomer
{
    /**
     * This controller is always accessible
     * TODO - check if it's really needed; remove if not
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function checkStorefrontAccessability()
    {
        return true;
    }

    /**
     * Handles the request
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function handleRequest()
    {
        \XLite\Core\Request::getInstance()->action = 'callback';

        parent::handleRequest();
    }

    /**
     * Process callback
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionCallback()
    {
        if (isset(\XLite\Core\Request::getInstance()->txn_id_name)) {
            /**
             * some of gateways can't accept return url on run-time and
             * use the one set in merchant account, so we can't pass
             * 'order_id' in run-time, instead pass the order id parameter name
             */
            $txnIdName = \XLite\Core\Request::getInstance()->txn_id_name;

        } else {
            $txnIdName = 'txn_id';
        }

        if (!isset(\XLite\Core\Request::getInstance()->$txnIdName)) {
            $this->doDie('The transaction ID variable \'' . $txnIdName . '\' is not found in request');
        }

        $txn = \XLite\Core\Database::getRepo('XLite\Model\Payment\Transaction')
            ->find(\XLite\Core\Request::getInstance()->$txnIdName);

        if (!$txn) {

            $methods = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->findAllActive();
            foreach ($methods as $method) {
                if (method_exists($method->getProcessor(), 'getCallbackOwnerTransaction')) {
                    $txn = $method->getProcessor()->getCallbackOwnerTransaction();
                    if ($txn) {
                        break;
                    }
                }
            }

        }

        if ($txn) {
            $txn->getPaymentMethod()->getProcessor()->processCallback($txn);

        } else {
            // TODO - add error logging
        }

        $this->set('silent', true);
    }
}
