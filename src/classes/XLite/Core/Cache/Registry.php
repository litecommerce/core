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

namespace XLite\Core\Cache;

/**
 * Cache registry
 */
class Registry extends \XLite\Base implements \Doctrine\Common\Cache\Cache
{
    const CELL_REGISTRY = 'registry';

    /**
     * Namespace
     *
     * @var string
     */
    protected $namespace;

    /**
     * Cache driver
     *
     * @var \XLite\Core\Cache
     */
    protected $driver;

    /**
     * Constructor
     *
     * @param string            $namesapce Namesapce
     * @param \XLite\Core\Cache $driver    Driver OPTIONAL
     *
     * @return void
     */
    public function __construct($namespace, \XLite\Core\Cache $driver = null)
    {
        $this->namespace = $namespace;
        $this->driver = $driver ?: \XLite\Core\Database::getCacheDriver();
    }

    /**
     * Call driver's method
     *
     * @param string $name      Method name
     * @param array  $arguments Arguments OPTIONAL
     *
     * @return mixed
     */
    public function __call($name, array $arguments = array())
    {
        return call_user_func_array(array($this->driver, $name), $arguments);
    }

    // {{{ Routines

    /**
     * Get cell
     * 
     * @param string $id Cell id
     *
     * @return mixed
     */
    public function fetch($id)
    {
        $value = $this->driver->fetch($this->assembleId($id));
        $value = $value ? @unserialize($value) : null;

        return $value;
    }

    /**
     * Set cell
     * 
     * @param string  $id       Cell id
     * @param mixed   $data     Cell data
     * @param integer $lifeTime Life time OPTIONAL
     *
     * @return void
     */
    public function save($id, $data, $lifeTime = 0)
    {
        $key = $this->assembleId($id);
        $registry = $this->getRegistry();
        $registry[$id] = $key;
        $this->driver->save($key, serialize($data));
        $this->setRegistry($registry);
    }

    /**
     * Check cell existing
     * 
     * @param string $id Cell id
     *
     * @return boolean
     */
    public function contains($id)
    {
        return $this->driver->contains($this->assembleId($id));
    }

    /**
     * Delete cell
     * 
     * @param string $id Cell id
     *
     * @return void
     */
    public function delete($id)
    {
        $this->driver->delete($this->assembleId($id));
        $registry = $this->getRegistry();
        if (isset($registry[$id])) {
            unset($registry[$id]);
            $this->setRegistry($registry);
        }
    }

    /**
     * Get statistics
     *
     * @return array
     */
    public function getStats()
    {
        return $this->driver->getStats();
    }

    /**
     * Delete all cells
     *
     * @return void
     */
    public function deleteAll()
    {
        foreach ($this->getRegistry() as $id) {
            $this->driver->delete($id);
        }
        $this->setRegistry(array());
    }

    /**
     * Get all cells id's
     *
     * @return array
     */
    public function getIds()
    {
        return array_keys($this->getRegistry());
    }

    /**
     * Get storage registry 
     * 
     * @return array
     */
    protected function getRegistry()
    {
        $registry = $this->driver->fetch($this->assembleServiceId(static::CELL_REGISTRY));
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
        $this->driver->save($this->assembleServiceId(static::CELL_REGISTRY), $registry);
    }

    /**
     * Assemble storage cell id
     * 
     * @param string $suffix Internal cell id
     *  
     * @return string
     */
    protected function assembleId($suffix)
    {
        return $this->namespace . '.' . $suffix;
    }

    /**
     * Assemble service cell id
     * 
     * @param string $suffix Service key
     *  
     * @return string
     */
    protected function assembleServiceId($suffix)
    {
        return $this->namespace . '_' . $suffix;
    }

    // }}}

}
