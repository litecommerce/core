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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View;

/**
 * \XLite\View\AddressBook 
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @see        ____class_see____
 * @since      3.0.0
 */
class Address extends \XLite\View\Dialog
{
    /**
     * Widget parameter names
     */

    const PARAM_DISPLAY_MODE    = 'displayMode';
    const PARAM_ADDRESS         = 'address';
    const PARAM_DISPLAY_WRAPPER = 'displayWrapper';

   /**
    * Allowed display modes
    */

    const DISPLAY_MODE_TEXT = 'text';
    const DISPLAY_MODE_FORM = 'form';

    /**
     * Service constants for schema definition
     */
    const SCHEMA_CLASS    = 'class';
    const SCHEMA_LABEL    = 'label';
    const SCHEMA_REQUIRED = 'required';

    /**
     * schema 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $schema = array(
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
        'street' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Street',
            self::SCHEMA_REQUIRED => true,
        ),
        'city' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'City',
            self::SCHEMA_REQUIRED => true,
        ),
        'state_id' => array(
            self::SCHEMA_CLASS    => '\XLite\View\StateSelect',
            self::SCHEMA_LABEL    => 'State',
            self::SCHEMA_REQUIRED => true,
        ),
        'custom_state' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Other state',
            self::SCHEMA_REQUIRED => false,
        ),
        'zipcode' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Zip code',
            self::SCHEMA_REQUIRED => true,
        ),
        'country_code' => array(
            self::SCHEMA_CLASS    => '\XLite\View\CountrySelect',
            self::SCHEMA_LABEL    => 'Country',
            self::SCHEMA_REQUIRED => true,
        ),
/*  TODO: move to the shipping module where this field is required       
        'address_type' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Select\AddressType',
            self::SCHEMA_LABEL    => 'Address type',
            self::SCHEMA_REQUIRED => true,
        ),
 */
        'phone' => array(
            self::SCHEMA_CLASS    => '\XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Phone',
            self::SCHEMA_REQUIRED => true,
        ),
    );

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_DISPLAY_MODE => new \XLite\Model\WidgetParam\String(
                'Display mode', self::DISPLAY_MODE_TEXT, false
            ),
            self::PARAM_ADDRESS => new \XLite\Model\WidgetParam\Object(
                'Address object', null, false
            ),
            self::PARAM_DISPLAY_WRAPPER => new \XLite\Model\WidgetParam\Bool(
                'Display wrapper', false, false
            ),
        );
    }

    /**
     * getDefaultTemplate 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'address/wrapper.tpl';
    }

    /**
     * useBodyTemplate 
     * 
     * @return bool 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function useBodyTemplate()
    {
        return !$this->getParam(self::PARAM_DISPLAY_WRAPPER);
    }

    /**
     * getSchemaFields 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSchemaFields()
    {
        return $this->schema;
    }

    /**
     * getFieldValue 
     * 
     * @param string $fieldName Field name
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getFieldValue($fieldName, $processValue = false)
    {
        $result = '';
        
        $address = $this->getParam(self::PARAM_ADDRESS);

        $methodName = 'get' . \XLite\Core\Converter::getInstance()->convertToCamelCase($fieldName);

        if (method_exists($address, $methodName)) {
            $result = $address->$methodName();

            if (false !== $processValue) {
                switch($fieldName) {
                    case 'state_id': {
                        $result = $address->getState()->getState();
                        break;
                    }
                    case 'country_code': {
                        $result = $address->getCountry()->getCountry();
                    }
                }
            }
        }

        return $result;
    }

    /**
     * getProfileId 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProfileId()
    {
        return \XLite\Core\Request::getInstance()->profile_id;
    }

    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'Address';
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'address/' . $this->getParam(self::PARAM_DISPLAY_MODE);
    }

    /**
     * Get a list of JS files required to display the widget properly 
     * 
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        if ($this->getParam(self::PARAM_DISPLAY_WRAPPER)) {
            $list[] = 'address/controller.js';
            $list[] = 'form_field/select_country.js';
        }

        return $list;
    }

    /**
     * Register CSS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'address/style.css';

        return $list;
    }

}
