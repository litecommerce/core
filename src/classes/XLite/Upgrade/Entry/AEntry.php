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

namespace XLite\Upgrade\Entry;

/**
 * AEntry 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class AEntry
{
    /**
     * Path to the unpacked entry archive
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $repositoryPath;


    /**
     * Return entry readable name
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function getName();

    /**
     * Return entry old major version
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function getMajorVersionOld();

    /**
     * Return entry old minor version
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function getMinorVersionOld();

    /**
     * Return entry new major version
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function getMajorVersionNew();

    /**
     * Return entry new minor version
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function getMinorVersionNew();

    /**
     * Return entry revision date
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function getRevisionDate();

    /**
     * Return module author readable name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function getAuthor();

    /**
     * Check if module is enabled
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function isEnabled();

    /**
     * Return entry pack size
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function getPackSize();

    /**
     * Method to get entry package
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function getSource();

    /**
     * Compose version
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getVersionOld()
    {
        return \Includes\Utils\Converter::composeVersion($this->getMajorVersionOld(), $this->getMinorVersionOld());
    }

    /**
     * Compose version
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getVersionNew()
    {
        return \Includes\Utils\Converter::composeVersion($this->getMajorVersionNew(), $this->getMinorVersionNew());
    }

    /**
     * Perform cleanup
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function clear()
    {
        $this->setRepositoryPath(null);
    }

    /**
     * Set repository path 
     * 
     * @param string $path Path to set
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setRepositoryPath($path)
    {
        if ($path !== $this->repositoryPath) {

            if ($this->isDownloaded()) {
                \Includes\Utils\FileManager::delete($this->repositoryPath);

            } elseif ($this->isUnpacked()) {
                \Includes\Utils\FileManager::unlinkRecursive($this->repositoryPath);
            }
        }

        $this->repositoryPath = $path;
    }

    /**
     * Get repository path
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getRepositoryPath()
    {
        return \Includes\Utils\FileManager::isReadable($this->repositoryPath) ? $this->repositoryPath : null;
    }

    /**
     * Download package
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function download()
    {
        $source = $this->getSource();
        $this->setRepositoryPath(null);

        if (!empty($source)) {

            // Check and set extension
            if (!isset($extension)) {
                $extension = \Includes\Utils\PHARManager::getExtension() ?: 'tar';
            }

            // Get unique file name
            $path = \Includes\Utils\FileManager::getUniquePath(LC_DIR_TMP, uniqid() . '.' . $extension);

            // Save data into a file
            if (\Includes\Utils\FileManager::write($path, $source)) {
                $this->setRepositoryPath($path);
            }
        }

        return $this->isDownloaded();
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
        if ($this->isDownloaded()) {

            // Extract archive files into a new directory
            $this->setRepositoryPath(\Includes\Utils\PHARManager::unpack($this->getRepositoryPath(), LC_DIR_TMP));
        }

        return $this->isUnpacked();
    }

    /**
     * Check if pack is already downloaded
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isDownloaded()
    {
        $path = $this->getRepositoryPath();

        return !empty($path) && \Includes\Utils\FileManager::isFile($path);
    }

    /**
     * Check if archive is already unpacked
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isUnpacked()
    {
        $path = $this->getRepositoryPath();

        return !empty($path) && \Includes\Utils\FileManager::isDir($path);
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
        return array('repositoryPath');
    }

    // {{{ Upgrade

    /**
     * Perform upgrade
     *
     * @param boolean $isTestMode Flag OPTIONAL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function upgrade($isTestMode = true)
    {
    }

    // }}}
}
