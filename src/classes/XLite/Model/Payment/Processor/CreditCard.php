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

namespace XLite\Model\Payment\Processor;

/**
 * Credit card
 *
 */
class CreditCard extends \XLite\Model\Payment\Processor\Offline
{
    /**
     * Get input template
     *
     * @return string|void
     */
    public function getInputTemplate()
    {
        return 'checkout/credit_card.tpl';
    }

    /**
     * Get input data labels list
     *
     * @return array
     */
    protected function getInputDataLabels()
    {
        return array(
            'name'       => 'Cardholder\'s name',
            'number'     => 'Credit card Number',
            'date'       => 'Expiration date',
            'start_date' => 'Start date',
            'issue'      => 'Issue number',
        );
    }

    /**
     * Get input data access levels list
     *
     * @return array
     */
    protected function getInputDataAccessLevels()
    {
        return array(
            'name'       => \XLite\Model\Payment\TransactionData::ACCESS_ADMIN,
            'number'     => \XLite\Model\Payment\TransactionData::ACCESS_ADMIN,
            'date'       => \XLite\Model\Payment\TransactionData::ACCESS_ADMIN,
            'start_date' => \XLite\Model\Payment\TransactionData::ACCESS_ADMIN,
            'issue'      => \XLite\Model\Payment\TransactionData::ACCESS_ADMIN,
        );
    }

}
