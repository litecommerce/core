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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Model\Payment\Base;

/**
 * Abstract credit card-based processor 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class CreditCard extends \XLite\Model\Payment\Base\Online
{
    /**
     * Processor operation codes
     */
    const OPERATION_SALE          = 'sale';
    const OPERATION_AUTH          = 'auth';
    const OPERATION_CAPTURE       = 'capture';
    const OPERATION_CAPTURE_PART  = 'capturePart';
    const OPERATION_CAPTURE_MULTI = 'captureMulti';
    const OPERATION_VOID          = 'void';
    const OPERATION_VOID_PART     = 'voidPart';
    const OPERATION_VOID_MULTI    = 'voidMulti';
    const OPERATION_REFUND        = 'refund';
    const OPERATION_REFUND_PART   = 'refundPart';
    const OPERATION_REFUND_MULTI  = 'refundMulti';


    /**
     * Processor transaction type codes
     */
    const TRANSACTION_SALE    = 'sale';
    const TRANSACTION_AUTH    = 'auth';
    const TRANSACTION_CAPTURE = 'capture';
    const TRANSACTION_VOID    = 'void';
    const TRANSACTION_REFUND  = 'refund';


    /**
     * Get input template
     *
     * @return string or null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getInputTemplate()
    {
        return 'checkout/credit_card.tpl';
    }

    /**
     * Get operation types 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOperationTypes()
    {
        return array(
            self::OPERATION_SALE,
        );
    }

    /* TODO - rework in next step
    public function getAvailableTransactions(\XLite\Model\Order $order)
    {
        $transactions = array();

        // Add initial transactions

        $openTotal = $order->getOpenTotal();
        if (0 < $openTotal) {
            if (in_array(self::OPERATION_SALE, $this->getOperationTypes())) {
                $transactions[self::TRANSACTION_SALE] = $openTotal;
            }

            if (in_array(self::OPERATION_AUTH, $this->getOperationTypes())) {
                $transactions[self::TRANSACTION_AUTH] = $openTotal;
            }
        }

        $authorized = 0;
        $charged = 0;
        $captured = 0;
        $refunded = 0;
        $voided = 0;

        foreach ($this->getTransactions() as $t) {
            if ($t::STATUS_SUCCESS == $t->getStatus()) {
                switch ($t->getType()) {
                    case self::TRANSACTION_CAPTURE:
                        $captured += $t->getValue();
                        $authorized -= $t->getValue();

                    case self::TRANSACTION_SALE:
                        $charged += $t->getValue();
                        break;
    
                    case self::TRANSACTION_AUTH:
                        $authorized += $t->getValue(); 
                        break;

                    case self::TRANSACTION_VOID:
                        $authorized -= $t->getValue();
                        $voided += $t->getValue();
                        break;

                    case self::TRANSACTION_REFUND;
                        $charged -= $t->getValue();
                        $refunded += $t->getValue();
                        break;
                }
            }
        }

        // Detect capture value
        if (0 < $authorized && in_array(self::OPERATION_CAPTURE, $this->getOperationTypes())) {
            if (0 == $captured && 0 == $voided) {
                $transactions[self::TRANSACTION_CAPTURE] = $authorized;

            } elseif (in_array(self::OPERATION_CAPTURE_MULTI, $this->getOperationTypes())) {
                $transactions[self::TRANSACTION_CAPTURE] = $authorized;
            }
        }

        // Detect void value
        if (
            (0 < $authorized && in_array(self::OPERATION_VOID, $this->getOperationTypes()))
            && ((0 == $captured && 0 == $voided) || in_array(self::OPERATION_VOID_MULTI, $this->getOperationTypes()))
        ) {
            $transactions[self::TRANSACTION_VOID] = $authorized;
        }

        // Detect refund valud
        if (
            (0 < $charged && in_array(self::OPERATION_REFUND, $this->getOperationTypes()))
            && (0 == $refunded || in_array(self::OPERATION_REFUND_MULTI, $this->getOperationTypes()))
        ) {
            $transactions[self::TRANSACTION_REFUND] = $charged;
        }

        return $transactions;
    }
    */
}
