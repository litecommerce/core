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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Controller\Customer;

/**
 * Payment method callback
 *
 */
class Callback extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Handles the request
     *
     * @return void
     */
    public function handleRequest()
    {
        \XLite\Core\Request::getInstance()->action = 'callback';

        parent::handleRequest();
    }


    /**
     * This controller is always accessible
     * TODO - check if it's really needed; remove if not
     *
     * @return void
     */
    protected function checkStorefrontAccessability()
    {
        return true;
    }

    /**
     * Process callback
     *
     * @return void
     */
    protected function doActionCallback()
    {
        $txn = null;
        $txnIdName = 'txnId';

        if (isset(\XLite\Core\Request::getInstance()->txn_id_name)) {
            /**
             * some of gateways can't accept return url on run-time and
             * use the one set in merchant account, so we can't pass
             * 'order_id' in run-time, instead pass the order id parameter name
             */
            $txnIdName = \XLite\Core\Request::getInstance()->txn_id_name;
        }

        if (isset(\XLite\Core\Request::getInstance()->$txnIdName)) {
            $txn = \XLite\Core\Database::getRepo('XLite\Model\Payment\Transaction')
                ->find(\XLite\Core\Request::getInstance()->$txnIdName);
        }

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

            $cart = $txn->getOrder();

            if (!$cart->isOpen()) {
                // TODO: move it to \XLite\Controller\ACustomer

                if ($cart->isPayed()) {

                    $status = $txn->isCaptured()
                        ? \XLite\Model\Order::STATUS_PROCESSED
                        : \XLite\Model\Order::STATUS_AUTHORIZED;

                } else {

                    if ($txn->isRefunded()) {
                        $status = \XLite\Model\Order::STATUS_DECLINED;

                    } elseif ($txn->isFailed()) {
                        $status = \XLite\Model\Order::STATUS_FAILED;

                    } else {
                        $status = \XLite\Model\Order::STATUS_QUEUED;;
                    }
                }

                $cart->setStatus($status);
            }

        } else {

            \XLite\Logger::getInstance()->log(
                'Request callback with undefined payment transaction' . PHP_EOL
                . 'Data: ' . var_export(\XLite\Core\Request::getInstance()->getData(), true)
                , LOG_ERR
            );
        }

        \XLite\Core\Database::getEM()->flush();

        $this->set('silent', true);
    }
}
