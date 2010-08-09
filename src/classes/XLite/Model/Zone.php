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
 * Zone model
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 * @Entity (repositoryClass="XLite\Model\Repo\Zone")
 * @Table (name="zones")
 */
class Zone extends AEntity
{
    /*
     * Zone element types
     */
    const ZONE_ELEMENT_COUNTRY = 'C';
    const ZONE_ELEMENT_STATE   = 'S';
    const ZONE_ELEMENT_TOWN    = 'T';
    const ZONE_ELEMENT_ZIPCODE = 'Z';
    const ZONE_ELEMENT_ADDRESS = 'A';

    /*
     * Zone types
     */
    const ZONE_SHIPPING = 'S';
    const ZONE_TAX      = 'T';

    /**
     * Zone unique id 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $zone_id;

    /**
     * Zone name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="64", nullable=false)
     */
    protected $zone_name;

    /**
     * Zone type (S - shipping zone, T - tax zone)
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="1", nullable=false)
     */
    protected $zone_type;

    /**
     * Zone elements (relation)
     * 
     * @var    \Doctrine\Common\Collections\ArrayCollection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @OneToMany (targetEntity="XLite\Model\ZoneElement", mappedBy="zone", cascade={"persist","remove"})
     */
    protected $zone_elements;

    /**
     * Get zone's countries list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCountries()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Zone')
            ->getElements($this->getZoneId(), self::ZONE_ELEMENT_COUNTRY);
    }

    /**
     * Get zone's states list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getStates()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Zone')
            ->getElements($this->getZoneId(), self::ZONE_ELEMENT_STATE);
    }

    /**
     * Get zone's city masks list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCities()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Zone')
            ->getElements($this->getZoneId(), self::ZONE_ELEMENT_TOWN);
    }

    /**
     * Get zone's zip code masks list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getZipCodes()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Zone')
            ->getElements($this->getZoneId(), self::ZONE_ELEMENT_ZIPCODE);
    }

    /**
     * Get zone's address masks list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAddresses()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Zone')
            ->getElements($this->getZoneId(), self::ZONE_ELEMENT_ADDRESS);
    }

    /**
     * getShippingZones 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShippingZones()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Zone')
            ->getZones(self::ZONE_SHIPPING);
    }

    /**
     * getTaxZones 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTaxZones()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Zone')
            ->getZones(self::ZONE_TAX);
    }

}
