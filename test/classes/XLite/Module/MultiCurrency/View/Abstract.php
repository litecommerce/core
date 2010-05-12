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
 * @subpackage View
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
class XLite_Module_MultiCurrency_View_Abstract extends XLite_View_Abstract implements XLite_Base_IDecorator
{
    public $currencies 		= null;
    public $defaultCurrency 	= null;

    function getCurrencies() 
    {
        if(is_null($this->currencies)) {
            $currency = new XLite_Module_MultiCurrency_Model_CurrencyCountries();
            $this->currencies = $currency->findAll("enabled = 1 and base = 0");
        }
        return $this->currencies;

    }
    
    function getDefaultCurrency()  
    {
        if (is_null($this->defaultCurrency)) {
            $this->defaultCurrency = new XLite_Module_MultiCurrency_Model_CurrencyCountries();
            $this->defaultCurrency->find("base = 1");
        }
        return $this->defaultCurrency;
    }

    function price_format($base, $field = "", $thousand_delim = null, $decimal_delim = null) 
    {
        $price_format 	= $this->config->getComplex('General.price_format');
        $price		 	= is_Object($base) ? $base->get($field) : $base;
        $default		= $this->get('defaultCurrency');
        $currencies 	= $this->get('currencies');
        
        $this->config->setComplex("General.price_format", $default->get('price_format'));
        $result = parent::price_format($price, $field, $thousand_delim, $decimal_delim);
        if (!empty($currencies) && ($this->isTargetAllowed())) {
            $additional = "";
            foreach ($currencies as $currency) {
                $this->config->setComplex("General.price_format", $currency->get('price_format'));
                $currency_price = $price * $currency->get('exchange_rate');
                $currency_price = parent::price_format($currency_price, $field, $thousand_delim, $decimal_delim);
                if ($this->auth->is('logged')&&$this->config->getComplex('MultiCurrency.country_currency')) {
                    if ($currency->inCurrencyCountries($this->auth->getComplex('profile.billing_country')))
                        $additional .= $currency_price . ", ";
                } else {
                    $additional .= $currency_price . ", ";
                }
            }
            if (!empty($additional)) $result .= " (" . substr($additional,0,-2) . ")";
        }

        return $result;
    }
    
    function isTargetAllowed() 
    {
        $result = true;
        $target = $this->get('target');
        if ($this->xlite->is('adminZone')) {
            $page = $this->get('page');
            if ((in_array($target, array('order', 'create_order'))) && (in_array($page, array('order_info','order_preview')))) {
                $result = false;
            }
        } else {
            $exceptionTargets = array('checkoutSuccess', 'order');
            $result = !in_array($target, $exceptionTargets);
        }
        return $result;
    }

}
