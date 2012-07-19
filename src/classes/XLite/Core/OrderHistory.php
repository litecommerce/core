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
    const CODE_CHANGE_ORDER         = 'CHANGE ORDER';
    const CODE_EMAIL_CUSTOMER_SENT  = 'EMAIL CUSTOMER SENT';
    const CODE_EMAIL_ADMIN_SENT     = 'EMAIL ADMIN SENT';
    const CODE_TRANSACTION          = 'TRANSACTION';

    public function registerEvent($orderId, $code, $description, $details = '')
    {
        \XLite\Core\Database::getRepo('XLite\Model\OrderHistoryEvents')->registerEvent($orderId, $code, $description, $details);
    }

    public function registerPlaceOrder($orderId)
    {
        $this->registerEvent($orderId, static::CODE_PLACE_ORDER, 'Order was placed');
    }

    public function registerChangeStatusOrder($orderId, $oldStatus, $newStatus)
    {
        $this->registerEvent($orderId, static::CODE_CHANGE_STATUS_ORDER, 'Order status was changed from "' . $oldStatus . '" to "' . $newStatus . '"');
    }

    public function registerCustomerEmailSent($orderId)
    {
        $this->registerEvent($orderId, static::CODE_EMAIL_CUSTOMER_SENT, 'Email was sent to the customer');
    }

    public function registerAdminEmailSent($orderId)
    {
        $this->registerEvent($orderId, static::CODE_EMAIL_ADMIN_SENT, 'Email was sent to the admin');
    }

    public function registerTransaction($orderId, $transactionData)
    {
        $this->registerEvent($orderId, static::CODE_TRANSACTION, 'Transaction was made', $transactionData);
    }
}
