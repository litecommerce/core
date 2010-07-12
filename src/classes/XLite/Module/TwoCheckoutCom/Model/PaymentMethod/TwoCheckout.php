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

namespace XLite\Module\TwoCheckoutCom\Model\PaymentMethod;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class TwoCheckout extends \XLite\Model\PaymentMethod\CreditCard
{
    public $cvverr = array(
            "M" => "Match",
            "N" => "Wrong CVV2 code",
            "P" => "CVV2 code was not processed",
            "S" => "Please specify your CVV2 code",
            "U" => "Issuer unable to process request"
            );
    public $avserr = array(
            "A" => "Wrong billing address: Address (Street) matches, ZIP does not",
            "E" => "Wrong billing address",
            "N" => "Wrong billing address: No Match on Address (Street) or ZIP",
            "P" => "AVS not applicable for this transaction",
            "R" => "Please Retry. System unavailable or timed out",
            "S" => "AVS Service not supported by issuer",
            "U" => "Address information is unavailable",
            "W" => "Wrong billing address: 9 digit ZIP matches, Address (Street) does not",
            "X" => "Exact AVS Match",
            "Y" => "Wrong billing address: Address (Street) and 5 digit ZIP match",
            "Z" => "Wrong billing address: 5 digit ZIP matches, Address (Street) does not"
            );

    public $processorName = "TwoCheckout.com";
    public $configurationTemplate = "modules/TwoCheckoutCom/config.tpl";
    public $formTemplate ="modules/TwoCheckoutCom/checkout.tpl";

    function handleRequest(\XLite\Model\Cart $cart)
    {
        $params = $this->get('params');
        if ($params['version'] != 2) {
    		// Authorize.Net now returns all POST in lowercase.
            if (!isset($_POST['securenumber']) || $_POST['securenumber'] != $cart->getComplex('details.secureNumber')) {
                die("<font color=red><b>Security check failed!</b></font> Please contact administrator <b>" . $this->config->Company->site_administrator . "</b> .");
            }
            require_once LC_MODULES_DIR . 'TwoCheckoutCom' . LC_DS . 'encoded.php';
            PaymentMethod_2checkout_handleRequest($this, $cart);
        } else {
    		$security_check = true;

    		$order_number = ($params['test_mode']=="Y") ? 1 : $_POST['order_number'];
    		$securekey = strtoupper(md5($params['secret_word'].$params['account_number'].$order_number.$_POST['total']));
    		if ($securekey != $_POST['key']) {
    			$security_check = false;
    		}

    		if ($cart->get('total') != $_POST['total']) {
                $security_check = false;
            }

    		if (isset($_SERVER['HTTP_REFERER'])) {
    			$referers = array('www.2checkout.com', "2checkout.com", "www2.2checkout.com");
    			$referer_check = false;
    			foreach ($referers as $referer) {
    				if (!(preg_match("/https?:\/\/([^\/]*)$referer/i", $_SERVER['HTTP_REFERER']) == false)) {
    					$referer_check = true;
    					break;
    				}
    			}
    			if (!$referer_check) {
    				$security_check = false;
    			}
    		}

            require_once LC_MODULES_DIR . 'TwoCheckoutCom' . LC_DS . 'encoded.php';
            PaymentMethod_2checkout_v2_handleRequest($this, $cart, $security_check);
        }
    }

    function createSecureNumber($order)
    {
        if (!$order->getComplex('details.secureNumber')) {
            $num = generate_code();
            $order->setComplex('details.secureNumber', $num);
            $order->update();
            return $num;
        }
        return $order->getComplex('details.secureNumber');
    }

    function price($value=null)
    {
    	if (!isset($value)) {
    		$value = 0;
    	}
    	return sprintf("%.02f", doubleval($value));
    }

    function stripSpecials($value)
    {
        $value = parent::_stripSpecials($value);
        $value = str_replace("\t", " ", $value);
        $value = str_replace("\r", " ", $value);
        $value = str_replace("\n", " ", $value);
        $value = str_replace("\"", "", $value);
        $value = strip_tags($value);
        return $value;
    }

    function fieldName($name, $idx)
    {
    	return $name . (($idx > 0) ? ("_" . $idx) : "");
    }
}
