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

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* Component base class
*
* @package Base
* @version $Id$
*/
class XLite_View extends XLite_View_Abstract
{
    var $params = array();
    var $components = array();
    var $valid = true;
    
    function init()
    {
		$originalRequest = $request;
		$this->_requestUpdated = false;

        // get request params
        foreach ($this->params as $name) {
            if (isset($_REQUEST[$name])) {
				if ($GLOBALS["XLITE_SELF"] == CART_SELF) {
        			$_REQUEST[$name] = $this->_validateRequestData($_REQUEST[$name], $name);
					if ($this->_requestValueUpdated) {
						$this->_requestUpdated = true;
					}
				}
                $this->set($name, $_REQUEST[$name]);
            }
        }

        parent::init();

		if ($this->_requestUpdated) {
			$this->_logSecurityReport($originalRequest);
		}
    }

    function initView()
    {
    }

    function handleRequest()
    {
        $components =& $this->get("components");
        for ($i = 0; $i < count($components); $i++) {
            $components[$i]->handleRequest();
        }
    }

    function display()
    {
        $this->initView();
        parent::display();
    }
    
    function fillForm()
    {
        $components =& $this->get("components");
        for ($i=0; $i<count($components); $i++) {
            if ($components[$i]->is("visible")) {
                $components[$i]->fillForm();
            }
        }
    }
 
    function &getAllParams()
    {
        $result = array();
        foreach ($this->get("params") as $name) {
            if (!is_null($this->get($name))) {
                $result[$name] = $this->get($name);
            }
        }
        // merge with subcomponents
        $components =& $this->get("components");
        for ($i=0; $i<count($components); $i++) {
            if ($components[$i]->is("visible")) {
                $result = array_merge($result, $components[$i]->get("allParams"));
            }
        }
        return $result;
    }

    function &getComponents()
    {
        return $this->components;
    }
    
    function addComponent(&$component)
    {
        $this->components[] =& $component;
    }

    function &getThisVar()
    {
        return $this;
    }

    function isValid()
    {
        if (!$this->valid) {
            return false;
        }
        $components =& $this->get("components");
        for ($i=0; $i<count($components); $i++) {
            if ($components[$i]->is("visible") && !$components[$i]->is("valid") && !$components[$i]->is("validationUnnecessary")) {
                return false;
            }
        }
        return true;
    }

    function mapRequest($request = null)
    {
        if (is_null($request) || (!is_null($request) && !is_array($request))) {
            $request = $_REQUEST;
        }

		$originalRequest = $request;
		$this->_requestUpdated = false;

        foreach($request as $name => $value) {
            if (strstr($name, '.') || $name=='auth' || $name=='xlite' || $name=='session' || $name=='config' || $name=='logger' || $name=='db' || $name=='template' || $name=='params' || isset($this->$name))
                continue;
			
        	if (!($this->xlite->is("adminZone") || $this->xlite->is("aspZone"))) {
        		$value = $this->_validateRequestData($value, $name);
                if ($this->_requestValueUpdated) {
					$this->_requestUpdated = true;
            		if (isset($_REQUEST[$name])) {
            			$_REQUEST[$name] = $this->_validateRequestData($_REQUEST[$name], $name);
            		}
            		if (isset($_GET[$name])) {
            			$_GET[$name] = $this->_validateRequestData($_GET[$name], $name);
            		}
            		if (isset($_POST[$name])) {
            			$_POST[$name] = $this->_validateRequestData($_POST[$name], $name);
            		}
                }
        	}

            $this->set($name, $value);
        }

		if ($this->_requestUpdated) {
			$this->_logSecurityReport($originalRequest);
		}
    }

    function _needConvertToInt($name)
    {
		switch ($name) {
			case "category_id":
			case "product_id":
			case "module_id":
			case "order_id":
			case "profile_id":
			case "orig_profile_id":
			case "shipping_id":
			case "field_id":
			case "parent_field_id":
			case "plan_id":
			case "banner_id":
			case "stat_id":
			case "partner_id":
			case "image_id":
			case "pin_id":
			case "ecard_id":
			case "news_id":
			case "list_id":
			case "option_id":
			case "offer_id":
			case "price_id":
			case "discount_id":
			case "shop_id":
			case "order_history_id":
			case "status_id":
			case "wishlist_id":
			case "related_product_id":
			case "notify_id":
			case "scheme_id":
			case "update_id":
			case "update_item_id":
			case "server_item_id":
			case "server_file_id":
	    	return true;
			default:
	    	return false;
		}
    }

