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
 * @subpackage Model
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
class XLite_Module_WholesaleTrading_Model_Profile extends XLite_Model_Profile implements XLite_Base_IDecorator
{
    public $_membershipChanged = false;
    public $_oldMembership = "";

    public function __construct($p_id = null)
    {
        $this->fields['membership_exp_date'] = 0;
        $this->fields['tax_id'] = '';
        $this->fields['vat_number'] = '';
        $this->fields['gst_number'] = '';
        $this->fields['pst_number'] = '';
        $this->fields['membership_history'] = '';
        $this->_securefields['membership_exp_date'] = "";
        $this->_securefields['membership_history'] = "";
        parent::__construct($p_id);
    }

    function _initMembershipHistory($history)
    {
        if (!is_array($history)) {
            $history = @unserialize($history);
            $history = ( is_array($history) ) ? $history : array();
        }
        if (is_array($history)) {
            foreach ($history as $mh_idx => $mh) {
                if (isset($mh['membership_exp_date']) && intval($mh['membership_exp_date']) <= 0) {
                    $history[$mh_idx]['membership_exp_date'] = 0;
                }
            }
        }

        return $history;
    }

    function read()
    {
        $readStatus = parent::read();

        if (isset($this->properties['membership_history'])) {
    		$this->properties['membership_history'] = $this->_initMembershipHistory($this->properties['membership_history']);
        }

        if ($readStatus && ($this->get('membership_exp_date') > 0) && (time() > $this->get('membership_exp_date')) ) {
            $mail = new XLite_Model_Mailer();
            $mail->profile = $this;

            // Notify customer
            $mail->adminMail = false;
            $mail->compose(
                    $this->config->Company->orders_department,
                    $this->get('login'),
                    "modules/WholesaleTrading/membership_expired");
            $mail->send();

            // Notify admin
            $mail->adminMail = true;
            $mail->compose(
                    $this->config->Company->site_administrator,
                    $this->config->Company->orders_department,
                    "modules/WholesaleTrading/membership_expired_admin");
            $mail->send();

            // Unset membership
            $this->set('membership', '');
            $this->set('membership_exp_date', 0);
            $this->update();

            // Restore membership from history
            $history = $this->get('membership_history');
            if ( is_array($history) && count($history) > 0 ) {
                while (count($history) > 0) {
                    $value = array_pop($history);
                    $exp_date = $value['membership_exp_date'];
                    if ( $exp_date > 0 && time() > $exp_date )
                        continue;

                    $this->set('membership', $value['membership']);
                    $this->set('membership_exp_date', $exp_date);
                    $this->update();
                    break;
                }

                $this->set('membership_history', $history);
                $this->update();
            }
        }

        return $readStatus;
    }

    function get($name)
    {
        $value = parent::get($name);
        if ( $name == "membership_history" ) {
        	if (!is_array($value)) {
            	$value = unserialize($value);
        	}

            $value = $this->_initMembershipHistory($value);
        }

        return $value;
    }

    function set($name, $value)
    {
        if ( $name == "membership_history" ) {
            if ( !is_array($value) )
                $value = array();
            parent::set($name, serialize($value));
        } else {
            $oldMembership = $this->get('membership');
            parent::set($name, $value);
            if ( $name == "membership" ) {
                if (!$this->_membershipChanged && $value != $oldMembership) {
                    $this->_membershipChanged = true; // call membershipChanged later
                    $this->_oldMembership = $oldMembership;
                }
            }
        }
    }

    function _beforeSave()
    {
        if ($this->_membershipChanged) {
            $this->membershipChanged($this->_oldMembership, $this->get('membership'));
            $this->_membershipChanged = false;
        }
        if (!empty($this->properties['membership_history']) && is_array($this->properties['membership_history'])) {
            $this->properties['membership_history'] = serialize($this->properties['membership_history']);
        }

        parent::_beforeSave();
    }

    function membershipChanged($oldMembership, $newMembership)
    {
        $mail = new XLite_Model_Mailer();
        $mail->profile = $this;
        $mail->oldMembership = $oldMembership;
        $mail->newMembership = $newMembership;

        // Changed
        $template = "modules/WholesaleTrading/membership_changed";
        if ( empty($oldMembership) ) {		// Assigned
            $template = "modules/WholesaleTrading/membership_assigned";
        } elseif ( empty($newMembership) ) {// Unassigned
            $template = "modules/WholesaleTrading/membership_unassigned";
        }

        $mail->adminMail = false;
        $mail->compose(
                $this->config->Company->orders_department,
                $this->get('login'),
                $template);
        $mail->send();
    }

    function isShowWholesalerFields()
    {
        if (
            $this->config->WholesaleTrading->WholesalerFieldsTaxId == "Y" ||
            $this->config->WholesaleTrading->WholesalerFieldsVat == "Y" ||
            $this->config->WholesaleTrading->WholesalerFieldsGst == "Y" ||
            $this->config->WholesaleTrading->WholesalerFieldsPst == "Y"
            ) {
                return true;
            }
            return false;
    }
}
