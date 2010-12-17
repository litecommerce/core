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

namespace XLite\Module\CDev\Affiliate\Model;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Profile extends \XLite\Model\Profile implements \XLite\Base\IDecorator
{
    public function __construct($id = null) 
    {
        $this->fields['parent'] = 0;
        $this->fields['partner_fields'] = "";
        $this->fields['plan'] = 0;
        $this->fields['pending_plan'] = 0;
        $this->fields['reason'] = ""; // approval / not approval reason
        $this->fields['partner_signup'] = time();
        // fields available only for core
        $this->_securefields['plan'] = "";
        $this->_securefields['reason'] = "";
        parent::__construct($id);
    }

    function set($name, $value) 
    {
        if ($name == "partner_fields" && is_array($value)) {
            $value = serialize($value);
        }
        parent::set($name, $value);
    }

    function get($name) 
    {
        $value = parent::get($name);
        if ($name == "partner_fields") {
            $result = unserialize($value);
            if (is_array($result)) {
                $value = $result;
            }
        }
        return $value;
    }

    function getParentProfile() 
    {
        if (is_null($this->parentProfile)) {
            $pp = new \XLite\Model\Profile();
            if ($pp->find("profile_id=".$this->get('parent'))) {
                $this->parentProfile = $pp;
            }
        }
        return $this->parentProfile;
    }

    // IS_A methods {{{
    function isDeclinedPartner()
    {
        return $this->auth->isDeclinedPartner($this);
    }
    function isPendingPartner()
    {
        return $this->auth->isPendingPartner($this);
    }
    function isPartner()
    {
        return $this->auth->isPartner($this);
    }
    

    function getPartnerPlan() 
    {
        if (is_null($this->partnerPlan)) {
            $this->partnerPlan = new \XLite\Module\CDev\Affiliate\Model\AffiliatePlan($this->get('plan'));
        }
        return $this->partnerPlan;
    }

    function getParents() 
    {
        $parents = array();
        $tiers = intval($this->config->CDev->Affiliate->tiers_number);
        if ($tiers > 1) {
            $parent = $this->get('parent');
            $level = 2; // start from level 2 affiliate
            // search for parents chain
            do {
                $p = new \XLite\Model\Profile();
                $found = $p->find("profile_id=".$parent);
                if ($found) {
                    $parents[$level] = $p;
                    $parent = $p->get('parent');
                }
            } while ($found && $level++ < $tiers);
        }
        return $parents;
    }

    function getAffiliates() 
    {
        if (is_null($this->affiliates)) {
            $this->affiliates = array();
            $level = 2;
            $this->buildAffiliatesTree($this->affiliates, $level);
        }
        return $this->affiliates;
   }

    function buildAffiliatesTree(&$affiliates, $level) 
    {
        $tiers = intval($this->config->CDev->Affiliate->tiers_number);
        $pp = new \XLite\Module\CDev\Affiliate\Model\PartnerPayment();
        foreach ($this->findAll("parent=".$this->get('profile_id')) as $cid => $child) {
            $child->set('level', $level);
            $child->set('relative', $level <= $tiers); // parent gets commissions from this child
            $affiliates[] = $child;
            $child->buildAffiliatesTree($affiliates, $level + 1);
        }
    }

    function getPartnerCommissions() 
    {
        if (is_null($this->partnerCommissions)) {
            $this->partnerCommissions = 0.00;
            $pp = new \XLite\Module\CDev\Affiliate\Model\PartnerPayment();
            // own commissions
            foreach ((array)$pp->findAll("partner_id=".$this->get('profile_id')." AND affiliate=0") as $payment) {
                $this->partnerCommissions += $payment->get('commissions');
            }
        }
        return $this->partnerCommissions;
    }

    function getAffiliateCommissions() 
    {
        if (is_null($this->affiliateCommissions)) {
            $this->affiliateCommissions = 0.00;
            $pp = new \XLite\Module\CDev\Affiliate\Model\PartnerPayment();
            // own commissions
            foreach ((array)$pp->findAll("partner_id=".$this->get('profile_id')." AND affiliate<>0") as $payment) {
                $this->affiliateCommissions += $payment->get('commissions');
            }
        }
        return $this->affiliateCommissions;
    }

    function getBranchCommissions() 
    {
        if (is_null($this->branchCommissions)) {
            $this->branchCommissions = 0.00;
            foreach ((array)$this->get('affiliates') as $partner) {
                $pp = new \XLite\Module\CDev\Affiliate\Model\PartnerPayment();
                foreach ((array)$pp->findAll("partner_id=".$partner->get('profile_id')) as $payment) {
                    $this->branchCommissions += $payment->get('commissions');
                }
            }

        }
        return $this->branchCommissions;
    }
}
