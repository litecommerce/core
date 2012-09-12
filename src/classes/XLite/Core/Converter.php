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

namespace XLite\Core;

/**
 * Miscelaneous convertion routines
 *
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
     * Use this char as separator, if the default one is not set in the config
     */
    const CLEAN_URL_DEFAULT_SEPARATOR = '-';

    /**
     * Method name translation records
     *
     * @var array
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
     * @var array
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
     * @var boolean
     */
    protected static $isLocaleSet = false;

    /**
     * Convert a string like "test_foo_bar" into the camel case (like "TestFooBar")
     *
     * @param string $string String to convert
     *
     * @return string
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

    // {{{ URL routines

    /**
     * Compose URL from target, action and additional params
     *
     * @param string $target    Page identifier OPTIONAL
     * @param string $action    Action to perform OPTIONAL
     * @param array  $params    Additional params OPTIONAL
     * @param string $interface Interface script OPTIONAL
     *
     * @return string
     */
    public static function buildURL($target = '', $action = '', array $params = array(), $interface = null, $forceCleanURL = false)
    {
        $result = null;
        $cuFlag = LC_USE_CLEAN_URLS && (!\XLite::isAdminZone() || $forceCleanURL);

        if ($cuFlag) {
            $result = static::buildCleanURL($target, $action, $params);
        }

        if (!isset($result)) {
            if (!isset($interface) && !$cuFlag) {
                $interface = \XLite::getInstance()->getScript();
            }

            $result = \Includes\Utils\Converter::buildURL($target, $action, $params, $interface);
            if ($cuFlag && !$result) {
                $result = \XLite::getInstance()->getShopURL($result, null, array(), \Includes\Utils\URLManager::URL_OUTPUT_SHORT);
            }
        }

        return $result;
    }

    /**
     * Compose full URL from target, action and additional params
     *
     * @param string $target Page identifier OPTIONAL
     * @param string $action Action to perform OPTIONAL
     * @param array  $params Additional params OPTIONAL
     * @param string $interface Interface script OPTIONAL
     *
     * @return string
     */
    public static function buildFullURL($target = '', $action = '', array $params = array(), $interface = null)
    {
        return \XLite::getInstance()->getShopURL(static::buildURL($target, $action, $params, $interface));
    }

    /**
     * Compose clean URL
     *
     * @param string $target Page identifier OPTIONAL
     * @param string $action Action to perform OPTIONAL
     * @param array  $params Additional params OPTIONAL
     *
     * @return string
     */
    public static function buildCleanURL($target = '', $action = '', array $params = array())
    {
        $result = null;
        $urlParams = array();

        if ('product' === $target && !empty($params['product_id'])) {
            $product = \XLite\Core\Database::getRepo('\XLite\Model\Product')->find($params['product_id']);

            if (isset($product) && $product->getCleanURL()) {
                $urlParams[] = $product->getCleanURL() . '.html';

                unset($params['product_id']);
            }
        }

        if (('category' === $target || ('product' === $target && !empty($urlParams))) && !empty($params['category_id'])) {
            $category = \XLite\Core\Database::getRepo('\XLite\Model\Category')->find($params['category_id']);

            if (isset($category) && $category->getCleanURL()) {
                foreach (array_reverse($category->getPath()) as $node) {
                    if ($node->getCleanURL()) {
                        $urlParams[] = $node->getCleanURL();
                    }
                }
            }

            if (!empty($urlParams)) {
                unset($params['category_id']);
            }
        }

        static::buildCleanURLHook($target, $action, $params, $urlParams);

        if (!empty($urlParams)) {
            unset($params['target']);

            $result  = \Includes\Utils\ConfigParser::getOptions(array('host_details', 'web_dir_wo_slash'));
            $result .= '/' . implode('/', array_reverse($urlParams));

            if (!empty($params)) {
                $result .= '?' . http_build_query($params);
            }
        }

        return $result;
    }

    /**
     * Parse clean URL (<rest>/<last>/<url>(?:\.<ext="htm">(?:l)))
     *
     * @param string $url  Main part of a clean URL
     * @param string $last First part before the "url" OPTIONAL
     * @param string $rest Part before the "url" and "last" OPTIONAL
     * @param string $ext  Extension OPTIONAL
     *
     * @return void
     */
    public static function parseCleanUrl($url, $last = '', $rest = '', $ext = '')
    {
        $target = null;
        $params = array();

        foreach (static::getCleanURLBook($url, $last, $rest, $ext) as $possibleTarget => $class) {
            $entity = \XLite\Core\Database::getRepo($class)->findOneByCleanURL($url);

            if (isset($entity)) {
                $target = $possibleTarget;
                $params[$entity->getUniqueIdentifierName()] = $entity->getUniqueIdentifier();
            }
        }

        static::parseCleanURLHook($url, $last, $rest, $ext, $target, $params);

        return array($target, $params);
    }

    /**
     * Return current separator for clean URLs
     *
     * @return string
     */
    public static function getCleanURLSeparator()
    {
        $result = \Includes\Utils\ConfigParser::getOptions(array('clean_urls', 'default_separator'));

        if (empty($result) || !preg_match('/' . static::getCleanURLAllowedCharsPattern() . '/S', $result)) {
            $result = static::CLEAN_URL_DEFAULT_SEPARATOR;
        }

        return $result;
    }

    /**
     * Return pattern to check clean URLs
     *
     * @return string
     */
    public static function getCleanURLAllowedCharsPattern()
    {
        return '[\w_\-]+';
    }

    /**
     * Getter
     *
     * @param string $url  Main part of a clean URL
     * @param string $last First part before the "url" OPTIONAL
     * @param string $rest Part before the "url" and "last" OPTIONAL
     * @param string $ext  Extension OPTIONAL
     *
     * @return array
     */
    protected static function getCleanURLBook($url, $last = '', $rest = '', $ext = '')
    {
        $list = array(
            'product'  => '\XLite\Model\Product',
            'category' => '\XLite\Model\Category',
        );

        if ('htm' === $ext) {
            unset($list['category']);
        }

        return $list;
    }

    /**
     * Hook for modules
     *
     * @param string $target     Page identifier
     * @param string $action     Action to perform
     * @param array  $params     Additional params
     * @param array  &$urlParams Params to prepare
     *
     * @return void
     */
    protected static function buildCleanURLHook($target, $action, array $params, array &$urlParams)
    {
    }

    /**
     * Hook for modules
     *
     * @param string $url  Main part of a clean URL
     * @param string $last First part before the "url"
     * @param string $rest Part before the "url" and "last"
     * @param string $ext  Extension
     * @param string $target Target
     * @param array  $params Additional params
     *
     * @return void
     */
    protected static function parseCleanURLHook($url, $last, $rest, $ext, &$target, array &$params)
    {
        if ('product' === $target && !empty($last)) {
            $category = \XLite\Core\Database::getRepo('\XLite\Model\Category')->findOneByCleanURL($last);

            if (isset($category)) {
                $params['category_id'] = $category->getCategoryId();
            }
        }
    }

    // }}}

    /**
     * Convert to one-dimensional array
     *
     * @param array  $data    Array to flat
     * @param string $currKey Parameter for recursive calls OPTIONAL
     *
     * @return array
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
     */
    public static function generateRandomToken()
    {
        return md5(microtime(true) + rand(0, 1000000));
    }

    /**
     * Check - is GDlib enabled or not
     *
     * @return boolean
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
     */
    public static function isURL($url)
    {
        static $pattern = '(?:([a-z][a-z0-9\*\-\.]*):\/\/(?:(?:(?:[\w\.\-\+!$&\'\(\)*\+,;=]|%[0-9a-f]{2})+:)*(?:[\w\.\-\+%!$&\'\(\)*\+,;=]|%[0-9a-f]{2})+@)?(?:(?:[a-z0-9\-\.]|%[0-9a-f]{2})+|(?:\[(?:[0-9a-f]{0,4}:)*(?:[0-9a-f]{0,4})\]))(?::[0-9]+)?(?:[\/|\?](?:[\w#!:\.\?\+=&@!$\'~*,;\/\(\)\[\]\-]|%[0-9a-f]{2})*)?)';

        return is_string($url) && 0 < preg_match('/^' . $pattern . '$/Ss', $url);
    }

    /**
     * Check for empty string
     *
     * @param string $string String to check
     *
     * @return boolean
     */
    public static function isEmptyString($string)
    {
        return '' === $string || false === $string;
    }

    /**
     * Return class name without backslashes
     *
     * @param \XLite_Base $obj Object to get class name from
     *
     * @return string
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
     * Format day time
     *
     * @param integer $base                  UNIX time stamp OPTIONAL
     * @param string  $format                Format string OPTIONAL
     * @param boolean $convertToUserTimeZone True if time value should be converted according to the time zone OPTIONAL
     *
     * @return string
     */
    public static function formatDayTime($base = null, $format = null, $convertToUserTimeZone = true)
    {
        if (!$format) {
            $format = \XLite\Core\Config::getInstance()->General->time_format;
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
     * Prepare human-readable output for file size
     *
     * @param integer $size Size in bytes
     *
     * @return string
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
