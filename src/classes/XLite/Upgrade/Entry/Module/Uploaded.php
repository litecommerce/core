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
     * Module metadata
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $metadata;

    /**
     * Return module actual name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getActualName()
    {
        return $this->getMetadata('ActualName');
    }

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
     * Return icon URL
     *
     * :TODO: actualize
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getIconURL()
    {
        return 'skins/admin/en/images/addon_default.png';
    }

    /**
     * Return entry old major version
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMajorVersionOld()
    {
        return $this->callModuleMethod('getMajorVersion');
    }

    /**
     * Return entry old minor version
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMinorVersionOld()
    {
        return $this->callModuleMethod('getMinorVersion');
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
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isEnabled()
    {
        return $this->isInstalled();
    }

    /**
     * Check if module is installed
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isInstalled()
    {
        return \Includes\Decorator\Utils\ModulesManager::isModuleInstalled($this->getActualName());
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
        return \Includes\Utils\FileManager::getFileSize($this->getRepositoryPath());
    }

    /**
     * Return module dependencies
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getDependencies()
    {
        return $this->getMetadata('Dependencies');
    }

    /**
     * Calculate hashes for current version
     *
     * :TODO: to improve. Check if module is installed and collect file hashes
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function loadHashesForInstalledFiles()
    {
        return $this->getHashes();
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

        $this->setRepositoryPath($path);

        $module = new \PharData($this->getRepositoryPath());
        $this->metadata = $module->getMetaData();

        if (empty($this->metadata)) {
            \Includes\ErrorHandler::fireError('Unable to read module metadata: "' . $path . '"');
        }

        parent::__construct();
    }

    /**
     * Names of variables to serialize
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __sleep()
    {
        $list = parent::__sleep();
        $list[] = 'metadata';

        return $list;
    }

    /**
     * Get module metadata (or only the certain field from it)
     * 
     * @param string $name Array index
     *  
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getMetadata($name)
    {
        return \Includes\Utils\ArrayManager::getIndex($this->metadata, $name, true);
    }

    /**
     * Method to access module main clas methods
     *
     * @param string $method Method to call
     * @param array  $args   Call arguments OPTIONAL
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function callModuleMethod($method, array $args = array())
    {
        return \Includes\Decorator\Utils\ModulesManager::callModuleMethod($this->getActualName(), $method, $args);
    }

    /**
     * Update database records
     *
     * @param string $author Module author
     * @param string $name   Module name
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function updateDBRecords($author, $name)
    {
        $module = new \XLite\Model\Module();
        $module->setAuthor($author);
        $module->setName($name);

        $module = \XLite\Core\Database::getRepo('\XLite\Model\Module')->getModuleInstalled($module) ?: $module;

        $module->setEnabled(true);
        $module->setDate(time());
        $module->setInstalled(true);
        $module->setMajorVersion($this->getMajorVersionNew());
        $module->setMinorVersion($this->getMinorVersionNew());
        $module->setRevisionDate($this->getRevisionDate());
        $module->setPackSize($this->getPackSize());
        $module->setModuleName($this->getName());
        $module->setAuthorName($this->getAuthor());
        $module->setIconURL($this->getIconURL());
        $module->setDependencies($this->getDependencies());

        \XLite\Core\Database::getEM()->persist($module);
        \XLite\Core\Database::getEM()->flush();
    }
}
