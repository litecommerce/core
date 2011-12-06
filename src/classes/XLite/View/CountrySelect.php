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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\View;

// FIXME - to remove

/**
 * \XLite\View\CountrySelect
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class CountrySelect extends \XLite\View\FormField
{
    /**
     * Widget param names
     */

    const PARAM_ALL        = 'all';
    const PARAM_FIELD_NAME = 'field';
    const PARAM_COUNTRY    = 'country';
    const PARAM_FIELD_ID   = 'fieldId';
    const PARAM_CLASS_NAME = 'className';
    const PARAM_ALLOW_LABEL_COUNTRY = 'allowLabelCountry';


    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'common/select_country.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_ALL        => new \XLite\Model\WidgetParam\Bool('All', false),
            self::PARAM_FIELD_NAME => new \XLite\Model\WidgetParam\String('Field name', ''),
            self::PARAM_FIELD_ID   => new \XLite\Model\WidgetParam\String('Field ID', ''),
            self::PARAM_CLASS_NAME => new \XLite\Model\WidgetParam\String('Class name', ''),
            self::PARAM_COUNTRY    => new \XLite\Model\WidgetParam\String('Value', ''),
            self::PARAM_ALLOW_LABEL_COUNTRY => new \XLite\Model\WidgetParam\Bool('Allow label-based country selector', false),
        );
    }

    /**
     * Check - display enabled only countries or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isEnabledOnly()
    {
        return !$this->getParam(self::PARAM_ALL);
    }

    /**
     * Get selected value 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function getSelectedValue()
    {
        return $this->getParam(self::PARAM_COUNTRY);
    }

    /**
     * Check - if country code is selected option in "SELECT" tag.
     *
     * @param string $countryCode Code of country to check.
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function isSelectedCountry($countryCode)
    {
        $country = $this->getParam(self::PARAM_COUNTRY);

        if ('' == $country) {
            $country = \XLite\Core\Config::getInstance()->General->default_country;
        }

        return $country === $countryCode;
    }

    /**
     * Return countries list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCountries()
    {
        return $this->isEnabledOnly()
            ? \XLite\Core\Database::getRepo('XLite\Model\Country')->findAllEnabled()
            : \XLite\Core\Database::getRepo('XLite\Model\Country')->findAllCountries();
    }

    /**
     * Check - country selector is label-based
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function isLabelBasedSelector()
    {
        return $this->getParam(self::PARAM_ALLOW_LABEL_COUNTRY)
            && 1 == count($this->getCountries());
    }

    /**
     * Get one country 
     * 
     * @return \XLite\Model\Country
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function getOneCountry()
    {
        $list = $this->getCountries();

        return reset($list);
    }
}
