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
     * @var \XLite\Model\AttributeGroup
     *
     * @ManyToOne  (targetEntity="XLite\Model\AttributeGroup", inversedBy="attributes")
     * @JoinColumn (name="attribute_group_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $attribute_group;

    /**
     * Option type
     *
     * @var string 
     *
     * @Column (type="fixedstring", length=1)
     */
    protected $type = self::TYPE_NUMBER;

    /**
     * Constructor
     *
     * @param array $data Entity properties OPTIONAL
     *
     * @return void
     */
    public function __construct(array $data = array())
    {
        $this->attribute_values = new \Doctrine\Common\Collections\ArrayCollection();

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
            self::TYPE_NUMBER   => 'Number',
            self::TYPE_TEXT     => 'Text',
            self::TYPE_CHECKBOX => 'Checkbox',
            self::TYPE_SELECT   => 'Select',
        );

        return isset($type)
            ? (isset($list[$type]) ? $list[$type] : null)
            : $list;
    }

}
