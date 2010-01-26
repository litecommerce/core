<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URL: http://www.litecommerce.com/license.php                |
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
|                                                                              |
| The Initial Developer of the Original Code is Ruslan R. Fazliev              |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2003 Creative        |
| Development. All Rights Reserved.                                            |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* 
*
* @package 
* @access public
* @version $Id$
*/

class XLite_Module_AntiFraud_Controller_Admin_Fraud extends XLite_Controller_Admin_Abstract
{	
	public $params = array("target", "mode", "order_id");	
	public $order = null;	
	public $response = null;

	function getTemplate() 
	{
		if($this->get("mode") == "track") 
			return "modules/AntiFraud/track.tpl"; 
		else
			return "main.tpl";
	}
	
	function getOrder()
	{
		if (is_null($order)) 
			$order = new XLite_Model_Order($this->get("order_id"));
		return $order;
	}
		
	function getIp()
	{
		if (isset($this->ip)) 
			return $this->ip;
		else 
			return $this->getComplex('order.address');
	}	
	
	function getZipcode() 
	{
		return isset($this->zipcode) ? $this->zipcode : $this->auth->getComplex('profile.billing_zipcode');
	}

    function getCity() 
    {
        return isset($this->city) ? $this->city : $this->auth->getComplex('profile.billing_city');
    }

	function getResponse()
	{
	  	if (is_null($this->response) && isset($this->distance)) {
			$this->response = $this->check_ip($this->distance);
			if (isset($this->response["result"]["error"]) && $this->response["result"]["error"]) {
				$this->response["result"]["some_problems"] = true;
			}
			if (isset($this->response["data"]["check_error"]) && $this->response["data"]["check_error"]) {
				$this->response["result"]["some_problems"] = true;
			}
		}
		return $this->response; 
	}

	function check_ip($check_distance)
	{
		$post = array();
		$post["service_key"] = $this->config->getComplex('AntiFraud.antifraud_license');
		$post["ip"] = $this->get("ip");
	
		$properties = $this->get("properties");

		if ($check_distance) {
		    $post["city"] = $properties["city"];
        	$post["state"] = $properties["state"];
	        $post["country"] = $properties["country"];
     	    if (isset($properties["zipcode"]) && !empty($properties["zipcode"]))
            	$post["zipcode"] = $properties["zipcode"];
		}
		
		$request = new XLite_Model_HTTPS();
		$request->data = $post;
		$request->url = $this->config->getComplex('AntiFraud.antifraud_url')."/check_ip.php";
		$request->request();
		if ($request->error) {
			return array
			(
				"result" => array
				(
					"error" => "COMMUNICATION_ERROR",
				), 
				"data" => array()
			);	
		}
		list($result,$data) = explode("\n", $request->response);
		$result = unserialize($result);
		$data	= unserialize($data);
		if ($result["available_request"] == $result["used_request"])
			$result["error"] = "LICENSE_KEY_EXPIRED";
		return array("result" => $result, "data" => $data);	
	}
}

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
