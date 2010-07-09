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

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_Affiliate_Controller_Admin_PlanCommissions extends XLite_Controller_Admin_AAdmin
{
    public $params = array('target', "plan_id");
    
    function action_update_commission()
    {
        if ($this->get('update')) {
            $commissions = $this->get('commission');
            $types = $this->get('commission_type');
            if (!is_array($commissions) || !is_array($types)) {
                return; // wrong data
            }
            foreach ($this->get('commission') as $itemID => $commission) {
                $pc = new XLite_Module_Affiliate_Model_PlanCommission($this->get('plan_id'), $itemID, $this->get('item_type'));
                $pc->set('commission', $commissions[$itemID]);
                $pc->set('commission_type', $types[$itemID]);
                $pc->update();
            }
        } elseif ($this->get('delete')) {
            $deleteItems = $this->get('delete_items');
            if (is_array($deleteItems)) {
                foreach ($deleteItems as $itemID => $status) {
                    $pc = new XLite_Module_Affiliate_Model_PlanCommission($this->get('plan_id'), $itemID, $this->get('item_type'));
                    $pc->delete();
                }
            }
        }
    }

    function action_add_commission()
    {
        $pc = $this->get('planCommission');
        $pc->set('properties', $_POST);
        $pc->create();
    }
    
    function action_basic_commission()
    {
        $pc = $this->get('basicCommission');
        $pc->set('properties', $_POST);
        if ($this->foundBasicCommission) {
            $pc->update();
        } else {
            $pc->create();
        }
    }

    function getAffiliatePlan()
    {
        $ap = new XLite_Module_Affiliate_Model_AffiliatePlan(isset($_REQUEST['plan_id']) ? $_REQUEST['plan_id'] : null);
        $ap->set('properties', $_REQUEST);
        return $ap;
    }

    function getCategoryCommissions()
    {
        $pc = $this->get('planCommission');
        return $pc->findAll("plan_id=".$_REQUEST['plan_id']." AND item_type='C'");
    }

    function getProductCommissions()
    {
        $pc = $this->get('planCommission');
        return $pc->findAll("plan_id=".$_REQUEST['plan_id']." AND item_type='P'");
    }

    function getBasicCommission()
    {
        $pc = $this->get('planCommission');
        $this->foundBasicCommission = $pc->find("plan_id=".$_REQUEST['plan_id']." AND item_type='B'");
        return $pc;
    }

    function getPlanCommission()
    {
        if (is_null($this->pc)) {
            $this->pc = new XLite_Module_Affiliate_Model_PlanCommission();
        }
        return $this->pc;
    }
}
