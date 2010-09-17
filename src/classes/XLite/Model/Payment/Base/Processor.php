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
 * Processor 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class Processor extends \XLite\Base
{
    /**
     * Payment procedure result codes
     */
    const PROLONGATION = 'R';
    const COMPLETED    = 'S';
    const PENDING      = 'P';
    const FAILED       = 'F';


    /**
     * Transaction (cache)
     * 
     * @var    \XLite\Model\Payment\Transaction
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $transaction;

    /**
     * Request cell with transaction input data
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $request;

    /**
     * Pay 
     * 
     * @param \XLite\Model\Payment\Transaction $transaction Transaction
     * @param array                            $request     Input data request
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @return string or null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getInputTemplate()
    {
        return null;
    }

    /**
     * Get settings widget or template 
     * 
     * @return string Widget class name or template path
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSettingsWidget()
    {
        return null;
    }

    /**
     * Get current trnsaction order 
     * 
     * @return \XLite\Model\Order
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getOrder()
    {
        return $this->transaction->getOrder();
    }

    /**
     * Get setting value by name
     * 
     * @param string $name Name
     *  
     * @return mixed
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSetting($name)
    {
        return $this->transaction->getPaymentMethod()->getSetting($name);
    }

    /**
     * Check - payment method is configured or not
     * 
     * @param \XLite\Model\Payment\Method $method Payment method
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isConfigured(\XLite\Model\Payment\Method $method)
    {
        return true;
    }

    /**
     * Do initial payment 
     * 
     * @return string Status code
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract protected function doInitialPayment();

    /**
     * Save input data 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function saveInputData()
    {
        $labels = $this->getInputDataLabels();
        $accessLevels = $this->getInputDataAccessLevels();

        foreach ($this->request as $name => $value)  {
            if (isset($accessLevels[$name])) {
                $record = new \XLite\Model\Payment\TransactionData;

                $record->setName($name);
                $record->setValue($value);
                if (isset($labels[$name])) {
                    $record->setLabel($labels[$name]);
                }

                $record->setAccessLevel($accessLevels[$name]);

                $this->transaction->getData()->add($record);
                $record->setTransaction($this->transaction);

                \XLite\Core\Database::getEM()->persist($record);
            }
        }
    }

    /**
     * Get input data labels list
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getInputDataLabels()
    {
        return array();
    }

    /**
     * Get input data access levels list
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getInputDataAccessLevels()
    {
        return array();
    }
}
