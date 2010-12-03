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

namespace XLite\Module\CDev\Intershipper\Model\Shipping;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Intershipper extends \XLite\Model\Shipping\Online
{
    public $error = "";
    public $xmlError = false;
    public $translations = array(
        "UGN" => "Ground (Non-Machinable)",
        "UGM" => "Ground (Machinable)",
        "UWE" => "World Wide Express",
        "UWP" => "Worldwide Express Plus",
        "UWX" => "World Wide Expedited",
        "UGD" => "Next Day Air");
        
    public $carriers = array(    
        "DHL" => "DHL",
        "FDX" => "FedEx",
        "UPS" => "UPS",
        "USP" => "USPS");

    public $configCategory = "Intershipper";
    public $optionsFields = array('userid',"password","delivery","pickup","length","width","height","dunit","packaging","contents","insvalue");

    function getModuleName()
    {
        return "Intershipper";
    }

    function getRates(\XLite\Model\Order $order)
    {
        include_once LC_MODULES_DIR . 'Intershipper' . LC_DS . 'encoded.php';
        return Shipping_intershipper_getRates($this, $order);
    }

    function _prepareRequest($weight, $ZipOrigination, $CountryOrigination, 
                         $ZipDestination, $CountryDestination, $options, $cod)
    {
        $ZipOrigination = $this->_normalizeZip($ZipOrigination);
        $ZipDestination = $this->_normalizeZip($ZipDestination);

        require_once LC_LIB_DIR . 'PEAR.php';
        require_once LC_LIB_DIR . 'HTTP' . LC_DS . 'Request2.php';

        $http = new HTTP_Request2('http://www.intershipper.com/Interface/Intershipper/XML/v2.0/HTTP.jsp', HTTP_Request2::METHOD_POST);
        $http->setConfig('timeout', 5);

        $http->addPostParameter('Version', '2.0.0.0');
        $http->addPostParameter('ShipmentID', ''); // must be empty?
        $http->addPostParameter('QueryID', 1);
        $http->addPostParameter('Username', $options->userid);
        $http->addPostParameter('Password', $options->password);
        $http->addPostParameter('TotalClasses', 4);
        $http->addPostParameter('ClassCode1', 'GND');
        $http->addPostParameter('ClassCode2', '1DY');
        $http->addPostParameter('ClassCode3', '2DY');
        $http->addPostParameter('ClassCode4', '3DY');
        $http->addPostParameter('DeliveryType', $options->delivery);
        $http->addPostParameter('ShipMethod', $options->pickup);
        $http->addPostParameter('OriginationPostal', $ZipOrigination);
        $http->addPostParameter('OriginationCountry', $CountryOrigination);
        $http->addPostParameter('DestinationPostal', $ZipDestination);
        $http->addPostParameter('DestinationCountry', $CountryDestination);
        $http->addPostParameter('Currency', 'USD');
        $http->addPostParameter('TotalPackages', 1);
        $http->addPostParameter('BoxID1', 'box1');
        $http->addPostParameter('Weight1', $weight);
        $http->addPostParameter('WeightUnit1', 'OZ');
        $http->addPostParameter('Length1', $options->length);
        $http->addPostParameter('Width1', $options->width);
        $http->addPostParameter('Height1', $options->height);
        $http->addPostParameter('DimensionalUnit1', $options->dunit);
        $http->addPostParameter('Packaging1', $options->packaging);
        $http->addPostParameter('Contents1', $options->contents);
        $http->addPostParameter('Insurance1', $options->insvalue);
        $http->addPostParameter('TotalCarriers', 4);
        $http->addPostParameter('CarrierCode1', 'UPS');
        $http->addPostParameter('CarrierCode2', 'FDX');
        $http->addPostParameter('CarrierCode3', 'USP');
        $http->addPostParameter('CarrierCode4', 'DHL');

        if ($cod) {
            $http->addPostParameter('Cod1', intval($cod * 100));
        }

        return $http;
    }

    function _queryRates($weight, $ZipOrigination, $CountryOrigination, 
                         $ZipDestination, $CountryDestination,$options, $cod)
    {
        try {
            $http = $this->_prepareRequest($weight, $ZipOrigination, 
    	        $CountryOrigination, $ZipDestination, $CountryDestination,$options, $cod);
        	$response = $http->send()->getBodyt();

        } catch (Exception $e) {
            // TODO - add error processing
            $response = false;
        }

        // parse response 
        if ($CountryDestination == $CountryOrigination) {
            $destination = "L"; // Local
        } else {
            $destination = "I"; // International
        }
        return $this->_parseResponse($response, $destination);
    }

    function cleanCache()
    {
        $this->_cleanCache('ints_cache');
    }
    
    function _parseResponse($response, $destination)
    {
        include_once LC_MODULES_DIR . 'Intershipper' . LC_DS . 'encoded.php';
        return Shipping_intershipper_parseResponse($this, $response, $destination);
    }
}
