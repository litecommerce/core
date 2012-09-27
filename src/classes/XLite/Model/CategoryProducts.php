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
 * Category
 *
 *
 * @Entity
 * @Table (name="category_products",
 *      uniqueConstraints={
 *          @UniqueConstraint (name="pair", columns={"category_id","product_id"})
 *      },
 *      indexes={
 *          @Index (name="orderby", columns={"orderby"})
 *      }
 * )
 */
class CategoryProducts extends \XLite\Model\AEntity
{
    /**
     * Primary key
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger", nullable=false)
     */
    protected $id;

    /**
     * Product position in the category
     *
     * @var integer
     *
     * @Column (type="integer", length=11, nullable=false)
     */
    protected $orderby = 0;

    /**
     * Relation to a category entity
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ManyToOne  (targetEntity="XLite\Model\Category", inversedBy="categoryProducts")
     * @JoinColumn (name="category_id", referencedColumnName="category_id")
     */
    protected $category;

    /**
     * Relation to a product entity
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ManyToOne  (targetEntity="XLite\Model\Product", inversedBy="categoryProducts")
     * @JoinColumn (name="product_id", referencedColumnName="product_id")
     */
    protected $product;
}
