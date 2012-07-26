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

namespace Includes\DataStructure;

/**
 * Common cell
 *
 * @package XLite
 */
class Cell
{
    /**
     * Array of properties
     *
     * @var array
     */
    protected $properties = array();

    /**
     * Constructor
     *
     * @param array $data data to set
     *
     * @return void
     */
    public function __construct(array $data = null)
    {
        !isset($data) ?: $this->setData($data);
    }

    /**
     * Get property by name
     *
     * @param string $name property name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return isset($this->properties[$name]) ? $this->properties[$name] : null;
    }

    /**
     * Set property value
     *
     * @param string $name  property name
     * @param mixed  $value property value
     *
     * @return void
     */
    public function __set($name, $value)
    {
        $this->properties[$name] = $value;
    }

    /**
     * Check if property exists
     *
     * @param string $name property name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->properties[$name]);
    }

    /**
     * Unset property
     *
     * @param string $name property name
     *
     * @return void
     */
    public function __unset($name)
    {
        unset($this->properties[$name]);
    }

    /**
     * Return all properties
     *
     * @return array
     */
    public function getData()
    {
        return $this->properties;
    }

    /**
     * Append data
     *
     * @param array $data data to set
     *
     * @return void
     */
    public function setData(array $data)
    {
        $this->properties = $data + $this->properties;
    }
}
