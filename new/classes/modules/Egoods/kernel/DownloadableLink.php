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

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
*
* @package Module_Egoods
* @access public
*/
class DownloadableLink extends Base
{
	var $alias = "downloadable_links";

	var $primaryKey = array("access_key");
	var $defaultOrder = "file_id";

	var $fields = array(
			"access_key"			=> '',
			"file_id"				=> 0,
			"available_downloads"	=> 9999,
			"exp_time"				=> 0,
			"expire_on"				=> 'T', // T - Time, D - downloads, B - time&downloads
			"link_type"				=> 'M', // M - Manual, A - Automatic
			);

	function create()
	{
		if (is_null($this->get('access_key')) || $this->get('access_key') == '') {
			$this->set('access_key', md5(getmicrotime()));
		}	
		parent::create();
	}

	function printDate($mod1, $mod2, $mod3, $delim = '/')
	{
		return date( "$mod1$delim$mod2$delim$mod3", $this->get('exp_time'));
	}

	function isActive()
	{
		switch ($this->get('expire_on')) {
			case 'T':
				if (time() < $this->get('exp_time')) {
					return true;
				}	
			break;

			case 'D':
				if ($this->get('available_downloads') > 0) {
					return true;
				}
			break;

			case 'B':
				if (time() < $this->get('exp_time') && $this->get('available_downloads') > 0) {
					return true;
				}
			break;	
		}
		return false;
	}

	function getDeniedReason()
	{
		switch ($this->get('expire_on')) {
			case 'T':
				if (time() >= $this->get('exp_time')) {
					return 'T';
				}	
			break;

			case 'D':
				if ($this->get('available_downloads') < 1) {
					return 'D';
				}
			break;

			case 'B':
				if (time() > $this->get('exp_time')) {
					return 'T';
				}
				if ($this->get('available_downloads') < 1) {
					return 'D';
				}
			break;	
		}
		return '';
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
