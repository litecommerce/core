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

namespace XLite\Module\ProductOptions\Model;

/**
 * Product option group item
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 * @Entity (repositoryClass="XLite\Module\ProductOptions\Model\Repo\Option")
 * @Table (name="options")
 */
class Option extends \XLite\Model\Base\I18n
{
    const PERCENT_MODIFIER  = '%';
    const ABSOLUTE_MODIFIER = '$';


    /**
     * Option unique id 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column (type="integer")
     */
    protected $option_id;

    /**
     * Group option unique id 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer")
     */
    protected $group_id;

    /**
     * Sort position
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer")
     */
    protected $orderby = 0;

    /**
     * Price modifier 
     * 
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="decimal", precision="4", scale="12")
     */
    protected $price_modifier = 0.0000;

    /**
     * Price modifier type 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="1")
     */
    protected $modifier_type = self::PERCENT_MODIFIER;

    /**
     * Enabled 
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="boolean")
     */
    protected $enabled = false;

    /**
     * Option group (relation)
     * 
     * @var    \XLite\Module\ProductOptions\Model\OptionGroup
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @ManyToOne (targetEntity="XLite\Module\ProductOptions\Model\OptionGroup", inversedBy="options")
     * @JoinColumn (name="group_id", referencedColumnName="group_id")
     */
    protected $group;

    /**
     * Exceptions (relation)
     * 
     * @var    \Doctrine\Common\Collections\ArrayCollection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @OneToMany (targetEntity="XLite\Module\ProductOptions\Model\OptionException", mappedBy="option", cascade={"persist","remove"})
     */
    protected $exceptions;


}
