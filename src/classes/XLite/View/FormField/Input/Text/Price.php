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

namespace XLite\View\FormField\Input\Text;

/**
 * Price
 *
 */
class Price extends \XLite\View\FormField\Input\Text\Symbol
{
    const PARAM_CURRENCY = 'currency';

    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = $this->getDir() . '/input/price.css';

        return $list;
    }

    /**
     * Set widget params
     *
     * @param array $params Handler params
     *
     * @return void
     */
    public function setWidgetParams(array $params)
    {
        parent::setWidgetParams($params);

        $currency = $this->getCurrency();

        foreach ($this->getWidgetParams() as $name => $param) {

            if (static::PARAM_E == $name) {
                $param->setValue($currency->getE());

            } elseif (static::PARAM_THOUSAND_SEPARATOR == $name) {
                $param->setValue($currency->getThousandDelimiter());

            } elseif (static::PARAM_DECIMAL_SEPARATOR == $name) {
                $param->setValue($currency->getDecimalDelimiter());
            }
        }
    }

    /**
     * Get currency
     *
     * @return \XLite\Model\Currency
     */
    public function getCurrency()
    {
        return $this->getParam(static::PARAM_CURRENCY);
    }

    /**
     * Get currency symbol
     *
     * @return string
     */
    public function getSymbol()
    {
        return $this->getCurrency()->getSymbol() ?: $this->getCurrency()->getCode();
    }

    /**
     * Define widget params
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_CURRENCY => new \XLite\Model\WidgetParam\Object(
                'Currency',
                \XLite::getInstance()->getCurrency(),
                false,
                'XLite\Model\Currency'
            ),
        );
    }

    /**
     * Assemble classes
     *
     * @param array $classes Classes
     *
     * @return array
     */
    protected function assembleClasses(array $classes)
    {
        $classes = parent::assembleClasses($classes);

        $classes[] = 'price';

        return $classes;
    }

    /**
     * getCommonAttributes
     *
     * @return array
     */
    protected function getCommonAttributes()
    {
        $attributes = parent::getCommonAttributes();

        $attributes['value'] = $this->getCurrency()->formatValue($attributes['value']);

        return $attributes;
    }

    /**
     * Get mantis
     *
     * @return integer
     */
    protected function getE()
    {
        return $this->getCurrency()->getE();
    }

}

