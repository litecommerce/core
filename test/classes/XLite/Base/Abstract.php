<?php

abstract class XLite_Base_Abstract
{
	protected function __construct()
	{
		// Application
        global $xlite;

        if (isset($xlite)) {
            $this->xlite = $xlite;
            $this->auth = $xlite->get('auth');
            $this->session = $xlite->get('session');
            $this->config = $xlite->get('config');
            $this->db = $xlite->get('db');
            $this->logger = $xlite->get('logger');
        } else {
            $xlite = true;
        }
	}

	public function _die($message)
	{
		// TODO - add logging

		die ($message);
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
            return $this->$func();
        }
        if (isset($this->$name)) {
            return $this->$name;
        }
        return null;
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
        if (strpos($name, '.')) {
            $obj = $this;
            $names = explode('.', $name);
            $last = array_pop($names);
            foreach ($names as $n) {
                $obj = $obj->get($n);
                if (is_null($obj)) {
                    return null;
                }
            }
            if (method_exists($obj, $last)) {
                $params = func_get_args();
                array_shift($params);
                return call_user_func_array(array($obj, $last), $params);
            }
            return null;
        } else if (method_exists($this, $name)){
            $params = func_get_args();
            array_shift($params);
            return call_user_func_array(array($this, $name), $params);
        }
        return null;
    } // }}}
}