    function _needConvertToIntStr($name)
    {
		switch ($name) {
			case "item_id":
	    	return true;
			default:
	    	return false;
		}
    }

	function _stripSQLinjection($value, $name)
	{
		// (UNION SELECT) case
		$unionPos = strpos(strtolower($value), "union");
		if ($unionPos !== false) {
			$value = preg_replace("/union([\s\(\)]|((?:\/\*).*(?:\*\/))|(?:union|select|all|distinct))+select/i", " ", $value);
		}

		// (BENCHMARK) case
		$benchmarkPos = strpos(strtolower($value), "benchmark(");
		if ($benchmarkPos !== false) {
			$value = preg_replace("/benchmark\(/i", " ", $value);
		}

		return $value;
	}

    function _stripTags($value, $name)
    {
		$value = $this->_stripSQLinjection($value, $name);
    	$value = strip_tags($value);
		if ($this->_needConvertToInt($name)) {
            $value = intval($value);
        }
		
		if ($this->_needConvertToIntStr($name)) {
            $value = strval($value);
			$valueInt = strval(intval($value));
			$valueStr = $valueInt . "|";
			if (!($value == $valueInt || substr($value, 0, strlen($valueStr)) == $valueStr)) {
				$value = $valueInt;
			}
		}

    	return $value;
    }

    function _stripArrayTags(&$data, $name)
    {
    	foreach($data as $key => $value) {
			$valueOld = $value;
			if (!is_array($value)) {
				$value = $this->_stripTags($value, $name);
    			if ($valueOld != $value) {
    				$this->_requestValueUpdated = true;
            	}
				$data[$key] = $value;
			} else {
				$data[$key] = $this->_stripArrayTags($value, $name);
			}
				
    	}

    	return $data;
    }

    function _validateRequestData(&$value, $name)
    {
		$this->_requestValueUpdated = false;
		if (!is_array($value)) {
			$valueOld = $value;
			$value = $this->_stripTags($value, $name);
			if (strval($valueOld) != strval($value)) {
				$this->_requestValueUpdated = true;
        	}
        } else {
			$value = $this->_stripArrayTags($value, $name);
        }

    	return $value;
    }

	function _logSecurityReport($_request=null)
	{
		if (is_null($_request)) {
			$_request = $_REQUEST;
		}

		$proxy_ip = "";
		if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			$proxy_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} elseif (!empty($_SERVER["HTTP_X_FORWARDED"])) {
			$proxy_ip = $_SERVER["HTTP_X_FORWARDED"];
		} elseif (!empty($_SERVER["HTTP_FORWARDED_FOR"])) {
			$proxy_ip = $_SERVER["HTTP_FORWARDED_FOR"];
		} elseif (!empty($_SERVER["HTTP_FORWARDED"])){
			$proxy_ip = $_SERVER["HTTP_FORWARDED"];
		} elseif (!empty($_SERVER["HTTP_CLIENT_IP"])) {
			$proxy_ip = $_SERVER["HTTP_CLIENT_IP"];
		} elseif (!empty($_SERVER["HTTP_X_COMING_FROM"])) {
			$proxy_ip = $_SERVER["HTTP_X_COMING_FROM"];
		} elseif (!empty($_SERVER["HTTP_COMING_FROM"])) {
			$proxy_ip = $_SERVER["HTTP_COMING_FROM"];
		}

		$this->xlite->logger->log("SEQURITY WARNING!");
		$this->xlite->logger->log("REMOTE IP: ".$_SERVER["REMOTE_ADDR"]);
		if (strlen($proxy_ip) > 0) {
			$this->xlite->logger->log("PROXY IP: ".$proxy_ip);
		}
		$this->xlite->logger->log("REQUEST: ".var_export($_request, true));
	}

	function stripHTMLtags(&$var_array, $fields, $tags="<p><b><i><u><br><li><ul>")
	{
		foreach ((array)$var_array as $key=>$value) {
			if (in_array($key, (array)$fields)) {
				$stripped = strip_tags($value, $tags);
				if (!$stripped) {
					$stripped = htmlspecialchars($value);
				}

				$var_array[$key] = $stripped;
			}
		}
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
