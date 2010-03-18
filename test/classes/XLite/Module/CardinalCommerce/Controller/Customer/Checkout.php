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

/**
* Dialog_checkout_CardinalCommerce description.
*
* @package Module_CardinalCommerce
* @access public
* @version $Id$
*/

class XLite_Module_CardinalCommerce_Controller_Customer_Checkout extends XLite_Controller_Customer_Checkout implements XLite_Base_IDecorator
{
    function isSupportedByCardinalCommerce($pm=null)
    {
        require_once LC_MODULES_DIR . 'CardinalCommerce' . LC_DS . 'encoded.php';
        return CardinalCommerce_isSupported($this, $pm);
    }

    function init()
    {
    	parent::init();
    	if (empty($this->action)) {
			$this->clear_cmpi_session();
    	}
    }

    function action_checkout()
    {
		$this->updateCart();
		if ($this->get("absence_of_product")) {
			$this->redirect("cart.php?target=cart");
			return;
		}

        $pm = $this->cart->get("paymentMethod");
        if (!is_null($pm)) {
        	if (!$this->isSupportedByCardinalCommerce($pm)) {
        		parent::action_checkout();
        	} else {
				if (!($this->session->isRegistered("cmpiRequest") && $this->session->get("cmpiRequest"))) {
        			$this->session->set("cmpiRequest", true);
        			$this->session->set("cmpiRequest_data", $_REQUEST);
                    $this->session->writeClose();
?>					
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
<TITLE>LiteCommerce online store builder</TITLE>
</HEAD>
<BODY>
<SCRIPT language="javascript">
enabled_refresh = true;

function refresh() 
{
	if (enabled_refresh) {
		document.frm_redirect.submit();
	}	
}
</SCRIPT>

<form method="POST" action="cart.php" name="frm_redirect">
<?php
					func_array2fields($_REQUEST);
?>					
</form>
<?php
					flush();
                    $this->redirect_Cardinal_validation();

					$this->session->set("cmpiRedirect", true);
					$this->session->writeClose();
																				
?>
<SCRIPT language="javascript">
document.frm_redirect.action.value = "checkout_payment";
document.frm_redirect.submit();
</SCRIPT>
<?php 
					echo str_repeat(" \n", 1000);
?>
</BODY>
</HTML>
<?php 

					exit;
					//parent::action_checkout();
        		} else {
					$this->clear_cmpi_session();

        			parent::action_checkout();
        		}
        	}
        }
    }


	function action_checkout_payment()
	{
        if (!($this->session->isRegistered("cmpiRedirect") && $this->session->get("cmpiRedirect"))) {
            $this->redirect("cart.php?target=checkout");
            return;
        }

		parent::action_checkout();
	}
	
	function action_checkout_cmpi()
    {
        require_once LC_MODULES_DIR . 'CardinalCommerce' . LC_DS . 'encoded.php';
        CardinalCommerce_checkout_cmpi($this);
    }

