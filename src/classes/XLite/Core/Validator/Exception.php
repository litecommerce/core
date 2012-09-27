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

namespace XLite\Core\Validator;

/**
 * Validate exception
 *
 */
class Exception extends \XLite\Core\Exception
{
    /**
     * Path
     *
     * @var array
     */
    protected $path = array();

    /**
     * Message label arguments
     *
     * @var array
     */
    protected $arguments = array();

    /**
     * Public name 
     * 
     * @var string
     */
    protected $publicName;

    /**
     * Add path item
     *
     * @param mixed $item Path item key
     *
     * @return void
     */
    public function addPathItem($item)
    {
        array_unshift($this->path, $item);
    }

    /**
     * Set public name 
     * 
     * @param string $name Public name
     *  
     * @return void
     */
    public function setPublicName($name)
    {
        $this->publicName = $name;
    }

    /**
     * Get public name 
     * 
     * @return string
     */
    public function getPublicName()
    {
        return $this->publicName;
    }

    /**
     * Get path as string
     *
     * @return string
     */
    public function getPath()
    {
        $path = $this->path[0];

        if (1 < count($this->path)) {
            $path .= '[' . implode('][', array_slice($this->path, 1)) . ']';
        }

        return $path;
    }

    /**
     * Mark exception as internal error exception
     *
     * @return void
     */
    public function markAsInternal()
    {
        $this->internal = true;
    }

    /**
     * Check - exception is internal or not
     *
     * @return boolean
     */
    public function isInternal()
    {
        return $this->internal;
    }

    /**
     * Set message arguments
     *
     * @param array $arguments Arguments
     *
     * @return void
     */
    public function setLabelArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * Get message arguments
     *
     * @return array
     */
    public function getLabelArguments()
    {
        return $this->arguments;
    }
}
