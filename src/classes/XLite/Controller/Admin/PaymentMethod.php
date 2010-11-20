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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Admin;

/**
 * Payment method
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class PaymentMethod extends \XLite\Controller\Admin\AAdmin
{
    /**
     * getPaymentMethod 
     * 
     * @return \XLite\Model\Payment\Method
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getPaymentMethod()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Payment\Method')->find(\XLite\Core\Request::getInstance()->method_id);
    }
    
    /**
     * Common method to determine current location
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return $this->getPaymentMethod()->getName();
    }

    /**
     * Add part to the location nodes list
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        $this->addLocationNode('Payment methods', $this->buildURL('payment_methods'));
    }

    /**
     * Update payment method
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdate()
    {
        $settings = \XLite\Core\Request::getInstance()->settings;
        $m = $this->getPaymentMethod();

       if (!is_array($settings)) {

            // TODO - add top message

        } elseif (!$m) {

            // TODO - add top message

        } else {

            foreach ($settings as $name => $value) {
                $m->setSetting($name, $value);
            }

            \XLite\Core\Database::getRepo('\XLite\Model\Payment\Method')->update($m);

            $this->setReturnUrl($this->buildUrl('payment_methods'));
        }
    }
}
