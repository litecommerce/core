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
     * Register event to the order history. Main point of action.
     *
     * @param integer $orderId
     * @param string  $code
     * @param string  $description
     * @param string  $details
     *
     * @return void
     */
    public function registerEvent($orderId, $code, $description, $details = '')
    {
        \XLite\Core\Database::getRepo('XLite\Model\OrderHistoryEvents')->registerEvent($orderId, $code, $description, $details);
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
        $this->registerEvent($orderId, static::CODE_PLACE_ORDER, 'Order placed');
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
            'Order status changed from ' . $statuses[$change['old']] . ' to ' . $statuses[$change['new']]
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
    public function registerOrderChangeNotes($orderId, $change)
    {
        $this->registerEvent($orderId, static::CODE_CHANGE_NOTES_ORDER, 'Order notes changed from "' . $change['old'] . '" to "' . $change['new'] . '"');
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
        $this->registerEvent($orderId, static::CODE_EMAIL_CUSTOMER_SENT, 'Email sent to the customer');
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
        $this->registerEvent($orderId, static::CODE_EMAIL_ADMIN_SENT, 'Email sent to the admin');
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
        $this->registerEvent($orderId, static::CODE_TRANSACTION, 'Transaction made', $transactionData);
    }
}
