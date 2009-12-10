<?php
//
// +----------------------------------------------------------------------+
// | PEAR :: PHPUnit                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2002 Sebastian Bergmann <sb@sebastian-bergmann.de>.    |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.00 of the PHP License,      |
// | that is available at http://www.php.net/license/3_0.txt.             |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
//
// $Id: Assert.php,v 1.1 2006/07/11 06:38:36 sheriff Exp $
//

/**
 * A set of assert methods.
 *
 * @package PHPUnit
 * @author  Sebastian Bergmann <sb@sebastian-bergmann.de>
 *          Based upon JUnit, see http://www.junit.org/ for details.
 */
class PHPUnit_Assert {
    /**
    * Asserts that two variables are equal.
    *
    * @param  mixed
    * @param  mixed
    * @param  string
    * @param  mixed
    * @access public
    */
    function assertEquals($expected, $actual, $message = '', $delta = 0) {
        if ((is_array($actual)  && is_array($expected)) ||
            (is_object($actual) && is_object($expected))) {
            if (is_array($actual) && is_array($expected)) {
                ksort($actual);
                ksort($expected);
            }

            $actual   = serialize($actual);
            $expected = serialize($expected);

            if (empty($message)) {
                $message = sprintf(
                  'expected %s, actual %s',

                  $expected,
                  $actual
                );
            }

            if (serialize($actual) != serialize($expected)) {
                return $this->fail($message);
            }
        }

        elseif (is_numeric($actual) && is_numeric($expected)) {
            if (empty($message)) {
                $_delta = ($delta != 0) ? ('+/- ' . $delta) : '';

                $message = sprintf(
                  'expected %s%s, actual %s',

                  $expected,
                  $_delta,
                  $actual
                );
            }

            if (!($actual >= ($expected - $delta) && $actual <= ($expected + $delta))) {
                return $this->fail($message);
            }
        }

        else {
            if (empty($message)) {
                $message = sprintf(
                  'expected %s, actual %s',

                  $expected,
                  $actual
                );
            }

            if ($actual != $expected) {
                return $this->fail($message);
            }
        }

        return $this->pass();
    }

    /**
    * Asserts that an object isn't null.
    *
    * @param  object
    * @param  string
    * @access public
    */
    function assertNotNull($object, $message = '') {
        if (empty($message)) {
            $message = 'expected NOT NULL, actual NULL';
        }

        if ($object === null) {
            return $this->fail($message);
        }

        return $this->pass();
    }

    /**
    * Asserts that an object is null.
    *
    * @param  object
    * @param  string
    * @access public
    */
    function assertNull($object, $message = '') {
        if (empty($message)) {
            $message = 'expected NULL, actual NOT NULL';
        }

        if ($object !== null) {
            return $this->fail($message);
        }

        return $this->pass();
    }

    /**
    * Asserts that two objects refer to the same object.
    * This requires the Zend Engine 2 (to work properly).
    *
    * @param  object
    * @param  object
    * @param  string
    * @access public
    */
    function assertSame($expected, $actual, $message = '') {
        if (empty($message)) {
            $message = 'expected two variables to refer to the same object';
        }

        if ($actual !== $expected) {
            return $this->fail($message);
        }

        return $this->pass();
    }

    /**
    * Asserts that two objects refer not to the same object.
    * This requires the Zend Engine 2 (to work properly).
    *
    * @param  object
    * @param  object
    * @param  string
    * @access public
    */
    function assertNotSame($expected, $actual, $message = '') {
        if (empty($message)) {
            $message = 'expected two variables to refer to different objects';
        }

        if ($actual === $expected) {
            return $this->fail($message);
        }

        return $this->pass();
    }

    /**
    * Asserts that a condition is true.
    *
    * @param  boolean
    * @param  string
    * @access public
    */
    function assertTrue($condition, $message = '') {
        if (empty($message)) {
            $message = 'expected true, actual false';
        }

        if (!$condition) {
            return $this->fail($message);
        }

        return $this->pass();
    }

    /**
    * Asserts that a condition is false.
    *
    * @param  boolean
    * @param  string
    * @access public
    */
    function assertFalse($condition, $message = '') {
        if (empty($message)) {
            $message = 'expected false, actual true';
        }

        if ($condition) {
            return $this->fail($message);
        }

        return $this->pass();
    }

    /**
    * Asserts that a string matches a given
    * regular expression.
    *
    * @param string
    * @param string
    * @param string
    * @access public
    * @author Sébastien Hordeaux <marms@marms.com>
    */
    function assertRegExp($expected, $actual, $message = '') {
        if (empty($message)) {
            $message = sprintf(
              '"%s" expected, actual "%s"',
              $expected,
              $actual
            );
        }

        if (!preg_match($expected, $actual)) {
            return $this->fail($message);
        }

        return $this->pass();
    }
        
    /**
    * Fails a test with the given message.
    *
    * @param  string
    * @access protected
    * @abstract
    */
    function fail($message = '') { /* abstract */ }

    /**
    * Passes a test.
    *
    * @access protected
    * @abstract
    */
    function pass() { /* abstract */ }
}
?>
