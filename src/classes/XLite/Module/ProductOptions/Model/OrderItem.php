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

namespace XLite\Module\ProductOptions\Model;

/**
 * Order item
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class OrderItem extends \XLite\Model\OrderItem implements \XLite\Base\IDecorator
{
    public $options = array();

    /**
     * Option subproperty names 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $options_names = array(
        'price'  => 'calculateSurcharge',
        'weight' => 'calculateWeight',
    );

    /**
     * Constructor
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct()
    {
        parent::__construct();

        $this->fields['options'] = '';
    }

    /**
     * Check - has item product options or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasOptions()
    {
        $product = $this->getProduct();

        return is_object($product) ? $product->hasOptions() : false;
    }
    
    /**
     * Set item product options 
     * 
     * @param array $options Options (from request)
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setProductOptions(array $options)
    {
        $result = false;

        $this->options = array();

        $resolved_options = array();
        foreach ($options as $key => $option) {
            $resolved_options[stripslashes($key)] = stripslashes($option);
        }
        
        $options = $resolved_options;

        // remove empty options
        foreach ($options as $k => $v) {
            if (strlen(trim($options[$k]))) {
                $options[$k] = nl2br($options[$k]);

            } elseif (isset($options[$k])) {
                  unset($options[$k]);
            }
        }

        // get product options, change option indexes to option values
        $product_options = $this->getProduct()->getProductOptions();
        foreach ($product_options as $product_option) {
            $class = $product_option->get('optclass');
            if (isset($options[$class])) {
                $option_values = $product_option->getProductOptions();

                if (!empty($option_values)) {
                    foreach ($option_values as $opt) {
                        if (strcmp($options[$class], $opt->option_id) == 0) {
                            $resolved_options[$class] = $opt->option;
                            $this->options[] = $opt;
                        }
                    }

                } else {
                    $this->options[] = (object) array(
                        'class' => $class,
                        'option' => $options[$class],
                        'surcharge' => '0'
                    );
                }
            }
        }

        // check for option exceptions
        foreach ($this->getProduct()->get('optionExceptions') as $k => $v) {

            $ex_found = 0;
            foreach (explode(';', $v->get('exception')) as $subvalue) {
                $exception = explode ('=', $subvalue, 2);
                $subkey = trim($exception[0]);

                if ($resolved_options[$subkey] == trim($exception[1])) {
                    $ex_found++;
                }
            }

            // exception for options found
            $result = true;
            if (0 < $ex_found && $ex_found == count($exceptions)) {
                // fill the options array from request with resolved values
                $this->set('invalidOptions', $resolved_options);
                $result = false;
            }
        }

        $this->set('options', serialize($this->options));

        return $result;
    }

    /**
     * Get item product options
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProductOptions()
    {
        $options = $this->get('options');

        return empty($options) ? array() : unserialize($options);
    }
    
    /**
     * Getter 
     * 
     * @param string $name Propery name
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function get($name)
    {
        $_opt = parent::get($name);

        if (isset($this->options_names[$name]) && $this->get('options')) {
            $func_name = $this->options_names[$name];
            foreach (unserialize($this->get('options')) as $option) {
                $_opt += $this->$func_name($option);
            }
        }

        return $_opt;
    }

    function getKey()
    {
        // calculate item key based on options
        $option_keys = array(parent::getKey());
        $options = $this->get('options');
        if (!empty($options)) {
            foreach (unserialize($options) as $option) {
                $option_keys[] = sprintf('%s:%s', $option->class, $option->option);
            }
        }

        return implode('|', $option_keys);
    }

    function calculateSurcharge($option)
    {
        global $calcAllTaxesInside;

        $surcharge = 0;

        if ($option->surcharge != 0 && !$calcAllTaxesInside) {

            $product = $this->getProduct();
            if (is_object($product)) {
                $po = new \XLite\Module\ProductOptions\Model\ProductOption();
                $po->set('product_id', $product->get('product_id'));

                $originalPrice = $product->get('listPrice');

                $full_price = null;
                if ($this->xlite->get('WholesaleTradingEnabled')) {
                    $p = new \XLite\Model\Product($this->get('product_id'));
                    $full_price = $p->getFullPrice($this->get('amount'));
                    if (doubleval($full_price) == $full_price) {
                        $originalPrice = $full_price;
                        if ($this->config->Taxes->prices_include_tax) {
                            $full_price = $p->get('price'); // restore product full price without taxes
                        }
                    }
                }

                $surcharge = $po->_modifiedPrice($option, false, $full_price) - $originalPrice;
            }

        } elseif ($option->surcharge != 0) {
            $price = parent::get('price');

            if ($this->xlite->get('WholesaleTradingEnabled')) {
                $p = new \XLite\Model\Product($this->get('product_id'));
                $full_price = $p->getFullPrice($this->get('amount'));
                if (doubleval($full_price) == $full_price) {
                    if ($this->config->Taxes->prices_include_tax) {
                        $full_price = $p->get('price'); // restore product full price without taxes
                    }

                    $price = $full_price;
                }
            }

            if (isset($option->percent)) {

                // calculate percent surcharge
                $surcharge = $price / 100 * $option->surcharge;

            } elseif (isset($option->absolute)) {

                // calculate absolute surcharge
                $surcharge = $option->surcharge;
            }
        }

        return $surcharge;
    }
    
    function calculateWeight($option)
    {
        $subweight = 0;

        if ($option->weight_modifier != 0) {
            if (isset($option->weight_percent)) {

                // calculate percent surcharge
                $subweight = parent::get('weight') / 100 * $option->weight_modifier;

            } elseif (isset($option->weight_absolute)) {

                // calculate absolute surcharge
                $subweight = $option->weight_modifier * $this->get('amount');

            }
        }

        return $subweight;
    }
}
