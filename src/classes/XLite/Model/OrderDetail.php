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
 * Order details
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 * @Entity
 * @Table (name="order_details")
 */
class OrderDetail extends \XLite\Model\AEntity
{
    /**
     * Order detail unique id
     * 
     * @var    mixed
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer")
     */
    protected $detail_id;

    /**
     * Record name (code)
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="255")
     */
    protected $name;

    /**
     * Record label
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="255")
     */
    protected $label = '';

    /**
     * Value
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="65536")
     */
    protected $value;

    /**
     * Relation to a order entity
     *
     * @var    \XLite\Model\Order
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\Order", inversedBy="details", fetch="LAZY")
     * @JoinColumn (name="order_id", referencedColumnName="order_id")
     */
    protected $order;

    /**
     * Get display record nName 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDisplayName()
    {
        return $this->getLabel() ?: $this->getName();
    }


}
