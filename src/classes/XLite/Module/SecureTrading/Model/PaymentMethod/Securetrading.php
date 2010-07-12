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

namespace XLite\Module\SecureTrading\Model\PaymentMethod;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Securetrading extends \XLite\Model\PaymentMethod\CreditCard
{

    public $configurationTemplate = "modules/SecureTrading/config.tpl";
    public $processorName = "SecureTrading";
    public $formTemplate = "modules/SecureTrading/checkout.tpl";

    function handleRequest(\XLite\Model\Cart $order) {
        require_once LC_MODULES_DIR . 'SecureTrading' . LC_DS . 'encoded.php';
        PaymentMethod_securetrading_handleRequest($this, $order, true);
    }
    function getTotalCost($cart)	{
        return $cart->get('total')*100;
    }
    function getBillingState($cart) {
        $state = \XLite\Core\Database::getEM()->find('XLite\Model\State', $cart->getComplex('profile.billing_state'));
        return $state ? $state->state : '';
    }
    function getCountry($cart)	{
        $country = \XLite\Core\Database::getEM()->find('XLite\Model\Country', $cart->getComplex('profile.billing_country'));
        return $country ? $country->country : '';
    }
    function getMerchantEmail() {
        return $this->config->Company->orders_department;
    }
    function getReturnURL($cart)	{
        return $this->xlite->getShopUrl("cart.php?target=checkout&action=return&order_id=" . $cart->get('order_id'));
    }
}
