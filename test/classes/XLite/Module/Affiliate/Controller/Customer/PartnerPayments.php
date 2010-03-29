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

/**
* @package Module_Affiliate
* @access public
* @version $Id$
*/
class XLite_Module_Affiliate_Controller_Customer_PartnerPayments extends XLite_Module_Affiliate_Controller_Partner
{	
    public $totalPaid = 0.00;


	/**
     * Common method to determine current location 
     * 
     * @return array
     * @access protected 
     * @since  3.0.0 
     */ 
    protected function getLocation()
    {
        return 'Payments history';
    }


    function getPayments()
    {
        if (!$this->auth->isAuthorized($this)) {
        	return null;
        }

        if (is_null($this->payments)) {
            $this->payments = array();
            $pp = new XLite_Module_Affiliate_Model_PartnerPayment();
            $table = $pp->db->getTableByAlias($pp->alias);
            $partnerID = $this->getComplex('auth.profile.profile_id');
            if ($this->get("period") == "period") {
                $startDate = $this->get("startDate");
                $endDate = $this->get("endDate") + 24 * 3600;
                $date = " AND paid_date>=$startDate AND paid_date<=$endDate ";
            }
            $sql = "SELECT sum(commissions) AS amount, paid_date   ".
                   "FROM $table ".
                   "WHERE partner_id=$partnerID AND paid=1 ".
                   $date .
                   "GROUP BY paid_date";
            $this->payments = $pp->db->getAll($sql);                   
            $sql = "SELECT sum(commissions) AS total ".
                   "FROM $table ".
                   "WHERE partner_id=$partnerID AND paid=1 ".
                   $date;
            $total = $pp->db->getAll($sql);
            $this->totalPaid = $total[0]["total"];
        }
        return $this->payments;
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
