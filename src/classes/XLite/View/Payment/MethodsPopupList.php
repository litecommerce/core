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

namespace XLite\View\Payment;

/**
 * List of payment methods for popup widget
 *
 */
class MethodsPopupList extends \XLite\View\AView
{
    /**
     * Return list of allowed targets
     *
     * @return array
     */
    public static function getAllowedTargets()
    {
        $list = parent::getAllowedTargets();
        $list[] = 'payment_method_selection';

        return $list;
    }

    /**
     * Return file name for the center part template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'payment/methods_popup_list/body.tpl';
    }

    /**
     * Define widget params
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            \XLite\View\Button\Payment\AddMethod::PARAM_PAYMENT_METHOD_TYPE => new \XLite\Model\WidgetParam\String('Payment methods type', ''),
        );
    }

    /**
     * Return payment type for the payment methods list
     *
     * @return string
     */
    protected function getPaymentType()
    {
        return $this->getParam(\XLite\View\Button\Payment\AddMethod::PARAM_PAYMENT_METHOD_TYPE);
    }

    /**
     * Return payment methods list structure to use in the widget
     *
     * @return array
     */
    protected function getPaymentMethods()
    {
        $result = array();

        foreach(\XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->findForAdditionByType($this->getPaymentType()) as $entry) {

            $result[$entry->getModuleName()][] = $entry;
        }

        return $result;
    }
}
