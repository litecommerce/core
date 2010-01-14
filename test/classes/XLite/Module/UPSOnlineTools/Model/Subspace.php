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
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Represent sub-space class
*
* @package Module_UPSOnlineTools
* @access public
* @version $Id$
*/
class XLite_Module_UPSOnlineTools_Model_Subspace extends XLite_Base
{	
	public $width, $length;	
	public $left, $top;	
	public $upper_limit;

	function init($_width, $_length, $_left=0, $_top=0)
	{
		$this->width = $_width;
		$this->length = $_length;
		$this->left = $_left;
		$this->top = $_top;
	}

	function getSquare()
	{
		return $this->width * $this->length;
	}

	function getEpsilon()
	{
		return ($this->width > $this->length) ? $this->length / $this->width : $this->width / $this->length;
	}

	function isNull()
	{
		return ($this->width == 0 || $this->length == 0) ? true : false;
	}

	function isPlaceable($_width, $_length)
	{
		if ($_width <= $this->width && $_length <= $this->length)
			return true;

		return false;
	}

	function placeBox($_width, $_length)
	{
		require_once LC_MODULES_DIR . 'UPSOnlineTools' . LC_DS . 'encoded.php';
		return UPSOnlineTools_placeBox($this, $_width, $_length);
	}

	function getUpperLimit()
	{
		return $this->upper_limit;
	}

	function setUpperLimit($_lim)
	{
		$this->upper_limit = $_lim;
	}

	function export()
	{
		$vars = array();
		$vars["left"] = $this->left;
		$vars["top"] = $this->top;
		$vars["width"] = $this->width;
		$vars["length"] = $this->length;

		return $vars;
	}

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
