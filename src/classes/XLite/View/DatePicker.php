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
 * Date picker widget
 *
 */
class DatePicker extends \XLite\View\FormField
{
    /*
     * Constants: names of a widget parameters
     */
    const PARAM_FIELD     = 'field';
    const PARAM_VALUE     = 'value';
    const PARAM_HIGH_YEAR = 'highYear';
    const PARAM_LOW_YEAR  = 'lowYear';


    /**
     * Date format (PHP)
     *
     * @var string
     */
    protected $phpDateFormat = '%b %d, %Y';

    /**
     * Date format (javascript)
     *
     * @var string
     */
    protected $jsDateFormat = 'M dd, yy';


    /**
     * Get element class name
     *
     * @return string
     */
    public function getClassName()
    {
        $name = str_replace(
            array('[', ']'),
            array('-', ''),
            $this->getParam(self::PARAM_FIELD)
        );

        return preg_replace('/([A-Z])/Sse', '"-" . strtolower("\1")', $name);
    }

    /**
     * Get widget value as string
     *
     * @return string
     */
    public function getValueAsString()
    {
        return 0 >= $this->getParam(self::PARAM_VALUE)
            ? ''
            : \XLite\Core\Converter::formatDate($this->getParam(self::PARAM_VALUE), $this->phpDateFormat);
    }

    /**
     * Register CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'common/ui.datepicker.css';
        $list[] = 'common/datepicker.css';

        return $list;
    }

    /**
     * Register JS files
     *
     * @return array
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = 'common/datepicker.js';

        return $list;
    }


    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'common/datepicker.tpl';
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
            self::PARAM_FIELD     => new \XLite\Model\WidgetParam\String('Name of date field prefix', 'date'),
            self::PARAM_VALUE     => new \XLite\Model\WidgetParam\Int('Value of date field (timestamp)', null),
            self::PARAM_HIGH_YEAR => new \XLite\Model\WidgetParam\Int('The high year', date('Y', time()) - 1),
            self::PARAM_LOW_YEAR  => new \XLite\Model\WidgetParam\Int('The low year', 2035),
        );
    }

    /**
     * Return specific for JS code widget options
     *
     * @return array
     */
    protected function getDatePickerOptions()
    {
        return array(
            'dateFormat' => $this->jsDateFormat,
            'highYear'   => $this->getParam(static::PARAM_HIGH_YEAR),
            'lowYear'    => $this->getParam(static::PARAM_LOW_YEAR),
        );
    }
}
