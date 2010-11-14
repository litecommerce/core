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
 * Shipping method model
 * 
 * @package    XLite
 * @subpackage Model
 * @see        ____class_see____
 * @since      3.0.0
 *
 * @Entity (repositoryClass="XLite\Model\Repo\Shipping\Method")
 * @Table  (name="shipping_methods")
 */
class Method extends \XLite\Model\Base\I18n
{
    /**
     * A unique ID of the method
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer", length="11", nullable=false)
     */
    protected $method_id;

    /**
     * Processor class name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="255", nullable=false)
     */
    protected $processor = '';
    
    /**
     * Carrier of the method (for instance, "UPS" or "USPS")
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="255", nullable=false)
     */
    protected $carrier = '';

    /**
     * Unique code of shipping method (within processor space)
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="255", nullable=false)
     */
    protected $code = '';

    /**
     * Whether the method is enabled or disabled
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer", length="1", nullable=false)
     */
    protected $enabled = '0';

    /**
     * A position of the method among other registered methods
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $position = 0;

    /**
     * Shipping rates (relation)
     * 
     * @var    \Doctrine\Common\Collections\ArrayCollection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\Shipping\Markup", mappedBy="shipping_method", cascade={"persist","remove"})
     */
    protected $shipping_markups;
}
