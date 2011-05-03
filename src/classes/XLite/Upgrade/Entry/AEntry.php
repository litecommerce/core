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
     * List of error messages 
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $errorMessages = array();

    /**
     * List of custom files 
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $customFiles = array();


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
     * Get hashes for current version
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract protected function loadHashesForInstalledFiles();

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
        if (!empty($path)) {
            $path = \Includes\Utils\FileManager::getRealPath($path);

            if (empty($path) || !\Includes\Utils\FileManager::isReadable($path)) {
                $path = null;
            }
        }

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
        return $this->repositoryPath;
    }

    /**
     * Name of the special file with hashes for installed files
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCurrentVersionHashesFilePath()
    {
        return LC_DIR_TMP . basename($this->getRepositoryPath()) . '.hash.php';
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
                $this->saveHashesForInstalledFiles();
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

        return !empty($path) 
            && \Includes\Utils\FileManager::isFile($path) 
            && \Includes\Utils\FileManager::isFile($this->getCurrentVersionHashesFilePath());
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

        return !empty($path) 
            && \Includes\Utils\FileManager::isDir($path)
            && \Includes\Utils\FileManager::isFile($this->getCurrentVersionHashesFilePath());
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
        return array('repositoryPath', 'errorMessages');
    }

    // {{{ Error handling

    /**
     * Retrun list of error messages
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getErrorMessages()
    {
        return array_unique($this->errorMessages);
    }

    /**
     * Check for errors
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isValid()
    {
        return ! (bool) $this->getErrorMessages();
    }

    /**
     * Add new error message
     * 
     * @param string $message Message to add
     * @param array  $args    Substitution arguments OPTIONAL
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addErrorMessage($message, array $args = array())
    {
        $this->errorMessages[] = \XLite\Core\Translation::getInstance()->translate($message, $args);
    }

    // }}}

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
        $this->errorMessages = array();

        if ($isTestMode) {
            $this->customFiles = array();
        }

        $hashes = $this->getHashes();

        // Walk through the installed and known files list
        foreach ($this->getHashesForInstalledFiles($isTestMode) as $path => $hash) {

            // Some useful variables
            $relativePath = \Includes\Utils\FilManager::getRealPath($path);
            $fullPath     = LC_ROOT_DIR . $relativePath;
            $directory    = \Includes\Utils\FilManager::getDir($fullPath);
            
            // File is presented in both old and new packages - (optionally) overwrite
            if (isset($hashes[$relativePath])) {

                // File exists on FS
                if (\Includes\Utils\FilManager::isFile($fullPath)) {

                    // Check if file is modified
                    if ($hash !== $hashes[$relativePath]) {

                        // Check if file was set to overwrite
                        if ($isTestMode xor !empty($this->customFiles[$relativePath])) {

                            if ($isTestMode) {

                                // Add file to the list of custom ones
                                $this->customFiles[$relativePath] = false;

                                // Check permissions
                                if (!\Includes\Utils\FilManager::isFileWritable($fullPath)) {
                                    $this->addErrorMessage(
                                        'File "{{file}} has no writable permissions"',
                                        array('file' => $relativePath)
                                    );
                                }

                            } else {

                                // Trying to overwrite
                                if (!/*\Includes\Utils\FilManager::write($fullPath, $this->getFileSource($relativePath))*/true) {
                                    $this->addErrorMessage(
                                        'An error occured while overwriting the "{{file}}" file',
                                        array('file' => $relativePath)
                                    );
                                }
                            }

                        } else {
                            // :TODO: add information message
                        }

                    } else {
                        // File is the same as in previous version, do nothing ...
                    }

                } else {

                    // Short names
                    $topDir  = \Includes\Utils\FilManager::getRealPath($directory);
                    $lsRoot  = \Includes\Utils\FilManager::getRealPath(LC_ROOT_DIR);
                    $sysRoot = \Includes\Utils\FilManager::getRealPath('/');

                    // Search for writable directory
                    while (
                        !($flag = \Includes\Utils\FilManager::isDirWritable($topDir))
                        && $topDir !== $lsRoot 
                        && $topDir !== $sysRoot
                    ) {
                        $topDir = \Includes\Utils\FilManager::getRealPath(\Includes\Utils\FilManager::getDir($topDir));
                    }

                    // Permissions are correct
                    if ($flag) {

                        if ($isTestMode) {
                            // Do nothing ...

                        } else {

                            // The FilManager::write() will create nested directories by itself (if needed)
                            if (!/*\Includes\Utils\FilManager::write($fullPath, $this->getFileSource($relativePath))*/true) {
                                $this->addErrorMessage(
                                    'An error occured while writing the "{{file}}" file to FS',
                                    array('file' => $relativePath)
                                );
                            }
                        }

                    } else {
                        $this->addErrorMessage(
                            'Unable to save file "{{file}}": the directory "{{dir}}" has no writable permissions',
                            array('file' => $relativePath, 'dir' => $topDir)
                        );
                    }
                }

            } else {

                // File is not presented in the new entry package - delete
                
                // Check if file was set to overwrite
                if ($isTestMode xor !empty($this->customFiles[$relativePath])) {

                    if ($isTestMode) {

                        // Add file to the list of custom ones
                        $this->customFiles[$relativePath] = false;

                        // Check permissions for delete
                        if (!\Includes\Utils\FilManager::isDirWritable($directory)) {
                            $this->addErrorMessage(
                                'Wrong permissions for the {{file}} file. Unable to delete',
                                array('file' => $path)
                            );
                        }

                    } else {

                        // Remove file
                        if (!/*\Includes\Utils\FilManager::delete($fullPath)*/true) {
                            $this->addErrorMessage(
                                'Unable to delete file "{{file}}"',
                                array('file' => $relativePath)
                            );
                        }
                    }
    
                } else {
                    // :TODO: add information message
                }
            }
        }

        return $this->isValid();
    }

    /**
     * Return file hashes
     *
     * @param boolean $isTestMode Flag
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHashes($isTestMode)
    {
        $path = $this->getRepositoryPath() . '.hash';
        $errorParams = array('file' => \Inludes\Utils\FileManager::getRelativePath($path, LC_DIR_TMP));

        if (!\Includes\Utils\FileManager::isFileReadable($path)) {
            $this->addErrorMessage('Hash file "{{file}}" is not exists or is not readable', $errorParams);

        } else {
            $data = \Includes\Utils\FileManager::read($path);

            if (empty($data)) {
                $this->addErrorMessage('Unable to read hash file "{{file}}" or it\'s empty', $errorParams);

            } else {
                $data = json_decode($data);

                if (!is_array($data)) {
                    $this->addErrorMessage('Hash file "{{file}}" has a wrong format', $errorParams);

                } else {
                    foreach ($data as $path => $hash) {
                        unset($data[$path]);
                        $data[\Includes\Utils\FilManager::getRealPath($path)] = $hash;
                    }
                }
            }
        }

        return (empty($data) || !is_array($data)) ? array() : $data;
    }

    /**
     * Return file hashes for the currently installed version
     *
     * @param boolean $isTestMode Flag
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHashesForInstalledFiles($isTestMode)
    {
        $path = $this->getCurrentVersionHashesFilePath();
        $errorParams = array('file' => \Inludes\Utils\FileManager::getRelativePath($path, LC_DIR_TMP));

        if (!\Includes\Utils\FileManager::isFileReadable($path)) {
            $this->addErrorMessage('Hash file "{{file}}" is not exists or is not readable', $errorParams);

        } else {
            require_once ($path);
        }

        return (empty($data) || !is_array($data)) ? array() : $data;
    }

    /**
     * Save hashes for current version
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function saveHashesForInstalledFiles()
    {
        $data = $this->loadHashesForInstalledFiles();

        if (is_array($data)) {
            \Includes\Utils\FilManager::write(
                $this->getCurrentVersionHashesFilePath(),
                '<?php' . PHP_EOL . '$data = ' . var_export($data, true) . ';'
            );
        }
    }

    /**
     * Read file from package
     * 
     * @param string $relativePath File relative path in package
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFileSource($relativePath)
    {
        return null;
    }

    // }}}
}
