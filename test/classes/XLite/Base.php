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

	public function __destruct()
	{
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



	/**
	 * "Magic" getter. It's called when object property is not found
	 * 
	 * @param string $name property name
	 *  
	 * @return mixed
	 * @access public
	 * @since  3.0
	 */
	public function __get($name)
	{
		return null;
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
        $this->_die('Trying to call undefined class method; class - "' . get_class($this) . '", function - "' . $method . '"');
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
            $this->_die(get_class($this) . ': method get() - invalid name passed ("' . $name . '")');
        }

        if (method_exists($this, 'get' . $name)) {
            $func = 'get' . $name;
            return $this->$func();
        }

        if (method_exists($this, 'is' . $name)) {
            $func = 'is' . $name;
            return $this->$func();
        }

        return $this->$name;
    }

    /**
     * Returns boolean property value named $name. If no property found, returns null
     * 
     * @param string $name property name
     *  
     * @return bool
     * @since  3.0
     */
    public function is($name)
    {
        return (bool) $this->get($name);
    }

	/**
	 * Backward compatibility - the ability to use "<arg_1> . <arg_2> . ... . <arg_N>" chains in getters
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
			$obj = is_array($obj) ? (isset($obj[$part]) ? $obj[$part] : null) : $obj->get($part);
			if (is_null($obj)) return null;
		}

		return $obj;
	}

	/**
     * Backward compatibility - the ability to use "<arg_1> . <arg_2> . ... . <arg_N>" chains in getters
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
}

