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

namespace XLite\Model\Payment\Base;

/**
 * Processor
 *
 */
abstract class Processor extends \XLite\Base
{
    /**
     * Payment procedure result codes
     */
    const PROLONGATION = 'R';
    const SILENT       = 'W';
    const SEPARATE     = 'E';
    const COMPLETED    = 'S';
    const PENDING      = 'P';
    const FAILED       = 'F';


    /**
     * Transaction (cache)
     *
     * @var \XLite\Model\Payment\Transaction
     */
    protected $transaction;

    /**
     * Request cell with transaction input data
     *
     * @var array
     */
    protected $request;


    /**
     * Do initial payment
     *
     * @return string Status code
     */
    abstract protected function doInitialPayment();

    /**
     * Get allowed transactions list
     *
     * @return string Status code
     */
    public function getAllowedTransactions()
    {
        return array();
    }

    /**
     * Return tru if backend transaction is allowed for current payment transaction
     * 
     * @param \XLite\Model\Payment\Transaction $transaction     Payment transaction object
     * @param string                           $transactionType Backend transaction type
     *  
     * @return boolean
     */
    public function isTransactionAllowed(\XLite\Model\Payment\Transaction $transaction, $transactionType)
    {
        $result = false;

        if (in_array($transactionType, $this->getAllowedTransactions())) {

            $methodName = 'is' . ucfirst($transactionType) . 'TransactionAllowed';

            if (method_exists($transaction, $methodName)) {
                // Call transaction tyoe specific method 
                $result = $transaction->$methodName();
            }
            
            if (method_exists($this, $methodName)) {
                $result = $this->{'is' . ucfirst($transactionType) . 'TransactionAllowed'}($transaction);
            }
        }

        return $result;
    }

    /**
     * doTransaction 
     * 
     * @param \XLite\Model\Payment\Transaction $transaction     Payment transaction object
     * @param string                           $transactionType Backend transaction type
     *  
     * @return void
     */
    public function doTransaction(\XLite\Model\Payment\Transaction $transaction, $transactionType)
    {
        if ($this->isTransactionAllowed($transaction, $transactionType)) {

            $methodName = 'do' . ucfirst($transactionType);

            if (method_exists($this, $methodName)) {
                $txn = $transaction->createBackendTransaction($transactionType);
                // Call backend transaction type specific method
                $this->$methodName($txn);

                $txn->registerTransactionInOrderHistory();
            }
        }
    }

    /**
     * Pay
     *
     * @param \XLite\Model\Payment\Transaction $transaction Transaction
     * @param array                            $request     Input data request OPTIONAL
     *
     * @return string
     */
    public function pay(\XLite\Model\Payment\Transaction $transaction, array $request = array())
    {
        $this->transaction = $transaction;
        $this->request = $request;

        $this->saveInputData();

        return $this->doInitialPayment();
    }

    /**
     * Get input template
     *
     * @return string|void
     */
    public function getInputTemplate()
    {
        return null;
    }

    /**
     * Get input errors
     *
     * @param array $data Input data
     *
     * @return array
     */
    public function getInputErrors(array $data)
    {
        return array();
    }

    /**
     * Check - payment method is configurable or not
     * 
     * @param \XLite\Model\Payment\Method $method Payment method
     *  
     * @return boolean
     */
    public function isConfigurable(\XLite\Model\Payment\Method $method)
    {
        return (bool)$this->getConfigurationURL($method);
    }

    /**
     * Get payment method configuration page URL
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return string
     */
    public function getConfigurationURL(\XLite\Model\Payment\Method $method)
    {
        $url = null;

        if ($this->getSettingsWidget()) {
            $url = \XLite\Core\Converter::buildURL('payment_method', '', array('method_id' => $method->getMethodId()));

        } elseif ($this->hasModuleSettings() && $this->getModule() && $this->getModule()->getSettingsForm()) {
            $url = $this->getModule()->getSettingsForm();
            $url .= (false === strpos($url, '?') ? '?' : '&')
                . 'return=' . urlencode(\XLite\Core\Converter::buildURL('payment_settings'));
        }

        return $url;
    }

    /**
     * Get settings widget or template
     *
     * @return string Widget class name or template path
     */
    public function getSettingsWidget()
    {
        return null;
    }

    /**
     * Payment method has settings into Module settings section
     *
     * @return boolean
     */
    public function hasModuleSettings()
    {
        return false;
    }

    /**
     * Check - payment method is configured or not
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return boolean
     */
    public function isConfigured(\XLite\Model\Payment\Method $method)
    {
        return true;
    }

    /**
     * Check - payment processor is applicable for specified order or not
     *
     * @param \XLite\Model\Order          $order  Order
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return boolean
     */
    public function isApplicable(\XLite\Model\Order $order, \XLite\Model\Payment\Method $method)
    {
        $currencies = $this->getAllowedCurrencies($method);

        return !$currencies || in_array($order->getCurrency()->getCode(), $currencies);
    }

    /**
     * Get payemnt method icon path
     *
     * @param \XLite\Model\Order          $order  Order
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return string
     */
    public function getIconPath(\XLite\Model\Order $order, \XLite\Model\Payment\Method $method)
    {
        return null;
    }

