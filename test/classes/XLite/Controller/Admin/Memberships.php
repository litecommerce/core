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
* Handles membership create/update/delete.
*
* @package Dialog
* @access public
* @version $Id$
*
*/

class XLite_Controller_Admin_Memberships extends XLite_Controller_Admin_Abstract
{	
	public $params = array("target", "mode");
	
    function action_update() // {{{
    {
	    if ($this->get("update_memberships")) {
            $profilesData = array(
                'profiles'   => array(),
                'membership' => array()
            );
            $membership = new XLite_Model_Membership();
            $memberships = $membership->findAll();
            foreach($memberships as $id => $membership_) {
                $profile = new XLite_Model_Profile();
                $profilesData['profiles'][$id] = $this->getMembershipProfiles($membership_->get("membership"));
                $profilesData['membership'][$id] = $membership_->get("membership");
            }
            $memberships = $this->get("update_memberships");
            foreach($memberships as $id => $membership_) {
                $membership = new XLite_Model_Membership($id);
				$membership_['membership'] = $membership->stripInvalidData($membership_['membership']);
				if (strlen($membership_['membership']) <= 0) {
					if (strlen($membership->get("membership")) <= 0) {
						// delete old empty membership
						$this->updateProfilesMembership($this->getMembershipProfiles($membership->get("membership")), $membership->get("membership"), '', true);
						$membership->delete();
					}
					// don't save this membership
					continue;
				}
				if (strlen($membership_['membership']) > 32) {
					$membership_['membership'] = substr($membership_['membership'], 0, 32);
				}
                $membership->set("properties", $membership_);
                $membership->update();
                if (isset($profilesData['profiles'][$id])) {
                    $this->updateProfilesMembership($profilesData['profiles'][$id], $profilesData['membership'][$id], $membership->get("membership"));
                }
            }
        }
    } // }}} 

    function action_delete() // {{{
    {
		if ($this->get("deleted_memberships")) {
			@set_time_limit(0);
            $memberships = $this->get("deleted_memberships");
			foreach($memberships as $membership_id) {
				$membership = new XLite_Model_Membership($membership_id);
			    $m = $membership->get('membership');
                $this->updateProfilesMembership($this->getMembershipProfiles($m), $m, '', true);
                $membership->delete();
            }
		}
    } // }}}

	function action_add() // {{{
	{
		if ($this->get("new_membership")) {
			$new_membership = $this->get("new_membership");
			$membership = new XLite_Model_Membership();

			$new_membership['membership'] = $membership->stripInvalidData($new_membership['membership']);
			if (strlen($new_membership['membership']) <= 0) {
				// don't save this membership
				return;
			}

			$membership->set("properties",$new_membership);
			if (strlen($membership->get("orderby")) == 0) {
				$newPos = 0;
    			$memberships = $membership->findAll();
    			foreach($memberships as $id => $membership_) {
    				if ($membership_->get("orderby") > $newPos) {
    					$newPos = $membership_->get("orderby");
    				}
    			}
    			$newPos += 10;
    			$newPos = floor($newPos/10)*10;
    			$membership->set("orderby", $newPos);
			}
			$membership->create();
			$this->set("actionProcessed", true);
		}	
	} // }}}
	
	function getMemberships() // {{{
	{
		$membership = new XLite_Model_Membership();
		return $membership->findAll();
	} // }}}

    function getMembershipProfiles($membership) // {{{
    {
        $profile = new XLite_Model_Profile();
        return $profile->findAll("membership = '".$membership."' OR pending_membership='".$membership."'");
    } // }}}

    /**
    * Update all profiles 
    */
    function updateProfilesMembership(&$profiles, $old, $new, $sendNotification=false) // {{{
    {
        if (!is_array($profiles) || count($profiles) === 0 || $old === $new) {
            return;
        }
        foreach($profiles as $profile) {
            if (strcmp($profile->get('membership'), $old) === 0) {
                $profile->set('membership', $new);
            }
            if (strcmp($profile->get('pending_membership'), $old) === 0) {
                $profile->set('pending_membership', $new);
            }
            
            if ($sendNotification) {
                $this->auth->modifyProfile($profile);
            } else {
                $profile->update();
            }
        }
    } // }}}

} // }}}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
