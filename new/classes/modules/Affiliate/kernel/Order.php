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

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* @package Module_Affiliate
* @version $Id$
*/
class Module_Affiliate_Order extends Order
{
    function constructor($id = null) // {{{
    {
        $this->fields["partnerClick"] = 0;
        parent::constructor($id);
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
            $pp = func_new("PartnerPayment");
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
            if ($this->session->isRegistered("PartnerClick")) {
                $partnerClick = $this->session->get("PartnerClick");
            } elseif (isset($_COOKIE["PartnerClick"])) {
                $partnerClick = $_COOKIE["PartnerClick"];
            }
            // update order with partner click ID
            if ($partnerClick) {
                $stat = func_new("BannerStats", $partnerClick);
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
            $stat = func_new("BannerStats", $this->get("partnerClick"));
            $partner = $stat->get("partner");
            if (!is_null($partner)) {
                $this->set("partner", $partner);
                $pp = func_new("PartnerPayment");
                $pp->charge($this);
            }
        }
    } // }}}

    function delete() // {{{
    {
        parent::delete();
        $pp = func_new("PartnerPayment");
        $payments = $pp->findAll("order_id=".$this->get("order_id"));
        foreach ($payments as $p) {
            $p->delete();
        }
    } // }}}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
