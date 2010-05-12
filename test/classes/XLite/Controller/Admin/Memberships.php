<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Controller_Admin_Memberships extends XLite_Controller_Admin_Abstract
{
    public $params = array("target", "mode");
    
    function action_update() 
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
    }

    function action_delete() 
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
    }

    function action_add() 
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
    }
    
    function getMemberships() 
    {
        $membership = new XLite_Model_Membership();
        return $membership->findAll();
    }

    function getMembershipProfiles($membership) 
    {
        $profile = new XLite_Model_Profile();
        return $profile->findAll("membership = '".$membership."' OR pending_membership='".$membership."'");
    }

    /**
    * Update all profiles 
    */
    function updateProfilesMembership(&$profiles, $old, $new, $sendNotification=false) 
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
    }

}
