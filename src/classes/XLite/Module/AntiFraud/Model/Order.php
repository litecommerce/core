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

namespace XLite\Module\AntiFraud\Model;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Order extends \XLite\Model\Order implements \XLite\Base\IDecorator
{
    function getAntiFraudData()
    {
        $this->xlite->logger->log("->AntiFraud_Order::getAntiFraudData");
    	if (is_null($this->getComplex('details.af_result.total_trust_score')))
    	{
            if ($this->xlite->get('AFcallbackProcessing')) {
                $this->xlite->logger->log("->AntiFraud_Order::AFcallbackProcessing");
            } else {
    			$proxy_ip = $this->getProxyIP();
    			if (!empty($proxy_ip)) {
    				$customer_ip = $proxy_ip;
    				$proxy_ip = $this->getCustomerIP();
    			}
    			else
    				$customer_ip = $this->getCustomerIP();

    			$this->setComplex('details.customer_ip', "<".$customer_ip.">");
    			$this->setComplex('details.proxy_ip', $proxy_ip);
                
                $this->checkFraud();
    			$this->xlite->logger->log("->AntiFraud_Order::checkFraud");
    		}
    	}
        $this->xlite->logger->log("<-AntiFraud_Order::getAntiFraudData");
    }

    function isAntiFraudForceQueued()
    {
        if ($this->getComplex('details.af_result.total_trust_score') >= $this->config->AntiFraud->antifraud_risk_factor && $this->config->AntiFraud->antifraud_force_queued == 'Y') {
            $this->xlite->logger->log("->AntiFraud_Order::isAntiFraudForceQueued(1)");
            return true;
        } else {
            $this->xlite->logger->log("->AntiFraud_Order::isAntiFraudForceQueued(0)");
            return false;
        }
    }

    function isStatusChanged2Processed($oldStatus, $newStatus)
    {
        if ($oldStatus != 'P' && $oldStatus != 'C' && ($newStatus =='P' || $newStatus == 'C')) {
            return true;
        } else {
            return false;
        }
    }
    
    function statusChanged($oldStatus, $newStatus) 
    {
        $this->xlite->logger->log("->AntiFraud_Order::statusChanged[".$oldStatus."][".$newStatus."]");
        if ($this->xlite->is('adminZone')) {
            $this->xlite->logger->log("->AntiFraud_Order::adminZone");
        } else {
            $this->xlite->logger->log("->AntiFraud_Order::!adminZone");

            $this->getAntiFraudData();

    		if ($this->isStatusChanged2Processed($oldStatus, $newStatus)) {
                // switching to PROCESSED
    			if ($this->isAntiFraudForceQueued()) {
    				$this->xlite->logger->log("->AntiFraud_Order::status=Q");
    				$newStatus = "Q";
    				$this->set('status', $newStatus);
                }
    		}
        }

        parent::statusChanged($oldStatus, $newStatus);
    }

    function getAddress()
    {
        $address = $this->getComplex('details.customer_ip');
        preg_match('/^<(.*)>$/',$address,$address);
        return isset($address[1]) ? $address[1] : '';
    }

