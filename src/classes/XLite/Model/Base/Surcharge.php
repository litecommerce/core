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
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Model\Base;

/**
 * Surcharge
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @MappedSuperclass
 */
abstract class Surcharge extends \XLite\Model\AEntity
{
    /**
     * Surcharge type codes
     */
    const TYPE_TAX      = 'tax';
    const TYPE_DISCOUNT = 'discount';
    const TYPE_SHIPPING = 'shipping';
    const TYPE_HANDLING = 'handling';


    /**
     * Type names 
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $typeNames = array(
        self::TYPE_TAX      => 'Tax cost',
        self::TYPE_DISCOUNT => 'Discount',
        self::TYPE_SHIPPING => 'Shipping cost',
        self::TYPE_HANDLING => 'Handling cost',
    );

    /**
     * ID
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger")
     */
    protected $id;

    /**
     * Type
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="fixedstring", length="8")
     */
    protected $type;

    /**
     * Code
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="128")
     */
    protected $code;

    /**
     * Control class name
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="255")
     */
    protected $class;

    /**
     * Surcharge include flag
     *
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="boolean")
     */
    protected $include = false;

    /**
     * Surcharge evailability
     *
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="boolean")
     */
    protected $available = true;

    /**
     * Value
     *
     * @var   float
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="decimal", precision=14, scale=4)
     */
    protected $value;

    /**
     * Name (stored)
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length=255)
     */
    protected $name;

    /**
     * Get order
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function getOrder();

    /**
     * Get unque surcharge key 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getKey()
    {
        return $this->getType() . $this->getClass() . $this->name;
    }

    /**
     * Get modifier
     *
     * @return \XLite\Model\Order\Modifier
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getModifier()
    {
        $found = null;

        foreach ($this->getOrder()->getModifiers() as $modifier) {
            if ($modifier->isSurchargeOwner($this)) {
                $found = $modifier;
                break;
            }
        }

        return $found;
    }

    /**
     * Get surcharge info
     *
     * @return \XLite\DataSet\Transport\Surcharge
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getInfo()
    {
        $modifier = $this->getModifier();

        return $modifier
            ? $modifier->getSurchargeInfo($this)
            : null;
    }

    /**
     * Get name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getName()
    {
        $info = $this->getInfo();

        return $info ? $info->name : $this->name;
    }

    /**
     * Get type name 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTypeName()
    {
        return isset(static::$typeNames[$this->getType()])
            ? \XLite\Core\Translation::getInstance()->translate(static::$typeNames[$this->getType()])
            : null;
    }

    /**
     * Set value 
     * 
     * @param float $value Value
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.8
     */
    public function setValue($value)
    {
        $this->value = round($value, \XLite\Logic\Math::STORE_PRECISION);
    }
}
