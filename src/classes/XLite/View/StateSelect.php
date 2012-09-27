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

namespace XLite\View;

// FIXME - to remove

/**
 * State selector
 *
 */
class StateSelect extends \XLite\View\FormField
{
    /**
     * Widget param names
     */

    const PARAM_FIELD_NAME = 'field';
    const PARAM_STATE      = 'state';
    const PARAM_FIELD_ID   = 'fieldId';
    const PARAM_IS_LINKED  = 'isLinked';
    const PARAM_CLASS_NAME = 'className';


    /**
     * States defined falg
     *
     * @var boolean
     */
    protected static $statesDefined = false;


    /**
     * Check - current state is custom state or not
     *
     * @return boolean
     */
    public function isCustomState()
    {
        return !$this->getParam(self::PARAM_STATE) || !$this->getParam(self::PARAM_STATE)->getStateId();
    }

    /**
     * Get current state value
     *
     * @return string
     */
    public function getStateValue()
    {
        return $this->getParam(self::PARAM_STATE) ? $this->getParam(self::PARAM_STATE)->getState() : '';
    }

    /**
     * Check - states list are defined as javascript array or not
     *
     * @return boolean
     */
    public function isDefineStates()
    {
        return $this->getParam(self::PARAM_IS_LINKED) && !self::$statesDefined;
    }

    /**
     * Get countries states
     *
     * @return array
     */
    public function getCountriesStates()
    {
        self::$statesDefined = true;

        return \XLite\Core\Database::getRepo('XLite\Model\Country')->findCountriesStates();
    }

    /**
     * Get javascript data block
     *
     * @return string
     */
    public function getJSDataDefinitionBlock()
    {
        $code = 'var CountriesStates = {};' . "\n";

        foreach ($this->getCountriesStates() as $countryCode => $states) {
            $code .= 'CountriesStates.' . $countryCode . ' = [' . "\n";
            $i = 1;
            $length = count($states);
            foreach ($states as $stateCode => $state) {
                $code .= '{state_code: "' . $stateCode . '", state: "' . $state . '"}'
                    . ($i == $length ? '' : ',')
                    . "\n";
                $i++;
            }
            $code .= '];' . "\n";
        }

        return $code;
    }

    /**
     * Check - specified state is selected or not
     *
     * @param \XLite\Model\State $state Specidied (current) state
     *
     * @return boolean
     */
    public function isStateSelected(\XLite\Model\State $state)
    {
        return $state->getStateId() == $this->getParam(self::PARAM_STATE)->getStateId();
    }


    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'common/select_state.tpl';
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
            self::PARAM_FIELD_NAME => new \XLite\Model\WidgetParam\String('Field name', ''),
            self::PARAM_FIELD_ID   => new \XLite\Model\WidgetParam\String('Field ID', ''),
            self::PARAM_STATE      => new \XLite\Model\WidgetParam\Object('Selected state', null, false, '\XLite\Model\State'),
            self::PARAM_CLASS_NAME => new \XLite\Model\WidgetParam\String('Class name', ''),
            self::PARAM_IS_LINKED  => new \XLite\Model\WidgetParam\Bool('Linked with country selector', 0),
        );
    }

    /**
     * Return states list
     *
     * @return array
     */
    protected function getStates()
    {
        $states = array();

        if (
            $this->getParam(self::PARAM_STATE)
            && $this->getParam(self::PARAM_STATE)->getCountry()
        ) {
            $states = $this->getParam(self::PARAM_STATE)->getCountry()->getStates();
        }

        return $states;;
    }
}
