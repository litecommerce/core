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
    protected function isExpressCheckout($method)
    {
        return 'ExpressCheckout' == $method->getServiceName();
    } 
}
