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
* @package View
* @access public
* @version $Id$
*/
class XLite_Module_GoogleCheckout_View_ButtonAltCheckout extends XLite_View_Button implements XLite_Base_IDecorator
	{	

	/**
     * Widget param names
     */
    const PARAM_SIZE  = 'size';
	const PARAM_BACKGROUND = 'background';

	public $buttonUrl = null;	
	public $gacObject = null;

	function init()
	{
		if (!isset($this->gacObject)) {
    		$this->gacObject = new XLite_Module_GoogleCheckout_View_GoogleAltCheckout();
        	$this->gacObject->initGoogleData();
		}

		if (isset($this->gacObject->GCMerchantID) && $this->getComplex('dialog.target') == "cart" && strtolower($this->get("label")) == "checkout") {
			$this->template = "modules/GoogleCheckout/button_alt_checkout.tpl";
		}

		parent::init();
	}

	function getGoogleCheckoutButtonUrl()
	{
		if (!isset($this->buttonUrl)) {
        	$this->buttonUrl = $this->gacObject->getGoogleCheckoutButtonUrl($this->getParam(self::PARAM_SIZE), $this->getParam(self::PARAM_BACKGROUND));
		}

		return $this->buttonUrl;
	}

	function isGoogleAllowPay()
	{
		return $this->gacObject->isGoogleAllowPay();
	}

	/**
	 * Define widget parameters
	 *
	 * @return void
	 * @access protected
	 * @since  1.0.0
	 */
	protected function defineWidgetParams()
	{
		parent::defineWidgetParams();
		$this->widgetParams += array(
			self::PARAM_SIZE => new XLite_Model_WidgetParam_String(
				'Button size', 'medium', false
			),
			self::PARAM_BACKGROUND => new XLite_Model_WidgetParam_String(
				'Background (white/transparent)', 'white', false
			),
		);
	}

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
