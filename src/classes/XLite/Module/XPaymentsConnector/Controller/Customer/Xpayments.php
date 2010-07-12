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

namespace XLite\Module\XPaymentsConnector\Controller\Customer;

/**
 * X-Payments special controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Xpayments extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Handles the request.
     * Parses the request variables if necessary. Attempts to call the specified action function
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function handleRequest()
    {
        $this->setReturnUrl($this->buildUrl('checkout'));

        parent::handleRequest();
    }

    /**
     * Return from X-Payments
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionReturn()
    {
        $request = \XLite\Core\Request::getInstance();

        if (
            $request->isPost()
            && $request->refId
            && $request->txnId
        ) {

            $pm = $this->getCart()->getPaymentMethod();
            if ($pm->processReturn($this->getCart(), $request->txnId, $request->refId)) {

                $this->setReturnUrl(
                    $this->buildUrl(
                        'checkoutSuccess',
                        '',
                         array('order_id' => $this->cart->get('order_id'))
                    )
                );

            } else {
                // TODO - add top message
            }

        } else {
            // TODO - add top message
        }
    }
}

