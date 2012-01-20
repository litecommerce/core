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
 * @since     1.0.14
 */

namespace XLite\Model;

/**
 * Attribute 
 *
 * @see   ____class_see____
 * @since 1.0.14
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\Attribute")
 * @Table  (name="attributes",
 *          uniqueConstraints={
 *              @UniqueConstraint (name="name", columns={"name"})
 *          }
 * )
 * @InheritanceType     ("JOINED")
 * @DiscriminatorColumn (name="type", type="uinteger")
 * @DiscriminatorMap    ({
 *      "1" = "XLite\Model\Attribute\Type\Number",
 *      "2" = "XLite\Model\Attribute\Type\Text",
 *      "3" = "XLite\Model\Attribute\Type\Boolean",
 *      "4" = "XLite\Model\Attribute\Type\Selector"
 * })
 */
abstract class Attribute extends \XLite\Model\Base\I18n
{
    /**
     * Attribute unique ID
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.14
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger")
     */
    protected $id;

    /**
     * Internal attribute name
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.14
     *
     * @Column (type="string", length="64")
     */
    protected $name;

    /**
     * Position in the list
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.14
     *
     * @Column (type="integer")
     */
    protected $pos = 0;

    /**
     * Relation to a group entity
     *
     * @var   \XLite\Model\Attribute\Group
     * @see   ____var_see____
     * @since 1.0.14
     *
     * @ManyToOne  (targetEntity="XLite\Model\Attribute\Group", inversedBy="attributes")
     * @JoinColumn (name="groupId", referencedColumnName="id")
     */
    protected $group;

    /**
     * Assigned product classes
     *
     * @var   \Doctrine\Common\Collections\ArrayCollection
     * @see   ____var_see____
     * @since 1.0.16
     *
     * @ManyToMany (targetEntity="XLite\Model\ProductClass", mappedBy="attributes")
     */
    protected $classes;

    /**
     * Type identifier
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.15
     */
    protected $typeName;

    /**
     * Return readable name for the attribute type
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.14
     */
    public function getTypeName()
    {
        if (!isset($this->typeName)) {
            $parts = explode('\\', get_class($this));

            $this->typeName = array_pop($parts);
        }

        return $this->typeName;
    }

    /**
     * Check attribute type
     *
     * @param string $type Type name
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    public function checkType($type)
    {
        return $type === $this->getTypeName();
    }

    /**
     * Get attribute value for the certain product
     *
     * @param \XLite\Model\Product $product Product to get value for
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.16
     */
    public function getValue(\XLite\Model\Product $product)
    {
        $object = $this->getAttrValueObject($product);

        return isset($object) ? $object->getValue() : $this->getDefaultValue();
    }

    /**
     * Set attribute value for the certain product
     *
     * @param \XLite\Model\Product $product Product to get value for
     * @param mixed                $value   Value to set
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.16
     */
    public function setValue(\XLite\Model\Product $product, $value)
    {
        $object = $this->getAttrValueObject($product);

        if (!isset($object)) {
            $class = '\XLite\Model\Attribute\Value\\' . $this->getTypeName();
            $object = new $class();
            $object->setAttributeId($this->getId());
            $object->setProduct($product);
            $product->addAttributeValues($object);
        }

        $object->setValue($value);
    }

    /**
     * Get attribute value object for the certain product
     *
     * @param \XLite\Model\Product $product Product to get value for
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getAttrValueObject(\XLite\Model\Product $product)
    {
        return \Includes\Utils\ArrayManager::searchInObjectsArray(
            $product->getAttributeValues(),
            'getAttributeId',
            $this->getId()
        );
    }
}
