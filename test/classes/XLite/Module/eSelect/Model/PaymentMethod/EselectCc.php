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
class XLite_Module_eSelect_Model_PaymentMethod_EselectCc extends XLite_Model_PaymentMethod_CreditCard
{
    public $configurationTemplate = "modules/eSelect/config.tpl";
    public $hasConfigurationForm = true;
    public $processorName = "eSelect";
    
    function process($cart)
    {
        require_once LC_MODULES_DIR . 'eSelect' . LC_DS . 'encoded.php';
        func_eSelect_process($cart, $this);
    }

    function prepareUrl($url)
    {
        $url = htmlspecialchars($url);

        return $url;
    }

    function getReturnUrl()  
    {
        $url = $this->xlite->getShopUrl("cart.php?target=eselect_checkout&action=return", $this->getComplex('config.Security.customer_security'));
        return $this->prepareUrl($url);
    }

    function getAVSMessageText($code)
    {
        $avs_messages = array(
            "A"	=> "Street addresses match. The street addresses match but the postal/ZIP codes do not, or the request does not include the postal/ZIP code.",
            "B"	=> "Street addresses match. Postal code not verified due to incompatible formats. (Acquirer sent both street address and postal code.)",
            "C"	=> "Street address and postal code not verified due to incompatible formats. (Acquirer sent both street address and postal code.)",
            "D"	=> "Street addresses and postal codes match.",
            "G"	=> "Address information not verified for international transaction.",
            "I"	=> "Address information not verified.",
            "M"	=> "Street address and postal code match.",
            "N"	=> "No match. Acquirer sent postal/ZIP code only, or street address only, or both postal code and street address.",
            "P"	=> "Postal code match. Acquirer sent both postal code and street address, but street address not verified due to incompatible formats.",
            "R"	=> "Retry: System unavailable or timed out. Issuer ordinarily performs its own AVS but was unavailable. Available for U.S. issuers only.",
            "S"	=> "Not applicable. If present, replaced with G (for international) or U (for domestic) by V.I.P. Available for U.S. Issuers only.",
            "U"	=> "Address not verified for domestic transaction. Visa tried to perform check on issuers behalf but no AVS information was available on record, issuer is not an AVS participant, or AVS data was present in the request but issuer did not return an AVS result.",
            "W"	=> "Not applicable. If present, replaced with Z by V.I.P. Available for U.S. issuers only.",
            "X"	=> "Not applicable. If present, replaced with Y by V.I.P. Available for U.S. issuers only.",
            "Y"	=> "Street address and postal code match.",
            "Z"	=> "Postal/ZIP matches; street address does not match or street address not included in request.",
        );

        $ucode = strtoupper($code);
        return ((isset($avs_messages[$ucode])) ? $avs_messages[$ucode] : $code);
    }

    function getCVDMessageText($code)
    {
        $cvd_messages = array(
            "M"	=> "Match",
            "N"	=> "No Match",
            "P"	=> "Not Processed",
            "S"	=> "CVD should be on the card, but Merchant has indicated that CVD is not present",
            "U"	=> "Issuer is not a CVD participant"
        );

        $ucode = strtoupper($code);
        $message = "($ucode) ";
        if (preg_match("/[MNPSU]/",$ucode, $match)) {
            $message .= ((isset($cvd_messages[$match[0]])) ? $cvd_messages[$match[0]] : "");
        }
        return $message;
    }

    function getMonerisMPG_URL()
    {
        $url = "";
        if ($this->getComplex('params.account_type') == "CA")
        {
            $url = "https://".(($this->getComplex('params.testmode') == "Y") ? "esqa.moneris.com" : "www3.moneris.com").":443/gateway2/servlet/MpgRequest";
        } else {
            $url ="https://".(($this->getComplex('params.testmode') == "Y") ? "esplusqa" : "esplus").".moneris.com:443/gateway_us/servlet/MpgRequest";
        }

        return $url;
    }

    function getMonerisMPI_URL()
    {
        $url = "";
        if ($this->getComplex('params.account_type') == "CA") {
        	$url = "https://".(($this->getComplex('params.testmode') == "Y") ? "esqa" : "www3").".moneris.com:443/mpi/servlet/MpiServlet";
        } else {
            $url = "https://".(($this->getComplex('params.testmode') == "Y") ? "esplusqa" : "esplus").".moneris.com:443/mpi/servlet/MpiServlet";
        }

        return $url;
    }

    function getOrderStatus($type, $default = 'Q')
    {
        $param  = 'status_' . $type;
        $params = $this->get('params');

        return (isset($params['sub' . $param]) && $this->xlite->AOMEnabled) ?
                    $params['sub' . $param] : (isset($params[$param]) ? $params[$param] : $default);
    }

    function getOrderSuccessStatus()
    {
        return $this->getOrderStatus('success', "P");
    }

    function getOrderFailStatus()
    {
        return $this->getOrderStatus('fail', "F");
    }
}
