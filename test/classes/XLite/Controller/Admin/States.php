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
* Class description.
*
* @package Dialog
* @access public
* @version $Id$
*
*/
class XLite_Controller_Admin_States extends XLite_Controller_Admin_Abstract
{
	function init()
	{
		if (!in_array("country_code", $this->params)) {
			$this->params[] = "country_code";
		}
		parent::init();
	}

	function obligatorySetStatus($status)
	{
		if (!in_array("status", $this->params)) {
			$this->params[] = "status";
		}
		$this->set("status", $status);
	}

    function fillForm()
    {
        if (isset(XLite_Core_Request::getInstance()->country_code)) {
            $this->set("country_code", XLite_Core_Request::getInstance()->country_code);
        } else {
            $this->set("country_code", $this->getComplex('config.General.default_country'));
        }
    }
    
    function getStates()
    {
        if (!is_null($this->states)) {
            return $this->states;
        }
        $state = new XLite_Model_State();
        $this->states = $state->findAll("country_code='".$this->get("country_code")."'");
        return $this->states;
    }

    function action_add()
    {

		$fields = array("country_code", "code", "state");
		$postData = XLite_Core_Request::getInstance()->getData();

		foreach ($postData as $k=>$v) {
			if (in_array($k, $fields)) {
				$postData[$k] = trim($v);
			}
		}

		if (empty($postData["country_code"])) {
			$this->set("valid", false);
			$this->obligatorySetStatus("country_code");
			return;
		}

		if (empty($postData["code"])) {
			$this->set("valid", false);
			$this->obligatorySetStatus("code");
			return;
		}

		if (empty($postData["state"])) {
			$this->set("valid", false);
			$this->obligatorySetStatus("state");
			return;
		}

        $state = new XLite_Model_State();
		if ( $state->find("state='".addslashes($postData["state"])."' AND code='".addslashes($postData["code"])."'") ) {
			$this->set("valid", false);
			$this->obligatorySetStatus("exists");
			return;
		}

        $state->set("properties", $postData);
        $state->create();
        $this->obligatorySetStatus("added");
    }

    function action_update()
    {
        $stateData = array();
        if (isset(XLite_Core_Request::getInstance()->state_data)) {
            $stateData = XLite_Core_Request::getInstance()->state_data;
        }
        // use POST'ed data to modify state properties
        foreach ($stateData as $state_id => $state_data) {
            $state = new XLite_Model_State($state_id);
            $state->set("properties", $state_data);
            $state->update();
        }
        $this->obligatorySetStatus("updated");
    }

    function action_delete()
    {
        $states = array();
        if (isset(XLite_Core_Request::getInstance()->delete_states)) {
            $states = XLite_Core_Request::getInstance()->delete_states;
        }
        foreach ($states as $id => $state_id) {
            $state = new XLite_Model_State($state_id);
            $state->delete();
        }    
        $this->obligatorySetStatus("deleted");
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
