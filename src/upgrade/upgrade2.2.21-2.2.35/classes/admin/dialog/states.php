<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2007 Creative Development <info@creativedevelopment.biz>  |
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
* @version $Id: states.php,v 1.3 2007/05/21 11:53:27 osipov Exp $
*
*/
class Admin_Dialog_states extends Admin_Dialog
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
        if (isset($_REQUEST["country_code"])) {
            $this->set("country_code", $_REQUEST["country_code"]);
        } else {
            $this->set("country_code", $this->get("config.General.default_country"));
        }
    }
    
    function &getStates()
    {
        if (!is_null($this->states)) {
            return $this->states;
        }
        $state =& func_new("State");
        $this->states =& $state->findAll("country_code='".$this->get("country_code")."'");
        return $this->states;
    }

    function action_add()
    {
		if ( empty($_POST["country_code"]) ) {
			$this->set("valid", false);
			$this->obligatorySetStatus("country_code");
			return;
		}

		if ( empty($_POST["code"]) ) {
			$this->set("valid", false);
			$this->obligatorySetStatus("code");
			return;
		}

		if ( empty($_POST["state"]) ) {
			$this->set("valid", false);
			$this->obligatorySetStatus("state");
			return;
		}

        $state =& func_new("State");
		if ( $state->find("state='".$_POST["state"]."' AND code='".$_POST["code"]."'") ) {
			$this->set("valid", false);
			$this->obligatorySetStatus("exists");
			return;
		}

        $state->set("properties", $_POST);
        $state->create();
        $this->obligatorySetStatus("added");
    }

    function action_update()
    {
        $stateData = array();
        if (isset($_POST["state_data"])) {
            $stateData = $_POST["state_data"];
        }
        // use POST'ed data to modify state properties
        foreach ($stateData as $state_id => $state_data) {
            $state =& func_new("State", $state_id);
            $state->set("properties", $state_data);
            $state->update();
        }
        $this->obligatorySetStatus("updated");
    }

    function action_delete()
    {
        $states = array();
        if (isset($_POST["delete_states"])) {
            $states = $_POST["delete_states"];
        }
        foreach ($states as $id => $state_id) {
            $state =& func_new("State", $state_id);
            $state->delete();
        }    
        $this->obligatorySetStatus("deleted");
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
