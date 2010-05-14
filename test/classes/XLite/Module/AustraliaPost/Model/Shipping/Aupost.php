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
class XLite_Module_AustraliaPost_Model_Shipping_Aupost extends XLite_Model_Shipping_Online 
{

    public $configCategory = "AustraliaPost";
    public $optionsFields	= array('length',"width","height","currency_rate");
    public $error = "";
    public $xmlError = false;

    function getWeightInGrams($order, $weight_unit=null) 
    {
        $weight = (is_object($order)) ? $order->get('weight') : $order;
        $weight_unit = (isset($weight_unit)) ? $weight_unit : $this->config->getComplex('General.weight_unit');

        switch ($weight_unit) {
        	case 'lbs': 
        		return $weight*453.0;
        	case 'oz':  
        		return $weight*28.31;
        	case 'kg':  
        		return $weight*1000.0;
        	case 'g':   
        		return $weight;
        }
        return 0;
    }

    function cleanCache()  
    {
        $this->_cleanCache('aupost_cache');
    }

    function getModuleName()  
    {
        return "Australia Post";
    }

    function getRates(XLite_Model_Order $order) 
    {
        include_once LC_MODULES_DIR . 'AustraliaPost' . LC_DS . 'encoded.php';
        return Shipping_aupost_getRates($this,$order);
    }
    
    function queryRates($options, $originalZipcode, $destinationZipcode, $destinationCountry, $weight, $weight_unit=null) 
    {
        include_once LC_MODULES_DIR . 'AustraliaPost' . LC_DS . 'encoded.php';
        return Shipping_aupost_queryRates($this, $options, $originalZipcode, $destinationZipcode, $destinationCountry, $weight, $weight_unit);
    }

    function parseResponse($rates_data, $destination) 
    {
        include_once LC_MODULES_DIR . 'AustraliaPost' . LC_DS . 'encoded.php';
        return Shipping_aupost_parseResponse($this, $rates_data, $destination);

    }

    function get($property)
    {
        if ($property == "name" && isset($this->shipping_time)) {
            return parent::get("$property") . " (" . $this->shipping_time . " day" . (($this->shipping_time > 1) ? "s" : "") . ")";
        } else {
            return parent::get($property);
        }
    }

    function _cacheResult($table, $fields, &$rates)
    {
    	parent::_cacheResult($table, $fields, $rates);

        $cacheTable = $this->db->getTableByAlias($table);
        $values = array();
        foreach ($fields as $field => $value) {
            $values[] = "$field='".addslashes($value)."'";
        }
        $values = implode(' AND ', $values);

        $serialized = array();
        foreach ($rates as $rate) {
            $serialized[] = $rate->shipping->get('shipping_id') . ":" . $rate->shipping->shipping_time;
        }
        $serialized = implode(",", $serialized);

        $this->db->query("UPDATE $cacheTable SET shipping_dates='$serialized' WHERE $values");
    }

    function _checkCache($table, $fields)
    {
    	$result = parent::_checkCache($table, $fields);
    	if ($result !== false) {
        	$cacheTable = $this->db->getTableByAlias($table);
            $condition = array();
            foreach ($fields as $key => $value) {
                $condition[] = "$key='".addslashes($value)."'";
            }
            $condition = join(' AND ', $condition);
            $cacheRow = $this->db->getRow("SELECT shipping_dates FROM $cacheTable WHERE $condition");
            if (!is_null($cacheRow)) {
            	$shipping_dates = explode(",", $cacheRow['shipping_dates']);
            	foreach ($result as $shr_key => $shr) {
            		$id = $shr->shipping->get('shipping_id');
            		foreach ($shipping_dates as $shd) {
            			$shd = explode(":", $shd);
            			if ($id == $shd[0]) {
            				$result[$shr_key]->shipping->shipping_time = $shd[1];
            				break;
            			}
            		}
            	}
            }
    	}

    	return $result;
    }
}
