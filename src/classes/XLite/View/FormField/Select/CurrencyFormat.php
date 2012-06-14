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

namespace XLite\View\FormField\Select;

/**
 * Currency format selector
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class CurrencyFormat extends \XLite\View\FormField\Select\Regular
{
    /**
     * Parameters name for a widget
     */
    const PARAM_E = 'param_exp';

    /**
     * Exp. part replace element in format
     */
    const FORMAT_EXP = 'e';

    /**
     * Currency format variants
     */
    const FORMAT_SPACE_DOT      = '1 999.e';
    const FORMAT_COMMA_DOT      = '1,999.e';
    const FORMAT_SPACE_COMMA    = '1 999,e';
    const FORMAT_DOT_COMMA      = '1.999,e';

    /**
     * Default format
     */
    const FORMAT_DEFAULT        = self::FORMAT_SPACE_DOT;

    /**
     * Format -> thousand, decimal delimiters associations
     *
     * @var array
     */
    protected static $delimiters = array(
        self::FORMAT_SPACE_DOT    => array(' ', '.'),
        self::FORMAT_COMMA_DOT    => array(',', '.'),
        self::FORMAT_SPACE_COMMA  => array(' ', ','),
        self::FORMAT_DOT_COMMA    => array('.', ','),
    );

    /**
     * Thousand, decimal -> format associations
     *
     * @var array
     */
    protected static $formats = array(
        ' ' => array(
            '.' => self::FORMAT_SPACE_DOT,
            ',' => self::FORMAT_SPACE_COMMA,
        ),
        '.' => array(
            ',' => self::FORMAT_DOT_COMMA
        ),
        ',' => array(
            '.' => self::FORMAT_COMMA_DOT
        ),
    );

    /**
     * Return thousand and decimal delimiters array for a given format
     *
     * @param string $format
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getDelimiters($format = self::FORMAT_DEFAULT)
    {
        return isset(static::$delimiters[$format]) ? static::$delimiters[$format] : static::$delimiters[static::FORMAT_DEFAULT];
    }

    /**
     * Return a format for thousand, decimal delimiters
     *
     * @param string $thousandDelimiter
     * @param string $decimalDelimiter
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getFormat($thousandDelimiter, $decimalDelimiter)
    {
        $format = static::FORMAT_DEFAULT;

        if (isset(static::$formats[$thousandDelimiter])) {

            if (isset(static::$formats[$thousandDelimiter][$decimalDelimiter])) {

                $format = static::$formats[$thousandDelimiter][$decimalDelimiter];
            }
        }

        return $format;
    }

    /**
     * Return formatted element string
     *
     * @param string $elem
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function formatElement($elem)
    {
        return 0 == $this->getE()
            ? substr($elem, 0, -2)
            : str_replace(static::FORMAT_EXP, str_repeat('9', $this->getE()), $elem);
    }

    /**
     * Set widget params
     *
     * @param array $params Handler params
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setWidgetParams(array $params)
    {
        parent::setWidgetParams($params);

        foreach ($this->getWidgetParams() as $name => $param) {
            if (static::PARAM_OPTIONS == $name) {
                $param->setValue($this->getFormatOptions());
                break;
            }
        }
    }

    /**
     * Return exp. part number for a selector
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getE()
    {
        return $this->getParam(static::PARAM_E);
    }

    /**
     * Get default options list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultOptions()
    {
        return array();
    }

    /**
     * Get options list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFormatOptions()
    {
        return array_unique(
            array_map(
                array($this, 'formatElement'),
                array(
                    static::FORMAT_SPACE_DOT => static::FORMAT_SPACE_DOT,
                    static::FORMAT_COMMA_DOT => static::FORMAT_COMMA_DOT,
                    static::FORMAT_SPACE_COMMA => static::FORMAT_SPACE_COMMA,
                    static::FORMAT_DOT_COMMA => static::FORMAT_DOT_COMMA,
                )
            )
        );
    }

    /**
     * Define widget params
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            static::PARAM_E => new \XLite\Model\WidgetParam\Int('Exp part', 2),
        );
    }
}
