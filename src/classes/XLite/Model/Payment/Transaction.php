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

namespace XLite\Model\Payment;

/**
 * Payment transaction
 *
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\Payment\Transaction")
 * @Table  (name="payment_transactions",
 *      indexes={
 *          @Index (name="status", columns={"status"}),
 *          @Index (name="o", columns={"order_id","status"}),
 *          @Index (name="pm", columns={"method_id","status"})
 *      }
 * )
 */
class Transaction extends \XLite\Model\AEntity
{
    /**
     * Transaction status codes
     */

    const STATUS_INITIALIZED = 'I';
    const STATUS_INPROGRESS  = 'P';
    const STATUS_SUCCESS     = 'S';
    const STATUS_PENDING     = 'W';
    const STATUS_FAILED      = 'F';

    /**
     * Transaction initialization result
     */

    const PROLONGATION = 'R';
    const COMPLETED    = 'C';
    const SILENT       = 'S';
    const SEPARATE     = 'E';


    /**
     * Primary key
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer")
     */
    protected $transaction_id;

    /**
     * Payment method name
     *
     * @var string
     *
     * @Column (type="string", length=128)
     */
    protected $method_name;

    /**
     * Payment method localized name
     *
     * @var string
     *
     * @Column (type="string", length=255)
     */
    protected $method_local_name = '';

    /**
     * Status
     *
     * @var string
     *
     * @Column (type="fixedstring", length=1)
     */
    protected $status = self::STATUS_INITIALIZED;

    /**
     * Transaction value
     *
     * @var float
     *
     * @Column (type="decimal", precision=14, scale=4)
     */
    protected $value = 0.0000;

    /**
     * Customer message
     *
     * @var string
     *
     * @Column (type="string", length=255)
     */
    protected $note = '';

    /**
     * Transaction type
     *
     * @var string
     *
     * @Column (type="string", length=8)
     */
    protected $type = 'sale';

    /**
     * Public transaction ID
     *
     * @var string
     *
     * @Column (type="string")
     */
    protected $public_id = '';

    /**
     * Order
     *
     * @var \XLite\Model\Order
     *
     * @ManyToOne  (targetEntity="XLite\Model\Order", inversedBy="payment_transactions")
     * @JoinColumn (name="order_id", referencedColumnName="order_id")
     */
    protected $order;

    /**
     * Payment method
     *
     * @var \XLite\Model\Payment\Method
     *
     * @ManyToOne  (targetEntity="XLite\Model\Payment\Method", inversedBy="transactions")
     * @JoinColumn (name="method_id", referencedColumnName="method_id")
     */
    protected $payment_method;

    /**
     * Transaction data
     *
     * @var \XLite\Model\Payment\TransactionData
     *
     * @OneToMany (targetEntity="XLite\Model\Payment\TransactionData", mappedBy="transaction", cascade={"all"})
     */
    protected $data;

    /**
     * Related backend transactions
     *
     * @var \XLite\Model\Payment\BackendTransaction
     *
     * @OneToMany (targetEntity="XLite\Model\Payment\BackendTransaction", mappedBy="payment_transaction", cascade={"all"})
     */
    protected $backend_transactions;

    /**
     * Readable statuses 
     * 
     * @var array
     */
    protected $readableStatuses = array(
        self::STATUS_INITIALIZED => 'Initialized',
        self::STATUS_INPROGRESS  => 'In progress',
        self::STATUS_SUCCESS     => 'Completed',
        self::STATUS_PENDING     => 'Pending',
        self::STATUS_FAILED      => 'Failed',
    );

