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
 *
 * @Entity (repositoryClass="XLite\Model\Repo\Zone")
 * @Table  (name="zones")
 */
class Zone extends \XLite\Model\AEntity
{
    /**
     * Zone unique id 
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
    protected $zone_id;

    /**
     * Zone name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="64", nullable=false)
     */
    protected $zone_name = '';

    /**
     * Zone default flag
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer", length="1", nullable=false)
     */
    protected $is_default = 0;

    /**
     * Zone elements (relation)
     * 
     * @var    \Doctrine\Common\Collections\ArrayCollection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\ZoneElement", mappedBy="zone", cascade={"all"})
     */
    protected $zone_elements;

    /**
     * Shipping rates (relation)
     * 
     * @var    \Doctrine\Common\Collections\ArrayCollection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\Shipping\Markup", mappedBy="zone", cascade={"all"})
     */
    protected $shipping_markups;

    /**
     * Get zone's countries list
     *
     * @param boolean $excluded Flag: true - get countries except zone countries OPTIONAL
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getZoneCountries($excluded = false)
    {
        $zoneCountries = array();

        $countryCodes = $this->getElementsByType(\XLite\Model\ZoneElement::ZONE_ELEMENT_COUNTRY);

        if (!empty($countryCodes) || $excluded) {

            $allCountries = \XLite\Core\Database::getRepo('XLite\Model\Country')->findAllCountries();

            foreach ($allCountries as $key=>$country) {

                $condition = in_array($country->getCode(), $countryCodes);

                if ($condition && !$excluded || !$condition && $excluded) {
                    $zoneCountries[] = $country;
                }
            }
        }
    
        return $zoneCountries;
    }

    /**
     * Get zone's states list
     * 
     * @param boolean $excluded Flag: true - get states except zone states OPTIONAL
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getZoneStates($excluded = false)
    {
        $zoneStates = array();

        $stateCodes = $this->getElementsByType(\XLite\Model\ZoneElement::ZONE_ELEMENT_STATE);

        if (!empty($stateCodes) || $excluded) {

            $allStates = \XLite\Core\Database::getRepo('XLite\Model\State')->findAllStates();

            usort($allStates, array('\XLite\Model\Zone', 'sortStates'));
        
            foreach ($allStates as $key=>$state) {

                $condition = in_array($state->getCountryCode() . '_' . $state->getCode(), $stateCodes);

                if ($condition && !$excluded || !$condition && $excluded) {
                    $zoneStates[] = $state;
                }
            }
        }
    
        return $zoneStates;
    }

    /**
     * Comparison states function for usort()
     * 
     * @param \XLite\Model\State $a First state object
     * @param \XLite\Model\State $b Second state object
     *  
     * @return integer 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    static protected function sortStates($a, $b)
    {
        $aCountry = $a->getCountry()->getCountry();
        $aState = $a->getState();

        $bCountry = $b->getCountry()->getCountry();
        $bState = $b->getState();

        if ($aCountry == $bCountry && $aState == $bState) {
            $result = 0;

        } elseif ($aCountry == $bCountry) {
            $result = ($aState > $bState) ? 1 : -1;

        } else {
            $result = ($aCountry > $bCountry) ? 1 : -1;
        }

        return $result;
    }

    /**
     * Get zone's city masks list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getZoneCities()
    {
        return $this->getElementsByType(\XLite\Model\ZoneElement::ZONE_ELEMENT_TOWN);
    }

    /**
     * Get zone's zip code masks list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getZoneZipCodes()
    {
        return $this->getElementsByType(\XLite\Model\ZoneElement::ZONE_ELEMENT_ZIPCODE);
    }

    /**
     * Get zone's address masks list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getZoneAddresses()
    {
        return $this->getElementsByType(\XLite\Model\ZoneElement::ZONE_ELEMENT_ADDRESS);
    }

    /**
     * hasZoneElements 
     * 
     * @return boolean 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasZoneElements()
    {
        return $this->getZoneElements()->count() > 0;
    }

    /**
     * Returns the list of zone elements by specified element type
     * 
     * @param string $elementType Element type
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getElementsByType($elementType)
    {
        $result = array();

        if ($this->hasZoneElements()) {

            foreach ($this->getZoneElements() as $element) {
                if ($elementType == $element->getElementType()) {
                    $result[] = $element->getElementValue();
                }
            }
        }
    
        return $result;
    }

    /**
     * getZoneWeight 
     * 
     * @param mixed $address ____param_comment____
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getZoneWeight($address)
    {
        $zoneWeight = 0;

        $elementTypesData = \XLite\Model\ZoneElement::getElementTypesData();

        if ($this->hasZoneElements()) {

            foreach ($elementTypesData as $type => $data) {

                $found = false;

                $checkFuncName = 'checkZone' . $data['funcSuffix'];

                // Get zone elements
                $elements = $this->getElementsByType($type);

                if (!empty($elements)) {

                    // Check if address field belongs to the elements
                    $found = $this->$checkFuncName($address, $elements);

                    if ($found) {
                        // Increase the total zone weight
                        $zoneWeight += $data['weight'];

                    } elseif ($data['required']) {
                        // Break the comparing
                        $zoneWeight = 0;
                        break;
                    }
                }
            }
        }

        return $zoneWeight;
    }

    /**
     * checkZoneCountries 
     * 
     * @param mixed $address  ____param_comment____
     * @param mixed $elements ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkZoneCountries($address, $elements)
    {
        return !empty($elements)
            && isset($address['country'])
            && in_array($address['country'], $elements);
    }

    /**
     * checkZoneStates 
     * 
     * @param mixed $address  ____param_comment____
     * @param mixed $elements ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkZoneStates($address, $elements)
    {
        return empty($elements)
            || (
                isset($address['country'])
                && isset($address['state'])
                && in_array($address['country'] . '_' . $address['state'], $elements)
            );
    }

    /**
     * checkZoneZipCodes 
     * 
     * @param mixed $address  ____param_comment____
     * @param mixed $elements ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkZoneZipCodes($address, $elements)
    {
        return empty($elements)
            || (
                isset($address['zipcode'])
                && $this->checkMasks($address['zipcode'], $elements)
            );
    }

    /**
     * checkZoneCities 
     * 
     * @param mixed $address  ____param_comment____
     * @param mixed $elements ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkZoneCities($address, $elements)
    {
        return empty($elements)
            || (
                isset($address['city'])
                && $this->checkMasks($address['city'], $elements)
            );
    }

    /**
     * checkZoneAddresses 
     * 
     * @param mixed $address  ____param_comment____
     * @param mixed $elements ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkZoneAddresses($address, $elements)
    {
        return empty($elements)
            || (
                isset($address['address'])
                && $this->checkMasks($address['address'], $elements)
            );
    }

    /**
     * checkMasks 
     * 
     * @param mixed $value     ____param_comment____
     * @param mixed $masksList ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkMasks($value, $masksList)
    {
        $found = false;

        foreach ($masksList as $mask) {

            $mask = str_replace('%', '.*', preg_quote($mask));

            if (preg_match('/' . $mask . '/', $value)) {
                $found = true;
                break;
            }
        }

        return $found;
    }

    /**
     * Constructor
     *
     * @param array $data Entity properties
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct(array $data = array())
    {
        $this->zone_elements    = new \Doctrine\Common\Collections\ArrayCollection();
        $this->shipping_markups = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }
}
