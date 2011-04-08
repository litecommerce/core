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
 * PHP version 5.3.0
 * 
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Logic\Order\Modifier;

/**
 * Abstract order modifier
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class AModifier extends \XLite\Logic\ALogic
{
    /**
     * Modifier type (see \XLite\Model\Base\Surcharge)
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $type;

    /**
     * Modifier unique code 
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $code;

    /**
     * Model 
     * 
     * @var   \XLite\Model\Order\Modifier
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $model;

    /**
     * Order 
     * 
     * @var   \XLite\Model\Order
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $order;

    /**
     * Modifiers list
     * 
     * @var   \XLite\DataSet\Collection\OrderModifier
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $list;

    /**
     * Surcharge identification pattern 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $identificationPattern;


    /**
     * Calculate
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function calculate();

    /**
     * Get surcharge information
     * 
     * @param \XLite\Model\Base\Surcharge $surcharge Surcharge
     *  
     * @return \XLite\DataSet\Transport\Order\Surcharge
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function getSurchargeInfo(\XLite\Model\Base\Surcharge $surcharge);


    /**
     * Constructor
     * 
     * @param \XLite\Model\Order\Modifier $model Model
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct(\XLite\Model\Order\Modifier $model)
    {
        $this->model = $model;
    }

    /**
     * Initialize modifier
     * 
     * @param \XLite\Model\Order                      $order Context
     * @param \XLite\DataSet\Collection\OrderModifier $list  Modifiers list
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function initialize(\XLite\Model\Order $order, \XLite\DataSet\Collection\OrderModifier $list)
    {
        $this->order = $order;
        $this->list = $list;
    }

    /**
     * Preprocess internal state
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function preprocess()
    {
    }

    /**
     * Check - can apply this modifier or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function canApply()
    {
        return $this->type && $this->code && $this->order && $this->list && 0 < count($this->list) && $this->type;
    }

    /**
     * Get modifier type 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get modifier unique code 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get order 
     * 
     * @return \XLite\Model\Order
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getOrder()
    {
        return $this->order;
    }

    // {{{ Surcharge operations

    /**
     * Check - modifier is specified surcharge owner or not
     *
     * @param \XLite\Model\Base\Surcharge $surcharge Surcharge
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isSurchargeOwner(\XLite\Model\Base\Surcharge $surcharge)
    {
        return ($this->identificationPattern && preg_match($this->identificationPattern, $surcharge->getCode()))
            || $surcharge->getCode() == $this->getCode();
    }

    /**
     * Add order surcharge 
     * 
     * @param string  $code      Surcharge code
     * @param float   $value     Value
     * @param boolean $include   Include flag OPTIONAL
     * @param boolean $available Availability flag OPTIONAL
     *  
     * @return \XLite\Model\Order\Surcharge
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addOrderSurcharge($code, $value, $include = false, $available = true)
    {
        $surcharge = new \XLite\Model\Order\Surcharge;

        $surcharge->setType($this->type);
        $surcharge->setCode($code);
        $surcharge->setValue($value);
        $surcharge->setInclude($include);
        $surcharge->setAvailable($available);
        $surcharge->setClass(get_called_class());

        $info = $this->getSurchargeInfo($surcharge);
        $surcharge->setName($info->name);

        $this->order->getSurcharges()->add($surcharge);
        $surcharge->setOwner($this->order);

        return $surcharge;
    }

    /**
     * Add order item surcharge 
     * 
     * @param \XLite\Model\OrderItem $item      Order item
     * @param string                 $code      Surcharge code
     * @param float                  $value     Value
     * @param boolean                $include   Include flag OPTIONAL
     * @param boolean                $available Availability flag OPTIONAL
     *  
     * @return \XLite\Model\OrderItem\Surcharge
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addOrderItemSurcharge(\XLite\Model\OrderItem $item, $code, $value, $include = false, $available = true)
    {
        $surcharge = new \XLite\Model\OrderItem\Surcharge;

        $surcharge->setType($this->type);
        $surcharge->setCode($code);
        $surcharge->setValue($value);
        $surcharge->setClass(get_called_class());

        $item->getSurcharges()->add($surcharge);

        return $surcharge;
    }

    // }}}
}
