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
class XLite_Module_SagePay_Model_PaymentMethod_SagepaydirectCc extends XLite_Model_PaymentMethod_CreditCard
{
    public $configurationTemplate = "modules/SagePay/config.tpl";
    public $hasConfigurationForm = true;
    public $processorName = "SagePay VSP Direct";

    function process($cart)
    {
        require_once LC_MODULES_DIR . 'SagePay' . LC_DS . 'encoded.php';
        return func_SagePayDirect_process($this, $cart);
    }

    function prepareUrl($url)
    {
        return htmlspecialchars($url);
    }

    function getReturnUrl()  
    {
        $url = $this->xlite->getShopUrl("cart.php?target=sagepaydirect_checkout&action=return", $this->getComplex('config.Security.customer_security'));
        return $this->prepareUrl($url);
    }

    function getServiceUrl($type="purchase", $is_simulator=false)
    {
        if ($is_simulator) {
            switch ($type) {
                case "callback":
                    return "https://test.sagepay.com:443/Simulator/VSPDirectCallback.asp";
                case "refund":
                    return "https://test.sagepay.com:443/Simulator/VSPServerGateway.asp?Service=VendorRefundTx";
                case "release":
                    return "https://test.sagepay.com:443/Simulator/VSPServerGateway.asp?Service=VendorReleaseTx";
                case "repeat":
                    return "https://test.sagepay.com:443/Simulator/VSPServerGateway.asp?Service=VendorRepeatTx";
                 case "purchase":
                 default:
                     return "https://test.sagepay.com:443/Simulator/VSPDirectGateway.asp";
            }
        }

        $subtag = (($this->getComplex('params.testmode') == "N") ? "live" : "test");
        switch ($type) {
            case "callback":
                return "https://$subtag.sagepay.com:443/gateway/service/direct3dcallback.vsp";
            case "refund":
                return "https://$subtag.sagepay.com:443/gateway/service/refund.vsp";
            case "release":
                return "https://$subtag.sagepay.com:443/gateway/service/release.vsp";
            case "repeat":
                return "https://$subtag.sagepay.com:443/gateway/service/repeat.vsp";
            case "purchase":
            default:
                return "https://$subtag.sagepay.com:443/gateway/service/vspdirect-register.vsp";
        }
    }

    function getOrderStatus($type, $default = 'Q')
    {
        $param  = 'status_' . $type;
        $params = $this->get('params');

        return (isset($params['sub' . $param]) && $this->xlite->AOMEnabled) ?
                    $params['sub' . $param] : (isset($params[$param]) ? $params[$param] : $default);
    }

    function getOrderAuthStatus() 
    {
        return $this->getOrderStatus('auth');
    }

    function getOrderRejectStatus() 
    {
        return $this->getOrderStatus('reject', 'F');
    }

    function getOrderSuccessNo3dStatus() 
    {
        return $this->getOrderStatus('success_no3d', 'P');
    }

    function getOrderSuccess3dOkStatus() 
    {
        return $this->getOrderStatus('success_3dok', 'P');
    }

    function getOrderSuccess3dFailStatus() 
    {
        return $this->getOrderStatus('success_3dfail');
    }

    function handleConfigRequest() 
    {
        $params = $_POST["params"];

        $statuses = array("auth", "reject", "success_no3d", "success_3dok", "success_3dfail");
        foreach ($statuses as $name) {
            $field = "status_" . $name;
            $result = $params[$field];
            if ($this->xlite->AOMEnabled) {
                $status = new XLite_Module_AOM_Model_OrderStatus();
                if ($status->find("status='".$params[$field]."'")) {
                    if ($status->get("parent")) {
                        $params[$field] = $status->get("parent");
                        $result = $status->get("status");
                    }
                }
            }
            $params["sub".$field] = $result;
        }

        $pm = XLite_Model_PaymentMethod::factory('sagepaydirect_cc');
        $pm->set("params", $params);
        $pm->update();
    }

    function getCCDetails()
    {
        $request = array();

        $request["CardHolder"] = $this->cc_info["cc_name"];
        $request["CardNumber"] = $this->cc_info["cc_number"];
        $request["ExpiryDate"] = $this->cc_info["cc_date"];
        $request["CV2"]        = $this->cc_info["cc_cvv2"];
        $request["CardType"]   = $this->cc_info["cc_type"];

        // Add additional informations
        switch ($request["CardType"]) {
            case "SW":
                $request["CardType"] = "SWITCH";
            break;
            case "SO":
                $request["CardType"] = "SOLO";
                if (isset($this->cc_info["cc_start_date"])) {
                    $request["StartDate"] = $this->cc_info["cc_start_date"];
                }
                if (isset($this->cc_info["cc_issue"])) {
                    $request["IssueNumber"] = $this->cc_info["cc_issue"];
                }
            break;
            case "AMEX":
                if (isset($this->cc_info["cc_start_date"])) {
                    $request["StartDate"] = $this->cc_info["cc_start_date"];
                }
            break;
        }

        return $request;
    }

    function getClientIP()
    {
        return $_SERVER["REMOTE_ADDR"];
    }
}
