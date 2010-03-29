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
* Module_Promotion_Dialog_checkout description.
*
* @package Module_Promotion
* @access public
* @version $Id$
*/
class XLite_Module_Promotion_Controller_Customer_Checkout extends XLite_Controller_Customer_Checkout implements XLite_Base_IDecorator
{
	/**
     * Common method to determine current location 
     * 
     * @return array
     * @access protected 
     * @since  3.0.0
     */
    protected function getLocation()
    {
        $location = parent::getLocation();

        switch ($this->get('mode')) {
            case 'bonusList':
                $location = 'Bonus list';
                break;
            case 'couponFailed':
                $location = 'Discount coupon failure';
                break;
        }
        
        return $location;
    }

	/**
     * Initialize controller 
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function init()
    {
        parent::init();

		// TODO - check if there is a more convenient way to do this 
		if (self::CHECKOUT_MODE_ZERO_TOTAL == XLite_Core_Request::getInstance()->mode) {
			$this->set('skipValidateDiscountCoupon', true);
		}
	}

	function _handleCouponFailed()
    {
        if ($this->session->isRegistered("couponFailed")) {
        	if (isset($_REQUEST["mode"]) && $_REQUEST["mode"] == "couponFailed") {
				$dc = new XLite_Module_Promotion_Model_DiscountCoupon();
				$found = $dc->find("coupon='".$this->session->get("couponFailed")."'");
				if ($found) {
					$this->set("discountCoupon", $dc);
				}
        	} else {
        		$this->session->set("couponFailed", null);
        	}
        }
    }
    
	function handleRequest()
    {
        if ($this->cart->validateDiscountCoupon() == 'used' && !$this->get("skipValidateDiscountCoupon") && (!isset($_REQUEST["action"]) || $_REQUEST["action"] != "return")) {
            //$this->session->set("couponFailed", $this->cart->get("DC"));
			$dc = $this->cart->get("DC");
        	$this->session->set("couponFailed", $dc->get("coupon"));
            $this->cart->set("DC", null); // remove coupon
            $this->updateCart();
            $this->redirect("cart.php?target=checkout&mode=couponFailed");
            return;
        }
        if (!isset($_REQUEST["action"]) && !isset($_REQUEST["mode"])) {
            if (!$this->session->isRegistered("bonusListDisplayed") && $this->config->getComplex('Promotion.showBonusList')) {
                if ($this->cart->getBonusList()) {
                	$needRedirect = false;
                    $bonusList = $this->cart->get("bonusList");
                	foreach ($bonusList as $bonus) {
                		$products = $bonus->get("allBonusProducts");
                		if (is_array($products) && count($products) > 0) {
                			$needRedirect = true;
                			break;
                		}
                		$categories = $bonus->get("allBonusCategories");
                		if (is_array($categories) && count($categories) > 0) {
                			$needRedirect = true;
                			break;
                		}
                		if ($bonus->get("bonusType") == "bonusPoints") {
                			$needRedirect = true;
                			break;
                		}
                	}
                	if ($needRedirect) {
                        $this->redirect("cart.php?target=checkout&mode=bonusList");
                        return;
                    }
                }
            }//  else we have already shown the bonus list dialog
        }

        $this->_handleCouponFailed();

        parent::handleRequest();
    }
	
	function getBonusList()
	{
		// collect products & prices
		$this->bonusList = $this->cart->getBonusList();
        $this->session->set("bonusListDisplayed", 1);
        return $this->bonusList;
	}

	/**
	* Format number as interger
	*/

	function integer($num)
	{
		return (double) ($num);
	}

	function isSecure()
    {
    	switch($this->mode) {
    		case "couponFailed":
    		return $this->isHTTPS();
    	}
    	return parent::isSecure();
    }

    function isShowBonus(&$bonus)
    {
        return ((bool) $bonus->get('allBonusProducts')
            || (bool) $bonus->get('allBonusCategories')
            || (bool) $bonus->get('bonusAllProducts')
            || $bonus->get("bonusType") == "bonusPoints"
        );
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
