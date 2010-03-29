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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * XLite_Module_GiftCertificates_Controller_Customer_AddGiftCertificate 
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Module_GiftCertificates_Controller_Customer_AddGiftCertificate extends XLite_Controller_Customer_Abstract
{	
    public $params = array('target', 'gcid');	
	public $gc = null;


	/**
     * Common method to determine current location 
     * 
     * @return array
     * @access protected 
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return 'Add gift certificate to cart';
    }


    
    function getGC()
    {
        if (is_null($this->gc)) {
            if ($this->get('gcid')) {
                $this->gc = new XLite_Module_GiftCertificates_Model_GiftCertificate($this->get('gcid'));

            } else {

                // set default form values
                $this->gc = new XLite_Module_GiftCertificates_Model_GiftCertificate();

                $this->gc->set("send_via", "E");
                $this->gc->set("border", "no_border");
                $auth = XLite_Model_Auth::getInstance();
                if ($auth->isLogged()) {
                    $profile = $auth->get("profile");
                    $this->gc->set("purchaser", $profile->get("billing_title") . " " . $profile->get("billing_firstname") . " " . $profile->get("billing_lastname"));
                }
                $this->gc->set("recipient_country", $this->config->General->default_country);
            }
        }

        return $this->gc;
    }

	function fillForm()
	{
        $this->set("properties", $this->getGC()->get('properties'));
    }

    function isGCAdded()
    {
        if (is_null($this->getGC()) || !$this->getGC()->isPersistent) {
            return false;
		}

        $items = $this->cart->get("items");
        $found = false;
        for ($i = 0; $i < count($items); $i++) {
            if ($items[$i]->get('gcid') == $this->getGC()->get('gcid')) {
                $found = true;
                break;
            }
        }

        return $found;
    }

	function action_add()
	{
        $this->saveGC();

        $found = false;
		$items = $this->cart->get("items");

		for ($i = 0; $i < count($items); $i++) {
			if ($items[$i]->get('gcid') == $this->getGC()->get('gcid')) {
				$items[$i]->set("GC", $this->getGC());
				$items[$i]->update();
                $found = true;
			}
		}

        if (!$found) {
			$oi = new XLite_Model_OrderItem();
			$oi->set("GC", $this->getGC());
			$this->cart->addItem($oi);
    	}

		if ($this->cart->isPersistent) {
			$this->cart->calcTotals();
			$this->cart->update();
    		$items = $this->cart->get("items");
    		for ($i = 0; $i < count($items); $i++) {
    			if ($items[$i]->get('gcid') == $this->getGC()->get('gcid')) {
    				$this->cart->updateItem($items[$i]);
    			}
    		}
		}

        $this->set("returnUrl", $this->buildURL('cart'));
	}

    function action_select_ecard()
    {
        $this->saveGC();
        $this->set('returnUrl', $this->buildURL('gift_certificate_ecards', '', array('gcid' => $this->getGC()->get('gcid'))));
    }

    function action_delete_ecard()
    {
        $this->saveGC();
		if (!is_null($this->getGC())) {
			$gc = $this->getGC();
            $gc->set("ecard_id", 0);
            $gc->update();
            $this->set("returnUrl", $this->buildURL('add_gift_certificate', '', array('gcid' => $gc->get('gcid'))));
        }
    }

    function action_preview_ecard()
    {
        $this->saveGC();
        $this->set("returnUrl", $this->buildURL('preview_ecard', '', array('gcid' => $this->getGC()->get('gcid'))));
    }

    function saveGC()
    {
        if (isset($this->border)) {
            $this->border = str_replace(array('.', '/'), array('', ''), $this->border);
        }

		if (!is_null($this->getGC())) {
			$gc = $this->getGC();
    		$gc->setProperties(XLite_Core_Request::getInstance()->getData());
    		$gc->set("status", "D");
    		$gc->set("debit", $gc->get("amount"));
    		$gc->set("add_date", time());
			if (!$gc->get("expiration_date")) {
				$month = 30 * 24 * 3600;
				$gc->set("expiration_date", time() + $month * $gc->get('defaultExpirationPeriod'));
			}

        	if ($gc->get('gcid')) {
                $gc->update();

            } else {
                $gc->set('gcid', $gc->generateGC());
				$gc->set("profile_id", $this->xlite->auth->getComplex('profile.profile_id'));
                $gc->create();
            }
        }
    }
    
    function getCountriesStates() {
        $countriesArray = array();

        $country = new XLite_Model_Country();
        $countries = $country->findAll("enabled = '1'");
        foreach($countries as $country) {
            $countriesArray[$country->get("code")]["number"] = 0;
            $countriesArray[$country->get("code")]["data"] = array();

            $state = new XLite_Model_State();
            $states = $state->findAll("country_code = '".$country->get("code")."'");
            if (is_array($states) && count($states) > 0) {
                $countriesArray[$country->get("code")]["number"] = count($states);
                foreach($states as $state) {
                    $countriesArray[$country->get("code")]["data"][$state->get("state_id")] = $state->get("state");
                }
            }
        }

        return $countriesArray;
    }
}

