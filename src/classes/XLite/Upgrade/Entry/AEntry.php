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
     * Some common tokens in messages
     */
    const TOKEN_ENTRY = 'entry';
    const TOKEN_FILE  = 'file';

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
     * List of post rebuild helpers
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $postRebuildHelpers;

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
     * Return entry actual name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function getActualName();

    /**
     * Set entry status
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract protected function setUpgradedPath();

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
            $this->addErrorMessage('Size of the entry "{{' . self::TOKEN_ENTRY . '}}" pack is zero', true);
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
     * @param string  $path            Path to set
     * @param boolean $preventDeletion Flag OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setRepositoryPath($path, $preventDeletion = false)
    {
        if (!empty($path)) {
            $path = \Includes\Utils\FileManager::getRealPath($path);

            if (empty($path) || !\Includes\Utils\FileManager::isReadable($path)) {
                $path = null;
            }
        }

        if (!$preventDeletion && !empty($this->repositoryPath) && $path !== $this->repositoryPath) {

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
     * Perform some action after upgrade 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setUpgraded()
    {
        $this->setUpgradedPath();

        if (!isset($this->postRebuildHelpers)) {
            $this->postRebuildHelpers = $this->getHelpers('post_rebuild');
        }
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
            $dir = \Includes\Utils\PHARManager::unpack($this->getRepositoryPath(), LC_DIR_TMP);

            if ($dir) {
                $this->setRepositoryPath($dir);
                $this->addFileInfoMessage('Entry "{{' . self::TOKEN_ENTRY . '}}" archive is unpacked', $dir, true);
            }
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
        return array('repositoryPath', 'errorMessages', 'customFiles', 'postRebuildHelpers');
    }

    // {{{ Error handling

    /**
     * Return list of error messages
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

    // }}}

    // {{{ Upgrade

    /**
     * Perform upgrade
     *
     * @param boolean    $isTestMode       Flag OPTIONAL
     * @param array|null $filesToOverwrite List of custom files to overwrite OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function upgrade($isTestMode = true, $filesToOverwrite = null)
    {
        $this->errorMessages = array();

        $hashesInstalled  = $this->getHashesForInstalledFiles($isTestMode);
        $hashesForUpgrade = $this->getHashes($isTestMode);

        // Overwrite only selected files or the all ones
        $this->customFiles = is_array($filesToOverwrite) ? $filesToOverwrite : $hashesInstalled;

        // Walk through the installed and known files list
        foreach ($hashesInstalled as $path => &$hash) {

            // Check file on FS
            if ($this->manageFile($path, 'isFile')) {

                // Calculate file md5-hash
                $fileHash = $this->manageFile($path, 'getHash');

                if (isset($fileHash)) {

                    if (isset($hashesForUpgrade[$path])) {
                        // File has been modified (by user, or by LC Team, see the third param)
                        if ($fileHash !== $hash || $hashesForUpgrade[$path] !== $hash) {
                            $this->updateFile($path, $isTestMode, $fileHash !== $hash);
                        }

                    } else {
                        // File has been removed (by user, or by LC Team, see the third param)
                        $this->deleteFile($path, $isTestMode, $fileHash !== $hash);
                    }

                } else {
                    // Do not skip any files during upgrade: all of them must be writable
                    $this->addFileErrorMessage('File is not readable', $path, !$isTestMode);
                }

            } elseif (isset($hashesForUpgrade[$path])) {
                // File has been removed from installation (by user)
                $this->addFile($path, $isTestMode, true);
            }

            // Only the new files will remain
            unset($hashesForUpgrade[$path]);
        }

        // Add new files
        foreach ($hashesForUpgrade as $path => $hash) {
            $this->addFile($path, $isTestMode, $this->manageFile($path, 'isFile'));
        }

        // Clear some data
        if (!$isTestMode) {
            $this->customFiles = array();
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
            $topDir  = $this->manageFile($path, 'getDir');
            $lcRoot  = \Includes\Utils\FileManager::getRealPath(LC_DIR_ROOT);
            $sysRoot = \Includes\Utils\FileManager::getRealPath('/');

            // Search for writable directory
            while (
                !($flag = \Includes\Utils\FileManager::isDirWriteable($topDir))
                && $topDir !== $lcRoot
                && $topDir !== $sysRoot
            ) {
                $topDir = \Includes\Utils\FileManager::getRealPath(\Includes\Utils\FileManager::getDir($topDir));
            }

            // Permissions are invalid
            if (!$flag) {
                $this->addFileErrorMessage(
                    'Directory is not writable: "{{dir}}"',
                    $path,
                    false,
                    array('dir' => $topDir)
                );
            }

        } elseif ($source = $this->getFileSource($path)) {
            if ($this->manageFile($path, 'write', array($source))) {
                $this->addFileInfoMessage('File is added', $path, true);

            } else {
                $this->addFileErrorMessage('Unable to add file', $path, true);
            }

        } else {
            $this->addFileErrorMessage('Unable to read file while adding', $path, true);
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
                $this->addFileErrorMessage('File is not writeable', $path, false);
            }

        } elseif ($source = $this->getFileSource($path)) {
            if ($this->manageFile($path, 'write', array($source))) {
                $this->addFileInfoMessage('File is updated', $path, true);

            } else {
                $this->addFileErrorMessage('Unable to update file', $path, true);
            }

        } else {
            $this->addFileErrorMessage('Unable to read file while updating', $path, true);
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
                $this->addFileErrorMessage('File\'s directory is not writable', $path, false);
            }

        } elseif ($this->manageFile($path, 'deleteFile')) {
            $this->addFileInfoMessage('File is deleted', $path, true);

        } else {
            $this->addFileErrorMessage('Unable to delete file', $path, true);
        }
    }

    /**
     * Common operation for add/update/delete
     *
     * :TODO: advise a more convinient logic for this method
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
    protected function addToCustomFiles($path, $flag = true)
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
        $path = \Includes\Utils\FileManager::getCanonicalDir($this->getRepositoryPath()) . '.hash';

        if (!\Includes\Utils\FileManager::isFileReadable($path)) {
            $message = 'Hash file for new entry "{{entry}}" doesn\'t exist or is not readable';

        } else {
            $data = \Includes\Utils\FileManager::read($path);

            if (empty($data)) {
                $message = 'Unable to read hash file for new entry "{{entry}}" (or it\'s empty)';

            } else {
                $data = json_decode($data, true);

                if (!is_array($data)) {
                    $message = 'Hash file for new entry "{{entry}}" has a wrong format';
                }
            }
        }

        if (!empty($message)) {
            $this->addFileErrorMessage($message, $path, !$isTestMode);
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
        if ($this->isInstalled()) {
            $path = $this->getCurrentVersionHashesFilePath();

            if (!\Includes\Utils\FileManager::isFileReadable($path)) {
                $message = 'Hash file for installed entry "{{entry}}" doesn\'t exist or is not readable';

            } else {
                require_once ($path);
            }

            if (!empty($message)) {
                $this->addFileErrorMessage($message, $path, !$isTestMode);
            }
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
        return \Includes\Utils\FileManager::read(
            \Includes\Utils\FileManager::getCanonicalDir($this->getRepositoryPath()) . $relativePath
        );
    }

    // }}}

    // {{{ So called upgrade helpers

    /**
     * Execute some methods
     *
     * @param string $type Helper type
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function runHelpers($type)
    {
        if ($this->isInstalled()) {
            $helpers = ('post_rebuild' === $type) ? $this->postRebuildHelpers : $this->getHelpers($type);

            foreach ((array) $helpers as $file) {
                $function = require_once $file;
                $function();
            }
        }
    }

    /**
     * Get upgrade helpers list
     *
     * @param string $type Helper type
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHelpers($type)
    {
        $helpers = array();

        foreach ($this->getUpgradeHelperMajorVersions() as $majorVersion) {
            foreach ($this->getUpgradeHelperMinorVersions($majorVersion) as $minorVersion) {

                if ($file = $this->getUpgradeHelperFile($type, $majorVersion, $minorVersion)) {
                    $helpers[] = $file;
                }
            }
        }

        return $helpers;
    }

    /**
     * Get file with an upgrade helper function
     * 
     * @param string $type         Helper type
     * @param string $majorVersion Major version to upgrade to
     * @param string $minorVersion Minor version to upgrade to
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getUpgradeHelperFile($type, $majorVersion, $minorVersion)
    {
        $file = null;

        if ($path = $this->getUpgradeHelperPath()) {
            $path .= $majorVersion . LC_DS . $minorVersion . LC_DS . $type . '.php';

            if (\Includes\Utils\FileManager::isFile($path)) {
                $file = $path;
            }
        }

        return $file;
    }

    /**
     * Return path where the upgrade helper scripts are placed
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getUpgradeHelperPath()
    {
        $path = \Includes\Utils\FileManager::getCanonicalDir($this->getRepositoryPath());

        if (!empty($path) && !\Includes\Utils\FileManager::isDir($path .= 'upgrade' . LC_DS)) {
            $path = null;
        }

        return $path;
    }

    /**
     * Get list of available major versions for the helpers 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getUpgradeHelperMajorVersions()
    {
        $old = $this->getMajorVersionOld();
        $new = $this->getMajorVersionNew();

        return array_filter(
            $this->getUpgradeHelperVersions(),
            function ($var) use ($old, $new) {
                return version_compare($old, $var, '<=') && version_compare($new, $var, '>=');
            }
        );
    }

    /**
     * Get list of available minor versions for the helpers
     *
     * @param string $majorVersion Current major version
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getUpgradeHelperMinorVersions($majorVersion)
    {
        $old = $this->getMinorVersionOld();
        $new = $this->getMinorVersionNew();

        return array_filter(
            $this->getUpgradeHelperVersions($majorVersion . LC_DS),
            function ($var) use ($old, $new) {
                return version_compare($old, $var, '<') && version_compare($new, $var, '>=');
            }
        );
    }

    /**
     * Get list of available versions for the helpers
     * 
     * @param string $path Path to scan OPTIONAL
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getUpgradeHelperVersions($path = null)
    {
        $result = array();

        if (\Includes\Utils\FileManager::isDir($path = $this->getUpgradeHelperPath() . $path)) {

            foreach (new \DirectoryIterator($path) as $fileinfo) {
                if ($fileinfo->isDir() && !$fileinfo->isDot()) {
                    $result[] = $fileinfo->getFilename();
                }
            }

            if (!usort($result, 'version_compare')) {
                $result = array();
            }
        }

        return $result;
    }

    // }}}

    // {{{ Logging and error handling

    /**
     * Add new info message
     *
     * @param string  $message Message to add
     * @param boolean $log     Flag
     * @param array   $args    Substitution arguments OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addInfoMessage($message, $log, array $args = array())
    {
        $this->addMessage('Info', $message, $log, $args);
    }

    /**
     * Add new error message
     *
     * @param string  $message Message to add
     * @param boolean $log     Flag
     * @param array   $args    Substitution arguments OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addErrorMessage($message, $log, array $args = array())
    {
        $this->addMessage('Error', $message, $log, $args);

        // Add message to the internal array
        $this->errorMessages[] = \XLite\Core\Translation::getInstance()->translate($message, $args);
    }

    /**
     * Add new info message which contains file path
     *
     * @param string  $message Message to add
     * @param string  $file    File path
     * @param boolean $log     Flag
     * @param array   $args    Substitution arguments OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addFileInfoMessage($message, $file, $log, array $args = array())
    {
        $this->addFileMessage('Info', $message, $file, $log, $args);
    }

    /**
     * Add new error message which contains file path
     *
     * @param string  $message Message to add
     * @param string  $file    File path
     * @param boolean $log     Flag
     * @param array   $args    Substitution arguments OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addFileErrorMessage($message, $file, $log, array $args = array())
    {
        $this->addFileMessage('Error', $message, $file, $log, $args);
    }

    /**
     * Add new message
     *
     * @param string  $method  Logger method to call
     * @param string  $message Message to add
     * @param boolean $log     Flag
     * @param array   $args    Substitution arguments OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addMessage($method, $message, $log, array $args = array())
    {
        // It's a quite common case
        $args += array(self::TOKEN_ENTRY => $this->getActualName());

        // Write message to the log (if needed)
        if (!empty($log)) {
            \XLite\Upgrade\Logger::getInstance()->{'log' . $method}($message, $args, false);
        }
    }

    /**
     * Add new error message which contains file path
     *
     * @param string  $method  Logger method to call
     * @param string  $message Message to add
     * @param string  $file    File path
     * @param boolean $log     Flag
     * @param array   $args    Substitution arguments OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addFileMessage($method, $message, $file, $log, array $args = array())
    {
        $this->{'add' . $method . 'Message'}(
            $message . ': "{{' . self::TOKEN_FILE . '}}"',
            $log,
            $args + array(self::TOKEN_FILE => $file)
        );
    }

    // }}}
}
