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
 * @subpackage Controller
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
