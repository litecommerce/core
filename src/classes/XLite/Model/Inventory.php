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
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Model;

/**
 * Product inventory
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 *
 * @Entity
 * @Table  (name="inventory",
 *      indexes={
 *          @Index (name="id", columns={"id"})
 *      }
 * )
 */
class Inventory extends \XLite\Model\AEntity
{
    /**
     * Default amounts 
     */

    const AMOUNT_DEFAULT_INV_TRACK = 1000;
    const AMOUNT_DEFAULT_LOW_LIMIT = 10;

    /**
     * Inventory unique ID
     *
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger")
     */
    protected $inventoryId;

    /**
     * Is inventory tracking enabled or not
     *                            
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="boolean")
     */
    protected $enabled = true;

    /**
     * Amount 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="uinteger")
     */
    protected $amount = self::AMOUNT_DEFAULT_INV_TRACK;

    /**
     * Is low limit notification enabled or not
     *
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="boolean")
     */
    protected $lowLimitEnabled = false;

    /**
     * Low limit amount
     *
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="uinteger")
     */
    protected $lowLimitAmount = self::AMOUNT_DEFAULT_LOW_LIMIT;

    /**
     * Product (association)
     * 
     * @var    \XLite\Model\Product
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @OneToOne   (targetEntity="XLite\Model\Product", inversedBy="inventory")
     * @JoinColumn (name="id", referencedColumnName="product_id")
     */
    protected $product;

    /**
     * Check if low limit is reached; send mail to admin 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkForLowLimit()
    {
        return $this->getLowLimitEnabled() && $this->getAmount() < $this->getLowLimitAmount();
    }

    /**
     * Send notification to admin: product low limit is reached
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function sendLowLimitNotification()
    {
        // TODO: add functionality after the Mailer will be refactored
    }

    /**
     * Get list of cart items containing current product
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLockedItems()
    {
        return \XLite\Model\Cart::getInstance()->getItemsByProductId($this->getProduct()->getProductId());
    }

    /**
     * Return "locked" amount: items already added to the cart
     * 
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLockedAmount()
    {
        return \Includes\Utils\ArrayManager::sumObjectsArrayFieldValues($this->getLockedItems(), 'getAmount', true);
    }

    /**
     * Default qty value to show to customers
     * 
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultAmount()
    {
        return self::AMOUNT_DEFAULT_INV_TRACK;
    }

    /**
     * Increase / decrease product inventory amount
     *
     * @param integer $delta Amount delta
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function changeAmount($delta)
    {
        if ($this->getEnabled()) {

            // Change by delta
            $this->setAmount($this->getAmount() + $delta);

            // Check for low limit
            !$this->checkForLowLimit() ?: $this->sendLowLimitNotification();
        }
    }

    /**
     * Return product amount available to add to cart
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAvailableAmount()
    {
        return $this->getEnabled() ? max(0, $this->getAmount() - $this->getLockedAmount()) : $this->getDefaultAmount();
    }

    /**
     * Alias: is product in stock or not
     *
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isOutOfStock()
    {
        return $this->getEnabled() ? 0 >= $this->getAvailableAmount() : false;
    }
}
