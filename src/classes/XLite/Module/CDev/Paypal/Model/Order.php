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

namespace XLite\Module\CDev\Paypal\Model;

/**
 * Order model
 *
 */
class Order extends \XLite\Model\Order implements \XLite\Base\IDecorator
{
    /**
     * Exclude Express Checkout from the list of available for checkout payment methods
     * if Payflow Link or Paypal Advanced are avavilable
     * 
     * @return array
     */
    public function getPaymentMethods()
    {
        $list = parent::getPaymentMethods();

        $transaction = $this->getFirstOpenPaymentTransaction();

        $paymentMethod = $transaction ? $transaction->getPaymentMethod() : null;

        if (!isset($paymentMethod) || !$this->isExpressCheckout($paymentMethod)) {
    
            $expressCheckoutKey = null;
            $found = false;

            foreach ($list as $k => $method) {
                if ($this->isExpressCheckout($method)) {
                    $expressCheckoutKey = $k;
                }

                if (in_array($method->getServiceName(), array('PayflowLink', 'PaypalAdvanced'))) {
                    $found = true;
                }

                if (isset($expressCheckoutKey) && $found) {
                    break;
                }
            }

            if (isset($expressCheckoutKey) && $found) {
                unset($list[$expressCheckoutKey]);
            }
        }

        return $list;
    }

    /**
     * Returns true if specified payment method is ExpressCheckout 
     * 
     * @param \XLite\Model\Payment\Method $method Payment method object
     *  
     * @return boolean
     */
    public function isExpressCheckout($method)
    {
        return 'ExpressCheckout' == $method->getServiceName();
    } 

    /**
     * Returns the associative array of transaction IDs: PPREF and/or PNREF
     * 
     * @return array
     */
    public function getTransactionIds()
    {
        $result = array();

        foreach ($this->getPaymentTransactions() as $t) {

            if ($this->isPaypalMethod($t->getPaymentMethod())) {

                $isTestMode = $t->getDataCell('test_mode');

                if (isset($isTestMode)) {
                    $result[] = array(
                        'url'   => '',
                        'name'  => 'Test mode',
                        'value' => 'yes',
                    );
                }

                $ppref = $t->getDataCell('PPREF');
                if (isset($ppref)) {
                    $result[] = array(
                        'url'   => $this->getTransactionIdURL($t, $ppref->getValue()),
                        'name'  => 'Unique PayPal transaction ID (PPREF)',
                        'value' => $ppref->getValue(),
                    );
                }

                $pnref = $t->getDataCell('PNREF');
                if (isset($pnref)) {
                    $result[] = array(
                        'url'   => '',
                        'name'  => 'Unique Payflow transaction ID (PNREF)',
                        'value' => $pnref->getValue(),
                    );
                }
            }
        }

        return $result;
    }


    /**
     * Get specific transaction URL on PayPal side
     * 
     * @param \XLite\Model\Payment\Transaction $transaction Payment transaction object
     * @param string                           $id          Transaction ID (PPREF)
     *  
     * @return string
     */
    protected function getTransactionIdURL($transaction, $id)
    {
        $isTestMode = $transaction->getDataCell('test_mode');

        return isset($isTestMode)
            ? 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_view-a-trans&id=' . $id
            : 'https://www.paypal.com/cgi-bin/webscr?cmd=_view-a-trans&id=' . $id;
    }

    /**
     * Return true if current payment method is PayPal
     * 
     * @param \XLite\Model\Payment\Method $method Payment method object
     *  
     * @return boolean
     */
    protected function isPaypalMethod($method)
    {
        return isset($method)
            && in_array(
                $method->getServiceName(),
                array(
                    \XLite\Module\CDev\Paypal\Main::PP_METHOD_PPA,
                    \XLite\Module\CDev\Paypal\Main::PP_METHOD_PFL,
                    \XLite\Module\CDev\Paypal\Main::PP_METHOD_EC,
                    \XLite\Module\CDev\Paypal\Main::PP_METHOD_PPS,
                )
            );
    }
}
