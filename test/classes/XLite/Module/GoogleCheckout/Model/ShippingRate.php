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
class XLite_Module_GoogleCheckout_Model_ShippingRate extends XLite_Model_ShippingRate implements XLite_Base_IDecorator
{
    function getGoogleCheckoutCurrency()
    {
        return $this->xlite->get('gcheckout_currency');
    }

    function getGoogleCheckoutXML()
    {
        require_once LC_MODULES_DIR . 'GoogleCheckout' . LC_DS . 'encoded.php';

        // Shipping method name
        $str = $this->getComplex('shipping.name');
        $value = strval($str);
        $value = str_replace("\n", " ", $value);
        $value = str_replace("\r", " ", $value);
        $value = str_replace("\t", " ", $value);
        $valueLength = strlen($value);
        $newValue = "";
        for ($i=0; $i<$valueLength; $i++) {
            $symbol = $value{$i};
            $symbolCode = ord($symbol);
            if (($symbolCode>=0 && $symbolCode<=31) || $symbolCode>=127) {
                $newValue .= "&#" . sprintf("%02d", $symbolCode) . ";";
            } else {
                $newValue .= $symbol;
            }
        }
        $shippingName = htmlspecialchars($newValue);

        $shippingPrice = sprintf("%.02f", doubleval($this->xlite->config->getComplex('GoogleCheckout.default_shipping_cost')));
        $currency = $this->getGoogleCheckoutCurrency();

        return <<<EOT
                <merchant-calculated-shipping name="$shippingName">
                    <price currency="$currency">$shippingPrice</price>
                    <address-filters> 
                        <allowed-areas> 
                            <world-area /> 
                        </allowed-areas> 
                    </address-filters> 
                </merchant-calculated-shipping>
EOT;
    }
}
