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

namespace XLite\Module\CDev\Promotion\Model\PaymentMethod;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class BonusPoints extends \XLite\Model\PaymentMethod
{
    public $processorName = "Promotion/bonus points";
    public $formTemplate = "modules/CDev/Promotion/checkout.tpl";
    
    function handleRequest(\XLite\Model\Cart $cart)
    {
        $payedByPoints = $_POST['payedByPoints'];
        $details = $cart->get('details');
        if ($cart->getComplex('origProfile.bonusPoints') < $payedByPoints) {
            $details['error'] = "No enought points";
            $cart->set('details', $details);
            $cart->update();
            return self::PAYMENT_FAILURE;
        }
        $totalBonusPoints = $cart->getTotalBonusPoints();
        if ($totalBonusPoints < $payedByPoints) { // too much
            $details['error'] = "Too much bonus points for this order";
            $cart->set('details', $details);
            $cart->update();
            return self::PAYMENT_FAILURE;
        }

        $cart->set('payedByPoints', min($payedByPoints * $this->config->CDev->Promotion->bonusPointsCost, $cart->getMaxPayByPoints()));
        $cart->calcTotals();
        if ($cart->get('total') > 0) {
            $cart->set('payment_method', ""); // choose payment method once again
        	$cart->update();
            header("Location: cart.php?target=checkout&mode=paymentMethod");
            return self::PAYMENT_SILENT;
        } else {
        	$cart->set('status', "P");
        	$cart->update();
            return self::PAYMENT_SUCCESS;
        }
    }

    function is($name)
    {
        if ($name == "enabled" && !$this->xlite->is('adminZone')) {
            if ($this->auth->is('logged')) {
                if ($this->auth->getComplex('profile.bonusPoints') == 0) {
                    // no bonus points, no payment method
                    return false;
                }
            } else {
                return false;
            }
        }
        return parent::is($name);
    }

}
