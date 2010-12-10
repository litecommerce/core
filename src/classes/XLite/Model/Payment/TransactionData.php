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
 * Transaction data storage
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 *
 * @Entity
 * @Table (name="payment_transaction_data",
 *      indexes={
 *          @Index (name="tn", columns={"transaction_id","name"})
 *      }
 * )
 */
class TransactionData extends \XLite\Model\AEntity
{
    /**
     * Access level codes
     */
    const ACCESS_ADMIN    = 'A';
    const ACCESS_CUSTOMER = 'C';


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
    protected $data_id;

    /**
     * Record name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * 
     * @Column (type="string", length="128")
     */
    protected $name;

    /**
     * Record public name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * 
     * @Column (type="string", length="255")
     */
    protected $label = '';

    /**
     * Access level
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * 
     * @Column (type="fixedstring", length="1")
     */
    protected $access_level = self::ACCESS_ADMIN;

    /**
     * Value
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="text")
     */
    protected $value;

    /**
     * Transaction
     * 
     * @var    \XLite\Model\Payment\Transaction
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * 
     * @ManyToOne  (targetEntity="XLite\Model\Payment\Transaction", inversedBy="data")
     * @JoinColumn (name="transaction_id", referencedColumnName="transaction_id")
     */
    protected $transaction;

    /**
     * Check record availability
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isAvailable()
    {
        return (\XLite::isAdminZone() && self::ACCESS_ADMIN == $this->getAccessLevel())
            || self::ACCESS_CUSTOMER == $this->getAccessLevel();
    }
}
