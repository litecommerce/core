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
     * Return entry icon URL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function getIconURL();

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
     * Check if module is installed
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function isInstalled();

    /**
     * Return entry pack size
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function getPackSize();

    /**
     * Get hashes for current version
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract protected function loadHashesForInstalledFiles();

    /**
     * Constructor
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct()
    {
        if (0 >= $this->getPackSize()) {
            $this->addErrorMessage('Pack for "' . $this->getName() . '" is empty');
        }
    }

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
                \Includes\Utils\FileManager::deleteFile($this->repositoryPath);

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
        $path = $this->getRepositoryPath();

        if (\Includes\Utils\FileManager::isFile($path)) {
            $path = LC_DIR_TMP . pathinfo($path, PATHINFO_FILENAME);
        }

        return $path . '.php';
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
     * Return list of custom files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCustomFiles()
    {
        return $this->customFiles;
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
     * @param boolean $isTestMode       Flag OPTIONAL
     * @param array   $filesToOverwrite List of custom files to overwrite OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function upgrade($isTestMode = true, array $filesToOverwrite = array())
    {
        $this->errorMessages = array();
        $this->customFiles   = $filesToOverwrite ?: array();

        $hashes = $this->getHashes();

        // Walk through the installed and known files list
        foreach ($this->getHashesForInstalledFiles() as $path => $hash) {

            // Check file on FS
            if ($this->manageFile($path, 'isFile')) {

                // Calculate file md5-hash
                $fileHash = $this->manageFile($path, 'getHash');

                if (isset($fileHash)) {

                    if (isset($hashes[$path])) {
                        // File has been modified (by user, or by LC Team, see the third param)
                        if ($fileHash !== $hash || $hashes[$path] !== $hash) {
                            $this->updateFile($path, $isTestMode, $fileHash !== $hash);
                        }

                    } else {
                        // File has been removed (by user, or by LC Team, see the third param)
                        $this->deleteFile($path, $isTestMode, $fileHash !== $hash);
                    }

                } else {
                    // Do not skip any files during upgrade: all of them must be writable
                    $this->addErrorMessage('File "{{file}}" is not readable', $path);
                }

            } else {
                // File has been removed from installation (by user)
                $this->addFile($path, $isTestMode, true);
            }

            // Only the new files will remain
            unset($hashes[$path]);
        }

        // Add new files
        foreach ($hashes as $path => $hash) {
            $this->addFile($path, $isTestMode, $this->manageFile($path, 'isFile'));
        }
    }

    /**
     * Perform some common operation for upgrade
     *
     * @param string  $path              File short path
     * @param boolean $isTestMode        If in test mode
     * @param boolean $manageCustomFiles Flag for custom files
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addFile($path, $isTestMode, $manageCustomFiles)
    {
        $this->modifyFile($path, $isTestMode, $manageCustomFiles, 'addFileCallback');
    }

    /**
     * Perform some common operation for upgrade
     *
     * @param string  $path              File short path
     * @param boolean $isTestMode        If in test mode
     * @param boolean $manageCustomFiles Flag for custom files
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function updateFile($path, $isTestMode, $manageCustomFiles)
    {
        $this->modifyFile($path, $isTestMode, $manageCustomFiles, 'updateFileCallback');
    }

    /**
     * Perform some common operation for upgrade
     *
     * @param string  $path              File short path
     * @param boolean $isTestMode        If in test mode
     * @param boolean $manageCustomFiles Flag for custom files
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function deleteFile($path, $isTestMode, $manageCustomFiles)
    {
        $this->modifyFile($path, $isTestMode, $manageCustomFiles, 'deleteFileCallback');
    }

    /**
     * Callback for a common operation for upgrade
     *
     * @param string  $path       File short path
     * @param boolean $isTestMode If in test mode
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addFileCallback($path, $isTestMode)
    {
        if ($isTestMode) {

            // Short names
            $topDir  = \Includes\Utils\FileManager::getRealPath($this->manageFile($path, 'getDir'));
            $lsRoot  = \Includes\Utils\FileManager::getRealPath(LC_DIR_ROOT);
            $sysRoot = \Includes\Utils\FileManager::getRealPath('/');

            // Search for writable directory
            while (
                !($flag = \Includes\Utils\FileManager::isDirWriteable($topDir))
                && $topDir !== $lsRoot
                && $topDir !== $sysRoot
            ) {
                $topDir = \Includes\Utils\FileManager::getRealPath(\Includes\Utils\FileManager::getDir($topDir));
            }

            // Permissions are invalid
            if (!$flag) {
                $this->addFileErrorMessage('Parent dir of the "{{file}}" file is not writable', $path);
            }

        } elseif ($this->manageFile($path, 'write', array($this->getFileSource($path)))) {
            $this->log('File "' . $path . '" successfully added');

        } else {
            $this->addFileErrorMessage('Unable to write "{{file}}" file', $path);
            $this->log('Unable to write "' . $path . '" file');
        }
    }

    /**
     * Callback for a common operation for upgrade
     *
     * @param string  $path       File short path
     * @param boolean $isTestMode If in test mode
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function updateFileCallback($path, $isTestMode)
    {
        if ($isTestMode) {

            if (!$this->manageFile($path, 'isFileWriteable')) {
                $this->addFileErrorMessage('File "{{file}}" is not writeable', $path);
            }

        } elseif ($this->manageFile($path, 'write', array($this->getFileSource($path)))) {
            $this->log('File "' . $path . '" successfully updated');

        } else {
            $this->addFileErrorMessage('Unable to write "{{file}}" file', $path);
            $this->log('Unable to write "' . $path . '" file');
        }
    }

    /**
     * Callback for a common operation for upgrade
     *
     * @param string  $path       File short path
     * @param boolean $isTestMode If in test mode
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function deleteFileCallback($path, $isTestMode)
    {
        if ($isTestMode) {

            if (!\Includes\Utils\FileManager::isDirWriteable($this->manageFile($path, 'getDir'))) {
                $this->addFileErrorMessage('Parent dir of the "{{file}}" file is not writable', $path);
            }

        } elseif ($this->manageFile($path, 'delete')) {
            $this->log('File "' . $path . '" successfully deleted');

        } else {
            $this->addFileErrorMessage('Unable to delete "{{file}}" file', $path);
            $this->log('Unable to delete "' . $path . '" file');
        }
    }

    /**
     * Common operation for add/update/delete
     *
     * @param string  $path              File short path
     * @param boolean $isTestMode        If in test mode
     * @param boolean $manageCustomFiles Flag for custom files
     * @param string  $callback          Callback to execute
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function modifyFile($path, $isTestMode, $manageCustomFiles, $callback)
    {
        if ($isTestMode) {

            if ($manageCustomFiles) {
                $this->addToCustomFiles($path);
            }

            // Call a specific class method
            $this->$callback($path, $isTestMode);

        } elseif (!$manageCustomFiles || $this->checkForCustomFileRewrite($path)) {

            // Call a specific class method
            $this->$callback($path, $isTestMode);
        }
    }

    /**
     * Short name for FileManager call
     *
     * @param string $path   File short path
     * @param string $method Method to call
     * @param array  $args   Call arguments OPTIONAL
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function manageFile($path, $method, array $args = array())
    {
        return call_user_func_array(
            array('\Includes\Utils\FileManager', $method),
            array_merge(array($this->getFullPath($path)), $args)
        );
    }

    /**
     * Compose file full path
     *
     * @param string $path File short path
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFullPath($path)
    {
        return LC_DIR_ROOT . $path;
    }

    /**
     * Add file to the custome files list
     *
     * @param string  $path File short path
     * @param boolean $flag Status OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addToCustomFiles($path, $flag = false)
    {
        $this->customFiles[$path] = $flag;
    }

    /**
     * Check status of custom file entry list
     *
     * @param string $path File short path
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkForCustomFileRewrite($path)
    {
        return !empty($this->customFiles[$path]);
    }

    /**
     * Short name for the "addFileErrorMessage" method
     *
     * @param string $message Message to set
     * @param string $path    File short path
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addFileErrorMessage($message, $path)
    {
        $this->addErrorMessage($message, array('file' => $path));
    }

    /**
     * Return file hashes
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHashes()
    {
        $path = \Includes\Utils\FileManager::getCanonicalDir($this->getRepositoryPath()) . '.hash';
        $errorParams = array('file' => \Includes\Utils\FileManager::getRelativePath($path, LC_DIR_TMP));

        if (!\Includes\Utils\FileManager::isFileReadable($path)) {
            $this->addErrorMessage('Hash file "{{file}}" is not exists or is not readable', $errorParams);

        } else {
            $data = \Includes\Utils\FileManager::read($path);

            if (empty($data)) {
                $this->addErrorMessage('Unable to read hash file "{{file}}" or it\'s empty', $errorParams);

            } else {
                $data = json_decode($data, true);

                if (!is_array($data)) {
                    $this->addErrorMessage('Hash file "{{file}}" has a wrong format', $errorParams);
                }
            }
        }

        return (empty($data) || !is_array($data)) ? array() : $data;
    }

    /**
     * Return file hashes for the currently installed version
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHashesForInstalledFiles()
    {
        $path = $this->getCurrentVersionHashesFilePath();
        $errorParams = array('file' => \Includes\Utils\FileManager::getRelativePath($path, LC_DIR_TMP));

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
            \Includes\Utils\FileManager::write(
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

    // {{{ Logging

    /**
     * Log message to the file
     *
     * @param string  $message Message text
     * @param boolean $isError Message type OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function log($message, $isError = false)
    {
        \Includes\Utils\FileManager::write(
            \XLite\Upgrade\Cell::getLogFilePath(),
            $this->getLogMessage($message, $isError),
            FILE_APPEND
        );
    }

    /**
     * Log message to the file
     *
     * @param string  $message Message text
     * @param boolean $isError Message type
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLogMessage($message, $isError)
    {
        return '[' . ($isError ? 'Error' : 'Info') . ']: ' . $message . PHP_EOL;
    }

    // }}}
}
