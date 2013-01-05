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
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\FastSession\Core;

/**
 * Registry storage 
 */
class Storage extends \XLite\Base
{
    /**
     * Storage prefix
     */
    const PREFIX = 'fs.';

    /**
     * Global prefix 
     * 
     * @var   string
     */
    protected $prefix;

    /**
     * Sub cell ID
     * 
     * @var   string
     */
    protected $id = '_';

    /**
     * TTL
     * 
     * @var   integer
     */
    protected $ttl = 0;

    /**
     * Exists flag (cache)
     * 
     * @var   boolean
     */
    protected $exists;

    /**
     * Constructor
     * 
     * @param string  $prefix Cell prefix
     * @param integer $ttl    TTL OPTIONAL
     *  
     * @return void
     */
    public function __construct($prefix, $ttl = 0)
    {
        // Force run database connector for cache driver creating
        \XLite\Core\Database::getInstance();

        $this->prefix = $prefix;
        $this->ttl = $ttl;
    }

    /**
     * Set subcell ID 
     * 
     * @param string $id Sub cell ID
     *  
     * @return void
     */
    public function setID($id)
    {
        $this->id = $id;
        $this->initialize();
    }

    /**
     * Get sub cell ID 
     * 
     * @return string
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Getter
     * 
     * @param string $name Cell name
     *  
     * @return mixed
     */
    public function __get($name)
    {
        $this->updateTTL();
        $value = $this->getCacheDriver()->fetch($this->assembleCellName($name));

        return $value ? @unserialize($value) : null;
    }

    /**
     * Setter
     * 
     * @param string $name  Cell name
     * @param mixed  $value Cell value
     *  
     * @return void
     */
    public function __set($name, $value)
    {
        $this->updateTTL();
        $this->saveRegistry($name);
        $this->getCacheDriver()->save($this->assembleCellName($name), serialize($value));
    }

    /**
     * Check cell is-set
     * 
     * @param string $name Cell name
     *  
     * @return boolean
     */
    public function __isset($name)
    {
        $this->updateTTL();

        return !is_null($this->__get($name));
    }

    /**
     * Remove cell
     * 
     * @param string $name Cell name
     *  
     * @return void
     */
    public function __unset($name)
    {
        $this->updateTTL();
        $this->deleteRegistry($name);
        $this->getCacheDriver()->delete($this->assembleCellName($name));
    }

    /**
     * Get all cells as array 
     * 
     * @return array
     */
    public function getArray()
    {
        $data = array();

        foreach ($this->getRegistry() as $name) {
            $value = $this->__get($name);
            if (isset($value)) {
                $data[$name] = $value;
            }
        }

        return $data;
    }

    /**
     * Remove storage
     * 
     * @return void
     */
    public function remove()
    {
        $this->deleteAll();

        $this->getCacheDriver()->delete($this->assembleTTLCell());
        $this->getCacheDriver()->delete($this->assembleRegistryCell());

        $registry = $this->getGlobalRegistry();
        if (isset($registry[$this->getID()])) {
            unset($registry[$this->getID()]);
            $this->setGlobalRegistry($registry);
        }
    }

    /**
     * Delete all cells
     * 
     * @return void
     */
    public function deleteAll()
    {
        foreach ($this->getRegistry() as $name) {
            $this->getCacheDriver()->delete($this->assembleCellName($name));
        }
        $this->setRegistry(array());
    }

    /**
     * Check - storage exists or not
     * 
     * @return boolean
     */
    public function isExists()
    {
        if (!isset($this->exists)) {
            $this->exists = (bool)$this->getCacheDriver()->fetch($this->assembleTTLCell());
        }

        return $this->exists;
    }

    /**
     * Clear garbage (garbage collector)
     * 
     * @return void
     */
    public function clearGarbage()
    {
        foreach ($this->getGlobalRegistry() as $id) {
            $storage = new static($this->prefix, $this->ttl);
            $storage->setID($id);
            if (!$storage->isExists()) {
                $storage->remove();
            }
        }
    }

