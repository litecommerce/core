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

/**
 * Date selector widget
 * 
 * @package    XLite
 * @subpackage View
 * @see        ____class_see____
 * @since      3.0.0
 */
class XLite_View_Date extends XLite_View_FormField
{
    /*
     * Constants: names of a widget parameters
     */
    const PARAM_FIELD       = 'field';
    const PARAM_VALUE       = 'value';
    const PARAM_YEARS_RANGE = 'yearsRange';

    /**
     * Parameters for prefilling the form
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $params = array();    

    /**
     * Lower year 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $lowerYear = 2000;    

    /**
     * Higher year
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $higherYear = 2035;    

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_FIELD => new XLite_Model_WidgetParam_String(
                'Name of date field prefix', '', false
            ),
            self::PARAM_VALUE => new XLite_Model_WidgetParam_Int(
                'Value of date field (timestamp)', time(), false
            ),
            self::PARAM_YEARS_RANGE => new XLite_Model_WidgetParam_Int(
                'The range of years', null, false
            )
        );

        $this->widgetParams[self::PARAM_TEMPLATE]->setValue('common/date.tpl');
    }

    /**
     * Prefill form
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getField()
    {
        return $this->getParam(self::PARAM_FIELD);
    }

    /**
     * Get days list
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMonth()
    {
        return $this->params[$this->getParam(self::PARAM_FIELD) . 'Month'];
    }

    /**
     * Get name of a month
     * 
     * @param int $monthIndex Number of month (1..12)
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMonthString($monthIndex = 0)
    {
        return date('F', mktime(0, 0, 0, intval($monthIndex), 1, 2000));
    }

    /**
     * Get day 
     * 
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDay()
    {
        return @$this->params[$this->getParam(self::PARAM_FIELD) . 'Day'];
    }

    /**
     * Get year 
     * 
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getYear()
    {
        return @$this->params[$this->getParam(self::PARAM_FIELD) . 'Year'];
    }
}
