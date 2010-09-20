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

namespace XLite\Model\Payment;

/**
 * Payment transaction
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\Payment\Transaction")
 * @Table (name="payment_transactions")
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


    /**
     * Primary key 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * 
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer")
     */
    protected $transaction_id;

    /**
     * Payment method name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * 
     * @Column (type="string", length="128")
     */
    protected $method_name;

    /**
     * Payment method localized name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     *  @since  3.0.0
     * 
     * @Column (type="string", length="255")
     */
    protected $method_local_name = '';

    /**
     * Status
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * 
     * @Column (type="string", length="1")
     */
    protected $status = self::STATUS_INITIALIZED;

    /**
     * Transaction value
     * 
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="decimal", precision="4", scale="12")
     */
    protected $value = 0.0000;

    /**
     * Customer message
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="255")
     */
    protected $note = '';

    /**
     * Transaction type
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="8")
     */
    protected $type = 'sale';

    /**
     * Order
     * 
     * @var    \XLite\Model\Order
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * 
     * @ManyToOne  (targetEntity="XLite\Model\Order", inversedBy="payment_transactions")
     * @JoinColumn (name="order_id", referencedColumnName="order_id")
     */
    protected $order;

    /**
     * Payment method 
     * 
     * @var    \XLite\Model\Payment\Method
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * 
     * @ManyToOne  (targetEntity="XLite\Model\Payment\Method", inversedBy="transactions")
     * @JoinColumn (name="method_id", referencedColumnName="method_id")
     */
    protected $payment_method;

    /**
     * Transaction data
     *
     * @var    \XLite\Model\Payment\TransactionData
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\Payment\TransactionData", mappedBy="transaction", cascade={"all"})
     */
    protected $data;

    /**
     * Process checkout action 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function handleCheckoutAction()
    {
        $this->setStatus(self::STATUS_INPROGRESS);

        $result = $this->getPaymentMethod()->getProcessor()->pay(
            $this,
            \XLite\Core\Request::getInstance()->payment
        );

        $return = self::COMPLETED;

        switch ($result) {
            case \XLite\Model\Payment\Base\Processor::PROLONGATION:
                $return = self::PROLONGATION;
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

        return $return;
    }

    /**
     * Get charge value modifier 
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * Check - transaction is failed or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isFailed()
    {
        return self::STATUS_FAILED == $this->getStatus();
    }

    /**
     * Check - order is completed or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isCompleted()
    {
        return self::STATUS_SUCCESS == $this->getStatus();
    }
}
