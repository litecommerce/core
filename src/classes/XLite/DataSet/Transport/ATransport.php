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

namespace XLite\DataSet\Transport;

/**
 * Abstract transport
 *
 */
abstract class ATransport extends \XLite\Base implements \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * Data storage
     *
     * @var array
     */
    protected $data = array();

    /**
     * Storage allowed keys list (cache)
     *
     * @var array
     */
    protected $keys;

    /**
     * Define keys
     *
     * @return array
     */
    abstract protected function defineKeys();

    /**
     * Map data
     *
     * @param array $data Data
     *
     * @return void
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
     */
    public function clear()
    {
        $this->data = array();
    }

    /**
     * Check transport complexity
     *
     * @return boolean
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
     */
    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }

    // }}}

}
