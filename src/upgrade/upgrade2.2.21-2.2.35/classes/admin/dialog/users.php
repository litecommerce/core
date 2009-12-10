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
* @version $Id: users.php,v 1.3 2007/05/21 11:53:27 osipov Exp $
*
*/
class Admin_Dialog_users extends Admin_Dialog
{
    var $params = array('target', 'mode', 'substring', 'user_type', 'membership');

    function init()
    {
    	parent::init();

        if ($this->get("mode") == "orders") {
            $this->search_orders();
            $this->redirect();
            exit;
        }
    }

    function getSearchQuery($field_values,$keywords,$logic) // {{{ 
    {
        $search = array();
        foreach($field_values as $field_value => $condition) {
            if ($condition) { 
                $query = array();
                foreach ($keywords as $keyword)
                    $query[] = "$field_value LIKE '%".addslashes($keyword)."%'";
                $search[] = (count($keywords) > 1 ? "(" . implode(" $logic ", $query) . ")" :  implode("", $query));
            }  
        }
        $search_query = implode(" OR ",$search);
        return $search_query;
    } // }}} 
	

    function &getUsers()
    {
        if ($this->get("mode") == "") {
            return array();
        }
        if ($this->get("mode") == "orders") {
            return array();
        }

        if (is_null($this->users)) {
            $where = array();
            // build WHERE condition for profile info
            if (!is_null($this->get("substring"))) {
				$substring = stripslashes($this->get("substring"));
        		$keywords = explode(" ",trim($substring));
				$field_values = array(	"login" 				=> true, 
										"billing_firstname" 	=> true,
										"billing_lastname"		=> true,
                                        "shipping_firstname" 	=> true,
                                        "shipping_lastname"  	=> true);
				$where[] = "(".$this->getSearchQuery($field_values, $keywords, "OR").")";
			}
            if ($this->membership == "%") { // default is ALL
                $where[] = "membership LIKE '%'";
            } elseif ($this->membership == "") { // NO membership set
                $where[] = "membership=''";
            } elseif ($this->membership == "pending_membership") { // pending
                $where[] = "(pending_membership<>membership AND LENGTH(pending_membership) > 0)";
            } else { // search for the specified members otherwise
                $where[] = "membership='$this->membership'";
            }
            // build WHERE condition for usertype
            $access_level = $this->auth->getAccessLevel($this->user_type);
            if (!is_null($access_level)) {
                $where[] = "access_level=$access_level";
            } elseif (is_null($access_level) && $this->user_type != "all") {
                $where[] = "access_level=-1";
            }
            $profile =& func_new("Profile");
			$profile->fetchKeysOnly = true;
			$profile->fetchObjIdxOnly = true;
            $this->users = $profile->findAll($this->_buildWhere($where), "login");
        }
        return $this->users;
    }

    function _buildWhere($where)
    {
        return join(' AND ',$where);
    }

    function getCount()
    {
        return count($this->get("users"));
    }

    function search_orders()
    {
        $profile =& func_new("Profile", $this->profile_id);
        $profile->read();
		$login = $profile->get("login");
        if (strlen($login) > 0) {
            $login = urlencode($login);
            $year = $this->config->get("Company.start_year");
            $date = getdate(time());
        	$this->set("returnUrl", "admin.php?target=order_list&mode=search&login=$login&startDateDay=1&startDateMonth=1&startDateYear=$year&endDateDay=$date[mday]&endDateMonth=$date[mon]&endDateYear=$date[year]");
        } else {
        	$this->set("returnUrl", $this->backUrl);
        }
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
