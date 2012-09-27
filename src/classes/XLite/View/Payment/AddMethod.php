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
 * Add payment method dialog widget
 *
 * @ListChild (list="admin.center", zone="admin")
 */
class AddMethod extends \XLite\View\SimpleDialog
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
     * Return title
     *
     * @return string
     */
    protected function getHead()
    {
        return 'Add payment method';
    }

    /**
     * Return file name for the center part template
     *
     * @return string
     */
    protected function getBody()
    {
        return 'payment/add_method/body.tpl';
    }

    /**
     * Return payment methods type which is provided to the widget
     *
     * @return string
     */
    protected function getPaymentType()
    {
        return \XLite\Core\Request::getInstance()->{\XLite\View\Button\Payment\AddMethod::PARAM_PAYMENT_METHOD_TYPE};
    }

    /**
     * Return payment methods structure to display payment methods list of specific type
     *
     * @param string $type
     *
     * @return array
     */
    protected function getPaymentMethods($type)
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->findForAdditionByType($type);
    }

}
