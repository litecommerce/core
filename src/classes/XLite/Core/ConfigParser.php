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
 * Abstract config parser
 *
 */
abstract class ConfigParser extends \XLite\Base
{
    /**
     * Parse both config files
     *
     * @param array|string $names Option names tree
     *
     * @return array|mixed
     */
    public static function getOptions($names = null)
    {
        $options = \Includes\Utils\ConfigParser::getOptions($names);

        if (is_array($options)) {
            foreach($options as $key => $value) {
                $options[$key] = static::prepare($value, $key, $names);
            }

        } elseif (is_array($names)) {
            $options = static::prepare($options, $names[1], $names[0]);

        } else {
            $options = static::prepare($options, $names);
        }

        return $options;
    }

    /**
     * Prepare option value
     *
     * @param string $value   Value
     * @param string $key     Key
     * @param string $section Section OPTIONAL
     *
     * @return string
     */
    protected static function prepare($value, $key, $section = null)
    {
        if (
            $value
            && isset($_SERVER['HTTP_HOST'])
            && 'host_details' == $section
            && (
                'http_host' == $key
                || 'https_host' == $key
            )
            && $value != $_SERVER['HTTP_HOST']
        ) {
            $domains = static::getOptions(array('host_details', 'domains'));
            $domains = explode(',', $domains);
            if (in_array($_SERVER['HTTP_HOST'], $domains)) {
                $value = $_SERVER['HTTP_HOST'];
            }
        }

        return $value;
    }
}
