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

namespace XLite;

/**
 * Base class
 * FIXME - must be abstract
 * FIXME - must extends \XLite\the Base\SuperClass
 *
 */
class Base extends \XLite\Base\Singleton
{
    /**
     * Singletons accessible directly from each object (see the "__get" method)
     *
     * @var array
     */
    protected static $singletons = array(
        'xlite'    => 'XLite',
        'auth'     => '\XLite\Core\Auth',
        'session'  => '\XLite\Core\Session',
        'logger'   => '\XLite\Logger',
        'config'   => '\XLite\Core\Config',
        'layout'   => '\XLite\Core\Layout',
        'mailer'   => '\XLite\Core\Mailer',
    );


    /**
     * "Magic" getter. It's called when object property is not found
     * FIXME - backward compatibility
     *
     * @param string $name Property name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return isset(self::$singletons[$name])
            ? call_user_func(array(self::$singletons[$name], 'getInstance'))
            : null;
    }

    /**
     * "Magic" caller. It's called when object method is not found
     *
     * @param string $method Method to call
     * @param array  $args   Call arrguments OPTIONAL
     *
     * @return void
     */
    public function __call($method, array $args = array())
    {
        $this->doDie(
            'Trying to call undefined class method;'
            . ' class - "' . get_class($this) . '", function - "' . $method . '"'
        );
    }

    /**
     * Returns property value named $name. If no property found, returns null
     *
     * @param string $name Property name
     *
     * @return mixed
     */
    public function get($name)
    {
        // FIXME - devcode; must be removed
        if (strpos($name, '.')) {
            $this->doDie(get_class($this) . ': method get() - invalid name passed ("' . $name . '")');
        }

        $result = null;

        if (method_exists($this, 'get' . $name)) {
            $func = 'get' . $name;

            // 'get' + property name
            $result = $this->$func();

        } elseif (method_exists($this, 'is' . $name)) {
            $func = 'is' . $name;

            // 'is' + property name
            $result = $this->$func();

        } else {
            $result = $this->$name;
        }

        return $result;
    }

    /**
     * Set object property
     *
     * @param string $name  Property name
     * @param mixed  $value Property value
     *
     * @return void
     */
    public function set($name, $value)
    {
        if (method_exists($this, 'set' . $name)) {
            $func = 'set' . $name;

            // 'set' + property name
            $this->$func($value);

        } else {
            $this->$name = $value;
        }
    }

    /**
     * Returns boolean property value named $name. If no property found, returns null
     *
     * @param mixed $name Property name
     *
     * @return boolean
     */
    public function is($name)
    {
        return (bool) $this->get($name);
    }

    /**
     * Backward compatibility - the ability to use "<arg_1> . <arg_2> . ... . <arg_N>" chains in getters
     * FIXME - must be removed
     *
     * @param string $name List of params delimeted by the "." (dot)
     *
     * @return mixed
     */
    public function getComplex($name)
    {
        $obj = $this;

        foreach (explode('.', $name) as $part) {

            if (is_object($obj)) {

                if ($obj instanceof \stdClass) {
                    $obj = isset($obj->$part) ? $obj->$part : null;

                } elseif ($obj instanceof \XLite\Model\AEntity) {
                    $obj = $obj->{'get' . \XLite\Core\Converter::convertToCamelCase($part)}();

                } elseif ($obj instanceof \XLite\Core\CommonCell) {
                    $obj = $obj->$part;

                } else {
                    $obj = $obj->get($part);
                }

            } elseif (is_array($obj)) {
                $obj = isset($obj[$part]) ? $obj[$part] : null;
            }

            if (is_null($obj)) {
                break;
            }
        }

        return $obj;
    }

    /**
     * Backward compatibility - the ability to use "<arg_1> . <arg_2> . ... . <arg_N>" chains in setters
     * FIXME - must be removed
     *
     * @param string $name  List of params delimeted by the "." (dot)
     * @param mixed  $value Value to set
     *
     * @return void
     */
    public function setComplex($name, $value)
    {
        $obj   = $this;
        $names = explode('.', $name);
        $last  = array_pop($names);

        foreach ($names as $part) {

            if (is_array($obj)) {
                $obj = $obj[$part];

            } else {
                $prevObj = $obj;
                $prevProp = $part;
                $obj = $obj->get($prevProp);
                $prevVal = $obj;
            }

            if (is_null($obj)) {
                break;
            }
        }

        if (is_array($obj)) {
            $obj[$last] = $value;
            $prevObj->set($prevProp, $prevVal);

        } elseif (!is_null($obj)) {
            $obj->set($last, $value);
        }
    }

    /**
     * Backward compatibility - the ability to use "<arg_1> . <arg_2> . ... . <arg_N>" chains in getters
     * FIXME - must be removed
     *
     * @param string $name List of params delimeted by the "." (dot)
     *
     * @return boolean
     */
    public function isComplex($name)
    {
        return (bool) $this->getComplex($name);
    }

    /**
     * Maps the specified associative array to this object properties
     *
     * @param array $assoc Array(properties) to set
     *
     * @return void
     */
    public function setProperties(array $assoc)
    {
        foreach ($assoc as $key => $value) {
            $this->set($key, $value);
        }
    }
}
