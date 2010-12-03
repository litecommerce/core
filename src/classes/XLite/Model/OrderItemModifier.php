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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Model;

/**
 * Order item modifier
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 *
 * @Entity
 * @Table (name="order_item_modifiers",
 *         indexes={
 *              @Index (name="ics", columns={"item_id", "code", "subcode"})
 *         }
 * )
 */
class OrderItemModifier extends \XLite\Model\AEntity
{
    /**
     * Primary key 
     * 
     * @var    int
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * 
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer")
     */
    protected $id;

    /**
     * Code
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * 
     * @Column (type="string", length="32")
     */
    protected $code;

    /**
     * Saved name
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="255")
     */
    protected $name;

    /**
     * Saved visibility flag
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="boolean")
     */
    protected $is_visible = 0;

    /**
     * Saved summable flag
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="boolean")
     */
    protected $is_summable = 1;

    /**
     * Subcode
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * 
     * @Column (type="string", length="32")
     */
    protected $subcode;

    /**
     * Surcharge
     *
     * @var    decimal
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="decimal", precision="14", scale="4")
     */
    protected $surcharge = 0.0000;

    /**
     * Item (relation)
     * 
     * @var    \XLite\Model\OrderItem
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * 
     * @ManyToOne  (targetEntity="XLite\Model\OrderItem", inversedBy="saved_modifiers")
     * @JoinColumn (name="item_id", referencedColumnName="item_id")
     */
    protected $owner;

    /**
     * Prepare subcode
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     * @PrePersist
     * @PreUpdate
     */
    protected function prepareSubcode()
    {
        if (!$this->getSubcode()) {
            $this->setSubcode($this->getCode());
        }
    }

}
