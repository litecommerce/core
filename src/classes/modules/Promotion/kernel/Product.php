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
| The Initial Developer of the Original Code is Creative Development LCC       |
| Portions created by Creative Development LCC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* Class description.
*
* @package Module_Promotion
* @version $Id: Product.php,v 1.3 2008/10/23 11:59:32 sheriff Exp $
*/
class Module_Promotion_Product extends Product
{
    function delete()
    {
		$so =& func_new("SpecialOffer");
		$soDeletedProducts =& $so->findAll("product_id='" . $this->get("product_id") . "'");
		if (is_array($soDeletedProducts) && count($soDeletedProducts) > 0) {
			foreach($soDeletedProducts as $sodp) {
				$sodp->markInvalid();
			}
		}

		$bp =& func_new("BonusPrice");
		$bpDeletedProducts =& $bp->findAll("product_id='" . $this->get("product_id") . "'");
		if (is_array($bpDeletedProducts) && count($bpDeletedProducts) > 0) {
			foreach($bpDeletedProducts as $bpdp) {
				$sodp =& func_new("SpecialOffer", $bpdp->get("offer_id"));
				$sodp->markInvalid();
			}
		}

		$bp =& func_new("SpecialOfferProduct");
		$bpDeletedProducts =& $bp->findAll("product_id='" . $this->get("product_id") . "'");
		if (is_array($bpDeletedProducts) && count($bpDeletedProducts) > 0) {
			foreach($bpDeletedProducts as $bpdp) {
				$sodp =& func_new("SpecialOffer", $bpdp->get("offer_id"));
				$sodp->markInvalid();
			}
		}

        // delete product
        parent::delete();
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
