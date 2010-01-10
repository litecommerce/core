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
* @package Module_Egoods
* @access public
* @version $Id$
*/
class XLite_Module_Egoods_Model_OrderItem extends XLite_Model_OrderItem implements XLite_Base_IDecorator
{
	public function __construct()
	{
        $this->fields["pincodes"] = "";
		$this->fields["egoods"] = "";
        parent::__construct();
	}
	
	function isEgood()
	{
		return $this->is('product.egood');
	}

	function isPin()
	{
		return $this->is('product.pin');
	}

	function getPinCodes()
	{
		if(!isset($this->pin_codes)) {
			$this->pin_codes = explode(",", $this->get('pincodes'));
		}
		return $this->pin_codes;
	}

	function createPins()
	{
		require_once LC_MODULES_DIR . 'Egoods' . LC_DS . 'encoded.php';
		$pins = func_moduleEgoods_getPinCodes($this);
		if (is_array($pins)) {
			$this->set('pincodes', implode(',', $pins));
		} else {
			$this->set('pincodes', "");
		}	
		$this->update();
	}

	function updateAmount($amount)
	{
		$amount = (int)$amount;

		if ($this->is('egood') && $amount > 0) {
		    if ($this->is('pin')) {
			    $pin = new XLite_Module_Egoods_Model_PinCode();
    			if ($amount > $pin->getFreePinCount($this->get('product.product_id'))) {
	    			$amount = $pin->getFreePinCount($this->get('product.product_id'));
		    		if ($amount <= 0) {
			    		$amount = 1;
				    }
    			}
		    } else {
                $amount = 1;
            }
        }    

		parent::updateAmount($amount);
	}

	function isShipped()
	{
		if ($this->is('pin') || $this->is('egood')) {
			return false;
		}
		return parent::isShipped();
	}

	function storeLinks()
	{
		$product = $this->get('product');
		$links = $product->createLinks();
		$this->set('egoods', implode(',', $links));
		$this->update();
	}

	function unStoreLinks()
	{
        $ids = explode(",", $this->get("egoods"));
        $link = new XLite_Module_Egoods_Model_DownloadableLink();
        foreach ($ids as $link_id) {
            $egoods_links = $link->findAll("access_key='$link_id'");
            foreach ($egoods_links as $egoods_link) {
                $egoods_link->delete();
            }
        }
        $this->set("egoods", "");
        $this->update();
	}

	function hasValidLinks()
	{
		return ($this->get('egoods') == '') ? false : true;
	}

	function getEgoods()
	{
		if (!isset($this->_egoods)) {
			$egoods_links = explode(',', $this->get('egoods'));
			foreach($egoods_links as $link_id) {
				$link = new XLite_Module_Egoods_Model_DownloadableLink($link_id);
				$file = new XLite_Module_Egoods_Model_DownloadableFile($link->get('file_id'));
				$record = array();
				$record['name'] = basename($file->get('data'));
				$record['link'] = $this->xlite->shopURL("cart.php?target=download&action=download&acc=") . $link_id;
				$record['expires'] = $link->get('expire_on');
				$record['exp_time'] = $this->get('xlite.config.Egoods.exp_days');
				$record['downloads'] = $link->get('available_downloads');
				$record['delivery'] = $file->get('delivery');
				$this->_egoods []= $record;
			}
		}
		return $this->_egoods;
	}

	function hasValidPins()
	{
		if ($this->get('pincodes') != '') {
			return true;
		}
		return false;
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