    /**
     * Process checkout action
     *
     * @return string
     */
    public function handleCheckoutAction()
    {
        $this->setStatus(self::STATUS_INPROGRESS);
        \XLite\Core\Database::getEM()->flush();

        $data = is_array(\XLite\Core\Request::getInstance()->payment)
            ? \XLite\Core\Request::getInstance()->payment
            : array();

        $result = $this->getPaymentMethod()->getProcessor()->pay($this, $data);

        $return = self::COMPLETED;

        switch ($result) {
            case \XLite\Model\Payment\Base\Processor::PROLONGATION:
                $return = self::PROLONGATION;
                break;

            case \XLite\Model\Payment\Base\Processor::SILENT:
                $return = self::SILENT;
                break;

            case \XLite\Model\Payment\Base\Processor::SEPARATE:
                $return = self::SEPARATE;
                break;

            case \XLite\Model\Payment\Base\Processor::COMPLETED:
                $this->setStatus(self::STATUS_SUCCESS);
                break;

            case \XLite\Model\Payment\Base\Processor::PENDING:
                $this->setStatus(self::STATUS_PENDING);
                break;

            default:
                $this->setStatus(self::STATUS_FAILED);
        }

        $this->registerTransactionInOrderHistory();

        return $return;
    }

    /**
     * Get charge value modifier
     *
     * @return float
     */
    public function getChargeValueModifier()
    {
        $value = 0;

        if ($this->isCompleted() || $this->isPending()) {
            $value += $this->getValue();
        }

        return $value;
    }

    /**
     * Check - transaction is failed or not
     *
     * @return boolean
     */
    public function isFailed()
    {
        return self::STATUS_FAILED == $this->getStatus();
    }

    /**
     * Check - order is completed or not
     *
     * @return boolean
     */
    public function isCompleted()
    {
        return self::STATUS_SUCCESS == $this->getStatus();
    }

    /**
     * Check - order is in progress state or not
     *
     * @return boolean
     */
    public function isInProgress()
    {
        return self::STATUS_INPROGRESS == $this->getStatus();
    }

    /**
     * Return true if transaction is in PENDING status
     *
     * @return boolean
     */
    public function isPending()
    {
        return self::STATUS_PENDING == $this->getStatus();
    }

    /**
     * Returns true if successful payment has type AUTH
     * 
     * @return boolean
     */
    public function isAuthorized()
    {
        $result = \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_AUTH == $this->getType() && $this->isCompleted();

        if ($result && $this->getBackendTransactions()) {
            foreach ($this->getBackendTransactions() as $transaction) {
                if (
                    \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_VOID == $transaction->getType()
                    && self::STATUS_SUCCESS == $transaction->getStatus()
                ) {
                    $result = false;
                }
            }
        }

        return $result;
    }

