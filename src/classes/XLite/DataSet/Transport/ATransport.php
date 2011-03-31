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
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

namespace XLite\DataSet\Transport;

/**
 * Abstract transport 
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
abstract class ATransport extends \XLite\Base implements \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * Data storage
     * 
     * @var   array
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $data = array();

    /**
     * Storage allowed keys list (cache)
     * 
     * @var   array
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $keys;

    /**
     * Define keys 
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract protected function defineKeys();

    /**
     * Map data
     * 
     * @param array $data Data
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function map(array $data)
    {
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
    }

    /**
     * Clear 
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function clear()
    {
        $this->data = array();
    }

    /**
     * Check transport complexity
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function check()
    {
        $result = true;

        foreach ($this->getKeys() as $k) {
            if (!isset($this->$k)) {
                $result = false;
                break;
            }
        }

        return $result;
    }

    /**
     * Get keys list
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getKeys()
    {
        if (!isset($this->keys)) {
            $this->keys = $this->defineKeys();
        }

        return $this->keys;
    }

    // {{{ Magic methods

    /**
     * Getter
     * 
     * @param string $name Storage cell name
     *  
     * @return mixed
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __get($name)
    {
        return in_array($name, $this->getKeys()) && isset($this->data[$name]) ? $this->data[$name] : null;
    }

    /**
     * Setter
     * 
     * @param string $name  Cell name
     * @param mixed  $value Value
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __set($name, $value)
    {
        if (in_array($name, $this->getKeys())) {
            $this->data[$name] = $value;
        }
    }

    /**
     * Check - cell is set or not
     * 
     * @param string $name Cell name
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __isset($name)
    {
        return in_array($name, $this->getKeys()) && isset($this->data[$name]);
    }

    /**
     * Unset cell
     * 
     * @param string $name Cell name
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __unset($name)
    {
        if (in_array($name, $this->getKeys()) && isset($this->data[$name])) {
            unset($this->data[$name]);
        }
    }

    /**
     * Sleep (serialization)
     * 
     * @return string 
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __sleep()
    {
        return serialize($this->data);
    }

    /**
     * Wakeup (unserialization)
     * 
     * @param string $serialized Seralized data
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __wakeup($serialized)
    {
        $this->clear();
        $this->map(unserialize($serialized));
    }

    // }}}

    // {{{ Countable

    /**
     * Count 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function count()
    {
        return count($this->getKeys());
    }

    // }}}

    // {{{ IteratorAggregate

    /**
     * Get iterator 
     * 
     * @return \Traversable
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getIterator()
    {
        $list = array();

        foreach ($this->getKeys() as $k) {
            $list[$k] = isset($this->$k) ? $this->$k : null;
        }

        return new ArrayIterator($list);
    }

    // }}}

    // {{{ ArrayAccess

    /**
     * Check - is offset exists or not
     * 
     * @param mixed $offset Offset
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }

    /**
     * Get offset value
     * 
     * @param mixed $offset Offset
     *  
     * @return mixed
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    /**
     * Set offset value
     * 
     * @param mixed $offset Offset
     * @param mixed $value  Value
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    /**
     * Unset offset
     * 
     * @param mixed $offset Offset
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }

    // }}}

}
