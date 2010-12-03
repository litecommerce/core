<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

function func_Affiliate_charge(&$payment, $order) 
{
    $commissions = 0;
    // process current partner
    $planCommission = new \XLite\Module\CDev\Affiliate\Model\PlanCommission();
    $commissions = $planCommission->calculate($order);
    if ($commissions >= 0.01) {
        // check for existing partner payment
        $update = $payment->find("partner_id=".$order->getComplex('partner.profile_id')." AND order_id=".$order->get('order_id'));
        // save partner commissions
        $payment->set('commissions', $commissions);
        $payment->set('partner_id',  $order->getComplex('partner.profile_id'));
        $payment->set('order_id',    $order->get('order_id'));
        // save payment
        if ($update) {
            $payment->update();
        } else {
            $payment->create();
        }
        // process partner parents
        $affiliate = $order->getComplex('partner.profile_id');
        foreach ((array)$order->getComplex('partner.parents') as $level => $parent) {
            $rate = $payment->get("config.Affiliate.tier_commission_rates.$level");
            if ($rate > 0) {
                $pp = new \XLite\Module\CDev\Affiliate\Model\PartnerPayment();
                $update = $pp->find("partner_id=".$parent->get('profile_id')." AND order_id=".$order->get('order_id')." AND affiliate=".$affiliate);
                $pc =  round((double)($commissions / 100 * $rate + 0.00000000001), 2);
                if ($pc >= 0.01) {
                    $pp->set('commissions', $pc);
                    $pp->set('partner_id',  $parent->get('profile_id'));
                    $pp->set('order_id',    $order->get('order_id'));
                    $pp->set('affiliate',   $affiliate);
                    if ($update) {
                        $pp->update();
                    } else {
                        $pp->create();
                    }
                }
            }
            $affiliate = $parent->get('profile_id');
        }
    }
    return $commissions;
}

function func_Affiliate_calc_order_commissions(&$planCommission) 
{
    $orderCommissions = 0;
    foreach ($planCommission->getComplex('order.items') as $item) {
        // search for iitem product commission
        $pc = $planCommission->getProductCommission($item->get('product_id'));
        if (!is_null($pc)) {
            $orderCommissions += func_Affiliate_calc_commission_rate($pc, $item);
            continue;
        }
        // search for item category commission 
        foreach ((array)$item->getComplex('product.categories') as $category) {
            $cc = $planCommission->getCategoryCommission($category->get('category_id'));
            if (!is_null($cc)) {
                $orderCommissions += func_Affiliate_calc_commission_rate($cc, $item);
                continue 2;  // next order item
            }
        }
        // search for item basic commission
        $bc = $planCommission->getBasicCommission();
        if (!is_null($bc)) {
            $orderCommissions += func_Affiliate_calc_commission_rate($bc, $item);
        }
    }
    return round((double)$orderCommissions + 0.00000000001, 2);
}

function func_Affiliate_calc_commission_rate($pc, $item) 
{
    $result = 0;
    if ($pc->get('commission_type') == "$") {
        // absolute commission type, return value
        $result = $pc->get('commission');
    } elseif ($pc->get('commission_type') == "%") {
        // percentage rate
        $result = ($item->get('price') * $item->get('amount')) / 100 * $pc->get('commission');
    }
    $result = round((double)$result + 0.00000000001, 2);
    if ($result > 0.01) {
        // save item commissions
        $item->set('commissions', $result);
        $item->update();
    }
    return $result;
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
