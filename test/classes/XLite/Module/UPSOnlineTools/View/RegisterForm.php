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
 * @subpackage View
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
class XLite_Module_UPSOnlineTools_View_RegisterForm extends XLite_View_RegisterForm implements XLite_Base_IDecorator
{	
    public $upsError = false;

    function checkAddress() {
        if ($this->xlite->get("adminZone"))
			return true;

        $action_type = $_REQUEST['action_type'];

        if ($action_type == 1) { // Use suggestion
            $suggest = $this->get('suggest');
            $value = $this->session->get('ups_av_result');
            $value = $value[$suggest];

            $_REQUEST['shipping_country'] = 'US';
            $obj = new XLite_Model_State();
            if ($obj->find("country_code='US' and code='$value[state]'")) {
                $_REQUEST['shipping_state'] = $obj->get("state_id");
				$_REQUEST['shipping_custom_state'] = "";
            } else {
                $_REQUEST['shipping_state'] = -1;
				$_REQUEST['shipping_custom_state'] = $value["state"];
			}

            $_REQUEST['shipping_city'] = $value['city'];
            $_REQUEST['shipping_zipcode'] = $value['zipcode'];

            $this->session->set('ups_av_result', null);
            return true;
        }
        $this->session->set('ups_av_result', null);
		$this->session->set('ups_av_error', null);
        if($action_type == 2) { // Keep current address
            return true;
        }
        elseif($action_type == 3) { // Re-enter address
            return false;
        }
        else {
            $obj = new XLite_Module_UPSOnlineTools_Model_Shipping_Ups();
            $av_result = array();

			# copy billing to shipping
            $arr = array('billing_country' => 'shipping_country', 'billing_state' => 'shipping_state', 'billing_city'=>'shipping_city', 'billing_zipcode'=>'shipping_zipcode', 'billing_custom_state'=>'shipping_custom_state');
            foreach($arr as $bil=>$ship) {
                if (empty($_REQUEST[$ship]) || ($ship == 'shipping_state' && $_REQUEST[$ship] == -1))
					$ups_used[$ship] = $_REQUEST[$bil];
                else $ups_used[$ship] = $_REQUEST[$ship];
		            $this->session->set('ups_used', $ups_used);
			}

            $result = $obj->checkAddress($ups_used["shipping_country"], $ups_used['shipping_state'], $ups_used["shipping_custom_state"], $ups_used['shipping_city'], $ups_used['shipping_zipcode'], $av_result, $request_result);
            $this->session->set('ups_av_result', $av_result);
            unset($_REQUEST['action_type']);
            $this->session->set('ups_av_profile', $_REQUEST);

			if ($result !== true && count($av_result) <= 0) { // AV return error
				$this->session->set('ups_av_error', 1);
				$this->session->set('ups_av_errorcode', $request_result["errorcode"]);
				$this->session->set('ups_av_errordescr', $request_result["errordescr"]);
			} else {
				$this->session->set('ups_av_error', 0);
				$this->session->set('ups_av_errorcode', "");
				$this->session->set('ups_av_errordescr', "");
			}

            return $result;
        }
    }

    function action_register() {
        if ($this->checkAddress()) return parent::action_register();
        $this->set("valid", false);
        $this->set('upsError', true);
    }

    function action_modify() {
        if ($this->checkAddress()) return parent::action_modify();
        $this->set("valid", false);
        $this->set('upsError', true);
    }

}
