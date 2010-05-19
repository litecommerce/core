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

/**
 * XLite_View_FormField_Select_Country 
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_View_FormField_Select_Country extends XLite_View_FormField_Select_Regular
{
    /**
     * Widget param names
     */

    const PARAM_ALL = 'all';
    const PARAM_STATE_SELECTOR = '';


    /**
     * searchCondition 
     * 
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected $searchCondition = null;

    /**
     * stateSelectorId 
     * 
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected $stateSelectorId = null;

    /**
     * stateInputId 
     * 
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected $stateInputId = null;


    /**
     * Return field template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getFieldTemplate()
    {
        return 'select_country.tpl';
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
            self::PARAM_ALL => new XLite_Model_WidgetParam_Bool('All', false),
        );
    }

    /**
     * getDefaultOptions
     *
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultOptions()
    {
        return XLite_Model_CachingFactory::getObjectFromCallback(
            __METHOD__,
            'XLite_Model_Country',
            'findAll',
            array($this->searchCondition)
        );
    }

    /**
     * getCountriesStates 
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getCountriesStates()
    {
        $statesInfo = XLite_Model_Factory::create('XLite_Model_Country')->getCountryStatesListSchema('enabled = \'1\'');

        foreach (XLite_Model_Factory::create('XLite_Model_State')->findAll() as $state) {
            $countryCode = $state->get('country_code');

            if (isset($statesInfo[$countryCode])) {
                $statesInfo[$countryCode][$state->get('state_id')] = $state->get('state');
            }
        }

        return $statesInfo;
    }

    /**
     * getStateSelectorId
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getStateSelectorId()
    {
        return $this->stateSelectorId;
    }

    /**
     * getStateInputId
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getStateInputId()
    {
        return $this->stateInputId;
    }


    /**
     * __construct 
     * 
     * @param array $params widget params
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __construct(array $params = array())
    {
        if (!empty($params[self::PARAM_ALL])) {
            $this->searchCondition = 'enabled = \'1\'';
        }

        parent::__construct($params);
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

        $list[] = $this->getDir() . '/select_country.js';

        return $list;
    }

    /**
     * Pass the DOM Id fo the "States" selectbox
     * NOTE: this function is public since it's called from the View_Model_Profile_Abstract class
     * 
     * @param string $selectorId DOM Id of the "States" selectbox
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function setStateSelectorIds($selectorId, $inputId)
    {
        $this->stateSelectorId = $selectorId;
        $this->stateInputId = $inputId;
    }
}

