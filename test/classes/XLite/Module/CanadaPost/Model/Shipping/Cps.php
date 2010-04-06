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
* @package CanadaPost
* @access public
* @version $Id$
*/
class XLite_Module_CanadaPost_Model_Shipping_Cps extends XLite_Model_Shipping_Online 
{	

	public $configCategory = "CanadaPost";	
	public $optionsFields	= array("merchant_id","length","width","height","packed","insured","currency_rate","test_server");	
    public $error = "";	
    public $xmlError = false;

    function getKgs($order) // {{{
    {
        $w = $order->get("weight");
        switch ($this->config->getComplex('General.weight_unit')) {
        case 'lbs': return $w*0.453;
        case 'oz':  return $w*0.02831;
        case 'kg':  return $w*1.0;
        case 'g':   return $w*0.001;
        }
        return 0;
    } // }}}

	function cleanCache() // {{{ 
	{
		$this->_cleanCache("cps_cache");
	} // }}}

    function getModuleName() // {{{ 
    {
        return "Canada Post";
    } // }}} 

	function getRates(XLite_Model_Order $order) // {{{
	{
		include_once LC_MODULES_DIR . 'CanadaPost' . LC_DS . 'encoded.php';
		return Shipping_cps_getRates($this,$order);
	} // }}}
	
	function queryRates($options,$originalZipcode,$originalCountry,$itemsPrice,$weight,$description,$packed,$destinationCity,$destinationZipcode,$destinationState, $destinationCountry) // {{{
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
	} // }}}

	function createRequest($options,$originalZipCode,$originalCountry,$itemsPrice,$weight,$description,$packed,$destinationCity,$destinationZipcode,$destinationState, $destinationCountry) // {{{ 
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
	} // }}}

	function parseResponse($response,$destination) // {{{
	{
		include_once LC_MODULES_DIR . 'CanadaPost' . LC_DS . 'encoded.php';
		return Shipping_cps_parseResponse($this, $response,$destination);
	} // }}} 

} // }}} 

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
