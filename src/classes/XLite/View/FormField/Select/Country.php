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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\View\FormField\Select;

/**
 * \XLite\View\FormField\Select\Country
 *
 */
class Country extends \XLite\View\FormField\Select\Regular
{
    /**
     * Widget param names
     */
    const PARAM_ALL               = 'all';
    const PARAM_STATE_SELECTOR_ID = 'stateSelectorId';
    const PARAM_STATE_INPUT_ID    = 'stateInputId';
    const PARAM_SELECT_ONE        = 'selectOne';

    /**
     * Display only enabled countries
     *
     * @var boolean
     */
    protected $onlyEnabled = true;

    /**
     * Save current form reference and sections list, and initialize the cache
     *
     * @param array $params Widget params OPTIONAL
     *
     * @return void
     */
    public function __construct(array $params = array())
    {
        if (!empty($params[static::PARAM_ALL])) {
            $this->onlyEnabled = false;
        }

        parent::__construct($params);
    }

    /**
     * Register JS files
     *
     * @return array
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = $this->getDir() . '/select_country.js';

        return $list;
    }

    /**
     * Pass the DOM Id fo the "States" selectbox
     * NOTE: this function is public since it's called from the View_Model_Profile_AProfile class
     *
     * @param string $selectorId DOM Id of the "States" selectbox
     * @param string $inputId    DOM Id of the "States" inputbox
     *
     * @return void
     */
    public function setStateSelectorIds($selectorId, $inputId)
    {
        $this->getWidgetParams(static::PARAM_STATE_SELECTOR_ID)->setValue($selectorId);
        $this->getWidgetParams(static::PARAM_STATE_INPUT_ID)->setValue($inputId);
    }


    /**
     * Define widget parameters
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            static::PARAM_ALL               => new \XLite\Model\WidgetParam\Bool('All', false),
            static::PARAM_STATE_SELECTOR_ID => new \XLite\Model\WidgetParam\String('State select ID', null),
            static::PARAM_STATE_INPUT_ID    => new \XLite\Model\WidgetParam\String('State input ID', null),
            static::PARAM_SELECT_ONE        => new \XLite\Model\WidgetParam\Bool('All', true),
        );
    }

    /**
     * Get selector default options list
     *
     * @return array
     */
    protected function getDefaultOptions()
    {
        $list = $this->onlyEnabled
            ? \XLite\Core\Database::getRepo('XLite\Model\Country')->findAllEnabled()
            : \XLite\Core\Database::getRepo('XLite\Model\Country')->findAllCountries();

        $options = array();

        foreach ($list as $country) {
            $options[$country->getCode()] = $country->getCountry();
        }

        return $options;
    }

    /**
     * getOptions
     *
     * @return array
     */
    protected function getOptions()
    {
        return $this->getParam(static::PARAM_SELECT_ONE)
            ? array('' => 'Select one...')
              + parent::getOptions()
            : parent::getOptions();
    }

    /**
     * getDefaultValue
     *
     * @return string
     */
    protected function getDefaultValue()
    {
        return \XLite\Core\Config::getInstance()->General->default_country;
    }

    /**
     * Return some data for JS external scripts if it is needed.
     *
     * @return null|array
     */
    protected function getFormFieldJSData()
    {
        return array(
            'statesList' => \XLite\Core\Database::getRepo('XLite\Model\Country')->findCountriesStates(),
            'stateSelectors' => array(
                'fieldId'           => $this->getFieldId(),
                'stateSelectorId'   => $this->getParam(static::PARAM_STATE_SELECTOR_ID),
                'stateInputId'      => $this->getParam(static::PARAM_STATE_INPUT_ID),
            ),
        );
    }

    /**
     * Get value container class
     *
     * @return string
     */
    protected function getValueContainerClass()
    {
        return parent::getValueContainerClass() . ' country-selector';
    }
}
