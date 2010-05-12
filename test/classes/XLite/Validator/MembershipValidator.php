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
 * @subpackage Validator
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
class XLite_Validator_MembershipValidator extends XLite_Validator_Abstract
{
    public $template = "common/membership_validator.tpl";
    
    function isValid()
    {
        if (!parent::isValid()) {
            return false;
        }
        
        if (!isset($_POST["action"])) {
            return true;
        }
        
        if ($_POST["action"] != $this->action) {
            return true;
        }

        $dialog = $this->get("dialog");
        if (is_object($dialog) && $dialog->get("actionProcessed")) {
            return true;
        }

        preg_match('/^(.+)\[(.+)\]$/',$this->get("field"),$field);
        $result = !empty($_POST[$field[1]][$field[2]]) || !isset($_POST[$field[1]][$field[2]]);
        if ($result && isset($_POST[$field[1]][$field[2]])) {
            $membershipData = $_POST[$field[1]][$field[2]];
    		if (strlen($membershipData) == 0) {
    			return false;
    		}
            $membership = new XLite_Model_Membership();
            if ($membershipData != $membership->stripInvalidData($membershipData)) {
                $this->set("dataInvalid", true);
                return false;
            }

    		if (strlen($membershipData) > 32) {
                $this->set("dataInvalid", true);
    			return false;
    		}
    		$memberships = $membership->findAll();
    		foreach($memberships as $membership_)
    		if ($membership_->get("membership") == $membershipData) {
    			return false;
    		}
    		$result = true;
    	}
        return $result;
    }
}
