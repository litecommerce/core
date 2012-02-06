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

namespace XLite\Controller\Admin;

/**
 * Payment method
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class PaymentMethod extends \XLite\Controller\Admin\AAdmin
{
    /**
     * getPaymentMethod
     *
     * @return \XLite\Model\Payment\Method
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPaymentMethod()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Payment\Method')
            ->find(\XLite\Core\Request::getInstance()->method_id);
    }

    /**
     * Update payment method
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionUpdate()
    {
        $settings = \XLite\Core\Request::getInstance()->settings;
        $m = $this->getPaymentMethod();

        if (!is_array($settings)) {
            \XLite\Core\TopMessage::addError('Wrong input data!');

        } elseif (!$m) {
            \XLite\Core\TopMessage::addError('An attempt to update settings of unknown payment method');

        } else {
            foreach ($settings as $name => $value) {
                $m->setSetting($name, $value);
            }

            \XLite\Core\Database::getRepo('\XLite\Model\Payment\Method')->update($m);

            \XLite\Core\TopMessage::addInfo('The settings of payment method successfully updated');

            $this->setReturnURL($this->buildURL('payment_methods'));
        }
    }
}
