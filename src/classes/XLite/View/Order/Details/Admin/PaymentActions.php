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

namespace XLite\View\Order\Details\Admin;

/**
 * Payment actions widget (capture, refund, void etc)
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class PaymentActions extends \XLite\View\AView
{
    /**
     *  Widget parameter names
     */
    const PARAM_ORDER         = 'order';
    const PARAM_UNITS_FILTER  = 'unitsFilter';


    protected $allowedTransactions = null;


    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return $this->getDir() . '/payment_actions.tpl';
    }

    /**
     * Register CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = $this->getDir() . '/style.css';

        return $list;
    }

    /**
     * Return widget directory
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDir()
    {
        return 'order/order';
    }


    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_ORDER        => new \XLite\Model\WidgetParam\Object('Order', null, false, 'XLite\Model\Order'),
            self::PARAM_UNITS_FILTER => new \XLite\Model\WidgetParam\Set('Units filter', array(), false),
        );
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && $this->getParam(self::PARAM_ORDER)
            && 0 < count($this->getTransactions());
    }

    // {{{ Content helpers

    /**
     * Get transactions 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTransactions()
    {
        return $this->getParam(self::PARAM_ORDER)->getPaymentTransactions();
    }

    /**
     * Get backend transactions 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getBackendTransactions($transaction)
    {
        return $transaction->getBackendTransactions();
    }

    /**
     * Get transaction human-readable status 
     * 
     * @param \XLite\Model\Payment\Transaction $transaction Transaction
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTransactionStatus(\XLite\Model\Payment\Transaction $transaction)
    {
        return static::t($transaction->getReadableStatus());
    }

    /**
     * Get transaction additional data 
     * 
     * @param \XLite\Model\Payment\Transaction $transaction Transaction
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTransactionData(\XLite\Model\Payment\Transaction $transaction)
    {
        $list = array();

        foreach ($transaction->getData() as $cell) {
            if ($cell->getLabel()) {
                $list[] = $cell;
            }
        }

        return $list;
    }

    /**
     * Get list of allowed backend transactions
     * 
     * @param \XLite\Model\Payment\Transaction $transaction Payment transaction
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function getTransactionUnits($transaction = null)
    {
        if (!isset($this->allowedTransactions) && isset($transaction)) {

            $processor = $transaction->getPaymentMethod()->getProcessor();

            $this->allowedTransactions = $processor->getAllowedTransactions();

            foreach ($this->allowedTransactions as $k => $v) {
                if (!$processor->isTransactionAllowed($transaction, $v) || !$this->isTransactionFiltered($v)) {
                    unset($this->allowedTransactions[$k]);
                }
            }
        }

        return $this->allowedTransactions;
    }

    /**
     * Returns true if transaction is in filter 
     * 
     * @param string $transactionType Type of backend transaction
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function isTransactionFiltered($transactionType)
    {
        $filter = $this->getParam(self::PARAM_UNITS_FILTER);

        return (empty($filter) || in_array($transactionType, $filter));
    }

    /**
     * Returns true if unit is last in the array (for unit separator displaying)
     * 
     * @param integer $key Key of unit in the array
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.1.0
     */
    protected function isLastUnit($key)
    {
        return array_pop(array_keys($this->getTransactionUnits())) == $key;
    }

    // }}}
}

