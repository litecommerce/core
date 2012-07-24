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

namespace XLite\Core;

/**
 * Order history main point of execution
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class OrderHistory extends \XLite\Base\Singleton
{
    /**
     * Codes for registered events of order history
     */
    const CODE_PLACE_ORDER          = 'PLACE ORDER';
    const CODE_CHANGE_STATUS_ORDER  = 'CHANGE STATUS ORDER';
    const CODE_CHANGE_NOTES_ORDER   = 'CHANGE NOTES ORDER';
    const CODE_EMAIL_CUSTOMER_SENT  = 'EMAIL CUSTOMER SENT';
    const CODE_EMAIL_ADMIN_SENT     = 'EMAIL ADMIN SENT';
    const CODE_TRANSACTION          = 'TRANSACTION';

    /**
     * Texts for the order history event descriptions
     */
    const TXT_PLACE_ORDER           = 'Order placed';
    const TXT_CHANGE_STATUS_ORDER   = 'Order status changed from {{oldStatus}} to {{newStatus}}';
    const TXT_CHANGE_NOTES_ORDER    = 'Order notes changed from "{{oldNote}}" to "{{newNote}}"';
    const TXT_EMAIL_CUSTOMER_SENT   = 'Email sent to the customer';
    const TXT_EMAIL_ADMIN_SENT      = 'Email sent to the admin';
    const TXT_TRANSACTION           = 'Transaction made';

    /**
     * Register event to the order history. Main point of action.
     *
     * @param integer $orderId
     * @param string  $code
     * @param string  $description
     * @param string  $details
     *
     * @return void
     */
    public function registerEvent($orderId, $code, $description, array $data = array(), $details = '')
    {
        \XLite\Core\Database::getRepo('XLite\Model\OrderHistoryEvents')
            ->registerEvent($orderId, $code, $description, $data, $details);
    }

    /**
     * Register "Place order" event to the order history
     *
     * @param integer $orderId
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function registerPlaceOrder($orderId)
    {
        $this->registerEvent(
            $orderId,
            static::CODE_PLACE_ORDER,
            $this->getPlaceOrderDescription($orderId),
            $this->getPlaceOrderData($orderId)
        );
    }

    /**
     * Register changes of order in the order history
     *
     * @param integer $orderId
     * @param array   $changes
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function registerOrderChanges($orderId, $changes)
    {
        foreach ($changes as $name => $change) {
            if (method_exists($this, 'registerOrderChange' . ucfirst($name))) {
                $this->{'registerOrderChange' . ucfirst($name)}($orderId, $change);
            }
        }
    }

    /**
     * Register status order changes
     *
     * @param integer $orderId
     * @param array   $change  old,new structure
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function registerOrderChangeStatus($orderId, $change)
    {
        $statuses = \XLite\Model\Order::getAllowedStatuses();

        $this->registerEvent(
            $orderId,
            static::CODE_CHANGE_STATUS_ORDER,
            $this->getOrderChangeStatusDescription($orderId, $change),
            $this->getOrderChangeStatusData($orderId, $change)
        );
    }

    /**
     * Register order notes changes
     *
     * @param integer $orderId
     * @param array   $change  old,new structure
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function registerOrderChangeAdminNotes($orderId, $change)
    {
        $this->registerEvent(
            $orderId,
            static::CODE_CHANGE_NOTES_ORDER,
            $this->getOrderChangeNotesDescription($orderId, $change),
            $this->getOrderChangeNotesData($orderId, $change)
        );
    }

    /**
     * Register email sending to the customer
     *
     * @param integer $orderId
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function registerCustomerEmailSent($orderId)
    {
        $this->registerEvent(
            $orderId,
            static::CODE_EMAIL_CUSTOMER_SENT,
            $this->getCustomerEmailSentDescription($orderId),
            $this->getCustomerEmailSentData($orderId)
        );
    }

    /**
     * Register email sending to the admin in the order history
     *
     * @param integer $orderId
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function registerAdminEmailSent($orderId)
    {
        $this->registerEvent(
            $orderId,
            static::CODE_EMAIL_ADMIN_SENT,
            $this->getAdminEmailSentDescription($orderId),
            $this->getAdminEmailSentData($orderId)
        );
    }

    /**
     * Register transaction data to the order history
     *
     * @param integer $orderId
     * @param string  $transactionData
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function registerTransaction($orderId, $transactionData)
    {
        $this->registerEvent(
            $orderId,
            static::CODE_TRANSACTION,
            $this->getTransactionDescription($orderId, $transactionData),
            $this->getTransactionData($orderId, $transactionData),
            $this->getTransactionDetails($orderId, $transactionData)
        );
    }

    /**
     * Text for place order description
     *
     * @param integer $orderId
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPlaceOrderDescription($orderId)
    {
        return static::TXT_PLACE_ORDER;
    }

    /**
     * Data for place order description
     *
     * @param integer $orderId
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPlaceOrderData($orderId)
    {
        return array(
            'orderId' => $orderId,
        );
    }

    /**
     * Text for change order status description
     *
     * @param integer $orderId
     * @param array   $change
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getOrderChangeStatusDescription($orderId, array $change)
    {
        return static::TXT_CHANGE_STATUS_ORDER;
    }

    /**
     * Data for change order status description
     *
     * @param integer $orderId
     * @param array   $change
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getOrderChangeStatusData($orderId, array $change)
    {
        $statuses = \XLite\Model\Order::getAllowedStatuses();

        return array(
            'orderId'   => $orderId,
            'newStatus' => $statuses[$change['new']],
            'oldStatus' => $statuses[$change['old']],
        );
    }

    /**
     * Text for change order notes description
     *
     * @param integer $orderId
     * @param array   $change
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getOrderChangeNotesDescription($orderId, $change)
    {
        return static::TXT_CHANGE_NOTES_ORDER;
    }

    /**
     * Data for change order notes description
     *
     * @param integer $orderId
     * @param array   $change
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getOrderChangeNotesData($orderId, $change)
    {
        return array(
            'orderId' => $orderId,
            'newNote' => $change['new'],
            'oldNote' => $change['old'],
        );
    }

    /**
     * Text for customer email sent description
     *
     * @param integer $orderId
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCustomerEmailSentDescription($orderId)
    {
        return static::TXT_EMAIL_CUSTOMER_SENT;
    }

    /**
     * Data for customer email sent description
     *
     * @param integer $orderId
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCustomerEmailSentData($orderId)
    {
        return array(
            'orderId' => $orderId,
        );
    }

    /**
     * Text for admin email sent description
     *
     * @param integer $orderId
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getAdminEmailSentDescription($orderId)
    {
        return static::TXT_EMAIL_ADMIN_SENT;
    }

    /**
     * Data for admin email sent description
     *
     * @param integer $orderId
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getAdminEmailSentData($orderId)
    {
        return array(
            'orderId' => $orderId,
        );
    }

    /**
     * Text for transaction description
     *
     * @param integer $orderId
     * @param mixed   $transactionData
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTransactionDescription($orderId, $transactionData)
    {
        return static::TXT_TRANSACTION;
    }

    /**
     * Data for transaction description
     *
     * @param integer $orderId
     * @param mixed   $transactionData
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTransactionData($orderId, $transactionData)
    {
        return array(
            'orderId'   => $orderId,
            'data'      => $transactionData,
        );
    }

    /**
     * Details for transaction description
     *
     * @param integer $orderId
     * @param mixed   $transactionData
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTransactionDetails($orderId, $transactionData)
    {
        return $transactionData;
    }
}
