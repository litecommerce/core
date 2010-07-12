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

namespace XLite\Module\Affiliate\Model;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class PlanCommission extends \XLite\Model\AModel
{
    public $fields = array(
            "plan_id" => 0,
            "commission" => "0.00",
            "commission_type" => '%',
            "item_id" => 0,
            "item_type" => "",
            );

    public $alias = "partner_plan_commissions";
    public $primaryKey = array('plan_id', "item_id", "item_type");

    function getProduct()
    {
        return new \XLite\Model\Product($this->get('item_id'));
    }

    function getCategory()
    {
        return new \XLite\Model\Category($this->get('item_id'));
    }

    function getBasicCommission()
    {
        $bc = new \XLite\Module\Affiliate\Model\PlanCommission();
        if ($bc->find("plan_id=".$this->getComplex('order.partner.plan')." AND item_id=0 AND item_type='B'")) {
            return $bc;
        }
        return null;
    }
    function getProductCommission($product_id)
    {
        $pc = new \XLite\Module\Affiliate\Model\PlanCommission();
        if ($pc->find("plan_id=".$this->getComplex('order.partner.plan')." AND item_id=$product_id AND item_type='P'")) {
            return $pc;
        }
        return null;
    }
    function getCategoryCommission($category_id)
    {
        $cc = new \XLite\Module\Affiliate\Model\PlanCommission();
        if ($cc->find("plan_id=".$this->getComplex('order.partner.plan')." AND item_id=$category_id AND item_type='C'")) {
            return $cc;
        }
        return null;
    }

    function getOrderCommissions()
    {
        require_once LC_MODULES_DIR . 'Affiliate' . LC_DS . 'encoded.php';
        return func_Affiliate_calc_order_commissions($this);
    }

    function calculate($order)
    {
        $this->order = $order;
        $commissions = 0;
        $ap = new \XLite\Module\Affiliate\Model\AffiliatePlan($order->getComplex('partner.plan'));
        if ($ap->is('enabled')) {
            $commissions = $this->get('orderCommissions');
        }
        return $commissions;
    }
}
