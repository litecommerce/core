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
    /**
     * Options (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $options;

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
     * @param array $options Options (prepared, from request)
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setProductOptions(array $options)
    {
        $result = false;

        // Check options
        if ($this->getProduct()->checkOptionsException($options)) {
            $itemId = $this->get('item_id');
            if (!$itemId) {
                $itemId = $this->getKey();
            }

            // Erase cached options
            $this->options = $options ? array() : null;

            // Remove old options
            $oldOptions = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\OrderItemOption')
                ->findByItemId($itemId);

            foreach ($oldOptions as $o) {
                \XLite\Core\Database::getEM()->remove($o);
            }

            // Save new options
            foreach ($options as $groupId => $data) {
                $group = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\OptionGroup')
                    ->find($groupId);
                $o = new \XLite\Module\ProductOptions\Model\OrderItemOption();
                $o->setItemId($itemId);
                $o->setGroup($group);
                $o->setName($group->getName());
                if (isset($data['option'])) {
                    $o->setOption($data['option']);
                    $o->setValue($data['option']->getName());

                } else {
                    $o->setValue($data['value']);
                }
                \XLite\Core\Database::getEM()->persist($o);

                $this->options[] = $o;
            }

            // Refresh item id
            foreach ($this->options as $o) {
                $o->setItemId($this->getKey());
            }

            \XLite\Core\Database::getEM()->flush();

            $result = true;
        }

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
        if (!isset($this->options)) {
            $itemId = $this->get('item_id');
            $this->options = \XLite\Core\Database::getRepo('XLite\Module\ProductOptions\Model\OrderItemOption')
                ->findByItemId($itemId);
        }

        return $this->options;
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

        // Calculate order item price and weight with options
        if (
            isset($this->options_names[$name])
            && is_object($this->getProduct())
            && $this->getProductOptions()
        ) {
            $func_name = $this->options_names[$name];
            foreach ($this->getProductOptions() as $option) {
                $_opt += $this->$func_name($option);
            }
        }

        return $_opt;
    }

    /**
     * Get item unique key 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getKey()
    {
        $option_keys = array(parent::getKey());

        // Add to key option group name and selected option name
        foreach ($this->getProductOptions() as $option) {
            $option_keys[] = sprintf(
                '%s:%s',
                $option->getName(),
                $option->getValue()
            );
        }

        return implode('|', $option_keys);
    }

    /**
     * Calculate price surcharge 
     * 
     * @param \XLite\Module\ProductOptions\Model\OrderItemOption $option Order item option_
     *  
     * @return float
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function calculateSurcharge(\XLite\Module\ProductOptions\Model\OrderItemOption $option)
    {
        $surcharge = 0;

        // TODO - rework this with tax susbsystem and Wholesale trading module

        if (
            $option->getOption()
            && $option->getOption()->hasActiveSurcharge('price')
        ) {
            $surcharge = $option->getOption()->getSurcharge('price')->getAbsoluteValue();
        }

        return $surcharge;
    }
    
    /**
     * Calculate weight surcharge
     * 
     * @param \XLite\Module\ProductOptions\Model\OrderItemOption $option Order item option
     *  
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function calculateWeight(\XLite\Module\ProductOptions\Model\OrderItemOption $option)
    {
        $subweight = 0;

        if (
            $option->getOption()
            && $option->getOption()->hasActiveSurcharge('weight')
        ) {
            $subweight = $option->getOption()->getSurcharge('weight')->getAbsoluteValue();
        }

        return $subweight;
    }
}
