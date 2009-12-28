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
* Class Dialog_
*
* @package Base
* @access public
* @version $Id$
*/
class XLite_Controller_Abstract extends XLite_View
{
    var $params = array('target');
    var $template = "main.tpl";
    var $dumpStarted = false; // startDump was called
    var $product = null; // current product or null
    var $category = null; // current category or null
    var $locationPath = array(); // path for dialog location

    function constructor()
    {
        parent::constructor();
        if (!$this->xlite->is("adminZone")) {
            $this->cart =& func_get_instance("Cart");
            // cleanup processed cart for non-checkout pages
            $target = isset($_REQUEST["target"]) ? $_REQUEST["target"] : "";
            if ($target != "checkout" && ($this->cart->is("processed") || $this->cart->is("queued"))) {
                $this->cart->clear();
            }
        }
    }

    function &getAllParams($exeptions=null)
    {
    	$allParams = parent::getAllParams();
    	$params = $allParams;
    	if (isset($exeptions)) {
    		$exeptions = explode(",", $exeptions);
    		if (is_array($allParams) && is_array($exeptions)) {
    			$params = array();
    			foreach($allParams as $p => $v) {
    				if (!in_array($p, $exeptions)) {
    					$params[$p] = $v;
    				}
    			}
    		}
    	}
        return $params;
    }

    function &getTemplate()
    {
        if (!$this->xlite->is("adminZone") && $this->get("config.General.add_on_mode")) {
            return "../../../cart.html";
        }
        return $this->template;
    }
    
    function &getProduct()
    {
        if (is_null($this->product) && isset($_REQUEST["product_id"])) {
            $this->product = new XLite_Model_Product($_REQUEST["product_id"]);
            if (!$this->xlite->is("adminZone") && !$this->product->get("enabled")) {
                $this->product = null;
            }
        }
        return $this->product;
    }

    function &getCategory()
    {
        if (is_null($this->category) && isset($_REQUEST["category_id"])) {
            $this->category = new XLite_Model_Category($this->get("category_id"));
        }
        return $this->category;
    }

    function init()
    {
        $this->mapRequest();
        parent::init();
    }

