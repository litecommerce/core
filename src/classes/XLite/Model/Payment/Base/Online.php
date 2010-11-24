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
 * Abstract online (gateway-based) processor 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class Online extends \XLite\Model\Payment\Base\Processor
{
    /**
     * Process callback 
     * 
     * @param \XLite\Model\Payment\Transaction $transaction Callback-owner transaction
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function processCallback(\XLite\Model\Payment\Transaction $transaction)
    {
        $this->transaction = $transaction;

        $this->logCallback(\XLite\Core\Request::getInstance()->getData());
    }

    /**
     * Get callback reqeust owner transaction or null
     * 
     * @return \XLite\Model\Payment\Transaction|void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCallbackOwnerTransaction()
    {
        return null;
    }

    /**
     * Get client IP 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getClientIP()
    {
        $result = null;

        if (
            isset($_SERVER['REMOTE_ADDR'])
            && preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/Ss', $_SERVER['REMOTE_ADDR'])
        ) {
            $result = $_SERVER['REMOTE_ADDR'];
        }

        return $result;
    }

    /**
     * Get invoice description 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getInvoiceDescription()
    {
        return 'Order #' . $this->getSetting('prefix') . $this->getOrder()->getOrderId()
            . '; transaction: ' . $this->transaction->getTransactionId();
    }
 
    /**
     * Define saved into transaction data schema
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineSavedData()
    {
        return array();
    }

    /**
     * Save request data into transaction
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function saveDataFromRequest()
    {
        foreach ($this->defineSavedData() as $key => $name) {
            if (isset(\XLite\Core\Request::getInstance()->$key)) {
                $this->setDetail($key, \XLite\Core\Request::getInstance()->$key, $name);
            }
        }
    }

    /**
     * Array cell mask
     * 
     * @param array  $list Array
     * @param string $name CEll key
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function maskCell(array $list, $name)
    {
        if (isset($list[$name])) {
            $list[$name] = str_repeat('*', strlen($list[$name]));
        }

        return $list;
    }

    /**
     * Log callback
     *
     * @param array $list Callback data
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function logCallback(array $list)
    {
        \XLite\Logger::getInstance()->log(
            $this->transaction->getPaymentMethod()->getServiceName() . ' payment gateway : callback' . PHP_EOL
            . 'Data: ' . var_export($list, true),
            LOG_DEBUG
        );
    }
}
