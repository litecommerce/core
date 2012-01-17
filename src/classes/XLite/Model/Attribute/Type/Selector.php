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
 * @since     1.0.15
 */

namespace XLite\Model\Attribute\Type;

/**
 * Selector
 *
 * @see   ____class_see____
 * @since 1.0.15
 *
 * @Entity
 * @Table  (name="attribute_type_selector")
 */
class Selector extends \XLite\Model\Attribute
{
    /**
     * Attribute default value
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.15
     *
     * @Column (type="integer", nullable=true)
     */
    protected $defaultValue;

    /**
     * Relation to attribute choices (only for "Selector" type)
     *
     * @var   \Doctrine\ORM\PersistentCollection
     * @see   ____var_see____
     * @since 1.0.14
     *
     * @OneToMany (targetEntity="XLite\Model\Attribute\Choice", mappedBy="attribute", fetch="LAZY", cascade={"all"})
     */
    protected $choices;
}