    /**
    * Handles the request. Parses the request variables if necessary.
    * Attempts to call the specified action function.
    *
    * @access public
    */
    function handleRequest()
    {
        $cart_self = $GLOBALS["XLITE_SELF"];

        $trusted_referer = false;
        if (strlen($_SERVER["HTTP_REFERER"]) > 0) {
            $referer = $this->_pure_url_path($_SERVER["HTTP_REFERER"]);

            $url = $this->_pure_url_path($this->xlite->shopURL(""));
            $surl = $this->_pure_url_path($this->xlite->shopURL("", true));
            if (strpos($referer, $url) == 0 || strpos($referer, $surl) == 0) {
                $trusted_referer = true;
            }
        }

		if (isset($_REQUEST[SESSION_DEFAULT_NAME]) && (isset($_GET[SESSION_DEFAULT_NAME]) || isset($_POST[SESSION_DEFAULT_NAME])) && ((isset($_COOKIE[SESSION_DEFAULT_NAME]) && isset($_SERVER["HTTP_REFERER"]) && ((isset($_GET[SESSION_DEFAULT_NAME]) && $_GET[SESSION_DEFAULT_NAME] != $_COOKIE[SESSION_DEFAULT_NAME]) || (isset($_POST[SESSION_DEFAULT_NAME]) && $_POST[SESSION_DEFAULT_NAME] != $_COOKIE[SESSION_DEFAULT_NAME])) && !$trusted_referer) || (isset($_COOKIE[SESSION_DEFAULT_NAME]) && !isset($_SERVER["HTTP_REFERER"]) && $this->xlite->get("script") == $cart_self && ((isset($_GET[SESSION_DEFAULT_NAME]) && $_GET[SESSION_DEFAULT_NAME] != $_COOKIE[SESSION_DEFAULT_NAME]) || (isset($_POST[SESSION_DEFAULT_NAME]) && $_POST[SESSION_DEFAULT_NAME] != $_COOKIE[SESSION_DEFAULT_NAME]))) || (!isset($_COOKIE[SESSION_DEFAULT_NAME]) && (!isset($_SERVER["HTTP_REFERER"]) || (isset($_SERVER["HTTP_REFERER"]) && !$trusted_referer))))) {

$this->xlite->logger->log("Dialog::handleRequest() >>>");
$this->xlite->logger->log("_COOKIE_SESSION_DEFAULT_NAME: ".$_COOKIE[SESSION_DEFAULT_NAME]);
$this->xlite->logger->log("_GET_SESSION_DEFAULT_NAME: ".$_GET[SESSION_DEFAULT_NAME]);
$this->xlite->logger->log("_POST_SESSION_DEFAULT_NAME: ".$_POST[SESSION_DEFAULT_NAME]);
$this->xlite->logger->log("_REQUEST_SESSION_DEFAULT_NAME: ".$_REQUEST[SESSION_DEFAULT_NAME]);
$this->xlite->logger->log("_SERVER_HTTP_REFERER: ".$_SERVER["HTTP_REFERER"]);
$this->xlite->logger->log("<<<");

			if ( $GLOBALS["XLITE_SELF"] == ADMIN_SELF ) {
				// Admin area - redirect to login page
				$this->_clear_xsid_data();
				header("Location: " . $this->shopURL(ADMIN_SELF."?target=login"));
				return;
			}

			if ($this->xlite->is("aspZone")) {
				// ASPE Control Center - redirect ot login page
				$this->_clear_xsid_data();
				header("Location: " . $this->shopURL("cpanel.php?target=login"));
				return;
			}

			$this->_clear_xsid_data();
			header("Location: " . $this->shopURL($cart_self));
			return;
		}

        if (isset($_REQUEST['no_https'])) {
            $this->session->set("no_https", true);
        }
        if (!isset($_REQUEST['action']) && ($this->get("secure") ^ $this->is("https"))) {
            $this->redirect();
            return;
        }
        if (!$this->auth->isAuthorized($this)) {
            $this->params = array("target", "mode");
            $this->set("target", "main");
            $this->set("mode", "access_denied");
            $this->redirect();
            return;
        }
        if (!$this->checkXliteForm()) {
			$this->set("returnUrl", null);
            $this->params = array("target");
            $this->set("target", "access_denied");
            $this->redirect();
            return;
        }

        parent::handleRequest();
        if (!isset($_REQUEST['action'])) {
            $this->fillForm();
            $this->output();
            return;
        }

        if ($this->is("valid")) {
            // call action method
            $action = "action_" . $_REQUEST['action'];
            if (method_exists($this, $action)) {
                $this->$action();
            }
            // action can change valid to false
            if ($this->is("valid") && !$this->is("silent")) {
                $this->redirect();
                return;
            }    
        }

        $this->output();
    }

	function _clear_xsid_data()
	{
		unset($_REQUEST[SESSION_DEFAULT_NAME]);
		$this->xlite->session->destroy();
		$this->xlite->session->setID(SESSION_DEFAULT_ID);
		$this->xlite->session->_initialize();
		$this->xlite->session->_data = array();
	}

	function _pure_url_path($str)
	{
		$pos = strpos($str, "?");
		if ($pos !== false) {
			$str = substr($str, 0, $pos);
		}

		$last = strlen($str) - 1;
		if ($last > 0 && $str{$last} == "/") {
			$str = substr($str, 0, $last);
		}

		return $str;
	}

