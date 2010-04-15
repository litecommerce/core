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
class XLite_Module_Affiliate_Controller_Admin_Partners extends XLite_Controller_Admin_Abstract
{	
    public $params = array("target", "search", "filter", "partnerStatus", "plan_id", "plan", "startDateMonth", "startDateDay", "startDateYear", "endDateMonth", "endDateDay", "endDateYear", "itemsPerPage");

    function fillForm()
    {
        if (!isset($this->startDate)) {
            $date = getdate(time());
            $this->set("startDate", mktime(0,0,0,$date['mon'],1,$date['year']));
        }
        parent::fillForm();
    }

    function action_update_partners()
    {
        $partners = $this->get("ids");
        if (!is_null($partners) && is_array($partners)) {
            foreach ($partners as $pid) {
                $partner = new XLite_Model_Profile($pid);
                if (!is_null($this->get("delete"))) {
                    $this->auth->deletePartner($partner);
                } else if (!is_null($this->get("update"))) {
                    $plan = $this->get("new_plan");
                    $status = $this->get("status");
                    if (!empty($status)) {
                        $action = $status . "Partner";
                        if (method_exists($this->auth, $action)) {
                            $this->auth->$action($partner);
                        }
                    }
                    if (!empty($plan)) {
                        $partner->set("plan", $plan);
                    }
                    if (!empty($plan) || !empty($status)) {
                        $partner->update();
                    }
                }
            }    
        }
    }
    
    function getPartners()
    {
        if (is_null($this->partners)) {
            $this->partners = array();
            $where = array();
            // build WHERE condition for profile info
            if (!is_null($this->get("filter")) && trim($this->get("filter")) != "") {
                $filter = "'%".trim($this->get("filter"))."%'";
                $where[] = "(login LIKE $filter".
                    "  OR billing_firstname LIKE $filter".
                    "  OR billing_lastname LIKE $filter)";
            }
            if (!is_null($this->get("partnerStatus")) && trim($this->get("partnerStatus")) != "") {
                $where[] = " access_level = ". trim($this->get("partnerStatus"));
            } else {    
                $where[] = " (access_level = ".   $this->getComplex('auth.partnerAccessLevel') .
                           " OR access_level = ". $this->getComplex('auth.pendingPartnerAccessLevel') . 
                           " OR access_level = ". $this->getComplex('auth.declinedPartnerAccessLevel') . ")"; 
            }
            if (!is_null($this->get("pending_plan")) && trim($this->get("pending_plan")) != "") {
                $where[] = " pending_plan = ".$this->get("pending_plan");
            }
            if (!is_null($this->get("plan_id")) && trim($this->get("plan_id")) != "") {
                $where[] = " ".$this->get("plan") . " = " . $this->get("plan_id");
            }
            if (!is_null($this->get("startDate"))) {
                $where[] = " partner_signup >= " . $this->get("startDate");
            }
            if (!is_null($this->get("endDate"))) {
                $where[] = " partner_signup <= " . ($this->get("endDate") + 24 * 3600);
            }
            $and = join(' AND ',$where);
            $profile = new XLite_Model_Profile();
            $this->partners = $profile->findAll($and, "partner_signup DESC");
            $this->partnersCount = count($this->partners);
        }
        return $this->partners;
    }

    function getPlan()
    {
        if (is_null($this->plan)) {
            return "plan";
        }
        return $this->plan;
    }

    function getItemsPerPage()
    {
        if (is_null($this->itemsPerPage)) {
            return 10;
        }
        return $this->itemsPerPage;
    }
}
