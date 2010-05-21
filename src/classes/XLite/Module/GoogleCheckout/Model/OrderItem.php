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
class XLite_Module_GoogleCheckout_Model_OrderItem extends XLite_Model_OrderItem implements XLite_Base_IDecorator
{
    function getGoogleCheckoutCurrency()
    {
        return $this->xlite->get('gcheckout_currency');
    }

    function getGoogleCheckoutXML()
    {
        $name = $this->get('name');
        $descr = $this->get('brief_description');

        // Product options
        if ($this->xlite->get('ProductOptionsEnabled') && $this->hasOptions()) {
            $options = (array)$this->get('productOptions');

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
            $itemDescription = $this->GoogleCheckout_encode_string($this->get('description'));
        }
        $unitPrice = sprintf("%.02f", doubleval($this->get('price')));
        $quantity = $this->get('amount');
        $currency = $this->getGoogleCheckoutCurrency();
        $itemSKU = $this->GoogleCheckout_encode_string($this->get('sku'));

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
        require_once LC_MODULES_DIR . 'GoogleCheckout' . LC_DS . 'encoded.php';
        return GoogleCheckout_encode_utf8_string($str);
    }
}
