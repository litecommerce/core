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
 * E-check
 *
 */
class Check extends \XLite\Model\Payment\Processor\Offline
{
    /**
     * Get input template
     *
     * @return string|void
     */
    public function getInputTemplate()
    {
        return 'checkout/echeck.tpl';
    }

    /**
     * Check - display check number or not
     *
     * @return boolean
     */
    public function isDisplayNumber()
    {
        return \XLite\Core\Config::getInstance()->General->display_check_number;
    }


    /**
     * Get input data labels list
     *
     * @return array
     */
    protected function getInputDataLabels()
    {
        return array(
            'routing_number' => 'ABA routing number',
            'acct_number'    => 'Bank Account Number',
            'type'           => 'Type of Account',
            'bank_name'      => 'Name of bank at which account is maintained',
            'acct_name'      => 'Name under which the account is maintained at the bank',
            'number'         => 'Check number',
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
            'routing_number' => \XLite\Model\Payment\TransactionData::ACCESS_ADMIN,
            'acct_number'    => \XLite\Model\Payment\TransactionData::ACCESS_ADMIN,
            'type'           => \XLite\Model\Payment\TransactionData::ACCESS_ADMIN,
            'bank_name'      => \XLite\Model\Payment\TransactionData::ACCESS_ADMIN,
            'acct_name'      => \XLite\Model\Payment\TransactionData::ACCESS_ADMIN,
            'number'         => \XLite\Model\Payment\TransactionData::ACCESS_ADMIN,
        );
    }
}
