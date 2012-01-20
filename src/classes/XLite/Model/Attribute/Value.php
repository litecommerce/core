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

namespace XLite\Model\Attribute;

/**
 * Attribute value for a product 
 *
 * @see   ____class_see____
 * @since 1.0.14
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\Attribute\Value")
 * @Table  (name="attribute_values",
 *          uniqueConstraints={
 *              @UniqueConstraint (name="pair", columns={"productId", "attributeId"})
 *          }
 * )
 * @InheritanceType     ("JOINED")
 * @DiscriminatorColumn (name="type", type="uinteger")
 * @DiscriminatorMap    ({
 *      "1" = "XLite\Model\Attribute\Value\Number",
 *      "2" = "XLite\Model\Attribute\Value\Text",
 *      "3" = "XLite\Model\Attribute\Value\Boolean",
 *      "4" = "XLite\Model\Attribute\Value\Selector"
 * })
 */
abstract class Value extends \XLite\Model\AEntity
{
    /**
     * Value unique ID
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
     * ID of associated attribute
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.16
     *
     * @Column (type="integer")
     */
    protected $attributeId;

    /**
     * Relation to a product entity
     *
     * @var   \XLite\Model\Product
     * @see   ____var_see____
     * @since 1.0.16
     *
     * @ManyToOne  (targetEntity="XLite\Model\Product", inversedBy="attributeValues", fetch="LAZY")
     * @JoinColumn (name="productId", referencedColumnName="product_id")
     */
    protected $product;
}
