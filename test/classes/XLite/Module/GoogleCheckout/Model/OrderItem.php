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
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Ruslan R. Fazliev              |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2003 Creative        |
| Development. All Rights Reserved.                                            |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* @package GoogleCheckout
* @access public
* @version $Id$
*/
class XLite_Module_GoogleCheckout_Model_OrderItem extends XLite_Model_OrderItem
{
	function getGoogleCheckoutCurrency()
	{
		return $this->xlite->get("gcheckout_currency");
	}

	function getGoogleCheckoutXML()
	{
		$name = $this->get("name");
		$descr = $this->get("brief_description");

		// Product options
		if ($this->xlite->get("ProductOptionsEnabled") && $this->hasOptions()) {
			$options = (array)$this->get("productOptions");

			$opt_short = array();
			$opt_long = array();
			foreach ($options as $option) {
				$opt_short[] = $option->option;
				$opt_long[] = $option->class.": ".$option->option;
			}

			if (is_array($opt_long) && count($opt_long) > 0) {
				$descr = "(".implode("; ", $opt_long).") ".$descr;
			}
		}

		$itemNname = $this->GoogleCheckout_encode_string($name);
		$itemDescription = $this->GoogleCheckout_encode_string($descr);
		if (strlen($itemDescription) == 0) {
			$itemDescription = $this->GoogleCheckout_encode_string($this->get("description"));
		}
		$unitPrice = sprintf("%.02f", doubleval($this->get("price")));
		$quantity = $this->get("amount");
		$currency = $this->getGoogleCheckoutCurrency();
        $itemSKU = $this->GoogleCheckout_encode_string($this->get("sku"));

		return <<<EOT
                <item>
                    <item-name>$itemNname</item-name>
                    <item-description>$itemDescription</item-description>
                    <unit-price currency="$currency">$unitPrice</unit-price>
                    <quantity>$quantity</quantity>
                    <merchant-item-id>$itemSKU</merchant-item-id>
                    <tax-table-selector>US Taxes</tax-table-selector>
                </item>
EOT;
	}

	function GoogleCheckout_encode_string($str)
	{
		include_once "modules/GoogleCheckout/encoded.php";
		return GoogleCheckout_encode_utf8_string($str);
	}
}

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
