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

class XLite_Module_DemoMode_Model_Abstract extends XLite_Model_Abstract implements XLite_Base_IDecorator
{
	public function __construct($id = null)
	{
		parent::__construct($id);

		global $safeData;
		if (!isset($safeData)) {
			$safeData = XLite_Model_Session::getInstance()->get('safeData');
			if (is_null($safeData)) {
				$safeData = array();
			}
		}
	}
	
	function _setSessionVar($path, $value)
	{
		global $safeData;
		$ptr = $safeData;
		foreach ($path as $key) {
			if (!isset($ptr[$key])) {
				$ptr[$key] = array();
			}
			$ptr = $ptr[$key];
		}
		$ptr = $value;
		XLite_Model_Session::getInstance()->set('safeData', $safeData);
	}

	function _getSessionVar($path)
	{
		global $safeData;
		$ptr = $safeData;
		foreach ($path as $key) {
			if (!isset($ptr[$key])) {
				return null;
			}
			if (is_scalar($ptr)) {
				return $ptr;
			}
			$ptr = $ptr[$key];
		}	
		return $ptr;
	}

	function _updateProperties(array $properties = array())
	{
		if (!XLite_Model_Session::getInstance()->get("superUser")) {
			$path = array($this->alias, '');
			foreach ($this->primaryKey as $pkey) {
				$path[] = $properties[$pkey];
			}
			foreach ($properties as $name => $value) {
				$path[1] = $name;
				$val = $this->_getSessionVar($path);
				if (isset($val)) {
					$properties[$name] = $val;
				}
			}
		}
		parent::_updateProperties($properties);
	}

	function update()
	{
		if (!XLite_Model_Session::getInstance()->get("superUser")) {
			$path = array($this->alias, '');
			foreach ($this->primaryKey as $pkey) {
				$path[] = $this->properties[$pkey];
			}
			if ($this->alias == "config" ||
				$this->alias == "modules" ||
				$this->alias == "payment_methods" ||
				$this->alias == "profiles" && $this->get("profile_id") == 1) {
				$this->_beforeSave();
				foreach ($this->properties as $key => $value) {
					if ($this->alias == "payment_methods" && ($key != "orderby" ) || $this->alias != "payment_methods") {
						$path[1] = $key;
						$this->_setSessionVar($path, $value);
					}
				}
			} else {
				parent::update();
			}
		} else {
			parent::update();
		}
	}

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
