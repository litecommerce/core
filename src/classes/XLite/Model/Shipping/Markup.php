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

namespace XLite\Model\Shipping;

/**
 * Shipping markup model
 * 
 * @package    XLite
 * @subpackage Model
 * @see        ____class_see____
 * @since      3.0.0
 * @Entity (repositoryClass="XLite\Model\Repo\Shipping\Markup")
 * @Table (name="shipping_markups")
 */
class Markup extends \XLite\Model\AEntity
{
    /**
     * A unique ID of the markup
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $markup_id;

    /**
     * Shipping method Id
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $method_id;

    /**
     * Zone Id
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $zone_id = 0;

    /**
     * Markup condition: min weight of products in the order
     * 
     * @var    decimal
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="decimal", precision="2", scale="12")
     */
    protected $min_weight = 0;

    /**
     * Markup condition: max weight of products in the order
     * 
     * @var    decimal
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="decimal", precision="2", scale="12")
     */
    protected $max_weight = 999999999;

    /**
     * Markup condition: min order subtotal
     * 
     * @var    decimal
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="decimal", precision="2", scale="12")
     */
    protected $min_total = 0;

    /**
     * Markup condition: max order subtotal
     * 
     * @var    decimal
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="decimal", precision="2", scale="12")
     */
    protected $max_total = 999999999;

    /**
     * Markup condition: min product items in the order
     * 
     * @var    decimal
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="decimal", precision="2", scale="12")
     */
    protected $min_items = 0;

    /**
     * Markup condition: max product items in the order
     * 
     * @var    decimal
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="decimal", precision="2", scale="12")
     */
    protected $max_items = 999999999;

    /**
     * Markup value: flat rate value
     * 
     * @var    decimal
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="decimal", precision="4", scale="12")
     */
    protected $markup_flat = 0;

    /**
     * Markup value: percent value
     * 
     * @var    decimal
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="decimal", precision="2", scale="12")
     */
    protected $markup_percent = 0;

    /**
     * Markup value: flat rate value per product item
     * 
     * @var    decimal
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="decimal", precision="4", scale="12")
     */
    protected $markup_per_item = 0;

    /**
     * Markup value: flat rate value per weight unit
     * 
     * @var    decimal
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="decimal", precision="4", scale="12")
     */
    protected $markup_per_weight = 0;

    /**
     * Shipping method (relation)
     * 
     * @var    \XLite\Model\Shipping\Method
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\Shipping\Method", inversedBy="shipping_markups")
     * @JoinColumn (name="method_id", referencedColumnName="method_id")
     */
    protected $shipping_method;

    /**
     * Zone (relation)
     * 
     * @var    \XLite\Model\Zone
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\Zone", inversedBy="shipping_markups")
     * @JoinColumn (name="zone_id", referencedColumnName="zone_id")
     */
    protected $zone;

    /**
     * Calculated markup value 
     * 
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $markupValue = 0;

    /**
     * getMarkupValue 
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMarkupValue()
    {
        return $this->markupValue;
    }

    /**
     * setMarkupValue 
     * 
     * @param integer $value Markup value
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setMarkupValue($value)
    {
        return $this->markupValue = $value;
    }

}
