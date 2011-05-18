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

namespace XLite\Model\Payment\Processor;

/**
 * Purchase order
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class PurchaseOrder extends \XLite\Model\Payment\Processor\Offline
{
    /**
     * Get input template
     *
     * @return string|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getInputTemplate()
    {
        return 'checkout/purchase_order.tpl';
    }

    /**
     * Get input errors
     *
     * @param array $data Input data
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getInputErrors(array $data)
    {
        $errors = parent::getInputErrors($data);

        foreach ($this->getInputDataLabels() as $k => $t) {
            if (!isset($data[$k]) || !$data[$k]) {
                $errors[] = \XLite\Core\Translation::lbl('X field is required', array('field' => $t));
            }
        }

        return $errors;
    }


    /**
     * Get input data labels list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getInputDataLabels()
    {
        return array(
            'number'    => 'Purchase order number',
            'company'   => 'Company name',
            'purchaser' => 'Name of purchaser',
            'position'  => 'Position',
        );
    }

    /**
     * Get input data access levels list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getInputDataAccessLevels()
    {
        return array(
            'number'    => \XLite\Model\Payment\TransactionData::ACCESS_CUSTOMER,
            'company'   => \XLite\Model\Payment\TransactionData::ACCESS_ADMIN,
            'purchaser' => \XLite\Model\Payment\TransactionData::ACCESS_ADMIN,
            'position'  => \XLite\Model\Payment\TransactionData::ACCESS_ADMIN,
        );
    }
}
