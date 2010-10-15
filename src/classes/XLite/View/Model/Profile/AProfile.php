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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View\Model\Profile;

/**
 * Profile model widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class AProfile extends \XLite\View\Model\AModel
{
    /**
     * Form sections 
     */
    
    const SECTION_BILLING  = 'billing';
    const SECTION_SHIPPING = 'shipping';

    /**
     * The "Shipping as billing address" checkbox
     */

    const FLAG_SHIP_AS_BILL = 'shipAsBill';


    /**
     * Schema of the "Billing/Shipping address" sections
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $addressSchema = array(
        'type' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Select\AddressType',
            self::SCHEMA_LABEL    => 'Address type',
            self::SCHEMA_REQUIRED => true,
        ),
        'title' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Select\Title',
            self::SCHEMA_LABEL    => 'Title',
        ),
        'firstname' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Firstname',
            self::SCHEMA_REQUIRED => true,
        ),
        'lastname' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Lastname',
            self::SCHEMA_REQUIRED => true,
        ),
        'company' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Company',
        ),
        'phone' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Phone',
            self::SCHEMA_REQUIRED => true,
        ),
        'fax' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Fax',
        ),
        'address' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Address',
            self::SCHEMA_REQUIRED => true,
        ),
        'city' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'City',
            self::SCHEMA_REQUIRED => true,
        ),
        'state' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Select\State',
            self::SCHEMA_LABEL    => 'State',
            self::SCHEMA_REQUIRED => true,
        ),
        'custom_state' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Other state',
            self::SCHEMA_REQUIRED => false,
        ),
        'country' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Select\Country',
            self::SCHEMA_LABEL    => 'Country',
            self::SCHEMA_REQUIRED => true,
        ),
        'zipcode' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Zip code',
            self::SCHEMA_REQUIRED => true,
        ),
    );


    /**
     * Add the checkbox to the fields list 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getShipAsBillSchema()
    {
        return array(
            self::FLAG_SHIP_AS_BILL => array(
                self::SCHEMA_CLASS => '\XLite\View\FormField\Input\Checkbox\ShipAsBill',
            ),
        );
    }

    /**
     * Return instance of the "Ship as bill" separator field
     * PHP_5_3
     * 
     * @return \XLite\View\FormField\Separator\ShippingAddress
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getShipAsBillWidget()
    {
        $class = '\XLite\View\FormField\Separator\ShippingAddress';
        $checkbox = $this->getFormField(self::SECTION_HIDDEN, self::FLAG_SHIP_AS_BILL);

        return new $class(
            array(
                self::SCHEMA_LABEL => $this->sections[self::SECTION_SHIPPING],
                $class::PARAM_SHIP_AS_BILL_CHECKBOX => $checkbox,
            )
        );
    }

    /**
     * Return list of the class-specific sections
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getProfileAddressSections()
    {
        return array(
            self::SECTION_BILLING  => 'Billing address',
            self::SECTION_SHIPPING => 'Shipping address',
        );
    }

    /**
     * This object will be used if another one is not pased
     *
     * @return \XLite\Model\AModel
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultModelObject()
    {
        return new \XLite\Model\Profile($this->getProfileId());
    }

    /**
     * Return name of web form widget class
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getFormClass()
    {
        return '\XLite\View\Form\Profile';
    }

    /**
     * Pass the DOM IDs of the "State" selectbox to the "CountrySelector" widget
     * 
     * @param array  &$fields widgets list
     * @param string $section current section
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setStateSelectorIds(array &$fields, $section)
    {
        $fields[$section . '_country']->setStateSelectorIds(
            $fields[$section . '_state']->getFieldId(),
            $fields[$section . '_custom_state']->getFieldId()
        );
    }

    /**
     * Define form field classes and values 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineFormFields()
    {
        parent::defineFormFields();

        // "Shipping as billing address" checkbox
        if (isset($this->formFields[self::SECTION_SHIPPING])) {
            $this->formFields[self::SECTION_SHIPPING][self::SECTION_PARAM_WIDGET] = $this->getShipAsBillWidget();
        }
    }


    /**
     * Modify address field schema for certain address type (billing or shipping) 
     * 
     * @param string $type address type
     *  
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getAddressSchema($type)
    {
        $result = array();

        foreach ($this->addressSchema as $key => $data) {
            $result[$type . '_' . $key] = $data;
        }

        return $result;
    }

    /**
     * Some JavaScript code to insert
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getInlineJSCode()
    {
        return $this->getWidget(array(), '\XLite\View\JS\StatesList')->getContent();
    }

    /**
     * Return text for the "Submit" button
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSubmitButtonLabel()
    {
        return \XLite\Model\Auth::getInstance()->isLogged() ? 'Update profile' : 'Create new account';
    }

    /**
     * Return list of the "Button" widgets
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFormButtons()
    {
        $result = parent::getFormButtons();
        $result['submit'] = new \XLite\View\Button\Submit(
            array(\XLite\View\Button\AButton::PARAM_LABEL => $this->getSubmitButtonLabel())
        );

        return $result;
    }

    /**
     * prepareRequestData
     *
     * @param array $data request data
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareRequestData(array $data)
    {
        $result = parent::prepareRequestData($data);

        if (!empty($result[self::FLAG_SHIP_AS_BILL])) {
            foreach (array_keys($this->addressSchema) as $key) {
                if (isset($result['billing_' . $key])) {
                    $result['shipping_' . $key] = $result['billing_' . $key];
                }
            }
        }

        return $result;
    }


    /**
     * Return model object to use
     *
     * @return \XLite\Model\AModel
     * @access public
     * @since  3.0.0
     */
    public function getModelObject()
    {
        $profile = parent::getModelObject();

        // Reset profile if it's not valid
        if (!\XLite\Model\Auth::getInstance()->checkProfile($profile)) {
            $profile = \XLite\Model\CachingFactory::getObject(__METHOD__, '\XLite\Model\Profile');
        }

        return $profile;
    }

    /**
     * Return fields list by the corresponding schema
     *
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getFormFieldsForSectionBilling()
    {
        $result = $this->getFieldsBySchema($this->getAddressSchema('billing'));

        // For country <-> state syncronization
        $this->setStateSelectorIds($result, 'billing');

        return $result;
    }

    /**
     * Return fields list by the corresponding schema
     *
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getFormFieldsForSectionShipping()
    {
        $result = $this->getFieldsBySchema($this->getAddressSchema('shipping'));

        // For country <-> state syncronization
        $this->setStateSelectorIds($result, 'shipping');

        return $result;
    }

    /**
     * Check if billing and shipping addresses are the same
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShipAsBillFlag()
    {
        return $this->isValid() && $this->getModelObject()->isSameAddress();
    }
    
    /**
     * getRequestProfileId 
     * 
     * @return int|null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRequestProfileId()
    {
        return \XLite\Core\Request::getInstance()->profile_id;
    }

    /**
     * Return current profile ID
     * 
     * @return int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProfileId()
    {
        return $this->getRequestProfileId() ?: \XLite\Core\Session::getInstance()->profile_id;
    }

    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = $this->getDir() . '/profile/addresses.css';

        return $list;
    }

    /**
     * Save current form reference and sections list, and initialize the cache
     *
     * @param array $params   widget params
     * @param array $sections sections list
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct(array $params = array(), array $sections = array())
    {
        $this->sections += $this->getProfileAddressSections();

        parent::__construct($params, $sections);

        $this->schemaHidden += $this->getShipAsBillSchema();
    }


    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'profile';
    
        return $result;
    }
}
