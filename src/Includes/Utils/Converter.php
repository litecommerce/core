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
 * @subpackage Includes_Utils
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace Includes\Utils;

/**
 * Converter 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
class Converter extends AUtils
{
    /**
     * Generate query string
     * 
     * @param array  $data      data to use
     * @param string $glue      string to add between param name and value
     * @param string $separator string to separate <name,value> pairs
     * @param string $quotes    char (string) to quote the value
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function buildQuery(array $data, $glue = '=', $separator = '&', $quotes = '')
    {
        $result = array();

        foreach ($data as $name => $value) {
            $result[] = $name . $glue . $quotes . $value . $quotes;
        }

        return implode($separator, $result);
    }

    /**
     * Parse string into array
     *
     * @param string $query     Query
     * @param string $glue      char to agglutinate "name" and "value"
     * @param string $separator char to agglutinate <"name", "value"> pairs
     * @param string $quotes    char to quote the "value" param
     *
     * @return string
     * @access public
     * @since  3.0
     */
    public static function parseQuery($query, $glue = '=', $separator = '&', $quotes = '')
    {
        $result = array();

        if (1 < count($parts = explode($separator, $query))) {
            foreach ($parts as $part) {
                if (1 < count($tokens = explode($glue, trim($part)))) {
                    $result[$tokens[0]] = trim($tokens[1], $quotes);
                }
            }
        }

        return $result;
    }

    /**
     * Remove trailing characters from string
     *
     * @param string $string string to prepare
     * @param string $chars  charlist to remove
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function trimTrailingChars($string, $chars)
    {
        return rtrim($string, $chars);
    }

    /**
     * Get formatted price
     * 
     * @param float $price value to format
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function formatPrice($price)
    {
        return sprintf('%.02f', round(doubleval($price), 2));
    }
}
