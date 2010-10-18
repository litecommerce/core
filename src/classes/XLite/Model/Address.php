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
 * Address model
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 * @Entity
 * @Table (name="profile_addresses")
 */
class Address extends \XLite\Model\AEntity
{
    /**
     * Unique id 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column (type="integer")
     */
    protected $address_id;

    /**
     * Profile Id
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer", length="11")
     */
    protected $profile_id;

    /**
     * Flag: is it a billing address
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer", length="1")
     */
    protected $is_billing = 0;

    /**
     * Flag: is it a shipping address
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer", length="1")
     */
    protected $is_shipping = 0;

    /**
     * Address type: residential/commercial
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="1")
     */
    protected $address_type = 'R';

    /**
     * Title
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="32")
     */
    protected $title = '';

    /**
     * First name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="128")
     */
    protected $firstname = '';

    /**
     * Last name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="128")
     */
    protected $lastname = '';

    /**
     * Phone
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="32")
     */
    protected $phone = '';

    /**
     * Street, number of building, apartment etc
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="255")
     */
    protected $street = '';

    /**
     * City
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="255")
     */
    protected $city = '';

    /**
     * State Id
     * 
     * @var    int
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer", length="11")
     */
    protected $state_id = 0;

    /**
     * Custom state
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="255")
     */
    protected $custom_state = '';

    /**
     * Country code
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="2")
     */
    protected $country_code = '';

    /**
     * Zip/postal code
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="32")
     */
    protected $zipcode = '';

    /**
     * Profile: many-to-one relation with profile entity
     * 
     * @var    \Doctrine\Common\Collections\ArrayCollection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @ManyToOne (targetEntity="XLite\Model\Profile")
     * @JoinColumn (name="profile_id", referencedColumnName="profile_id")
     */
    protected $profile;

    /**
     * getState 
     * 
     * @return \XLite\Model\State
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getState()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\State')->findOneByStateId($this->getStateId());
    }

    /**
     * getCountry 
     * 
     * @return \XLite\Model\Country
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCountry()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Country')->find($this->getCountryCode());
    }

    public static function getAddressFields()
    {
        return array(
            'firstname',
            'lastname',
            'phone',
            'street',
            'city',
            'zipcode',
            'state_id',
            'custom_state',
            'country_code'    
        );
    }

}
