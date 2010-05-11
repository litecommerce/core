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
class XLite_Module_CanadaPost_Model_Shipping_Cps extends XLite_Model_Shipping_Online 
{	

	public $configCategory = "CanadaPost";	
	public $optionsFields	= array("merchant_id","length","width","height","packed","insured","currency_rate","test_server");	
    public $error = "";	
    public $xmlError = false;

    function getKgs($order) 
    {
        $w = $order->get("weight");
        switch ($this->config->getComplex('General.weight_unit')) {
        case 'lbs': return $w*0.453;
        case 'oz':  return $w*0.02831;
        case 'kg':  return $w*1.0;
        case 'g':   return $w*0.001;
        }
        return 0;
    } 

	function cleanCache()  
	{
		$this->_cleanCache("cps_cache");
	} 

    function getModuleName()  
    {
        return "Canada Post";
    }  

	function getRates(XLite_Model_Order $order) 
	{
		include_once LC_MODULES_DIR . 'CanadaPost' . LC_DS . 'encoded.php';
		return Shipping_cps_getRates($this,$order);
	} 
	
	function queryRates($options,$originalZipcode,$originalCountry,$itemsPrice,$weight,$description,$packed,$destinationCity,$destinationZipcode,$destinationState, $destinationCountry) 
	{
		$request = new XLite_Model_HTTPS();
		$request->url = "sellonline.canadapost.ca:30000";
		$request->method = "POST";
		$request->data = $this->createRequest($options,$originalZipcode,$originalCountry,$itemsPrice,$weight,$description,$packed,$destinationCity,$destinationZipcode,$destinationState, $destinationCountry);
		$request->request();
        if ($request->request() == XLite_Model_HTTPS::HTTPS_ERROR) {
            $this->error = $request->error;
            return array();
        }

		$originalCountry == $destinationCountry ? $destination = "L" 
												: $destination = "I";

		return $this->parseResponse($request->response,$destination);
	} 

	function createRequest($options,$originalZipCode,$originalCountry,$itemsPrice,$weight,$description,$packed,$destinationCity,$destinationZipcode,$destinationState, $destinationCountry)  
	{
		$description = htmlspecialchars($description); 
		$options->test_server ? $merchant_id = "CPC_DEMO_XML" 
							  : $merchant_id = $options->merchant_id;
		return <<<EOT
<?xml version='1.0'?>
<eparcel>
<language>en</language>
<ratesAndServicesRequest>
	<merchantCPCID>$merchant_id</merchantCPCID>
	<fromPostalCode>$originalZipCode</fromPostalCode>
	<itemsPrice>$itemsPrice</itemsPrice>
	<lineItems>
		<item>
			<quantity>1</quantity>
			<weight>$weight</weight>
			<length>$options->length</length>
			<width>$options->width</width>
			<height>$options->height</height>
			<description>$description</description> 
			$packed	
		</item>
	</lineItems>
	<city>$destinationCity</city>
	<provOrState>$destinationState</provOrState>
	<country>$destinationCountry</country>	
	<postalCode>$destinationZipcode</postalCode>
</ratesAndServicesRequest>	
</eparcel>
EOT;
	} 

	function parseResponse($response,$destination) 
	{
		include_once LC_MODULES_DIR . 'CanadaPost' . LC_DS . 'encoded.php';
		return Shipping_cps_parseResponse($this, $response,$destination);
	}  

} 
