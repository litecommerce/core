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

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_Affiliate_Model_Order extends XLite_Model_Order implements XLite_Base_IDecorator
{
    public function __construct($id = null) // {{{
    {
        $this->fields["partnerClick"] = 0;
        parent::__construct($id);
    } // }}}
    
    /**
    * handles partner click, if present
    */
    function queued() // {{{
    {
        parent::queued();
        $this->chargePartnerCommissions();
    } // }}}

	function Affiliate_processed() // {{{
	{
		if ($this->_oldStatus != 'Q') {
            $this->chargePartnerCommissions();
        }
        if ($this->get("partnerClick") != 0) {
            $pp = new XLite_Module_Affiliate_Model_PartnerPayment();
            if ($pp->find("order_id=".$this->get("order_id")." AND affiliate=0")) {
                // found commission payment for this order, notify partner
                $pp->notifyPartner();
            }
        }
	} // }}}
	
    function processed() // {{{
    {
        parent::processed();
		$this->Affiliate_processed();
    } // }}}

    function storePartnerClick()
    {
        if ($this->get("partnerClick") == 0) { // first time order's placed 

			$partnerClick = false;

            if ($this->session->isRegistered("PartnerClick")) {
                $partnerClick = $this->session->get("PartnerClick");
            } elseif (isset($_COOKIE["PartnerClick"])) {
                $partnerClick = $_COOKIE["PartnerClick"];
            }
            // update order with partner click ID
            if ($partnerClick) {
                $stat = new XLite_Module_Affiliate_Model_BannerStats($partnerClick);
                $partner = $stat->get("partner");
                if (!is_null($stat->get("partner"))) {
                    $this->set("partnerClick", $partnerClick);
                }
            }
        }
    }

    function statusChanged($oldStatus, $newStatus)
    {
    	parent::statusChanged($oldStatus, $newStatus);

        if ($oldStatus == 'T' && $newStatus == 'I') {
            $this->storePartnerClick();
        }
    }

    function chargePartnerCommissions() // {{{
    {
		$this->storePartnerClick();

        if ($this->get("partnerClick") != 0) { // click found for this order
            // charge and save partner's commissions
            $stat = new XLite_Module_Affiliate_Model_BannerStats($this->get("partnerClick"));
            $partner = $stat->get("partner");
            if (!is_null($partner)) {
                $this->set("partner", $partner);
                $pp = new XLite_Module_Affiliate_Model_PartnerPayment();
                $pp->charge($this);
            }
        }
    } // }}}

    function delete() // {{{
    {
        parent::delete();
        $pp = new XLite_Module_Affiliate_Model_PartnerPayment();
        $payments = $pp->findAll("order_id=".$this->get("order_id"));
        foreach ($payments as $p) {
            $p->delete();
        }
    } // }}}
}
