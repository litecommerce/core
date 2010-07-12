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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\MultiCurrency\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Currencies extends \XLite\Controller\Admin\AAdmin
{
    
    public $params = array('target');
    public $countries = null;
    public $allCurrencies = null;
    public $defaultCurrency = null;

    function getDefaultCurrency() 
    {
        if (is_null($this->defaultCurrency)) {
            $this->defaultCurrency = new \XLite\Module\MultiCurrency\Model\CurrencyCountries();
       		$found = $this->defaultCurrency->find("base = 1");
            if (!$found) {
                $this->defaultCurrency = new \XLite\Module\MultiCurrency\Model\CurrencyCountries();
                $this->defaultCurrency->set('code',"USD");
                $this->defaultCurrency->set('name',"US dollar");
                $this->defaultCurrency->set('exchange_rate',1);
                $this->defaultCurrency->set('price_format',$this->config->General->price_format);
                $this->defaultCurrency->set('base',1);
                $this->defaultCurrency->set('enabled',1);
                $this->defaultCurrency->set('countries',serialize(array()));
                $this->defaultCurrency->create();
            }
        }
        return $this->defaultCurrency;
    }

    function getAllCurrencies()  
    {
        if (is_null($this->allCurrencies)) {
            $currency = new \XLite\Module\MultiCurrency\Model\CurrencyCountries();
            $this->allCurrencies = $currency->findAll("base = 0");
        }
        return $this->allCurrencies;
    } // }}

    function action_update_default()  
    {
        $currency = $this->get('defaultCurrency');
        $properties = $this->currency;
        $currency->set('code',$properties['code']);
        $currency->set('name',$properties['name']);
    	$currency->set('price_format',$properties['price_format']);
        $currency->update();

    }
    
    function getCountries()  
    {
        if (is_null($this->countries)) {
            $country = new \XLite\Model\Country();
            $this->countries = $country->findAll("enabled = 1");
        }
        return $this->countries;
    }
    
    function action_add()  
    {
        $currency = new \XLite\Module\MultiCurrency\Model\CurrencyCountries();
        $properties = $this->currency;
        $properties['countries'] = serialize(isset($properties['countries']) ? $properties['countries'] : array());
        $properties['enabled'] = "1";
        $currency->set('properties',$properties);
        $currency->create();

    }
    
    function action_update()  
    {
        foreach ($this->currencies as $currency_) {
            $currency = new \XLite\Module\MultiCurrency\Model\CurrencyCountries($currency_['currency_id']);
            $currency_['countries'] = serialize(isset($currency_['countries']) ? $currency_['countries'] : array());
        	$currency_['enabled'] = isset($currency_['enabled']) ? "1" : "0";
            $currency->set('properties',$currency_);
            $currency->update();
        }

    }

    function action_delete() 
    {
        if (isset($this->deleted)) {
            foreach ($this->deleted as $currency_id) {
                $currency = new \XLite\Module\MultiCurrency\Model\CurrencyCountries($currency_id);
                $currency->delete();
            }
        }
    }

}
