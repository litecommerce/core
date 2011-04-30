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
 * @since     1.0.0
 */

namespace XLite\Upgrade\Entry\Module;

/**
 * Uploaded 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class Uploaded extends \XLite\Upgrade\Entry\Module\AModule
{
    /**
     * Path to the module package
     * 
     * @var   mixed
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $path;

    /**
     * Module object (cache)
     * 
     * @var   \PharData
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $module;

    /**
     * Return entry readable name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getName()
    {
        return $this->getMetadata('Name');
    }

    /**
     * Return entry old major version
     *
     * :TODO: actualize
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMajorVersionOld()
    {
        return null;
    }

    /**
     * Return entry old minor version
     *
     * :TODO: actualize
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMinorVersionOld()
    {
        return null;
    }

    /**
     * Return entry new major version
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMajorVersionNew()
    {
        return $this->getMetadata('VersionMajor');
    }

    /**
     * Return entry new minor version
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMinorVersionNew()
    {
        return $this->getMetadata('VersionMinor');
    }

    /**
     * Return entry revision date
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getRevisionDate()
    {
        return $this->getMetadata('RevisionDate');
    }

    /**
     * Return module author readable name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getAuthor()
    {
        return $this->getMetadata('Author');
    }

    /**
     * Check if module is enabled
     *
     * :TODO: actualize
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isEnabled()
    {
        return false;
    }

    /**
     * Return entry pack size
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPackSize()
    {
        return \Includes\Utils\FileManager::getFileSize($this->path);
    }

    /**
     * Method to get entry package
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getSource()
    {
        return \Includes\Utils\FileManager::read($this->path);
    }

    /**
     * Overloaded constructor
     *
     * @param string $path Path to the module package
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct($path)
    {
        if (!\Includes\Utils\FileManager::isFileReadable($path)) {
            \Includes\ErrorHandler::fireError('Unable to read module package: "' . $path . '"');
        }

        $this->path = $path;
    }

    /**
     * Return module description object
     * 
     * @return \PharData
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModule()
    {
        if (!isset($this->module)) {
            $this->module = new \PharData($this->path);
        }

        return $this->module;
    }

    /**
     * Get module metadata (or only the certain field from it)
     * 
     * @param string $name Array index OPTIONAL
     *  
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getMetadata($name = null)
    {
        return \Includes\Utils\ArrayManager::getIndex($this->getModule()->getMetaData(), $name, isset($name));
    }
}
