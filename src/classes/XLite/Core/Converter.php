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

namespace XLite\Core;

/**
 * Miscelaneous convertion routines
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Converter extends \XLite\Base\Singleton
{
    /**
     * Sizes 
     */
    const GIGABYTE = 1073741824;
    const MEGABYTE = 1048576;
    const KILOBYTE = 1024;


    /**
     * Method name translation records
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $to = array(
        'Q', 'W', 'E', 'R', 'T',
        'Y', 'U', 'I', 'O', 'P',
        'A', 'S', 'D', 'F', 'G',
        'H', 'J', 'K', 'L', 'Z',
        'X', 'C', 'V', 'B', 'N',
        'M',
    );

    /**
     * Method name translation patterns
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $from = array(
        '_q', '_w', '_e', '_r', '_t',
        '_y', '_u', '_i', '_o', '_p',
        '_a', '_s', '_d', '_f', '_g',
        '_h', '_j', '_k', '_l', '_z',
        '_x', '_c', '_v', '_b', '_n',
        '_m',
    );

    /**
     * Flag to avoid multiple setlocale() calls
     *
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $isLocaleSet = false;

    /**
     * Convert a string like "test_foo_bar" into the camel case (like "TestFooBar")
     *
     * @param string $string String to convert
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function convertToCamelCase($string)
    {
        return ucfirst(str_replace(self::$from, self::$to, strval($string)));
    }

    /**
     * Convert a string like "testFooBar" into the underline style (like "test_foo_bar")
     *
     * @param string $string String to convert
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function convertFromCamelCase($string)
    {
        return str_replace(self::$to, self::$from, lcfirst(strval($string)));
    }

    /**
     * Prepare method name
     *
     * @param string $string Underline-style string
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function prepareMethodName($string)
    {
        return str_replace(self::$from, self::$to, strval($string));
    }

    /**
     * Compose controller class name using target
     *
     * @param string $target Current target
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getControllerClass($target)
    {
        if (\XLite\Core\Request::getInstance()->isCLI()) {
            $zone = 'Console';

        } elseif (\XLite::isAdminZone()) {
            $zone = 'Admin';

        } else {
            $zone = 'Customer';
        }

        return '\XLite\Controller\\'
               . $zone
               . (empty($target) ? '' : '\\' . self::convertToCamelCase($target));
    }

    /**
     * Compose URL from target, action and additional params
     *
     * @param string $target    Page identifier OPTIONAL
     * @param string $action    Action to perform OPTIONAL
     * @param array  $params    Additional params OPTIONAL
     * @param string $interface Interface script OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function buildURL($target = '', $action = '', array $params = array(), $interface = null)
    {
        $url = isset($interface) ? $interface : \XLite::getInstance()->getScript();

        $urlParams = array();

        if ($target) {
            $urlParams['target'] = $target;
        }

        if ($action) {
            $urlParams['action'] = $action;
        }

        $params = $urlParams + $params;

        if (!empty($params)) {
            uksort($params, array(get_called_class(), 'sortURLParams'));
            $url .= '?' . http_build_query($params);
        }

        return $url;
    }

    /**
     * Compose full URL from target, action and additional params
     *
     * @param string $target Page identifier OPTIONAL
     * @param string $action Action to perform OPTIONAL
     * @param array  $params Additional params OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function buildFullURL($target = '', $action = '', array $params = array())
    {
        return \XLite::getInstance()->getShopURL(static::buildURL($target, $action, $params));
    }

    /**
     * Convert to one-dimensional array
     *
     * @param array  $data    Array to flat
     * @param string $currKey Parameter for recursive calls OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function convertTreeToFlatArray(array $data, $currKey = '')
    {
        $result = array();

        foreach ($data as $key => $value) {
            $key = $currKey . (empty($currKey) ? $key : '[' . $key . ']');
            $result += is_array($value) ? self::convertTreeToFlatArray($value, $key) : array($key => $value);
        }

        return $result;
    }

    /**
     * Generate random token (32 chars)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function generateRandomToken()
    {
        return md5(microtime(true) + rand(0, 1000000));
    }

    /**
     * Check - is GDlib enabled or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function isGDEnabled()
    {
        return function_exists('imagecreatefromjpeg')
            && function_exists('imagecreatetruecolor')
            && function_exists('imagealphablending')
            && function_exists('imagesavealpha')
            && function_exists('imagecopyresampled');
    }

    /**
     * Check if specified string is URL or not
     *
     * @param string $url URL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function isURL($url)
    {
        static $pattern = '(?:([a-z][a-z0-9\*\-\.]*):\/\/(?:(?:(?:[\w\.\-\+!$&\'\(\)*\+,;=]|%[0-9a-f]{2})+:)*(?:[\w\.\-\+%!$&\'\(\)*\+,;=]|%[0-9a-f]{2})+@)?(?:(?:[a-z0-9\-\.]|%[0-9a-f]{2})+|(?:\[(?:[0-9a-f]{0,4}:)*(?:[0-9a-f]{0,4})\]))(?::[0-9]+)?(?:[\/|\?](?:[\w#!:\.\?\+=&@!$\'~*,;\/\(\)\[\]\-]|%[0-9a-f]{2})*)?)';

        return is_string($url) && 0 < preg_match('/^' . $pattern . '$/Ss', $url);
    }

    /**
     * Return class name without backslashes
     *
     * @param \XLite_Base $obj Object to get class name from
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getPlainClassName(\XLite\Base $obj)
    {
        return str_replace('\\', '', get_class($obj));
    }

    /**
     * Format currency value
     *
     * @param mixed $price Currency unformatted value
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function formatCurrency($price)
    {
        if (isset($price)) {
            $config = \XLite\Core\Config::getInstance();
            $price = number_format(
                doubleval($price),
                2,
                $config->General->decimal_delim,
                $config->General->thousand_delim
            );
        }

        return $price;
    }

    /**
     * Convert value from one to other weight units
     *
     * @param float  $value   Weight value
     * @param string $srcUnit Source weight unit
     * @param string $dstUnit Destination weight unit
     *
     * @return float
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function convertWeightUnits($value, $srcUnit, $dstUnit)
    {
        $unitsInGrams = array(
            'lbs' => 453.59,
            'oz'  => 28.35,
            'kg'  => 1000,
            'g'   => 1,
        );

        $multiplier = $unitsInGrams[$srcUnit] / $unitsInGrams[$dstUnit];

        return $value * $multiplier;
    }

    /**
     * Format time
     *
     * @param integer $base                  UNIX time stamp OPTIONAL
     * @param string  $format                Format string OPTIONAL
     * @param boolean $convertToUserTimeZone True if time value should be converted according to the time zone OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function formatTime($base = null, $format = null, $convertToUserTimeZone = true)
    {
        if (!$format) {
            $config = \XLite\Core\Config::getInstance();
            $format = $config->General->date_format . ', ' . $config->General->time_format;
        }

        if ($convertToUserTimeZone) {
            $base = \XLite\Core\Converter::convertTimeToUser($base);
        }

        return static::getStrftime($format, $base);
    }

    /**
     * Format date
     *
     * @param integer $base                  UNIX time stamp OPTIONAL
     * @param string  $format                Format string OPTIONAL
     * @param boolean $convertToUserTimeZone True if time value should be converted according to the time zone OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function formatDate($base = null, $format = null, $convertToUserTimeZone = true)
    {
        if (!$format) {
            $format = \XLite\Core\Config::getInstance()->General->date_format;
        }

        if ($convertToUserTimeZone) {
            $base = \XLite\Core\Converter::convertTimeToUser($base);
        }

        return static::getStrftime($format, $base);
    }

    /**
     * Get strftime() with specified format and timestamp value
     *
     * @param string  $format Format string
     * @param integer $base   UNIX time stamp OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function getStrftime($format, $base = null)
    {
        static::setLocaleToUTF8();

        if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
            $format = str_replace('%e', '%#d', $format);
        }

        return isset($base) ? strftime($format, $base) : strftime($format);
    }

    /**
     * Attempt to set locale to UTF-8
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function setLocaleToUTF8()
    {
        if (
            !self::$isLocaleSet
            && preg_match('/(([^_]+)_?([^.]*))\.?(.*)?/', setlocale(LC_TIME, 0), $match)
            && !preg_match('/utf\-?8/i', $match[4])
        ) {
            setlocale(
                LC_TIME,
                $match[1] . '.UTF8',
                $match[1] . '.UTF-8',
                'en_US.UTF8',
                'en_US.UTF-8',
                'en_US',
                'ENG',
                'English',
                $match[0]
            );

            self::$isLocaleSet = true;
        }
    }

    /**
     * Sort URL parameters (callback)
     *
     * @param string $a First parameter
     * @param string $b Second parameter
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected static function sortURLParams($a, $b)
    {
        return ('target' == $b || ('action' == $b && 'target' != $a)) ? 1 : 0;
    }

    /**
     * Prepare human-readable output for file size
     *
     * @param integer $size Size in bytes
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function formatFileSize($size)
    {
        list($size, $suffix) = \Includes\Utils\Converter::formatFileSize($size);

        return $size . ' ' . ($suffix ? static::t($suffix) : '');
    }

    /**
     * Convert short size (2M, 8K) to human readable
     * 
     * @param string $size Shortsize
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.14
     */
    public static function convertShortSizeToHumanReadable($size)
    {
        $size = static::convertShortSize($size);

        if ($size > static::GIGABYTE) {
            $label = 'X GB';
            $size = round($size / static::GIGABYTE, 3);

        } elseif ($size > static::MEGABYTE) {
            $label = 'X MB';
            $size = round($size / static::MEGABYTE, 3);

        } elseif ($size > static::KILOBYTE) {
            $label = 'X kB';
            $size = round($size / static::KILOBYTE, 3);

        } else {
            $label = 'X bytes';
        }

        return \XLite\Core\Translation::lbl($label, array('value' => $size));
    }

    /**
     * Convert short size (2M, 8K) to normal size (in bytes)
     * 
     * @param string $size Short size
     *  
     * @return integer
     * @see    ____func_see____
     * @since  1.0.14
     */
    public static function convertShortSize($size)
    {
        if (preg_match('/^(\d+)([a-z])$/Sis', $size, $match)) {
            $size = intval($match[1]);
            switch ($match[2]) {
                case 'G':
                    $size *= 1024;

                case 'M':
                    $size *= 1024;

                case 'K':
                    $size *= 1024;

                default:
            }

        } else {
            $size = intval($size);
        }

        return $size;
    }

    // {{{ Time

    /**
     * Convert user time to server time
     *
     * @param integer $time User time
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function convertTimeToServer($time)
    {
        $server = new \DateTime();
        $server = $server->getTimezone()->getOffset($server);

        $user = new \DateTime();
        $timeZone = \XLite\Core\Config::getInstance()->General->time_zone ?: $user->getTimezone()->getName();
        $user->setTimezone(new \DateTimeZone($timeZone));
        $user = $user->getTimezone()->getOffset($user);

        $offset = $server - $user;

        return $time + $offset;
    }

    /**
     * Convert server time to user time
     *
     * @param integer $time Server time
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function convertTimeToUser($time)
    {
        $server = new \DateTime();
        $server = $server->getTimezone()->getOffset($server);

        $user = new \DateTime();
        $timeZone = \XLite\Core\Config::getInstance()->General->time_zone ?: $user->getTimezone()->getName();
        $user->setTimezone(new \DateTimezone($timeZone));
        $user = $user->getTimezone()->getOffset($user);

        $offset = $server - $user;

        return $time - $offset;
    }

    // }}}
}