    /**
     * Returns true if successful payment has type SALE or has successful CAPTURE transaction 
     * 
     * @return boolean
     */
    public function isCaptured()
    {
        $result = \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_SALE == $this->getType() && $this->isCompleted();

        if ($this->getBackendTransactions()) {

            foreach ($this->getBackendTransactions() as $transaction) {
                if (
                    \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_CAPTURE == $transaction->getType()
                    && self::STATUS_SUCCESS == $transaction->getStatus()
                ) {
                    $result = true;
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Returns true if payment has successful REFUND transaction 
     * 
     * @return boolean
     */
    public function isRefunded()
    {
        $result = false;

        if ($this->getBackendTransactions()) {
            foreach ($this->getBackendTransactions() as $transaction) {
                if (
                    \XLite\Model\Payment\BackendTransaction::TRAN_TYPE_REFUND == $transaction->getType()
                    && self::STATUS_SUCCESS == $transaction->getStatus()
                ) {
                    $result = true;
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Returns true if CAPTURE transaction is allowed for this payment
     * 
     * @return boolean
     */
    public function isCaptureTransactionAllowed()
    {
        return $this->isAuthorized() && !$this->isCaptured() && !$this->isRefunded();
    }

    /**
     * Returns true if VOID transaction is allowed for this payment
     * 
     * @return boolean
     */
    public function isVoidTransactionAllowed()
    {
        return $this->isCaptureTransactionAllowed();
    }

    /**
     * Returns true if REFUND transaction is allowed for this payment
     * 
     * @return boolean
     */
    public function isRefundTransactionAllowed()
    {
        return $this->isCaptured() && !$this->isRefunded();
    }

    /**
     * Constructor
     *
     * @param array $data Entity properties OPTIONAL
     *
     * @return void
     */
    public function __construct(array $data = array())
    {
        $this->data = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }

    /**
     * Get human-readable status 
     *
     * @param string $status Transaction status
     *
     * @return string
     */
    public function getReadableStatus($status = null)
    {
        if (!isset($status)) {
            $status = $this->getStatus();
        }

        return isset($this->readableStatuses[$status])
            ? $this->readableStatuses[$status]
            : 'Unknown';
    }

    // {{{ Data operations

    /**
     * Set data cell 
     * 
     * @param string $name  Data cell name
     * @param string $value Value
     * @param string $label Public name OPTIONAL
     *  
     * @return void
     */
    public function setDataCell($name, $value, $label = null)
    {
        $data = null;

        foreach ($this->getData() as $cell) {
            if ($cell->getName() == $name) {
                $data = $cell;
                break;
            }
        }

        if (!$data) {
            $data = new \XLite\Model\Payment\TransactionData;
            $data->setName($name);
            $this->addData($data);
            $data->setTransaction($this);
        }

        if (!$data->getLabel() && $label) {
            $data->setLabel($label);
        }

        $data->setValue($value);
    }

    /**
     * Get data cell object by name
     * 
     * @param string $name Name of data cell
     *  
     * @return \XLite\Model\Payment\TransactionData
     */
    public function getDataCell($name)
    {
        $value = null;

        foreach ($this->getData() as $cell) {
            if ($cell->getName() == $name) {
                $value = $cell;
                break;
            }
        }

        return $value;
    }

    // }}}

    /**
     * Create backend transaction 
     * 
     * @param string $transactionType Type of backend transaction
     *  
     * @return \XLite\Model\Payment\BackendTransaction
     */
    public function createBackendTransaction($transactionType)
    {
        $data = array(
            'date'                => time(),
            'type'                => $transactionType,
            'value'               => $this->getValue(),
            'payment_transaction' => $this,
        );

        $bt = \XLite\Core\Database::getRepo('XLite\Model\Payment\BackendTransaction')->insert($data);
      
        $this->addBackendTransactions($bt);

        return $bt;
    }

    /**
     * Get initial backend transaction (related to the first payment transaction)
     * 
     * @return \XLite\Model\Payment\BackendTransaction
     */
    public function getInitialBackendTransaction()
    {
        $bt = null;

        foreach ($this->getBackendTransactions() as $transaction) {
            if ($transaction->isInitial()) {
                $bt = $transaction;
                break;
            }
        }

        return $bt;
    }

    /**
     * Register transaction in order history 
     * 
     * @param string $suffix Suffix text to add to the end of event description
     *  
     * @return void
     */
    public function registerTransactionInOrderHistory($suffix = null)
    {
        $descrSuffix = !empty($suffix) ? ' [' . static::t($suffix) . ']' : '';

        \XLite\Core\OrderHistory::getInstance()->registerTransaction(
            $this->getOrder()->getOrderId(),
            static::t($this->getHistoryEventDescription(), $this->getHistoryEventDescriptionData()) . $descrSuffix,
            $this->getEventData()
        );
    }

    /**
     * Get description of order history event (language label is returned)
     * 
     * @return string
     */
    public function getHistoryEventDescription()
    {
        return 'Payment transaction X issued';
    }

    /**
     * Get data for description of order history event (substitution data for language label is returned)
     * 
     * @return return
     */
    public function getHistoryEventDescriptionData()
    {
        return array(
            'trx_method' => static::t($this->getPaymentMethod()->getName()),
            'trx_type'   => static::t($this->getType()),
            'trx_value'  => $this->getOrder()->getCurrency()->roundValue($this->getValue()),
            'trx_status' => static::t($this->getReadableStatus()),
        );
    }

    /**
     * getEventData 
     * 
     * @return void
     */
    public function getEventData()
    {
        $result = array();

        foreach ($this->getData() as $cell) {
            $result[] = array(
                'name'  => $cell->getLabel() ?: $cell->getName(),
                'value' => $cell->getValue()
            );
        }

        return $result;
    }
}
