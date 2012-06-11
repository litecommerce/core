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
     * Currency format variants
     */
    const FORMAT_SPACE_DOT      = '1 999.99';
    const FORMAT_COMMA_DOT      = '1,999.99';
    const FORMAT_SPACE_COMMA    = '1 999,99';
    const FORMAT_DOT_COMMA      = '1.999,99';

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
     * Get default options list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultOptions()
    {
        return array(
            static::FORMAT_SPACE_DOT    => static::FORMAT_SPACE_DOT,
            static::FORMAT_COMMA_DOT    => static::FORMAT_COMMA_DOT,
            static::FORMAT_SPACE_COMMA  => static::FORMAT_SPACE_COMMA,
            static::FORMAT_DOT_COMMA    => static::FORMAT_DOT_COMMA,
        );
    }
}