    function isHTTPS()
    {
        if ((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS'] == 'on') || $_SERVER['HTTPS'] == '1')) ||
            (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') ||
            (isset($_SERVER['REMOTE_ADDR']) && isset($this->xlite->options->host_details->remote_addr) && $_SERVER['REMOTE_ADDR'] == $this->xlite->options->host_details->remote_addr))
        {
            return true;
        }
        return false;
    }

    function startPage()
    {
        // send no-cache headers
        $error_reporting = error_reporting(0); // suppress warning messages
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Content-Type: text/html");
        error_reporting($error_reporting);
    }
    
    function startDownload($filename, $contentType = "application/force-download")
    {
        @set_time_limit(0);
        header("Content-type: $contentType");
        header("Content-disposition: attachment; filename=$filename");
    }

    function startImage()
    {
        header("Content-type: image/gif");
        $this->set("silent", true);
    }

    function startDump()
    {
        @set_time_limit(0);
        $this->set("silent", true);
        if (!isset($_REQUEST["mode"]) || $_REQUEST["mode"]!="cp") {
            func_refresh_start();
            $this->dumpStarted = true;
        }
    }

    function output()
    {
        $this->xlite->profiler->log("request_time");
        if (!$this->is("silent")) {
            ob_start();
            $this->startPage();
            $this->display();
            ob_end_flush();
        }
        if ($this->dumpStarted) {
            func_refresh_end();
        }
    }
    
    /**
    * Provides access to accessdenied function.
    */
    function accessDenied()
    {
    }

    /**
    * Returns the access level value required to access this dialog.
    *
    * @access public
    * @return integer The access level value
    */
    function getAccessLevel()
    {
        return $this->get("auth.customerAccessLevel");
    }

    function redirect($url = null)
    {
        $location = $this->get("url");
        if (!is_null($this->get("returnUrl"))) {
            $location = $this->get("returnUrl");
        } elseif (!is_null($url)) {
            $location = $url;
        }

		// filter xlite_form_id from redirect url
		$action = $this->get("action");
		if (empty($action))
		    $location = $this->filterXliteFormID($location);

        $this->xlite->profiler->enabled = false;
        $this->xlite->done();

		if ($this->get(returnUrlAbsolute))
		{
			header("Location: " . $location);
		} 
		else
		{
        	header("Location: " . $this->shopURL($location, $this->get("secure")));
        }
    }

    /**
    * Get the full URL of the page.
    * Example: shopURL("cart.php") = "http://domain/dir/cart.php
    */
    function shopURL($url, $secure = false)
    {
        if ($fc = $this->config->get("Security.full_customer_security")) {
            if (!$this->xlite->is("adminZone") && $fc) 
                return $this->xlite->shopURL($url, $fc);
            else
                return $this->xlite->shopURL($url, $secure);
        } else {
            return $this->xlite->shopURL($url, $secure);
        }    
    }

    function &getProperties()
    {
        $result = array();
        foreach ($_REQUEST as $name => $value)
        {
            $result[$name] = $this->get($name);
        }
        return $result;
    }

    function getUrl($params = null)
    {
        if (is_null($params)) {
            $params =& $this->get("allParams");
        }
        $url = $this->xlite->get("script") . "?";
        foreach ($params as $param => $value) {
            if (!is_null($value)) {
                $url .= $param . '=' . urlencode($value) . '&';
            }
        }
        return rtrim($url, '&');
    }

    function getLoginURL()
    {
        $script = $this->get("xlite.script");
        $secure = $this->get("xlite.adminZone") ? $this->get("config.Security.admin_security") : $this->get("config.Security.customer_security");
        $url = $this->shopUrl($script, $secure);
        return $url;
    }

    function getPageTemplate()
    {
        if (isset($this->pageTemplates[$this->get("page")])) {
            return $this->pageTemplates[$this->get("page")];
        }
        return null;
    }
    
    /**
    * Recalculates the shopping cart.
    */
    function updateCart()
    {
        $items =& $this->cart->get("items");
		$this->set("absence_of_product", null);
        foreach ($items as $key => $i) {
            if(!$i->isValid()) {
            	$this->set("absence_of_product", true);
            	$this->redirect("cart.php?target=cart");
        		return;
            }
        }
        if ($this->cart->isPersistent) {
            $this->cart->calcTotals();
            $this->cart->update();
        }
    }

    function recalcCart()
    {
        if (!$this->cart->get("empty")) {
        	$this->cart->recalcItems();
            $this->cart->calcTotal();
            $this->cart->update();

    		$this->set("absence_of_product", null);
        	$items =& $this->cart->get("items");
            foreach ($items as $key => $i) {
                if(!$i->isValid()) {
                	$this->set("absence_of_product", true);
                	$this->redirect("cart.php?target=cart");
                	return;
                }
            }
        }
    }

    function getUploadedFile()
    {
        $file = null;

        if (is_uploaded_file($_FILES["userfile"]['tmp_name'])) {
            $file = $_FILES["userfile"]['tmp_name'];
        } elseif (is_readable($_POST["localfile"])) {
            $file = $_POST["localfile"];
        } else {
            $this->_die("FAILED: data file unspecified");
        }
        // security check
        $name = $_FILES['userfile']['name'];
        if (strstr($name, '../') || strstr($name, '..\\')) {
            $this->_die("ACCESS DENIED");
        }
        return $file;
    }

    function checkUploadedFile()
    {
        $check = true;

        if (is_uploaded_file($_FILES["userfile"]['tmp_name'])) {
            $file = $_FILES["userfile"]['tmp_name'];
        } elseif (is_readable($_POST["localfile"])) {
            $file = $_POST["localfile"];
        } else {
            return false;
        }
        // security check
        $name = $_FILES['userfile']['name'];
        if (strstr($name, '../') || strstr($name, '..\\')) {
            return false;
        }

        return $check;
    }

	function getCharset()
	{
		$charset = $this->get("cart.profile.billingCountry.charset");
		if ($charset)
			return $charset;

		if ($this->auth->isLogged()) {
			$profile = $this->auth->get("profile");
			return $profile->get("billingCountry.charset");
		} else {
			$country = $this->config->get("General.default_country");
			$obj = new XLite_Model_Country($country);
			return ($obj->get("charset")) ? $obj->get("charset") : "iso-8859-1";
		}
	}

	function getEmailValidatorRegExp()
	{
		$values = array();
		$domains = split(",| |;|\||\/", $this->config->get("Email.valid_email_domains"));
		foreach ((array)$domains as $key=>$val) {
			if (!trim($val))
				continue;

			$values[$key] = "(\.".trim($val).")";
		}

		if (count($values) <= 0) {
			$values[] = "(\..{2,3})";
		}

		return "/\b(^(\S+@).+(".implode("|", $values).")$)\b/gi";
	}

    function isSecure()
    {
        if (!$this->xlite->is("adminZone")) {
            if ($this->get("config.Security.full_customer_security")) {
                if ($this->xlite->get("HTMLCatalogWorking"))
                    return false;
                else
                    return true;
            } else {
                if (!is_null($this->get("feed")) && $this->get("feed") == "login")
                    return $this->get("config.Security.customer_security");
            }        
        }
        return false;
    }
    
    function strftime($format)
    {
        return strftime($format);
    }

    function rand()
    {
        return rand();
    }

	function filterXliteFormID($url)
	{
		if (preg_match("/(\?|&)(xlite_form_id=[a-zA-Z0-9]+)(&.+)?$/", $url, $matches)) {
			if ($matches[1] == '&') $param = $matches[1].$matches[2];
			elseif (empty($matches[3])) $param = $matches[1].$matches[2];
			else $param = $matches[2]."&";
			$url = str_replace($param, "", $url);
		}
		return $url;
	}

    function checkHtaccess()
    {
        if($this->get("config.Security.htaccess_protection") == "Y"){
            $htaccess = new XLite_Model_Htaccess();
            $htaccess->checkFiles();
        }
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
