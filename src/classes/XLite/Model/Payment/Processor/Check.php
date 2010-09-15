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

namespace XLite\Model\Payment\Processor;

/**
 * E-check
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Check extends \XLite\Model\Payment\Processor\Offline
{
    /**
     * Get input template
     *
     * @return string or null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getInputTemplate()
    {
        return 'checkout/echeck.tpl';
    }

    /**
     * Get input data labels list
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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

    /**
     * Check - display check number or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isDisplayNumber()
    {
        return $this->config->General->display_check_number;
    }

}
