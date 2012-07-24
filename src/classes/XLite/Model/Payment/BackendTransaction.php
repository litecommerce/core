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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Model\Payment;

/**
 * Payment backend transaction
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @Entity
 * @Table  (name="payment_backend_transactions",
 *      indexes={
 *          @Index (name="td", columns={"transaction_id","date"})
 *      }
 * )
 */
class BackendTransaction extends \XLite\Model\AEntity
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
     * Transaction types
     */

    const TRAN_TYPE_AUTH          = 'auth';
    const TRAN_TYPE_SALE          = 'sale';
    const TRAN_TYPE_CAPTURE       = 'capture';
    const TRAN_TYPE_CAPTURE_PART  = 'capturePart';
    const TRAN_TYPE_CAPTURE_MULTI = 'captureMulti';
    const TRAN_TYPE_VOID          = 'void';
    const TRAN_TYPE_VOID_PART     = 'voidPart';
    const TRAN_TYPE_VOID_MULTI    = 'voidMulti';
    const TRAN_TYPE_REFUND        = 'refund';
    const TRAN_TYPE_REFUND_PART   = 'refundPart';
    const TRAN_TYPE_REFUND_MULTI  = 'refundMulti';
    const TRAN_TYPE_GET_INFO      = 'getInfo';
    const TRAN_TYPE_ACCEPT        = 'accept';
    const TRAN_TYPE_DECLINE       = 'decline';
    const TRAN_TYPE_TEST          = 'test';


    /**
     * Primary key
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer")
     */
    protected $id;

    /**
     * Transaction creation timestamp
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $date;

    /**
     * Status
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="fixedstring", length=1)
     */
    protected $status = self::STATUS_INITIALIZED;

    /**
     * Transaction value
     *
     * @var   float
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="decimal", precision=14, scale=4)
     */
    protected $value = 0.0000;

    /**
     * Transaction type
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length=8)
     */
    protected $type;

    /**
     * Payment transactions
     *
     * @var   \Doctrine\Common\Collections\Collection
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\Payment\Transaction", inversedBy="backend_transactions")
     * @JoinColumn (name="transaction_id", referencedColumnName="transaction_id")
     */
    protected $payment_transaction;

    /**
     * Transaction data
     *
     * @var   \XLite\Model\Payment\BackendTransactionData
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\Payment\BackendTransactionData", mappedBy="transaction", cascade={"all"})
     */
    protected $data;


    /**
     * Get charge value modifier
     *
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getChargeValueModifier()
    {
        $value = 0;

        if (!$this->isFailed()) {
            $value += $this->getValue();
        }

        return $value;
    }

    /**
     * Get payment method object related to the parent payment transaction
     * 
     * @return \XLite\Model\Payment\Method
     * @see    ____func_see____
     * @since  1.1.0
     */
    public function getPaymentMethod()
    {
        return $this->getPaymentTransaction()->getPaymentMethod();
    }

    /**
     * Check - transaction is failed or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isFailed()
    {
        return self::STATUS_FAILED == $this->getStatus();
    }

    /**
     * Check - order is completed or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isCompleted()
    {
        return self::STATUS_SUCCESS == $this->getStatus();
    }

    /**
     * Constructor
     *
     * @param array $data Entity properties OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct(array $data = array())
    {
        $this->data = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }

    /**
     * Get human-readable status 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getReadableStatus()
    {
        return isset($this->getPaymentTransaction()->readableStatuses[$this->getStatus()])
            ? $this->getPaymentTransaction()->readableStatuses[$this->getStatus()]
            : 'Unknown';
    }

    /**
     * Return true if operation is allowed for currect transaction
     * 
     * @param string $operation Name of operation
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function isOperationAllowed($operation)
    {
        return in_array($operation, $this->getPaymentMethod()->getProcessor()->getAllowedTransactions());
    }

    /**
     * Return true if transaction is an initial 
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.1.0
     */
    public function isInitial()
    {
        return in_array(
            $this->getType(),
            array(
                self::TRAN_TYPE_AUTH,
                self::TRAN_TYPE_SALE,
            )
        );
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
     * @see    ____func_see____
     * @since  1.0.0
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
            $data = new \XLite\Model\Payment\BackendTransactionData;
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
     * Get data cell 
     * 
     * @param string $name Parameter name
     *  
     * @return \XLite\Model\Payment\BackendTransactionData
     * @see    ____func_see____
     * @since  1.1.0
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
}
