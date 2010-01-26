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
* Class represents the shopping cart product.
*
* @package Module_Egoods
* @access public
* @version $Id$
*/
class XLite_Module_Egoods_Model_Product extends XLite_Model_Product implements XLite_Base_IDecorator
{	
	public $egoodsNumber = null;

	public function __construct($p_id = null)
	{
		$this->fields['egood_free_for_memberships'] = '';
		parent::__construct($p_id);
	}
	
	function getEgoodsNumber()
	{
		if (!isset($this->egoodsNumber)) {
			$this->getEgoods();
		}

		return $this->egoodsNumber;
	}

	function getEgoods()
	{
		if (!isset($this->egoods)) {
			$df = new XLite_Module_Egoods_Model_DownloadableFile();
			$this->egoods = $df->findAll('product_id=' . $this->get('product_id'));
			$this->egoodsNumber = (is_array($this->egoods)) ? count($this->egoods) : 0;
		}
		return $this->egoods;
	}

	function getLinkDeliveryFiles()
	{
		$files = array();
		if (!$this->is('egood')) {
			return (object)$files;
		}	
		$egoods = $this->get('egoods');
		for ($i = 0; $i < count($egoods); $i ++) {
			if ($egoods[$i]->get('delivery') == 'L') {
				$file = array();
				$file['name'] = basename($egoods[$i]->get('data'));
				$links = $egoods[$i]->get('activeLinks');
				foreach($links as $key=>$link) {
					$file['links'][] = $this->xlite->shopUrl('cart.php?target=download&action=download&acc=') . $link->get('access_key');
				}
				
				$files []= $file;
			}	
		}
		return (object)$files;
	}

	function getValidLinkDeliveryFiles()
	{
		$files = $this->get('linkDeliveryFiles');
		$valid = array();
		foreach ($files as $key=>$file) {
			if (count($file['links']) > 0) {
				$valid []= $file;
			}	
		}
		return $valid;
	}

	function hasValidLinks()
	{
		$valid = $this->get('validLinkDeliveryFiles');
		return (empty($valid)) ? false : true;
	}

	function getMailDeliveryFiles()
	{
		$files = array();
		$egoods = $this->get('egoods');
		for ($i = 0; $i < count($egoods); $i ++) {
			if ($egoods[$i]->get('delivery') == 'M') {
				$files [] = $egoods[$i];
			}	
		}
		return $files;
	}

	function isEgood()
	{
		if (count($this->get('egoods')) == 0) {
			return false;
		} 
		return true;
	}

	function isPin()
	{
		if ($this->get('pin_type') != '' && $this->get('pin_type') != 'N') {
			return true;
		}
		return false;
	}

	function getPin_type()
	{
		return $this->getComplex('pinSettings.pin_type');
	}

	function isFreeForMembership($membership)
	{
		if (($membership == '') || (!$this->is('egood'))) {
			return false;
		}	
		$free_for_memberships = split(',', $this->get('egood_free_for_memberships'));
		return in_array($membership, $free_for_memberships);
	}

	function getPinSettings()
	{
		if (!isset($this->pin_settings)) {
			$this->pin_settings = new XLite_Module_Egoods_Model_PinSettings();
			if (!$this->pin_settings->find('product_id=' . $this->get('product_id'))) {
				$this->pin_settings->set('product_id', $this->get('product_id'));
			}
		}	
		return $this->pin_settings;
	}

	function createLink($file_id)
	{
		$dl = new XLite_Module_Egoods_Model_DownloadableLink(md5(microtime()));
		$dl->set('file_id', $file_id);
		$dl->set('exp_time', mktime(0, 0, 0, 
				date("n", time()), 
				date("j", time()) + $this->getComplex('xlite.config.Egoods.exp_days'), 
				date("Y", time())
		));
		
		$dl->set('available_downloads', $this->getComplex('xlite.config.Egoods.exp_downloads'));
		$dl->set('expire_on', $this->getComplex('xlite.config.Egoods.link_expires'));
		$dl->set('link_type', 'A');
		$dl->create();
		return $dl->get('access_key');
	}

	function createLinks()
	{
		$acc = array();
		$df = new XLite_Module_Egoods_Model_DownloadableFile();
		$files = $df->findAll("product_id=" . $this->get('product_id'));
		for ($i = 0; $i < count($files); $i++) {
			if ($files[$i]->get('delivery') == 'L') {
				$acc []= $this->createLink($files[$i]->get('file_id'));
			}	
		}
		return $acc;
	}

	function filter()
	{
		if ($this->xlite->is('adminZone')) {
			return parent::filter();
		}	
		$pin = new XLite_Module_Egoods_Model_PinCode();
		$avail_amount = $pin->getFreePinCount($this->get('product_id'));
		if ($this->is('pin') && $avail_amount < 1) {
			return false;
		}
		return parent::filter();
	}
} 

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
