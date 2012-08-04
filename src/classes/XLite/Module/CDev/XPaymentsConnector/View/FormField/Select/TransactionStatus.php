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

namespace XLite\Module\CDev\XPaymentsConnector\View\FormField\Select;

/**
 * Transaction status selector
 *
 */
class TransactionStatus extends \XLite\View\FormField\Select\Regular
{
    /**
     * Return default options list
     *
     * @return array
     */
    protected function getDefaultOptions()
    {
        return array(
            ''                                                   => static::t('Do not change'),
            \XLite\Model\Payment\Transaction::STATUS_INITIALIZED => static::t('Initialized'),
            \XLite\Model\Payment\Transaction::STATUS_INPROGRESS  => static::t('In progress'),
            \XLite\Model\Payment\Transaction::STATUS_SUCCESS     => static::t('Completed'),
            \XLite\Model\Payment\Transaction::STATUS_PENDING     => static::t('Pending'),
            \XLite\Model\Payment\Transaction::STATUS_FAILED      => static::t('Failed'),
        );
    }

}
