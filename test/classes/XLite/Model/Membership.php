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
* Class represents memembership.
*
* @package kernel
* @access public
* @version $Id$
*/
class XLite_Model_Membership extends XLite_Model_Abstract
{
    var $fields = array
    (
    	"membership_id" => 0,
        "membership"	=> "",
        "orderby"		=> ""
    );
	var $isRead         = true;		
	var $memberships    = null;		
    var $countRequested = null;
    var $countGranted   = null;

	function getMemberships() // {{{
	{
        if (!is_null($this->memberships)) {
			return $this->memberships;
		}

        $config = new XLite_Model_Config();

		$config->find("name = 'membershipsCollection' AND category = 'Memberships'");
		$this->memberships = $memberships = unserialize($config->get("value"));

		$config->find("name = 'memberships' AND category = 'Memberships'");
		$oldMemberships = unserialize($config->get("value"));
		if (!is_array($memberships)) {
			if (is_array($oldMemberships) && count($oldMemberships) > 0) {
				$id = 1;
				$new_memberships = array();
				foreach($oldMemberships as $membership) {
					if (!is_array($membership)) {
    					$new_memberships[$id] = array
    					(
    						"orderby" => ($id * 10),
    						"membership" => $membership,
    						"membership_id" => $id
    					);
    				} else {
    					$new_memberships[$id] = $membership;
    				}
					$id ++;
				}
				$this->memberships = $new_memberships;

                $config = new XLite_Model_Config();
        		$config->createOption("Memberships","membershipsCollection", serialize($this->memberships), "serialized");
			} else {
				$this->memberships = array();

                $config = new XLite_Model_Config();
        		$config->createOption("Memberships","membershipsCollection", serialize($this->memberships), "serialized");
			}
		}
		
		return $this->memberships;
	} // }}} 
	
	public function __construct() // {{{
	{
		parent::__construct();
		$memberships = $this->get("memberships");
		$args = func_get_args();
		if (count($args)) {
			if (!is_null($args[0])) {
				$this->set("properties", $memberships[$args[0]]);
			}
		}
	} // }}}

	function create() // {{{
	{
		$max = 1; 
        $memberships = $this->get("memberships");
		if ($memberships) {
			foreach($memberships as $membership) {
				if ($membership['membership_id'] >= $max) {
					$max = $membership['membership_id'] + 1;
				}
			}
		}
		$this->set("membership_id",$max); 
        $membershipData = $this->get("properties");
		$membershipData['membership'] = $this->stripInvalidData($membershipData['membership']);
        if (strlen($membershipData["membership"]) > 32) {
        	$membershipData["membership"] = substr($membershipData["membership"], 0, 32);
        }
		$memberships[$max] = $membershipData;
		$memberships = (array) $this->sortMemberships($memberships);
        $config = new XLite_Model_Config();
		$config->createOption("Memberships","membershipsCollection", serialize($memberships));    
	} // }}}

	function update() // {{{
	{
	    $config = new XLite_Model_Config();
        $memberships = $this->get("memberships");
        $membershipData = $this->get("properties");
		$membershipData['membership'] = $this->stripInvalidData($membershipData['membership']);
        if (strlen($membershipData["membership"]) > 32) {
        	$membershipData["membership"] = substr($membershipData["membership"], 0, 32);
        }
		$memberships[$this->get("membership_id")] = $membershipData;
		$memberships = (array) $this->sortMemberships($memberships);
		$config->createOption("Memberships","membershipsCollection", serialize($memberships));
    } // }}}

	function delete() // {{{
	{
		$config = new XLite_Model_Config();
        $memberships = $this->get("memberships");
		unset($memberships[$this->get("membership_id")]);
		$memberships = (array) $this->sortMemberships($memberships);
		$config->createOption("Memberships","membershipsCollection", serialize($memberships));    
	} // }}}

 	function findAll($where = null, $orderby = null, $groupby = null, $limit = null) // {{{
	{
		$result = array();
		$memberships = (array) $this->sortMemberships();
		foreach($memberships as $membership_id => $membership_) {
			$membership = new XLite_Model_Membership($membership_id);
			$result[$membership_id] = $membership;	
		}
		return $result;
	} // }}}	

	function sortMemberships($memberships = null)
	{
		if (!is_array($memberships)) $memberships = (array) $this->get("memberships");

		uasort($memberships, array($this, "cmp"));
		return $memberships;
	}

	function cmp($a, $b) // {{{
	{
		if  ($a['orderby'] == $b['orderby']) return 0;
		return ($a['orderby'] > $b['orderby']) ? 1 : -1;
	} // }}}

    function getRequestedCount() // {{{
    {
        $count = 0;
        if (is_null($this->countRequested)) {
            $prop = $this->get("properties");
            if (is_array($prop)) {
                $profile = new XLite_Model_Profile();
                $where = $profile->_buildWhere("(pending_membership<>membership AND pending_membership='".$prop['membership']."')");
                $count = $profile->count($where);
            }
            $this->countRequested = $count;
        } else {
            $count = $this->countRequested;
        }

        return $count;
    } // }}}

    function getGrantedCount() // {{{
    {
        $count = 0;
        if (is_null($this->countGranted)) {
            $prop  = $this->get("properties");
            if (is_array($prop)) {
                $profile = new XLite_Model_Profile();
                $where = $profile->_buildWhere("membership='".$prop['membership']."'");
                $count = $profile->count($where);
            }
            $this->countGranted = $count;
        } else {
            $count = $this->countGranted;
        }

        return $count;
    } // }}}

	/*
	 * remove from the $value characters, that are slashed by mysql_real_escape_string()
	 * they are: x00, \n, \r, \, ', " and \x1a
	 */
	function stripInvalidData($value) 
	{
		$expression = $this->getStripRegExp();
		$value = preg_replace("/$expression/", " ", $value);
		$value = trim($value);
		return $value;
	}

	function getStripRegExp()
	{
		$expression = "[\\x00\\n\\r\\\\'\"\\x1a]+";
		return $expression;
	}

} // }}}

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