    /**
     * Get payment method row checkout template
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return string
     */
    public function getCheckoutTemplate(\XLite\Model\Payment\Method $method)
    {
        return 'checkout/steps/payment/method.tpl';
    }

    /**
     * Get processor module
     *
     * @return \XLite\Model\Module
     */
    public function getModule()
    {
        return preg_match('/XLite\\\Module\\\(\w+)\\\(\w+)\\\/Ss', get_called_class(), $match)
            ? \XLite\Core\Database::getRepo('XLite\Model\Module')
                ->findOneBy(array('author' => $match[1], 'name' => $match[2]))
            : null;
    }

    /**
     * Get initial transaction type (used when customer places order)
     *
     * @param \XLite\Model\Payment\Method $method Payment method object OPTIONAL
     *
     * @return string
     */
    public function getInitialTransactionType($method = null)
    {
        return \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_SALE;
    }

    /**
     * Get current transaction order
     *
     * @return \XLite\Model\Order
     */
    protected function getOrder()
    {
        return $this->transaction->getOrder();
    }

    /**
     * Get current transaction order profile
     *
     * @return \XLite\Model\Profile
     */
    protected function getProfile()
    {
        return $this->transaction->getOrder()->getProfile();
    }

    /**
     * Get setting value by name
     *
     * @param string $name Name
     *
     * @return mixed
     */
    protected function getSetting($name)
    {
        return $this->transaction->getPaymentMethod()->getSetting($name);
    }

    /**
     * Save input data
     *
     * @return void
     */
    protected function saveInputData($backendTransaction = null)
    {
        $labels = $this->getInputDataLabels();
        $accessLevels = $this->getInputDataAccessLevels();

        foreach ($this->request as $name => $value) {
            if (isset($accessLevels[$name])) {
                $this->setDetail(
                    $name,
                    $value,
                    isset($labels[$name]) ? $labels[$name] : null,
                    isset($backendTransaction) ? $backendTransaction : null
                );
            }
        }
    }

    /**
     * Set transaction detail record
     *
     * @param string                                  $name               Code
     * @param string                                  $value              Value
     * @param string                                  $label              Label OPTIONAL
     * @param \XLite\Model\Payment\BackendTransaction $backendTransaction Backend transaction object OPTIONAL
     *
     * @return void
     */
    protected function setDetail($name, $value, $label = null, $backendTransaction = null)
    {
        $transaction = isset($backendTransaction) ? $backendTransaction : $this->transaction;

        $transaction->setDataCell($name, $value, $label);
    }

    /**
     * Get input data labels list
     *
     * @return array
     */
    protected function getInputDataLabels()
    {
        return array();
    }

    /**
     * Get input data access levels list
     *
     * @return array
     */
    protected function getInputDataAccessLevels()
    {
        return array();
    }

    /**
     * Get allowed currencies
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return array
     */
    protected function getAllowedCurrencies(\XLite\Model\Payment\Method $method)
    {
        return array();
    }

    // {{{ Method helpers

    /**
     * Get payment method admin zone icon URL
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return string
     */
    public function getAdminIconURL(\XLite\Model\Payment\Method $method)
    {
        return null;
    }

    /**
     * Check - payment method has enabled test mode or not
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return boolean
     */
    public function isTestMode(\XLite\Model\Payment\Method $method)
    {
        return \XLite\View\FormField\Select\TestLiveMode::TEST === $method->getSetting('mode');
    }

    /**
     * Get warning note by payment method
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return string
     */
    public function getWarningNote(\XLite\Model\Payment\Method $method)
    {
        return null;
    }

    /**
     * Check - payment method is forced enabled or not
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return boolean
     */
    public function isForcedEnabled(\XLite\Model\Payment\Method $method)
    {
        return false;
    }

    /**
     * Get note with explanation why payment method was forcibly enabled
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return string
     */
    public function getForcedEnabledNote(\XLite\Model\Payment\Method $method)
    {
        return null;
    }

    /**
     * Check - payment method can be enabled or not
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return boolean
     */
    public function canEnable(\XLite\Model\Payment\Method $method)
    {
        return true;
    }

    /**
     * Get note with explanation why payment method can not be enabled
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return string
     */
    public function getForbidEnableNote(\XLite\Model\Payment\Method $method)
    {
        return null;
    }

    /**
     * Get links
     *
     * @param \XLite\Model\Payment\Method $method Payment method
     *
     * @return array
     */
    public function getLinks(\XLite\Model\Payment\Method $method)
    {
        return array();
    }

    /**
     * Get URL of referral page
     *
     * @return string
     */
    public function getReferralPageURL(\XLite\Model\Payment\Method $method)
    {
        return null;
    }

    /**
     * Return true if payment method settings form should use default submit button.
     * Otherwise, settings widget must define its own button
     * 
     * @return boolean
     */
    public function useDefaultSettingsFormButton()
    {
        return true;
    }

    /**
     * Do something when payment method is enabled 
     * 
     * @return void
     */
    public function enableMethod(\XLite\Model\Payment\Method $method)
    {
        return null;
    }

    // }}}
}
