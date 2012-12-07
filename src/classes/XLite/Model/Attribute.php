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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Model;

/**
 * Attribute
 *
 * @Entity
 * @Table  (name="attributes")
 */
class Attribute extends \XLite\Model\Base\I18n
{
    /*
     * Attribute types
     */
    const TYPE_NUMBER   = 'N';
    const TYPE_TEXT     = 'T';
    const TYPE_CHECKBOX = 'C';
    const TYPE_SELECT   = 'S';

    /**
     * ID
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger")
     */
    protected $id;

    /**
     * Position
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $position = 0;

    /**
     * Default value 
     *
     * @var   text   
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="text")
     */
    protected $defaultValue = '';

    /**
     * Decimals
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer", length=1)
     */
    protected $decimals = 0;

    /**
     * Product class 
     *
     * @var \XLite\Model\ProductClass
     *
     * @ManyToOne  (targetEntity="XLite\Model\ProductClass", inversedBy="attributes")
     * @JoinColumn (name="product_class_id", referencedColumnName="id")
     */
    protected $product_class;

    /**
     * Attribute group 
     *
     * @var selfGroup
     *
     * @ManyToOne  (targetEntity="XLite\Model\AttributeGroup", inversedBy="attributes")
     * @JoinColumn (name="attribute_group_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $attribute_group;

    /**
     * Attribute options
     *
     * @var \Doctrine\Common\Collections\Collection
     *
     * @OneToMany (targetEntity="XLite\Model\AttributeOption", mappedBy="attribute")
     */
    protected $attribute_options;

    /**
     * Option type
     *
     * @var string 
     *
     * @Column (type="fixedstring", length=1)
     */
    protected $type = self::TYPE_TEXT;

    /**
     * Constructor
     *
     * @param array $data Entity properties OPTIONAL
     *
     * @return void
     */
    public function __construct(array $data = array())
    {
        $this->attribute_options = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }

    /**
     * Return number of products associated with this attribute
     *
     * @return integer
     */
    public function getProductsCount()
    {
        return count($this->getClass()->getProducts());
    }

    /**
     * Return list of types or type
     *
     * @param string $type Type OPTIONAL
     *
     * @return array | string
     */
    public static function getTypes($type = null)
    {
        $list = array(
            self::TYPE_TEXT     => 'Text',
            self::TYPE_NUMBER   => 'Number',
            self::TYPE_CHECKBOX => 'Checkbox',
            self::TYPE_SELECT   => 'Combo box',
        );

        return isset($type)
            ? (isset($list[$type]) ? $list[$type] : null)
            : $list;
    }

    /**
     * Return values associated with this attribute
     *
     * @return mixed
     */
    public function getAttributeValues()
    {
        $cnd = new \XLite\Core\CommonCell;
        $cnd->attribute = $this;

        return \XLite\Core\Database::getRepo($this->getAttributeValueClass())
            ->search($cnd);
    }

    /**
     * Return number of values associated with this attribute
     *
     * @return integer
     */
    public function getAttributeValuesCount()
    {
        $cnd = new \XLite\Core\CommonCell;
        $cnd->attribute = $this;

        return \XLite\Core\Database::getRepo($this->getAttributeValueClass())
            ->search($cnd, true);
    }

    /**
     * Set type
     *
     * @param string $type Type
     *
     * @return void
     */
    public function setType($type)
    {
        $types = static::getTypes();

        if (isset($types[$type])) {
            if (
                $this->type
                && $type != $this->type
                && $this->getId()
            ) {
                $this->setDefaultValue($this->defaultValue);
                foreach ($this->getAttributeOptions() as $option) {
                    \XLite\Core\Database::getEM()->remove($option);
                }
                foreach ($this->getAttributeValues() as $value) {
                    \XLite\Core\Database::getEM()->remove($value);
                }
            }
            $this->type = $type;
        }
    }

    /**
     * Set default type
     *
     * @param string $value Value
     *
     * @return void
     */
    public function setDefaultValue($value)
    {
        if (self::TYPE_NUMBER == $this->type) {
            $value = (float)$value;

        } elseif (self::TYPE_CHECKBOX == $this->type) {
            $value = (boolean)$value;

        }
        
        $this->defaultValue = $value;
    }

