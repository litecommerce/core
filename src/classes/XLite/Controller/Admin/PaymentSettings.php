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

namespace XLite\Controller\Admin;

/**
 * Payment methods
 *
 */
class PaymentSettings extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Return the current page title (for the content area)
     *
     * @return string
     */
    public function getTitle()
    {
        return 'Payment settings';
    }

    /**
     * Enable method 
     * 
     * @return void
     */
    protected function doActionEnable()
    {
        $method = \XLite\Core\Request::getInstance()->id
            ? \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->find(\XLite\Core\Request::getInstance()->id)
            : null;

        if ($method && $method->canEnable()) {
            $method->setEnabled(true);
            \XLite\Core\TopMessage::addInfo('Payment method has been enabled successfully');
            \XLite\Core\Database::getEM()->flush();
        }

        $this->setReturnURL(\XLite\Core\Converter::buildURL('payment_settings'));
    }

    /**
     * Disable method
     * 
     * @return void
     */
    protected function doActionDisable()
    {
        $method = \XLite\Core\Request::getInstance()->id
            ? \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->find(\XLite\Core\Request::getInstance()->id)
            : null;

        if ($method && !$method->isForcedEnabled()) {
            $method->setEnabled(false);
            \XLite\Core\TopMessage::addInfo('Payment method has been disabled successfully');
            \XLite\Core\Database::getEM()->flush();
        }

        $this->setReturnURL(\XLite\Core\Converter::buildURL('payment_settings'));
    }

    /**
     * Remove method
     *
     * @return void
     */
    protected function doActionRemove()
    {
        $method = \XLite\Core\Request::getInstance()->id
            ? \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->find(\XLite\Core\Request::getInstance()->id)
            : null;

        if ($method && !$method->isForcedEnabled()) {
            $method->setAdded(false);
            \XLite\Core\TopMessage::addInfo('Payment method has been removed successfully');
            \XLite\Core\Database::getEM()->flush();
        }

        $this->setReturnURL(\XLite\Core\Converter::buildURL('payment_settings'));
    }

}
