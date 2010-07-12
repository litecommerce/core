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
 * Country 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 * @Entity (repositoryClass="XLite\Model\Repo\Country")
 * @Table (name="countries")
 */
class Country extends \XLite\Model\AEntity
{

    /**
     * Country name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="50", nullable=false)
     */
    protected $country;

    /**
     * Country code 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Id
     * @Column (type="string", length="2", nullable=false, unique=true)
     */
    protected $code;

    /**
     * Country language
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="32", nullable=false)
     */
    protected $language = '';

    /**
     * Country languge charset
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="32", nullable=false)
     */
    protected $charset = 'iso-8859-1';

    /**
     * Enabled falg
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="boolean", nullable=false)
     */
    protected $enabled = false;

    /**
     * Country is EU memeber or not
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="boolean", nullable=false)
     */
    protected $eu_member = false;

    /**
     * Country shipping zone
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $shipping_zone = 0;

    /**
     * States (relation)
     * 
     * @var    \Doctrine\Common\Collections\ArrayCollection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @OneToMany (targetEntity="\XLite\Model\State", mappedBy="country", cascade={"persist","remove"})
     */
    protected $states;
}