    /**
     * Initialize storage
     * 
     * @return void
     */
    protected function initialize()
    {
        $this->exists = null;

        if ($this->isExists()) {
            $this->getCacheDriver()->save($this->assembleTTLCell(), 'TTL', $this->ttl);

        } else {
            $this->remove();
        }
    }

    /**
     * Update TTL service record
     * 
     * @return void
     */
    protected function updateTTL()
    {
        if (!$this->isExists()) {
            $this->getCacheDriver()->save($this->assembleTTLCell(), 'TTL', $this->ttl);

            $registry = $this->getGlobalRegistry();
            if (!isset($registry[$this->getID()])) {
                $registry[$this->getID()] = true;
                $this->setGlobalRegistry($registry);
            }

            $this->exists = true;
        }
    }

    /**
     * Assemble global registry cell 
     * 
     * @return string
     */
    protected function assembleGlobalRegistryCell()
    {
        return static::PREFIX . $this->prefix . '_registry';
    }

    /**
     * Assemble root storage cell prefix 
     * 
     * @return string
     */
    protected function assembleRootCellPrefix()
    {
        return static::PREFIX . $this->prefix . '.' . $this->id;
    }

    /**
     * Assemble storage registry cell 
     * 
     * @return string
     */
    protected function assembleRegistryCell()
    {
        return $this->assembleRootCellPrefix() . '_registry';
    }

    /**
     * Assemble storage TTL cell 
     * 
     * @return string
     */
    protected function assembleTTLCell()
    {
        return $this->assembleRootCellPrefix() . '_ttl';
    }

    /**
     * Assemble storage cell name 
     * 
     * @param string $name Cell name
     *
     * @return string
     */
    protected function assembleCellName($name)
    {
        return $this->assembleRootCellPrefix() . '.' . $name;
    }

    /**
     * Get cache driver 
     * 
     * @return \Doctrine\Common\Cache\AbstractCache
     */
    protected function getCacheDriver()
    {
        return \XLite\Core\Database::getCacheDriver();
    }

    // {{{ Registry

    /**
     * Save cell into registry 
     * 
     * @param string $name Cell name
     *  
     * @return void
     */
    protected function saveRegistry($name)
    {
        $registry = $this->getRegistry();
        if (!in_array($name, $registry)) {
            $registry[] = $name;
            $this->setRegistry($registry);
        }
    }

    /**
     * Delete storage cell from registry 
     * 
     * @param string $name Cell name
     *  
     * @return void
     */
    protected function deleteRegistry($name)
    {
        $registry = $this->getRegistry();
        $idx = array_search($name, $registry);
        if (false !== $idx) {
            unset($registry[$idx]);
            $this->setRegistry($registry);
        }
    }

    /**
     * Get storage registry 
     * 
     * @return array
     */
    protected function getRegistry()
    {
        $registry = $this->getCacheDriver()->fetch($this->assembleRegistryCell());
        $registry = $registry ? unserialize($registry) : array();

        return is_array($registry) ? $registry : array();
    }

    /**
     * Set storage registry 
     * 
     * @param array $registry Storage registry
     *  
     * @return void
     */
    protected function setRegistry(array $registry)
    {
        $this->getCacheDriver()->save($this->assembleRegistryCell(), $registry);
    }

    // }}}

    // {{{ Global registry

    /**
     * Get global registry 
     * 
     * @return array
     */
    protected function getGlobalRegistry()
    {
        $registry = $this->getCacheDriver()->fetch($this->assembleGlobalRegistryCell());
        $registry = $registry ? unserialize($registry) : array();

        return is_array($registry) ? $registry : array();
    }

    /**
     * Set global registry 
     * 
     * @param array $registry Global registry
     *  
     * @return void
     */
    protected function setGlobalRegistry(array $registry)
    {
        $this->getCacheDriver()->save($this->assembleGlobalRegistryCell(), $registry);
    }

    // }}}    

}

