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

namespace XLite\Model\Category;

/**
 * Category quick flags
 *
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\Category\QuickFlags")
 * @Table  (name="category_quick_flags")
 */
class QuickFlags extends \XLite\Model\AEntity
{
    /**
     * Doctrine ID
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger")
     */
    protected $id;

    /**
     * Total number of subcategories
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $subcategories_count_all = 0;

    /**
     * Number of enabled subcategories
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $subcategories_count_enabled = 0;

    /**
     * Relation to a category entity
     *
     * @var \XLite\Model\Category
     *
     * @OneToOne   (targetEntity="XLite\Model\Category", inversedBy="quickFlags")
     * @JoinColumn (name="category_id", referencedColumnName="category_id")
     */
    protected $category;
}
