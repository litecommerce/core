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

// FIXME - to remove

/**
 * State selector
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_View_StateSelect extends XLite_View_FormField
{
    /**
     * Widget param names
     */

    const PARAM_FIELD_NAME = 'field';
    const PARAM_STATE      = 'state';
    const PARAM_FIELD_ID   = 'fieldId';
    const PARAM_ONCHANGE   = 'onchange';
    const PARAM_IS_LINKED  = 'isLinked';


    /**
     * States defined falg
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $statesDefined = false;

    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'common/select_state.tpl';
    }

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
            self::PARAM_FIELD_NAME => new XLite_Model_WidgetParam_String('Field name', ''),
            self::PARAM_FIELD_ID   => new XLite_Model_WidgetParam_String('Field ID', ''),
            self::PARAM_STATE      => new XLite_Model_WidgetParam_String('Value', ''),
            self::PARAM_ONCHANGE   => new XLite_Model_WidgetParam_String('onchange event handler', ''),
            self::PARAM_IS_LINKED  => new XLite_Model_WidgetParam_Bool('Linked with country selector', 0),
        );
    }

    /**
     * Return states list
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getStates()
    {
        return XLite_Model_CachingFactory::getObjectFromCallback(
            __METHOD__,
            'XLite_Model_State',
            'findAll'
        );
    }

    /**
     * Check - states list are defined as javascript array or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isDefineStates()
    {
        return $this->getParam(self::PARAM_IS_LINKED) && !self::$statesDefined;
    }

    /**
     * Get countries states 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCountriesStates()
    {
        self::$statesDefined = true;

        $countriesArray = array();

        $country = new XLite_Model_Country();
        $countries = $country->findAll('enabled = \'1\'');
        foreach ($countries as $country) {
            $state = new XLite_Model_State();
            $states = $state->findAll('country_code = \'' . $country->get('code') . '\'');
            if (is_array($states) && count($states) > 0) {
                $countriesArray[$country->get('code')] = array();
                foreach ($states as $state) {
                    $countriesArray[$country->get('code')][$state->get('state_id')] = $state->get('state');
                }
            }
        }

        return $countriesArray;
    }

    /**
     * Register JS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        if ($this->getParam(self::PARAM_IS_LINKED)) {
            $list[] = 'common/select_state.js';
        }

        return $list;
    }
}