    /**
     * Get default value
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        $value = $this->defaultValue;
        if (self::TYPE_NUMBER == $this->type) {
            $value = (float)$value;

        } elseif (self::TYPE_CHECKBOX == $this->type) {
            $value = (boolean)$value;

        }
       
        return $value; 
    }

    /**
     * Return name of widget class
     *
     * @return string
     */
    public function getWidgetClass()
    {
        switch ($this->getType()) {
            case self::TYPE_NUMBER:
                $class = '\XLite\View\FormField\Input\Text\Float';
                break;

            case self::TYPE_TEXT:
                $class = '\XLite\View\FormField\Textarea\Simple';
                break;

            case self::TYPE_CHECKBOX:
                $class = '\XLite\View\FormField\Input\Checkbox\Enabled';
                break;

            case self::TYPE_SELECT:
                $class = '\XLite\View\FormField\Input\Text\AttributeOption';
                break;

        }

        return $class;
    }

    /**
     * Return name of value class
     *
     * @return string
     */
    public function getAttributeValueClass()
    {
        return '\XLite\Model\AttributeValue\AttributeValue'
            . (
                self::TYPE_SELECT == $this->getType()
                    ? 'Select'
                    : $this->getTypes($this->getType())
            ); 
    }

    /**
     * Return field comment
     *
     * @return string
     */
    public function getFieldComment()
    {
        return self::TYPE_NUMBER == $this->getType()
            ? $this->getUnit()
            : '';
    }

    /**
     * Set attribute value
     *
     * @param \XLite\Model\Product $product Product
     * @param mixed                $value   Value
     *
     * @return void
     */
    public function setAttributeValue(\XLite\Model\Product $product, $value)
    {
        $class = $this->getAttributeValueClass();
        $attributeValue = \XLite\Core\Database::getRepo($class)
            ->findOneBy(array('product' => $product, 'attribute' => $this));

        if (!$attributeValue) {
            !$attributeValue = new $class();
            $attributeValue->setProduct($product);
            $attributeValue->setAttribute($this);
            \XLite\Core\Database::getEM()->persist($attributeValue);
        }

        if (self::TYPE_SELECT == $this->getType()) {
            $value = trim($value);
            if ($value) {
                $attributeOption = \XLite\Core\Database::getRepo('XLite\Model\AttributeOption')
                    ->findOneByNameAndAttribute($value, $this);

                if (!$attributeOption) {
                    $attributeOption = new \XLite\Model\AttributeOption();
                    $attributeOption->setAttribute($this);
                    $attributeOption->setName($value);
                   \XLite\Core\Database::getEM()->persist($attributeOption);
                }
                $attributeValue->setAttributeOption($attributeOption);
            }

        } else {
            $attributeValue->setValue($value);    
        }
    }

    /**
     * Get attribute value
     *
     * @param \XLite\Model\Product $product  Product
     * @param bollean              $asString As string flag OPTIONAL
     *
     * @return mixed
     */
    public function getAttributeValue(\XLite\Model\Product $product, $asString = false)
    {
        $attributeValue = \XLite\Core\Database::getRepo($this->getAttributeValueClass())
            ->findOneBy(array('product' => $product, 'attribute' => $this));

        if (self::TYPE_SELECT == $this->getType()) {
            if ($attributeValue) {
                $attributeValue = $attributeValue->getAttributeOption();
            } else {
                $attributeValue = \XLite\Core\Database::getRepo('XLite\Model\AttributeOption')
                    ->findOneBy(array('defaultValue' => 1, 'attribute' => $this));
            }

            if (
                $attributeValue
                && $asString
            ) {
                $attributeValue = $attributeValue->getName();
            }

        } elseif ($attributeValue) {
            $attributeValue = $attributeValue->getValue();
        }

        if (is_null($attributeValue)) {
            $attributeValue = self::TYPE_SELECT == $this->getType()
                ? ''
                : $this->getDefaultValue();
        }

        if ($asString) {
            if(self::TYPE_NUMBER == $this->getType()) {
                $attributeValue = number_format($attributeValue, $this->getDecimals()) 
                    . ' ' . $this->getUnit();

            } elseif (self::TYPE_CHECKBOX == $this->getType()) {
                $attributeValue = static::t($attributeValue ? 'yes' : 'no');
            }
        }

        return $attributeValue;
    }
}