	function Cardinal_lookup_method()
	{
		$currency = new XLite_Module_CardinalCommerce_Model_Currency();
        $currency->find("code='".$this->config->getComplex('CardinalCommerce.current_currency')."'");

    	$hash = array
    	(
    		'CardinalMPI' => array
    		(
        		"MsgType" 			=> "cmpi_lookup",
        		"Version" 			=> "1.5",
    			"ProcessorId" 		=> $this->config->getComplex('CardinalCommerce.processor_id'),
    			"MerchantId" 		=> $this->config->getComplex('CardinalCommerce.merchant_id'),
        		"OrderNumber" 		=> $this->cart->get("order_id"),
        		"PurchaseAmount" 	=> $this->price_format($this->cart->get("total")),
        		"RawAmount" 		=> preg_replace("/\D/Ss","", sprintf("%.2f", $this->cart->get("total"))),
        		"PurchaseCurrency" 	=> $currency->get("code_int"),
        		"PAN" 				=> $this->cc_info["cc_number"],
        		"PANExpr" 			=> substr($this->cc_info["cc_date"], 2).substr($this->cc_info["cc_date"], 0, 2),
        		"UserAgent" 		=> (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : "unknown"),
        		"EMail" 			=> $this->cart->getComplex('profile.login'),
        		"FirstName" 		=> $this->cart->getComplex('profile.billing_firstname'),
        		"LastName" 			=> $this->cart->getComplex('profile.billing_lastname'),
        		"Address1" 			=> $this->cart->getComplex('profile.billing_address'),
        		"City" 				=> $this->cart->getComplex('profile.billing_city'),
        		"State" 			=> $this->cart->getComplex('profile.billingState.code'),
        		"PostalCode" 		=> $this->cart->getComplex('profile.billing_zipcode'),
        		"CountryCode" 		=> $this->cart->getComplex('profile.billing_country'),
        		"IPAddress" 		=> (isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : "unknown"),
        		"BrowserHeader" 	=> "*/*",
    		)
    	);
    	$xml = func_hash2xml($hash);

		$https = new XLite_Model_HTTPS();
        if ($this->config->Security->httpsClient == "autodetect") {
            $software = $https->AutoDetect();
        } else {
            $software = $this->config->Security->httpsClient;
        }

		if ($software == "openssl") {
?>        
<SCRIPT language="javascript">
setTimeout('refresh()', 15000);
</SCRIPT>
<?php
		}		
		$start_time = time();
		$cmpi_lookup_timeout = 15;
		$this->xlite->set("httpsTimeOut", $cmpi_lookup_timeout);	
    	list($header, $res) = func_https_request2("POST", $this->config->getComplex('CardinalCommerce.transaction_url'), array("cmpi_msg=".$xml));
		$this->xlite->set("httpsTimeOut", null);
		
?>     
<SCRIPT language="javascript">
enabled_refresh = false;
</SCRIPT>
<?php
		
		$res = func_xml2hash($res);
		$res = $res["CardinalMPI"];
		if ($software != "openssl" && empty($res) && ((time() - $start_time) >= $cmpi_lookup_timeout)) {
            $error = "CMPI_ERROR(" . $res["ErrorNo"] . "): " . $res["ErrorDesc"];
            $this->cart->set("details.cmpi_timeout", "HTTPS: Time out ($cmpi_lookup_timeout)");
            $this->cart->set("detailLabels.cmpi_timeout", "Cardinal Commerce Response");
			$this->cart->update();			
?>
<SCRIPT language="javascript">refresh();</SCRIPT>
<?php
		}

		if (strcmp($res["ErrorNo"], "0") == 0 && $res["Enrolled"] == "Y") {
			$this->cmpiResult = $res;
			return;
		} elseif (strcmp($res["ErrorNo"], "0") != 0) {
			// inner error !!!
            $error = "CMPI_ERROR(" . $res["ErrorNo"] . "): " . $res["ErrorDesc"];
            $this->cart->setComplex("details.cmpi_info", $error);
            $this->cart->set("detailLabels.cmpi_info", "Cardinal Commerce Response");
			if ($res["Enrolled"] == "Y" || $res["Enrolled"] == "N") {
            	$this->cart->set("status", "F");
			}
            $this->cart->update();

			$this->clear_cmpi_session();

			if ($res["Enrolled"] == "Y" || $res["Enrolled"] == "N") {
?>    
<SCRIPT language="javascript">
document.window.location = "<?php echo "cart.php?target=checkout&mode=error&order_id=".$this->cart->get("order_id"); ?>";
</SCRIPT>
</BODY>
</HTML>
<?php
            	//$this->redirect("cart.php?target=checkout&mode=error&order_id=".$this->cart->get("order_id"));
            	exit;
			}		
		}

		$this->cmpiResult = null;
		$this->clear_cmpi_session();
	}

	function redirect_Cardinal_validation()
	{
		$this->Cardinal_lookup_method();
		if (is_null($this->cmpiResult)) {
			return;
		}

		$this->session->set("cmpi_tid", $this->cmpiResult["TransactionId"]);
		$this->session->set("cmpi_spahf", $this->cmpiResult["SPAHiddenFields"]);
		$this->session->writeClose();

?>
<FORM name="frm" action="<?php echo $this->cmpiResult["ACSUrl"]; ?>" method="POST">
<INPUT type="hidden" name="PaReq" value="<?php echo $this->cmpiResult["Payload"]; ?>">
<INPUT type="hidden" name="TermUrl" value="<?php echo $this->xlite->getShopUrl("cart.php?target=checkout&action=checkout_cmpi"); ?>">
<INPUT type="hidden" name="MD" value="<?php echo $this->session->getID(); ?>">
<CENTER>Please wait while connecting to <b>Cardinal commerce (Verified by VISA)</b> gateway...</CENTER>
</FORM>
<SCRIPT language="javascript">
document.frm.submit();
</SCRIPT>
</BODY>
</HTML>
<?php
		exit;
	}

	function clear_cmpi_session()
	{
		$this->session->set("cmpiRequest", null);
		$this->session->set("cmpiRequest_data", null);
		$this->session->set("cmpi_tid", null);
		$this->session->set("cmpi_spahf", null);
        $this->session->writeClose();
	}
}

require_once LC_MODULES_DIR . 'CardinalCommerce' . LC_DS . 'encoded.php';

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
