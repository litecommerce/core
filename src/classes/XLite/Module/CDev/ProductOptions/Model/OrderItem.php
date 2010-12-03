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

namespace XLite\Module\CDev\ProductOptions\Model;

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
     * Item options
     *
     * @var    \Doctrine\ORM\PersistentCollection|array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @OneToMany (targetEntity="XLite\Module\CDev\ProductOptions\Model\OrderItemOption", mappedBy="order_item", cascade={"all"})
     * @OrderBy   ({"orderby"="ASC", "option_id"="ASC"})
     */
    protected $options;

    /**
     * Return hash of the options names/values
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSerializedOptions()
    {
        $result = '';

        foreach ($this->getOptions() as $option) {
            $result .= '|' . $option->getActualName() . ':' . $option->getActualValue();
        }

        return $result;
    }

    /**
     * This key is used when checking if item is unique in the cart
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getKey()
    {
        return parent::getKey() . $this->getSerializedOptions();
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
        return $this->getProduct()->hasOptions();
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
            $itemId = $this->getItemId();

            // Erase cached options
            foreach ($this->getOptions() as $option) {
                \XLite\Core\Database::getEM()->remove($option);
            }
            $this->getOptions()->clear();

            // Save new options
            foreach ($options as $groupId => $data) {
                $group = \XLite\Core\Database::getRepo('\XLite\Module\CDev\ProductOptions\Model\OptionGroup')
                    ->find($groupId);

                if ($group) {
                    $o = new \XLite\Module\CDev\ProductOptions\Model\OrderItemOption();
                    $o->setOrderItem($this);
                    $o->setGroup($group);
                    $o->setOrderby($group->getOrderby());
                    $o->setName($group->getName());
                    if (isset($data['option'])) {
                        $o->setOption($data['option']);
                        $o->setValue($data['option']->getName());

                    } else {
                        $o->setValue($data['value']);
                    }

                    $this->getOptions()->add($o);
                }
            }

            $result = true;
        }

        return $result;
    }

    /**
     * Get item product options
     * TODO - remove
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProductOptions()
    {
        return $this->getOptions();
    }

    /**
     * Count item product options 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function countProductOptions()
    {
        return count($this->getProductOptions());
    }

    /**
     * Get price 
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPrice()
    {
        $price = parent::getPrice();

        foreach ($this->getOptions() as $option) {
            if (
                $option->getOption()
                && $option->getOption()->hasActiveSurcharge('price')
            ) {
                $price += $option->getOption()->getSurcharge('price')->getAbsoluteValue();
            }
        }

        return $price;
    }

    /**
     * Get weight 
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getWeight()
    {
        $weight = parent::getWeight();

        foreach ($this->getOptions() as $option) {
            if (
                $option->getOption()
                && $option->getOption()->hasActiveSurcharge('weight')
            ) {
                $weight += $option->getOption()->getSurcharge('weight')->getAbsoluteValue();
            }
        }

        return $weight;
    }

    /**
     * Get event cell base information
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getEventCell()
    {
        $cell = parent::getEventCell();

        $cell['options'] = array();

        foreach ($this->getOptions() as $option) {
            $cell['options'][] = array(
                'group_id'  => $option->getGroupId(),
                'option_id' => $option->getOptionId(),
                'value'     => $option->getValue(),
            );
        }

        return $cell;
    }

    /**
     * Constructor
     *
     * @param array $data Entity properties
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct(array $data = array())
    {
        $this->options = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }
}
