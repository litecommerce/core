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

namespace XLite\Module\WholesaleTrading\Controller\Customer;

// FIXME - must be completely revised

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Profile extends \XLite\Controller\Customer\Profile implements \XLite\Base\IDecorator
{
/*    function init()
    {
        parent::init();
        $this->params[] = "payed_membership";
        $this->params[] = "membership_name";
    }
    
    function action_register()
    {
        parent::action_register();
        if ($this->registerForm->is('valid')) {
            $product = new \XLite\Model\Product();
            if ($this->registerForm->getComplex('profile.pending_membership') != "" && $product->find("selling_membership='" . $this->registerForm->getComplex('profile.pending_membership') . "'")) {
                $oi = new \XLite\Model\OrderItem();
                $oi->set('product', $product);
                $this->cart->addItem($oi);
                $this->updateCart();
                $this->payed_membership = true;
                $this->membership_name = $this->registerForm->getComplex('profile.pending_membership');
            }
        }
    }

    function expDate($period)
    {
        $modifier = array(
            "month" => "m",
            "day"	=> "d",
            "year"	=> "Y"
        );
        
        return date($modifier[$period], $this->auth->getComplex('profile.membership_exp_date'));
    }

    function isShowWholesalerFields()
    {
        if (
            $this->xlite->config->WholesaleTrading->WholesalerFieldsTaxId 	== "Y" ||
            $this->xlite->config->WholesaleTrading->WholesalerFieldsVat 	== "Y" ||
            $this->xlite->config->WholesaleTrading->WholesalerFieldsGst 	== "Y" ||
            $this->xlite->config->WholesaleTrading->WholesalerFieldsPst 	== "Y" 
            ) {
                return true;
            }
            return false;
    }*/
}
