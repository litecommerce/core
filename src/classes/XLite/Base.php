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
 * @subpackage Core
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite;

/**
 * Base class
 * FIXME - must be abstract (see Model/Config.php)
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Base
{
    /**
     * Array of instances for all derived classes
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0
     */
    protected static $instances = array();

    /**
     * Singletons accessible directly from each object (see the "__get" method)
     * 
     * @var    array
     * @access protected
     * @since  3.0
     */
    protected static $singletons = array(
        'xlite'    => 'XLite',
        'auth'     => '\XLite\Model\Auth',
        'session'  => '\XLite\Model\Session',
        'db'       => '\XLite\Model\Database',
        'logger'   => '\XLite\Logger',
        'config'   => '\XLite\Core\Config',
        'profiler' => '\XLite\Model\Profiler',
        'mm'       => '\XLite\Model\ModulesManager',
        'layout'   => '\XLite\Model\Layout',
    );


    /**
     * Protected constructor. It's empty now
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function __construct() 
    {
    }

    /**
     * Stop script execution 
     * 
     * @param string $message text to display
     *  
     * @return void
     * @access protected
     * @since  3.0
     */
    protected function doDie($message)
    {
        if (!($this instanceof \XLite\Logger)) {
            \XLite\Logger::getInstance()->log($message, PEAR_LOG_ERR);
        }

        if (
            $this instanceof XLite
            || \XLite::getInstance()->getOptions(array('log_details', 'suppress_errors'))
        ) {
            $message = 'Internal error. Contact the site administrator.';
        }

        die ($message);
    }


    /**
     * Method to access a singleton
     *
     * @return \XLite\Base
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getInstance()
    {
        $className = get_called_class();

        // Create new instance of the object (if it is not already created)
        if (!isset(self::$instances[$className])) {
            self::$instances[$className] = new $className();
        }

        return self::$instances[$className];
    }

    /**
     * "Magic" getter. It's called when object property is not found
     * FIXME - backward compatibility
     * 
     * @param string $name property name
     *  
     * @return mixed
     * @access public
     * @since  3.0
     */
    public function __get($name)
    {
        return isset(self::$singletons[$name]) ? call_user_func(array(self::$singletons[$name], 'getInstance')) : null;
    }

    /**
     * "Magic" caller. It's called when object method is not found
     * 
     * @param string $method method to call
     * @param array  $args   call arrguments
     *  
     * @return void
     * @access public
     * @since  3.0
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
     * @param string $name property name
     *  
     * @return mixed
     * @access public
     * @since  3.0
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
     * @param string $name  property name
     * @param mixed  $value property value
     *  
     * @return void
     * @access public
     * @since  3.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function is($name)
    {
        return (bool) $this->get($name);
    }

    /**
     * Backward compatibility - the ability to use "<arg_1> . <arg_2> . ... . <arg_N>" chains in getters
     * FIXME - must be removed
     * 
     * @param string $name list of params delimeted by the "." (dot)
     *  
     * @return mixed
     * @access public
     * @since  3.0
     */
    public function getComplex($name)
    {
        $obj = $this;

        foreach (explode('.', $name) as $part) {
            if (is_object($obj)) {
                if ($obj instanceof \stdClass) {
                    $obj = isset($obj->$part) ? $obj->$part : null;

                } elseif (
                    $obj instanceof \XLite\Model\AEntity
                    || $obj instanceof \XLite\Core\CommonCell
                ) {
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
     * @param string $name  list of params delimeted by the "." (dot)
     * @param mixed  $value value to set
     *  
     * @return void
     * @access public
     * @since  3.0
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
     * @param string $name list of params delimeted by the "." (dot)
     *
     * @return mixed
     * @access public
     * @since  3.0
     */
    public function isComplex($name)
    {
        return (bool) $this->getComplex($name);
    }

    /**
     * Maps the specified associative array to this object properties 
     * 
     * @param array $assoc array of properties to set
     *  
     * @return void
     * @access public
     * @since  3.0
     */
    public function setProperties(array $assoc)
    {
        foreach ($assoc as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * Language label translation short method
     * 
     * @param string $name      Label name
     * @param array  $arguments Substitution arguments
     * @param string $code      Language code
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function t($name, array $arguments = array(), $code = null)
    {
        return \XLite\Core\Translation::lbl($name, $arguments, $code);
    }

}

