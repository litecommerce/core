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

namespace XLite\Module\CDev\ProductOptions\Model;

/**
 * Order item options
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @Entity (repositoryClass="XLite\Module\CDev\ProductOptions\Model\Repo\OrderItemOption")
 * @Table  (name="order_item_options",
 *      indexes={
 *          @Index (name="item", columns={"item_id","orderby"})
 *      }
 * )
 */
class OrderItemOption extends \XLite\Model\AEntity
{
    /**
     * Primary key
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer", length="11", nullable=false)
     */
    protected $id;

    /**
     * Option id
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer", nullable=true)
     */
    protected $option_id = 0;

    /**
     * Group option id
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer", nullable=true)
     */
    protected $group_id;

    /**
     * Saved option name
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="255", nullable=false)
     */
    protected $name;

    /**
     * Saved option value
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="text")
     */
    protected $value = '';

    /**
     * Group option sort position (from XLite\Module\CDev\ProductOptions\Model\OptionGroup)
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $orderby;

    /**
     * Option group (relation)
     *
     * @var   \XLite\Module\CDev\ProductOptions\Model\OptionGroup
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Module\CDev\ProductOptions\Model\OptionGroup")
     * @JoinColumn (name="group_id", referencedColumnName="group_id")
     */
    protected $group;

    /**
     * Option (relation)
     *
     * @var   \XLite\Module\CDev\ProductOptions\Model\Option
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Module\CDev\ProductOptions\Model\Option")
     * @JoinColumn (name="option_id", referencedColumnName="option_id")
     */
    protected $option;

    /**
     * Order item (relation)
     *
     * @var   \XLite\Model\OrderItem
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\OrderItem", inversedBy="options")
     * @JoinColumn (name="item_id", referencedColumnName="item_id")
     */
    protected $order_item;

    
    /**
     * Clone order item option object
     *
     * @return \XLite\Model\AEntity
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function cloneEntity()
    {
        $entity = parent::cloneEntity();

        if ($this->getOption()) {
            $entity->setOption($this->getOption());
        }

        if ($this->getGroup()) {
            $entity->setGroup($this->getGroup());
        }

        return $entity;
    }

    /**
     * Get actual selected option name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getActualName()
    {
        return $this->getGroup() ? $this->getGroup()->getName() : $this->getName();
    }

    /**
     * Get actual selected option value
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getActualValue()
    {
        return ($this->getOption() && $this->getOption()->getOptionId())
            ? $this->getOption()->getName()
            : $this->getValue();
    }
}
