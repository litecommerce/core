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
class DownloadableFile extends Base
{
	var $alias = "downloadable_files";
	var $autoIncrement = "file_id";

	var $primaryKey = array("file_id");
	var $defaultOrder = "file_id";

	/**
	 * @var array $fields downloadable files properties.
	 * @access private
	 */
	var $fields = array(
			"file_id"				=> 0,
			"product_id"			=> 0,
			"store_type"			=> 'F',
			"delivery"				=> 'L', //L - link, M - mail
			"data"					=> '',
			);
			
	function &getLinks()
	{
		$links =& func_new('DownloadableLink');
		return $links->findAll('file_id=' . $this->get('file_id'));
	}

	function &getManualLinks()
	{
		$links =& func_new('DownloadableLink');
		if (!isset($this->_manual_links)) {
			$this->_manual_links = $links->findAll('file_id=' . $this->get('file_id') . " and link_type='M'");
		}	
		return $this->_manual_links;
	}
	
	function hasManualLinks()
	{
		$ml = $this->get('manualLinks');
		if (count($ml) > 0) {
			return true;
		}
		return false;
	}
	
	function &getActiveLinks()
	{
		$l =& func_new('DownloadableLink');
		$links =& $l->findAll('file_id=' . $this->get('file_id'));
		$active_links = array();
		for ($i = 0; $i < count($links); $i ++) {
			if ($links[$i]->is('Active')) {
				$active_links []=& $links[$i];
			}	
		}
		return $active_links;
	}

	function getFileName()
	{
		return basename($this->get('data'));
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