    function checkFraud()
    {
        $profile = $this->get('profile');

        $post = array();
        $post['ip'] 		= $this->get('address');
        $post['proxy_ip'] 	= $this->getComplex('details.proxy_ip');
        $post['email'] 		= preg_replace("/^[^@]+@/Ss","",$profile->get('login'));
        $post['country'] 	= $profile->get('billing_country');
        $post['state'] 		= $profile->getComplex('billingState.code');
        $post['city'] 		= $profile->get('billing_city');
        $post['zipcode'] 	= $profile->get('billing_zipcode');
        $post['phone'] 		= $profile->get('billing_phone');
        $post['service_key'] = $this->config->AntiFraud->antifraud_license;
        $post['safe_distance'] = $this->config->AntiFraud->antifraud_safe_distance;

        $request = new \XLite\Model\HTTPS();
        $request->url = $this->config->AntiFraud->antifraud_url."/antifraud_service.php";
        $request->data = $post;
        $request->request();
        
        if ($request->error) {
            $this->setComplex('details.error', $request->error);
            $this->set('detailLabels.error',"HTTPS error");
            return null;
        } else {

    		list($result,$data) = explode("\n",$request->response);
    		$result = unserialize($result);
    		$data 	= unserialize($data);

    		$risk_factor_multiplier = 1;
    		$found = new \XLite\Model\Order($this->get('order_id'));

    		if ($this->config->AntiFraud->antifraud_order_total > 0 && $this->get('total') > 0 &&  $this->get('total') > $this->config->AntiFraud->antifraud_order_total)	{
    			$risk_factor_multiplier *= $this->config->AntiFraud->order_total_multiplier;
    		}
    		$processed_orders = $found->count("(status='P' OR status='C') AND orig_profile_id='" . $this->getComplex('origProfile.profile_id') . "' AND order_id<>'" . $this->get('order_id') . "'");

    		if ($processed_orders > 0) {
    			$this->setComplex('details.processed_orders', $processed_orders);
    			$risk_factor_multiplier /= $this->config->AntiFraud->processed_orders_multiplier;
    		}
    		
    		$declined_orders = $found->count("(status='D' OR status='F') AND orig_profile_id='" . $this->getComplex('origProfile.profile_id') . "' AND order_id<>'" . $this->get('order_id') . "'");

    		if ($declined_orders > 0) {
    			$this->setComplex('details.declined_orders', $declined_orders);
    			$risk_factor_multiplier *= $this->config->AntiFraud->declined_orders_multiplier;
    		}
    		$duplicate_ip = $found->count("orig_profile_id <> ".$this->getComplex('origProfile.profile_id')." AND details LIKE '%".$this->getComplex('details.customer_ip')."%'");

    		if ($duplicate_ip) {
    			$risk_factor_multiplier *= $this->config->AntiFraud->duplicate_ip_multiplier;
    		}
    		
    		$result['total_trust_score'] = $result['total_trust_score'] * $risk_factor_multiplier;

            $country = \XLite\Core\Database::getEM()->find('XLite\Model\Country', $profile->get('billing_country'));
            if ($country->isRiskCountry()) {
                $result['total_trust_score'] +=  $this->config->AntiFraud->risk_country_multiplier;
            }
    		
            if ($result['available_request'] == $result['used_request']) {
                $result['error'] = 'LICENSE_KEY_EXPIRED';
    			$mailer = new \XLite\Model\Mailer();
    			$mailer->compose($this->config->Company->orders_department,
    							 $this->config->Company->site_administrator, 
    							 "modules/AntiFraud/license_expired");
                $mailer->send();
    		}

    		if ($result['total_trust_score'] > 10) {
    			$result['total_trust_score'] = 10;
    		}
    	
            $this->setComplex('details.af_result', $result);
            $this->setComplex('details.af_data', $data);
        }

        return $result;
    }
    
    function getProxyIP() 
    {
        
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED'])) {
            return $_SERVER['HTTP_X_FORWARDED'];
        } elseif (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['HTTP_FORWARDED'])){
            return $_SERVER['HTTP_FORWARDED'];
        } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_COMING_FROM'])) {
            return $_SERVER['HTTP_X_COMING_FROM'];
        } elseif (!empty($_SERVER['HTTP_COMING_FROM'])) {
            return $_SERVER['HTTP_COMING_FROM'];
        } else 
            return '';
    }
    
    function getCustomerIP() 
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    function isAFServiceValue($value)
    {
        switch ($value) {
            case "IP_NOT_FOUND":
            case "POSTAL_CODE_NOT_FOUND":
            case "COUNTRY_NOT_FOUND":
            case "CITY_NOT_FOUND":
            case "IP_REQUIRED":
            case "DOMAIN_REQUIRED":
            case "EMPTY_SERVICE_KEY":
            case "NOT_AVAILABLE_SERVICE":
            case "NO_ACTIVE_LICENSES":
            case "NOT_ALLOWED_SHOP_IP":
            return true;
            default:
            return false;
        }
    }

    function getTotalTrustScore()
    {
        $score = $this->getComplex('details.af_result.total_trust_score');
        return round($score, 1);
    }

    function update()
    {
        if ($this->xlite->is('adminZone') && $this->config->AntiFraud->always_keep_info) {
        	$afFields = array('customer_ip', "proxy_ip", "af_result", "processed_orders", "declined_orders", "af_data");

            $oldOrder = new \XLite\Model\Order($this->get('order_id'));
        	$oldDetails = $oldOrder->get('details');
        	$details = $this->get('details');
        	$detailsUpdated = false;
        	foreach ($afFields as $fieldName) {
        		if (isset($oldDetails[$fieldName]) && !isset($details[$fieldName])) {
        			$details[$fieldName] = $oldDetails[$fieldName];
        			$detailsUpdated = true;
        		}
        	}
        	if ($detailsUpdated) {
        		$this->set('details', $details);
        	}
        }
        parent::update();
    }
}
