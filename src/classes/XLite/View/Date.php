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

/**
 * Date selector widget
 *
 */
class Date extends \XLite\View\FormField
{
    /*
     * Names of the widget parameters
     */

    const PARAM_FIELD       = 'field';
    const PARAM_VALUE       = 'value';
    const PARAM_YEARS_RANGE = 'yearsRange';


    /**
     * Parameters for prefilling the form
     *
     * @var array
     */
    protected $params = array();

    /**
     * Lower year
     *
     * @var integer
     */
    protected $lowerYear = 2000;

    /**
     * Higher year
     *
     * @var integer
     */
    protected $higherYear = 2035;


    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'common/date.tpl';
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
            self::PARAM_FIELD       => new \XLite\Model\WidgetParam\String('Name of date field prefix', ''),
            self::PARAM_VALUE       => new \XLite\Model\WidgetParam\Int('Value of date field (timestamp)', time()),
            self::PARAM_YEARS_RANGE => new \XLite\Model\WidgetParam\Int('The range of years', null),
        );
    }

    /**
     * Prefill form
     *
     * @return void
     */
    protected function initView()
    {
        parent::initView();

        $value = $this->getParam(self::PARAM_VALUE);

        if (is_null($value) || !is_numeric($value)) {
            $value = time();
        }

        $date = getdate($value);

        $this->params[$this->getParam(self::PARAM_FIELD) . 'Day']   = $date['mday'];
        $this->params[$this->getParam(self::PARAM_FIELD) . 'Month'] = $date['mon'];
        $this->params[$this->getParam(self::PARAM_FIELD) . 'Year']  = $date['year'];
    }


    /**
     * Get field prefix value
     *
     * @return string
     */
    protected function getField()
    {
        return $this->getParam(self::PARAM_FIELD);
    }

    /**
     * Get days list
     *
     * @return array
     */
    protected function getDays()
    {
        $daysArray = array();

        for ($i = 1; 31 >= $i; $i++) {
            $daysArray[$i] = $i == $this->getDay() ? 'selected' : '';
        }

        return $daysArray;

    }

    /**
     * Get months list
     *
     * @return array
     */
    protected function getMonths()
    {
        $monthsArray = array();

        for ($i = 1; 13 > $i; $i++) {
            $monthsArray[$i] = $i == $this->getMonth() ? 'selected' : '';
        }

        return $monthsArray;
    }

    /**
     * Get years list
     *
     * @return array
     */
    protected function getYears()
    {
        $yearsArray = array();

        $yearsRange = $this->getParam(self::PARAM_YEARS_RANGE);

        $higherYear = (!is_null($yearsRange) && intval($yearsRange) > 0)
            ? $this->lowerYear + intval($yearsRange)
            : $this->higherYear;

        for ($i = $this->lowerYear; $i <= $higherYear; $i++) {
            $yearsArray[$i] = $i == $this->getYear() ? 'selected' : '';
        }

        return $yearsArray;
    }

    /**
     * Get month
     *
     * @return integer
     */
    protected function getMonth()
    {
        return $this->params[$this->getParam(self::PARAM_FIELD) . 'Month'];
    }

    /**
     * Get name of a month
     *
     * @param integer $monthIndex Number of month (1..12) OPTIONAL
     *
     * @return string
     */
    protected function getMonthString($monthIndex = 0)
    {
        return date('F', mktime(0, 0, 0, intval($monthIndex), 1, 2000));
    }

    /**
     * Get day
     *
     * @return integer
     */
    protected function getDay()
    {
        return @$this->params[$this->getParam(self::PARAM_FIELD) . 'Day'];
    }

    /**
     * Get year
     *
     * @return integer
     */
    protected function getYear()
    {
        return @$this->params[$this->getParam(self::PARAM_FIELD) . 'Year'];
    }
}
