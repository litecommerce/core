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

namespace XLite\Module\CDev\Paypal\Controller\Admin;

/**
 * Payment method page controller
 * 
 */
class PaymentMethod extends \XLite\Controller\Admin\PaymentMethod implements \XLite\Base\IDecorator
{
    /**
     * Modify request to allow process specific GET-actions
     *
     * @return string
     */
    public function handleRequest()
    {
        if (
            isset(\XLite\Core\Request::getInstance()->action)
            && $this->isPaypalInstructionAction()
        ) {
            \XLite\Core\Request::getInstance()->setRequestMethod('POST');
        }

        parent::handleRequest();
    }


    /**
     * Return true if action is hide/show instruction
     * 
     * @return boolean
     */
    protected function isPaypalInstructionAction()
    {
        return in_array(
            \XLite\Core\Request::getInstance()->action,
            array(
                'hide_instruction',
                'show_instruction',
            )
        );
    }

    /**
     * Hide payment settings instruction block
     *
     * @return void
     */
    protected function doActionHideInstruction()
    {
        $this->switchDisplayInstructionFlag(true);
    }

    /**
     * Hide payment settings instruction block
     *
     * @return void
     */
    protected function doActionShowInstruction()
    {
        $this->switchDisplayInstructionFlag(false);
    }

    /**
     * Switch hide_instruction parameter 
     * 
     * @param boolean $value Value of parameter
     *  
     * @return void
     */
    protected function switchDisplayInstructionFlag($value)
    {
        $paymentMethod = $this->getPaymentMethod();

        if ($paymentMethod) {
            $paymentMethod->setSetting('hide_instruction', $value);
            \XLite\Core\Database::getRepo('\XLite\Model\Payment\Method')->update($paymentMethod);
        }

        $this->setReturnURL(
            $this->buildURL(
                'payment_method',
                null,
                array('method_id' => \XLite\Core\Request::getInstance()->method_id)
            )
        );
    }
}
