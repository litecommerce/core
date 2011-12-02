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
     * Return module description
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.13
     */
    public function getDescription()
    {
        return $this->getMetadata('Description');
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
        return \Includes\Utils\ModulesManager::isModuleInstalled($this->getActualName());
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
     * Unpack archive
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function unpack()
    {
        parent::unpack();
        $this->saveHashesForInstalledFiles();

        return $this->isUnpacked();
    }

    /**
     * Calculate hashes for current version
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function loadHashesForInstalledFiles()
    {
        $result = array();
        $module = $this->getModuleInstalled();

        if ($module) {
            $pack = new \XLite\Core\Pack\Module($module);

            foreach ($pack->getDirectoryIterator() as $file) {

                if ($file->isFile()) {
                    $relativePath = \Includes\Utils\FileManager::getRelativePath($file->getPathname(), LC_DIR_ROOT);

                    if ($relativePath) {
                        $result[$relativePath] = \Includes\Utils\FileManager::getHash($file->getPathname(), true);
                    }
                }
            }
        }

        return $result ?: $this->getHashes(true);
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
        return \Includes\Utils\ModulesManager::callModuleMethod($this->getActualName(), $method, $args);
    }

    /**
     * Find installed module
     *
     * @return \XLite\Model\Module
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModuleInstalled()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Module')->findOneBy($this->getModuleData());
    }

    /**
     * Return common module data
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModuleData()
    {
        list($author, $name) = explode('\\', $this->getActualName());

        return array(
            'name'            => $name,
            'author'          => $author,
            'majorVersion'    => $this->getMajorVersionNew(),
            'minorVersion'    => $this->getMinorVersionNew(),
            'fromMarketplace' => false,
            'installed'       => true,
        );
    }

    /**
     * Update database records
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function updateDBRecords()
    {
        $module = $this->getModuleInstalled() ?: new \XLite\Model\Module($this->getModuleData());;

        $module->setDate(time());
        $module->setRevisionDate($this->getRevisionDate());
        $module->setPackSize($this->getPackSize());
        $module->setModuleName($this->getName());
        $module->setAuthorName($this->getAuthor());
        $module->setDescription($this->getDescription());
        $module->setIconURL($this->getIconURL());
        $module->setDependencies($this->getDependencies());

        // Save changes in DB
        if ($module->getModuleID()) {
            $module->setMajorVersion($this->getMajorVersionNew());
            $module->setMinorVersion($this->getMinorVersionNew());
            \XLite\Core\Database::getRepo('\XLite\Model\Module')->update($module);

        } else {
            $module->setEnabled(true);
            \XLite\Core\Database::getRepo('\XLite\Model\Module')->insert($module);
        }
    }
}
