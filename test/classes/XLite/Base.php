<?php

class XLite_Base
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

	public function __construct()
	{
		// Application
        global $xlite;

        if (isset($xlite) && is_object($xlite)) {
            $this->xlite = $xlite;
            $this->auth = $xlite->get('auth');
            $this->session = $xlite->get('session');
            $this->config = $xlite->get('config');
            $this->db = $xlite->get('db');
            $this->logger = $xlite->get('logger');
        } else {
            $xlite = true;
        }

		// MB used
		$GLOBALS['memory_usage'] = max(isset($GLOBALS['memory_usage']) ? $GLOBALS['memory_usage'] : 0, memory_get_usage()) / 1024 / 1024;
	}

	protected function _die($message)
	{
		// TODO - add logging

		die ($message);
	}

	/**
     * Return pointer to the single instance of current class
     *
	 * @param string $className name of derived class
	 *
     * @return XLite_Base_Singleton
     * @access protected
     * @see    ____func_see____
     * @since  3.0
     */
    protected static function _getInstance($className)
    {
        // Create new instance of the object (if it is not already created)
        if (!isset(self::$instances[$className])) {
            self::$instances[$className] = new $className();
        }

        return self::$instances[$className];
    }

	/**
    * Returns boolean property value named $name.
    * If no property found, returns null.
    */
    function is($name) // {{{
    {
        return (bool) $this->get($name);
    } // }}}

	/**
    * Returns property value named $name.
    * If no property found, returns null.
    * The value is returned by reference.
    */
    function get($name) // {{{
    {
        if (strpos($name, '.')) {
            $obj = $this;
            foreach (explode('.', $name) as $n) {
                if (isset($a)) {
                    unset($a);
                }
                if (is_array($obj)) {
                    $a = isset($obj[$n]) ? $obj[$n] : null;
                    $obj = $a;
                } else {
                    if (!method_exists($obj,'get')) {
                        if (($obj instanceof stdClass) && isset($obj->$n)) {
                            return $obj->$n;
                        }
                        return null;
                    }
                    $a = $obj->get($n);
                    $obj = $a;
                }
                if (is_null($obj)) {
                    return null;
                }
            }
            return $obj;
        }
        if (method_exists($this, 'get' . $name)) {
            $func = 'get' . $name;
            return $this->$func();
        }
        if (method_exists($this, 'is' . $name)) {
            $func = 'is' . $name;
			// echo get_class($this) . '---> ' . $func . '()' . "\n";
            return $this->$func();
        }
		return $this->$name;
    } // }}}

	function set($name, $value) // {{{
    {
        if (strpos($name, '.')) {
            $obj = $this;
            $names = explode('.', $name);
            $last = array_pop($names);
            foreach ($names as $n) {
                if (is_array($obj)) {
                    $obj = $obj[$n];
                } else {
                    $prevObj = $obj;
                    $obj = $obj->get($n);
                    $prevVal = $obj;
                    $prevProp = $n;
                }
                if (is_null($obj)) {
                    return;
                }
            }
            if (is_array($obj)) {
                $obj[$last] = $value;
                $prevObj->set($prevProp, $prevVal);
            } else {
                if (!method_exists($obj,'set')) {
                    $this->_die("No setter for " . get_class($this) . "." . $name);
                }
                $obj->set($last, $value);
            }
        } else if (method_exists($this, 'set' . $name)) {
            $func = 'set' . $name;
            $this->$func($value);
        } else {
            $this->$name = $value;
        }
    }

	function call($name) // {{{
    {
		$obj = $this;

        if (strpos($name, '.')) {

            $names = explode('.', $name);
            $last  = array_pop($names);

            foreach ($names as $n) {
				if (is_null($obj = $obj->get($n))) {
                    return null;
                }
            }
        }

        return call_user_func_array(array($obj, $name), array_shift(func_get_args()));;
    } // }}}

	/**
    * Maps the specified associative array to this object properties.
    *
    * @access public
    * @param array $assoc The associative array
    */
    function setProperties($assoc) // {{{
    {
        if (!is_array($assoc)) {
            $this->_die("argument must be an array");
        }
        foreach ($assoc as $key => $value) {
            $this->set($key, $value);
        }
    }

	public function __get($name)
	{
		return null;
	}

	public function __call($method, array $args = array())
    {
        $this->_die('Trying to call undefined class method; class - "' . get_class($this) . '", function - "' . $method . '"');
    }

}

