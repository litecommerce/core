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

namespace XLite\View;

/**
 * Date picker widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
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
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $phpDateFormat = '%b %d, %Y';

    /**
     * Date format (javascript)
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $jsDateFormat = 'M dd, yy';


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'common/datepicker.tpl';
    }

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
            self::PARAM_FIELD     => new \XLite\Model\WidgetParam\String('Name of date field prefix', 'date'),
            self::PARAM_VALUE     => new \XLite\Model\WidgetParam\Int('Value of date field (timestamp)', null),
            self::PARAM_HIGH_YEAR => new \XLite\Model\WidgetParam\Int('The high year', date('Y', time()) - 1),
            self::PARAM_LOW_YEAR  => new \XLite\Model\WidgetParam\Int('The low year', 2035),
        );
    }


    /**
     * Get element class name 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * Get date format (javascript)
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDateFormat()
    {
        return $this->jsDateFormat;
    }

    /**
     * Get widget value as string 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getValueAsString()
    {
        return 0 >= $this->getParam(self::PARAM_VALUE)
            ? ''
            : strftime($this->phpDateFormat, $this->getParam(self::PARAM_VALUE));
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

        $list[] = 'common/ui.datepicker.css';
        $list[] = 'common/datepicker.css';

        return $list;
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

        $list[] = 'common/datepicker.js';

        return $list;
    }
}
