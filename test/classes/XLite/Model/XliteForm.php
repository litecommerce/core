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
* Class XliteForm provides access to generated form information.
*
* @package Kernel
* @version $Id$
*/
class XLite_Model_XliteForm extends XLite_Model_Abstract
{

    /**
    * @var string $alias The forms database table alias.
    * @access public
    */	
    public $alias = "forms";	

    public $primaryKey = array("form_id", "session_id");

    /**
    * default payment method orider field
    */	
    public $defaultOrder = "date";

    /**
    * @var array $fields The form properties.
    * @access private
    */	
    public $fields = array(
        'form_id'    => '',
        'session_id'  => '',
        'date'       => 0
    );

	function collectGarbage($session_id = null)
	{
		if (!is_null($session_id)) {
			$session_id = addslashes($session_id);
			$where = "session_id='$session_id'";
			$count = $this->count($where);
		} else {
			$count = $this->count();
		}
		$max_count = $this->getMaxFormsPerSession();
		if ($count > $max_count) {
			// don't delete more than 100 at once
			$delete_count = min(100, $count - $max_count);
			$table = $this->getTable();

			$where = (!is_null($session_id))?"WHERE session_id='$session_id'":"";
			$query = "SELECT date FROM $table $where ORDER BY date LIMIT $delete_count, 1";
			$delete_date = $this->db->getOne($query);

			$where = (!is_null($session_id))?"WHERE session_id='$session_id' AND date < '$delete_date'":"WHERE date < '$delete_date'";
			$query = "DELETE FROM $table $where LIMIT $delete_count";
			$this->db->query($query);
		}
	}

	function getMaxFormsPerSession()
	{
		$max_count = $this->xlite->get("options.HTML_Template_Flexy.max_forms_per_session");
		if ($max_count <= 0) $max_count = 100;
		return $max_count;
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
