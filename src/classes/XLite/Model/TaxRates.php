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

namespace XLite\Model;

/**
 * Tax rates
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class TaxRates extends \XLite\Base
{
    const TAX_TOLOWERCASE = 1;

    public $_rates;

    /**
    * "default tax schema name" => array(rate) rules
    */    
    public $_predefinedSchemas = array();
    public $_taxValues = array();
    public $_conditionValues = array();
    
    public function __construct()
    {
        $this->_createOneGlobalTax();
        $this->_createUSStateRates();
        $this->_createVatTax();
        $this->_createCanadianTax();
        $this->_init();
    }

    function _init()
    {
        if (is_array($this->config->Taxes->tax_rates)) {
            $this->_rates = $this->config->Taxes->tax_rates;

        } elseif (strlen($this->config->Taxes->tax_rates) > 0) {
            $this->_rates = unserialize($this->config->Taxes->tax_rates);

        } else {
            $this->_rates = array();
        }

        if (!is_array($this->_rates)) {
            $this->_rates = array();
        }

        if (is_array($this->config->Taxes->taxes)) {
            $this->_taxes = $this->config->Taxes->taxes;

        } elseif (strlen($this->config->Taxes->taxes) > 0) {
            $this->_taxes = unserialize($this->config->Taxes->taxes);

        } else {
            $this->_taxes = array();
        }

        if (!is_array($this->_taxes)) {
            $this->_taxes = array();
        }
    }
   
    function _createOneGlobalTax()
    {
        $this->_predefinedSchemas['One global tax value'] = array(
            'taxes' => array(
                array("name" => "Tax", "display_label" => "Tax")
            ),
            'prices_include_tax' => "",
            'include_tax_message' => "",
            'tax_rates' => array(
                "Tax:=0", 
                array("condition"=>"product class=shipping service", "action"=>"Tax:=0"),
                array("condition"=>"product class=Tax free", "action"=>"Tax:=0")
            )
        );
    }
    
    function _createUSStateRates() 
    {
        // create pre-defined tax rules
        $this->_predefinedSchemas['US state sales tax rates'] = array(
"use_billing_info" => "N", 
'taxes' => array(array("name" => "Tax", "display_label" => "Tax")),
'prices_include_tax' => "",
'include_tax_message' => "",
'tax_rates' => array(
"Tax:=0",
array("condition" => "country=United States", "action" => array(
"City tax:=0",
"State tax:=0",
"Tax:==State tax + City tax",
array("condition" => "state=Alabama", "action" => array(
    "State tax:=4",
    array("condition"=>"product class=Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Arizona", "action" => array(
    "State tax:=5.6",
    array("condition"=>"product class=Food,Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Arkansas", "action" => array(
    "State tax:=5.125",
    array("condition"=>"product class=Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=California", "action" => array(
    "State tax:=7.25",
    array("condition"=>"product class=Food,Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Colorado", "action" => array(
    "State tax:=2.9",
    array("condition"=>"product class=Food,Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Connecticut", "action" => array(
    "State tax:=6",
    array("condition"=>"product class=Food,Prescription Drugs,Non-prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Florida", "action" => array(
    "State tax:=6",
    array("condition"=>"product class=Food,Prescription Drugs,Non-prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Georgia", "action" => array(
    "State tax:=4",
    array("condition"=>"product class=Food,Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Hawaii", "action" => array(
    "State tax:=4",
    array("condition"=>"product class=Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Idaho", "action" => array(
    "State tax:=4",
    array("condition"=>"product class=Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Illinois", "action" => array(
    "State tax:=6.25",
    array("condition"=>"product class=Food,Prescription Drugs,Non-prescription Drugs", "action"=>"State tax:=1"),
    )),
array("condition" => "state=Indiana", "action" => array(
    "State tax:=6",
    array("condition"=>"product class=Food,Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Iowa", "action" => array(
    "State tax:=5",
    array("condition"=>"product class=Food,Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Kansas", "action" => array(
    "State tax:=5.3",
    array("condition"=>"product class=Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Kentucky", "action" => array(
    "State tax:=6",
    array("condition"=>"product class=Food,Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Louisiana", "action" => array(
    "State tax:=4",
    array("condition"=>"product class=Food", "action"=>"State tax:=2"),
    array("condition"=>"product class=Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Maine", "action" => array(
    "State tax:=5",
    array("condition"=>"product class=Food,Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Maryland", "action" => array(
    "State tax:=5",
    array("condition"=>"product class=Food,Prescription Drugs,Non-prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Massachusetts", "action" => array(
    "State tax:=5",
    array("condition"=>"product class=Food,Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Michigan", "action" => array(
    "State tax:=6",
    array("condition"=>"product class=Food,Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Minnesota", "action" => array(
    "State tax:=6.5",
    array("condition"=>"product class=Food,Prescription Drugs,Non-prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Mississippi", "action" => array(
    "State tax:=7",
    array("condition"=>"product class=Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Missouri", "action" => array(
    "State tax:=4.225",
    array("condition"=>"product class=Food", "action"=>"State tax:=1.225"),
    array("condition"=>"product class=Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Nebraska", "action" => array(
    "State tax:=5.5",
    array("condition"=>"product class=Food,Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Nevada", "action" => array(
    "State tax:=6.5",
    array("condition"=>"product class=Food,Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=New Jersey", "action" => array(
    "State tax:=6",
    array("condition"=>"product class=Food,Prescription Drugs,Non-prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=New Mexico", "action" => array(
    "State tax:=5",
    array("condition"=>"product class=Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=New York", "action" => array(
    "State tax:=4",
    array("condition"=>"product class=Food,Prescription Drugs,Non-prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=North Carolina", "action" => array(
    "State tax:=4.5",
    array("condition"=>"product class=Food,Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=North Dakota", "action" => array(
    "State tax:=5",
    array("condition"=>"product class=Food,Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Ohio", "action" => array(
    "State tax:=5",
    array("condition"=>"product class=Food,Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Oklahoma", "action" => array(
    "State tax:=4.5",
    array("condition"=>"product class=Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Pennsylvania", "action" => array(
    "State tax:=6",
    array("condition"=>"product class=Food,Prescription Drugs,Non-prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Rhode Island", "action" => array(
    "State tax:=7",
    array("condition"=>"product class=Food,Prescription Drugs,Non-prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=South Carolina", "action" => array(
    "State tax:=5",
    array("condition"=>"product class=Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=South Dakota", "action" => array(
    "State tax:=4",
    array("condition"=>"product class=Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Tennessee", "action" => array(
    "State tax:=7",
    array("condition"=>"product class=Food", "action"=>"State tax:=6"),
    array("condition"=>"product class=Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Texas", "action" => array(
    "State tax:=6.25",
    array("condition"=>"product class=Food,Prescription Drugs,Non-prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Utah", "action" => array(
    "State tax:=4.75",
    array("condition"=>"product class=Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Vermont", "action" => array(
    "State tax:=5",
    array("condition"=>"product class=Food,Prescription Drugs,Non-prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Virginia", "action" => array(
    "State tax:=4.5",
    array("condition"=>"product class=Food", "action"=>"State tax:=4"),
    array("condition"=>"product class=Prescription Drugs,Non-prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Washington", "action" => array(
    "State tax:=6.5",
    array("condition"=>"product class=Food,Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=West Virginia", "action" => array(
    "State tax:=6",
    array("condition"=>"product class=Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Wisconsin", "action" => array(
    "State tax:=5",
    array("condition"=>"product class=Food,Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=Wyoming", "action" => array(
    "State tax:=4",
    array("condition"=>"product class=Prescription Drugs", "action"=>"State tax:=0"),
    )),
array("condition" => "state=District of Columbia", "action" => array(
    "State tax:=5.75",
    array("condition"=>"product class=Food,Prescription Drugs,Non-prescription Drugs", "action"=>"State tax:=0"),
    ))),"open" => true), 
    // no tax on shipping
    array("condition"=>"product class=shipping service", "action"=>"Tax:=0"),
    array("condition"=>"product class=Tax free", "action"=>"Tax:=0")
    ));
    }

    function _createVatTax() 
    {
         $this->_predefinedSchemas['VAT system'] = array
         (
            'taxes' => array
            (
                array
                (
                    "name" => "VAT", 
                    "display_label" => "VAT"
                ),
            ),
            'prices_include_tax' => "Y",
            'include_tax_message' => ", including VAT",
            'tax_rates' => array
            (
                "Tax:==VAT", 
                "VAT:=0", 
                array
                (
                    "condition"=>"country=EU country", 
                    "action"=> array
                    (
                        "VAT:=17.5",  
                        array
                        (
                            "condition"=>"product class=shipping service", 
                            "action"=>"VAT:=17.5"
                        ),
                          array
                          (
                              "condition"=>"product class=5% VAT", 
                              "action"=>"VAT:=5"
                          ),
                          array
                          (
                              "condition"=>"product class=VAT exempt", 
                              "action"=>"VAT:=0"
                          ),
                    ), 
                    "open" => true
                )
            )
        );
         $this->_predefinedSchemas["VAT system (alternative)"] = array
         (
            'taxes' => array
            (
                array
                (
                    "name" => "VAT", 
                    "display_label" => "VAT (products)"
                ),
                array
                (
                    "name" => "SVAT", 
                    "display_label" => "VAT (shipping)"
                ),
            ),
            'prices_include_tax' => "Y",
            'include_tax_message' => ", including VAT",
            'tax_rates' => array
            (
                "Tax:==VAT", 
                "VAT:=0", 
                array
                (
                    "condition"=>"country=EU country", 
                    "action"=> array
                    (
                        "VAT:=17.5",  
                        array
                        (
                            "condition"=>"product class=shipping service", 
                            "action"=>"VAT:=0"
                        ),
                          array
                          (
                              "condition"=>"product class=5% VAT", 
                              "action"=>"VAT:=5"
                          ),
                          array
                          (
                              "condition"=>"product class=VAT exempt", 
                              "action"=>"VAT:=0"
                          ),
                        array
                        (
                            "condition"=>"product class=shipping service", 
                            "action"=>"SVAT:=17.5"
                        ),
                        array
                        (
                            "condition"=>"product class=shipping service", 
                            "action"=>"Tax:==SVAT"
                        ),
                    ), 
                    "open" => true
                ),
            )
        );
    }

    function _createCanadianTax() 
    {
        $this->_predefinedSchemas['Canadian GST/PST system'] = array(
            'taxes' => array(
                array("name" => "GST", "display_label" => "GST"),
                array("name" => "HST", "display_label" => "HST"),
                array("name" => "PST", "display_label" => "PST")
            ),
            'prices_include_tax' => "",
            'include_tax_message' => "",
            'tax_rates' => array(
                "Tax:==GST+PST",
                array(
                    "condition" => "country=Canada",
                    "open" => true,
                    "action" => array(
                        array("condition"=>"product class=shipping service", "action" => "Tax:==GST"),
                        // HST states
                        array(
                            "condition" => "state=Newfoundland,Labrador,Nova Scotia,New Brunswick",
                            "action" => array(
                                "HST:=15", 
                                array("condition"=>"product class=shipping service", "action" => "Tax:==HST")
                            )
                        ),
                        array(
                            "condition"=>"state=Quebec",
                            "action" => array("GST:=6", "PST:=7.5", "Tax:==GST+(1+GST/100.0)*PST")
                        ),
                        array(
                            "condition"=>"state=Ontario",
                            "action" => array("GST:=6", "PST:=8")
                        ),
                        array("condition"=>"state=Manitoba", "action" => array("GST:=6", "PST:=7")),
                        array("condition"=>"state=Saskatchevan", "action" => array("GST:=6", "PST:=6")),
                        array("condition"=>"state=British Columbia", "action" => array("GST:=6", "PST:=7.5"))
                    )
                ),
                array("condition" => "country=New Zealand", "action" => array("Tax:==GST", "GST:=12.5")),
                array("condition" => "country=Australia", "action" => array("Tax:==GST", "GST:=10"))
            )
        );
    }

    function setPredefinedSchema($name)
    {
        $schemas = $this->get('predefinedSchemas');
        $schema = $schemas[$name];
        $this->setSchema($schema);
    }

    function setSchema($schema)
    {
        if (is_array($schema)) {

            foreach ($schema as $name => $value) {

                $optionType = null;
                if (!is_scalar($value)) {
                    $value = serialize($value);
                    $optionType = 'serialized';
                }

                \XLite\Core\Database::getRepo('\XLite\Model\Config')->createOption(
                    array(
                        'category' => 'Taxes',
                        'name'     => $name,
                        'value'    => $value,
                        'type'     => $optionType
                    )
                );
            }
        }

        $this->_init();
    }
    
    function setOrder($order)
    {
        $profile = $order->getProfile();

        if (!is_null($profile)) {
            $this->set('profile', $profile);

        } else {
            if ($this->config->General->def_calc_shippings_taxes) {
                $default_country = \XLite\Core\Database::getRepo('XLite\Model\Country')
                    ->find($this->config->General->default_country);
                $this->_conditionValues['country'] = $default_country->code;
                /* TODO - rework
                if ($default_country->eu_memeber) {
                    $this->_conditionValues['country'] .= ",EU country";
                }
                */
            }
        }

        $paymentMethod = $order->getPaymentMethod();
        if (isset($paymentMethod)) {
            $this->_conditionValues['payment method'] = $paymentMethod->getServiceName();
        }
    }

    function setProfile($profile)
    {
        $address = $this->config->Taxes->use_billing_info ? $profile->getBillingAddress() : $profile->getShippingAddress();

        if (isset($address)) {
            $this->_conditionValues['state'] = $address->getStateId();
            $this->_conditionValues['country'] = $address->getCountryCode();
            $this->_conditionValues['city'] = $address->getCity();
            $this->_conditionValues['zip'] = $address->getZipcode();
        }

        /* TODO - rework
        $c = \XLite\Core\Database::getRepo('XLite\Model\Country')->find($this->_conditionValues['country']);
        if ($c->eu_memeber) {
            $this->_conditionValues['country'] .= ",EU country";
        }
        */

        $this->_conditionValues['membership'] = $profile->getMembershipId();
    }

    /**
     * Set order item 
     * 
     * @param \XLite\Model\OrderItem $item Order item
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setOrderItem(\XLite\Model\OrderItem $item)
    {
        if (!is_null($item->getProduct())) {
            $this->_conditionValues['product class'] = $item->getProduct()->getTaxClass();

            // categories
            $categories = array();
            foreach ($item->getProduct()->getCategories() as $category) {
                $categories[] = $category->getCategoryId();
            }

            $this->_conditionValues['category'] = implode(',', $categories);
        }

        if ($this->config->Taxes->prices_include_tax) {
            $this->_conditionValues['cost'] = $item->getPrice();
            $this->_conditionValues['amount'] = $item->getAmount();

        } else {
            $this->_conditionValues['cost'] = $item->getTaxableTotal();
        }
    }

    function calculateTaxes()
    {
        $this->_taxValues = array();
        $this->_interpretAction($this->_rates);
    }
    
    function getTaxRate($name)
    {
        return $this->_calcFormula($this->_taxValues[$name]);
    }

    function _interpretAction($action)
    {
        if (is_array($action)) {

            if (isset($action['condition'])) {

                // interpret conditional taxes
                if ($this->_interpretCondition($action['condition'])) {
                    $this->_interpretAction($action['action']);
                }

            } else {

                foreach ($action as $rate) {
                    $this->_interpretAction($rate);
                }
            }

        } else {

            // tax-name:=value|=expression syntax
            list($tax, $value) = explode(':=', $action);
            $this->_taxValues[trim($tax)] = trim($value);
        }
    }

    function _calcFormula($expression)
    {
        if ($expression{0} == '=') {
            $expression = substr($expression,1);

            // first, replace all names with $this->_conditionValues[name]
            $sortedValues = array();
            $values = $this->_taxValues;

            for ($i = 0; $i < count($this->_taxValues); $i++) {

                $maxName = '';
                foreach ($values as $name => $value) {
                    if (strlen($maxName) < strlen($name)) {
                        $maxName = $name;
                    }
                }

                if ($maxName != "") {
                    $sortedValues[$maxName] = $values[$maxName];
                    if (isset($values[$maxName])) {
                        unset($values[$maxName]);
                    }
                }
            }

            foreach ($sortedValues as $name => $value) {
                $pattern = '/\b'.stripslashes($name).'\b/';
                if (preg_match($pattern, stripslashes($expression))) {

                    // eternal recursion protection: 
                    // if the tax value depends on itself, then don't continue recursive calculation
                    static $searchingForNames;
                    if (!is_array($searchingForNames)) $searchingForNames = array();
                    if (isset($searchingForNames[$name])) return 0;
                    $searchingForNames[$name] = 1;

                    $expression = preg_replace($pattern, $this->_calcFormula($value), $expression);
                    unset($searchingForNames[$name]);
                }
            }
            $expression = preg_replace('/(?=\b\D)[ \w]+\b/', '0', $expression);
            $value = 0;
            @eval('$value='.$expression.';');
            return $value;

        } else {
            return $expression;
        }
    }

    function _interpretCondition($cond)
    {
        $conjuncts = $this->_parseCondition($cond, self::TAX_TOLOWERCASE);

        foreach ($conjuncts as $param => $values) {
            if (!isset($this->_conditionValues[$param])) {
                return false;
            }

            $orderValues = explode(',', strtolower(trim($this->_conditionValues[$param])));

            // search for value(s)
            $found = array_intersect($orderValues, $values);

            if (!count($found)) {
                return $param == 'zip'
                    && $this->_compareZip($orderValues[0], $values);
            }
        }

        return true;
    }

    function _compareZip($zip, $ranges)
    {
        foreach ($ranges as $r) {
            if (strpos($r, '-')) {
                list($start, $end) = explode('-', $r, 2);
                if ($zip <= $end && $zip >= $start) {
                    return true;
                }

            } elseif ($r == $zip) {
                return true;
            }
        }

        return false;
    }

    function getAllTaxes()
    {
        $this->_shippingTaxes = array();

        $taxes = array();
        foreach ($this->_taxValues as $name => $percent) {

            $percent = $this->_calcFormula($percent);

            if (isset($this->_conditionValues['cost'])) {

                $tax = $this->_conditionValues['cost'] * $percent / 100.0;

                if ($this->config->Taxes->prices_include_tax) {

                    $tax = $this->formatCurrency($tax);

                    if (isset($this->_conditionValues['amount']) && $this->_conditionValues['product class'] != "shipping service") {
                        $tax = $this->formatCurrency($tax * $this->_conditionValues['amount']);
                    }

                } else {
                    $tax = $this->formatCurrency($tax);
                }

            } else {
                $tax = 0;
            }

            if (isset($this->_conditionValues['product class']) && $this->_conditionValues['product class'] == "shipping service") {
                $this->_shippingTaxes[$name] = $tax;
            }

            $taxes[$name] = $tax;
        }

        return $taxes;
    }

    function getShippingTaxes()
    {
        if (isset($this->_shippingTaxes)) {
            return $this->_shippingTaxes;
        }

        $this->_shippingTaxes = array();

        foreach ($this->_taxValues as $name => $percent) {
            $percent = $this->_calcFormula($percent);
            $tax = isset($this->_conditionValues['cost'])
                ? $this->_conditionValues['cost'] * $percent / 100.0
                : 0;
            $tax = $this->formatCurrency($tax);

            if (
                isset($this->_conditionValues['product class'])
                && $this->_conditionValues['product class'] == 'shipping service'
                && $name != 'Tax'
            ) {
                $this->_shippingTaxes[$name] = $tax;
            }
        }

        return $this->_shippingTaxes;
    }

    function getShippingDefined()
    {
        $isShippingDefined = array_search('shipping service', $this->getProductClasses());

        return !($isShippingDefined === false || is_null($isShippingDefined));
    }

    function getTaxLabel($name)
    {
        foreach ($this->_taxes as $tax) {
            if ($tax['name'] == $name) {
                return $tax['display_label'];
            }
        }

        return '';
    }
    
    function getRegistration($name)
    {
        foreach ($this->_taxes as $tax) {
            if ($tax['name'] == $name) {
                return isset($tax['registration']) ? $tax['registration'] : '';
            }
        }

        return '';
    }

    function getTaxPosition($name)
    {
        foreach ($this->_taxes as $pos => $tax) {
            if ($tax['name'] == $name) {
                return $pos;
            }
        }
        return '';
    }

    function getProductClasses()
    {
        $classes = array();

        $this->_collectClasses($this->_rates, $classes);

        return array_unique($classes);
    }
    
    function _collectClasses(&$tree, &$classes)
    {
        foreach ($tree as $node) {
            if (is_array($node)) {
                $cond = $this->_parseCondition($node['condition']);
                if (isset($cond['product class'])) {
                    $classes = array_merge($classes, $cond['product class']);
                }

                $node = $node['action'];

                if (is_array($node)) {
                    $this->_collectClasses($node, $classes);
                }
            }
        }
    }

    function getActions()
    {
        $actions = array();

        $this->_collectActions($this->_rates, $actions);

        return $actions;
    }

    function _collectActions(&$tree, &$actions)
    {
        foreach ($tree as $node) {
            if (is_array($node)) {
                if (!isset($node['action'])) {
                    continue;
                }

                $action = $node['action'];

                if (is_array($action)) {
                    $this->_collectActions($action, $actions);
                    continue;
                }

            } else {
                $action = $node;
            }

            list($name, $value) = explode(':=', $action);
            $value = trim($value);

            if ($value{0} != '='){
                $value = '=' . $value;
            }

            $actions[] =  trim($value);
        }
    }

    function getTaxNames()
    {
        $names = array();
        $this->_collectNames($this->_rates, $names);
        $names = array_unique($names);
        return $names;
    }

    function getAllTaxNames()
    {
        $names = array();

        $this->_collectNames($this->_rates, $names);

        return $names;
    }

    function _collectNames(&$tree, &$names)
    {
        foreach ($tree as $node) {
            if (is_array($node)) {
                if (!isset($node['action'])) {
                    print_r($tree);
                    $this->doDie("Must contain 'action' key");
                }

                $action = $node['action'];

                if (is_array($action)) {
                    $this->_collectNames($action, $names);
                    continue;
                }

            } else {
                $action = $node;
            }

            list($name) = explode(':=', $action);

            $names[] =  trim($name);
        }
    }

    function _parseCondition($cond, $transform = 0)
    {

        $cond = explode(' AND ', $cond);
        $result = array();
        foreach ($cond as $conjunct) {
            if (trim($conjunct) == '') {
                continue;
            }

            list($name, $values) = explode('=', $conjunct, 2);

            if ($transform == self::TAX_TOLOWERCASE) {
                $values = strtolower($values);
            }

            $result[trim($name)] = array_map('trim', explode(',', $values));
        }

        return $result;
    }

    function getPredefinedSchemas()
    {
        $schemas = $this->_predefinedSchemas; // default set
        if (!is_null($this->config->Taxes->schemas)) {
            $savedSchemas = unserialize($this->config->Taxes->schemas);
            if (is_array($savedSchemas)) {
                foreach (unserialize($this->config->Taxes->schemas) as $k=>$v) {
                    $schemas[$k] = $v;
                }
            }
        }
        return $schemas;
    }

    function saveSchema($name, $schema = "")
    {
        // Schema includes the following properties
        // 
        // config.taxes
        // config.tax_rates
        // config.use_billing_info
        // config.prices_include_tax
        // config.include_tax_message
        //
        if (!is_null($schema) && $schema == '') {
            $schema = array(
                'taxes'               => unserialize($taxes = $this->config->Taxes->taxes),
                'tax_rates'           => unserialize($this->config->Taxes->tax_rates),
                'use_billing_info'    => $this->config->Taxes->use_billing_info ? 'Y' : 'N',
                'prices_include_tax'  => $this->config->Taxes->prices_include_tax ? 'Y' : 'N',
                'include_tax_message' => $this->config->Taxes->include_tax_message,
            );
        }

        if (is_null($this->config->Taxes->schemas)) {
            // create schemas repositary
            $schemas = array($name => $schema);

        } else {
            // update existing schemas repositary
            $schemas = $this->config->Taxes->schemas;

            if (is_null($schema)) {

                if (isset($schemas[$name])) {
                    unset($schemas[$name]);
                }

            } else {
                $schemas[$name] = $schema;
            }
        }

        \XLite\Core\Database::getRepo('\XLite\Model\Config')->createOption(
            array(
                'category' => 'Taxes',
                'name'     => 'schemas',
                'value'    => serialize($schemas),
                'type'     => 'serialized'
            )
        );

    }

    function formatCurrency($price)
    {
        if (!isset($this->_BaseObj)) {
            $this->_BaseObj = new \XLite\Model\AModel();
        }

        return $this->_BaseObj->formatCurrency($price);
    }

    /**
    * Check expression
    * @param string $exp    Expression
    * @param array  $errors Invalid tax names
    * @return true - expression ok
    */
    function checkExpressionSyntax($exp, &$errors, $tax_name = 'Tax')
    {
        if (substr($exp, 0, 1) == '=') {
            $exp = substr($exp, 1);

        } else {
            return false;
        }

        $exp   = ' ' . stripslashes($exp) . ' ';
        $taxes = $this->getTaxNames();
        if (in_array($tax_name, $taxes)) {
            $index = array_search($tax_name, $taxes);
            unset($taxes[$index]);
        }

        $exp = preg_replace('/\b\d+\b/', '@', $exp); // remove all numbers

        // remove all tax names
        foreach ($taxes as $t) {
            $exp = preg_replace('/\b' . stripslashes($t) . '\b/', '@', $exp);
        }

        $tmp = preg_split('/[^ \w]+/', $exp, -1, PREG_SPLIT_NO_EMPTY);

        $errors = array();
        foreach ($tmp as $t) {
            if (trim($t)) {
                $errors[] = str_replace(' ', '&nbsp;', trim($t));
            }
        }

        return 0 === count($errors);
    }

    function isUsedInExpressions($oldName, $newName)
    {
        $count = 0;
        $names = $this->getAllTaxNames();
        foreach ($names as $name){
            if ($name == $oldName){
                $count++;
            }

            if ($count > 1) {
                return false;
            }
        }

        $errors = array();
        $actions = $this->getActions();
        foreach ($actions as $action){
            if (!$this->checkExpressionSyntax($action, $errors, $oldName)){
                return true;
            }
        }

        return false;
    }
}
