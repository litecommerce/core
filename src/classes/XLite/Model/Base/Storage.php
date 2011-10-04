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

namespace XLite\Model\Base;

/**
 * Storage abstract store
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @MappedSuperclass
 * @HasLifecycleCallbacks
 */
abstract class Storage extends \XLite\Model\AEntity
{
    /**
     * Storage type codes 
     */
    const STORAGE_RELATIVE = 'r';
    const STORAGE_ABSOLUTE = 'f';
    const STORAGE_URL      = 'u';

    /**
     * MIME type to extenstion translation table
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.10
     */
    protected static $types = array();

    /**
     * Unique id
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger")
     */
    protected $id;

    /**
     * Path (URL or file name in storage directory)
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="512")
     */
    protected $path;

    /**
     * File name
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string")
     */
    protected $fileName = '';

    /**
     * MIME type
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="64")
     */
    protected $mime = 'application/octet-stream';

    /**
     * Storage type
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="1")
     */
    protected $storageType = self::STORAGE_RELATIVE;

    /**
     * Size
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="uinteger")
     */
    protected $size;

    /**
     * Create / modify date (UNIX timestamp)
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="uinteger")
     */
    protected $date = 0;

    /**
     * Load error code
     * 
     * @var   mstring
     * @see   ____var_see____
     * @since 1.0.10
     */
    protected $loadError;

    // {{{ Getters / setters

    /**
     * Get body
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getBody()
    {
        if ($this->isURL()) {
            $body = \XLite\Core\Operator::getURLContent($this->getPath());

        } else {
            $body = \Includes\Utils\FileManager::read($this->getStoragePath());
        }

        return $body;
    }

    /**
     * Read output 
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.11
     */
    public function readOutput()
    {
        $result = true;

        if ($this->isURL()) {
            $body = \XLite\Core\Operator::getURLContent($this->getPath());
            if ($body) {
                print $body;

            } else {
                $result = false;
            }

        } else {
            $result = (bool)@readfile($this->getStoragePath());
        }

        return $result;
    }

    /**
     * Check - file exists or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.11
     */
    public function isFileExists()
    {
        if ($this->isURL()) {
            $request = new \PEAR2\HTTP\Request($this->getPath());
            $request->verb = 'HEAD';
            $response = $request->sendRequest();
            $exists = 200 == $response->code
                && isset($response->headers->ContentLength)
                && 0 < $response->headers->ContentLength;

        } else {
            $path = $this->getStoragePath();
            $exists = file_exists($path) && is_readable($path);
        }

        return $exists;
    }

    /**
     * Get URL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getURL()
    {
        $url = null;

        if ($this->isURL()) {
            $url = $this->getPath();

        } elseif (static::STORAGE_RELATIVE == $this->getStorageType()) {
            $url = \XLite::getInstance()->getShopURL(
                $this->getWebRoot() . $this->convertPathToURL($this->getPath()),
                \XLite\Core\Request::getInstance()->isHTTPS()
            );

        } else {

            $root = $this->getFileSystemRoot();
            if (0 === strncmp($root, $this->getPath(), strlen($root))) {
                $path = substr($this->getPath(), strlen($root));
                $url = \XLite::getInstance()->getShopURL(
                    $this->getWebRoot() . $this->convertPathToURL($path),
                    \XLite\Core\Request::getInstance()->isHTTPS()
                );

            } else {
                $url = $this->getGetterURL();
            }
            
        }

        return $url;
    }

    /**
     * Get URL for customer front-end
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getFrontURL()
    {
        return $this->getURL();
    }

    /**
     * Get attachment getter URL 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.11
     */
    public function getGetterURL()
    {
        return \XLite\Core\Converter::buildURL('storage', 'download', array('storage' => get_called_class(), 'id' => $this->getId()));
    }

    /**
     * Get file extension
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getExtension()
    {
        return $this->isURL() ? $this->getExtensionByMIME() : pathinfo($this->getPath(), PATHINFO_EXTENSION);
    }

    /**
     * Get file extension by MIME type
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getExtensionByMIME()
    {
        if (isset(static::$types[$this->getMime()])) {
            $result = static::$types[$this->getMime()];

        } elseif ($this->getMime()) {
            list($tmp, $result) = explode('/', $this->getMime(), 2);

        } else {
            $result = '';
        }

        return $result;
    }

    /**
     * Check file is URL-based or not
     *
     * @param string $path Path OPTIONAL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isURL($path = null)
    {
        return (bool) preg_match('/^(https?|ftp):\/\//Ss', !isset($path) ? $this->getPath() : $path);
    }

    /**
     * Get MIME type icon URL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.10
     */
    public function getMimeClass()
    {
        return 'mime-icon-' . ($this->getExtension() ?: 'unknown');
    }

    /**
     * Get MIME type name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.10
     */
    public function getMimeName()
    {
        $ext = $this->getExtension();

        return $ext ? ($ext . ' file type') : '';
    }

    /**
     * Get load error code
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.10
     */
    public function getLoadError()
    {
        return $this->loadError;
    }

    /**
     * Convert saved path to URL part
     * 
     * @param string $path Path
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function convertPathToURL($path)
    {
        return str_replace(LC_DS, '/', $path);
    }

    // }}}

    // {{{ Loading

    /**
     * Load from request
     *
     * @param string $key    Key in $_FILES service array
     * @param string $subkey Optional subkey OPTIONAL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function loadFromRequest($key, $subkey = null)
    {
        $result = false;

        $cell = isset($_FILES[$key]) ? $_FILES[$key] : null;

        if ($cell && (!$subkey || isset($cell['name'][$subkey]))) {

            $error = $subkey ? $cell['error'][$subkey] : $cell['error'];

            if (UPLOAD_ERR_OK == $error) {

                $tmp = $subkey ? $cell['tmp_name'][$subkey] : $cell['tmp_name'];
                $basename = $subkey ? $cell['name'][$subkey] : $cell['name'];

                $path = \Includes\Utils\FileManager::getUniquePath($this->getStoreFileSystemRoot(), $basename);

                if (move_uploaded_file($tmp, $path)) {

                    $mime = $subkey ? $cell['type'][$subkey] : $cell['type'];
                    if ($mime) {
                        $this->setMime($mime);
                    }
                    chmod($path, 0644);

                    $this->setStorageType(static::STORAGE_RELATIVE);

                    if ($this->savePath($path)) {

                        $result = true;

                    } else {
                        \Includes\Utils\FileManager::deleteFile($path);
                    }
                }
            }

        }

        return $result;
    }

    /**
     * Load from local file
     *
     * @param string $path     Absolute path
     * @param string $basename File name OPTIONAL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function loadFromLocalFile($path, $basename = null)
    {
        $result = true;

        $local = false;
        foreach ($this->getAllowedFileSystemRoots() as $root) {
            if (0 === strncmp($root, $path, strlen($root))) {
                $local = true;
            }
        }

        if (!$local) {

            // Move file
            $newPath = \Includes\Utils\FileManager::getUniquePath(
                $this->getStoreFileSystemRoot(),
                $basename ?: basename($path)
            );

            if (!\Includes\Utils\FileManager::copy($path, $newPath)) {
                $result = false;
            }

            $path = $newPath;
        }

        if ($result) {
            $this->setStorageType($local ? static::STORAGE_ABSOLUTE : static::STORAGE_RELATIVE);
        }

        return $result && $this->savePath($path);
    }

    /**
     * Load from URL
     *
     * @param string  $url     URL
     * @param boolean $copy2fs Copy file to file system or not OPTIONAL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function loadFromURL($url, $copy2fs = false)
    {
        if (2 > func_num_args()) {
            $copy2fs = $this->getRepository()->isStoreRemote();
        }

        $result = $this->isURL($url);

        if ($result) {

            $name = basename(parse_url($url, PHP_URL_PATH));

            $this->setStorageType(static::STORAGE_URL);

            if ($copy2fs) {

                $fn = LC_DIR_TMP . $name;
                $file = \XLite\Core\Operator::getURLContent($url);
                $result = ($file && file_put_contents($fn, $file))
                    ? $this->loadFromLocalFile($fn)
                    : false;

                \Includes\Utils\FileManager::deleteFile($fn);

            } else {

                $this->setPath($url);
                $this->setFileName($name);
                $result = $this->renew();
            }
        }

        return $result;
    }

    // }}}

    // {{{ Service operations

    /**
     * Remove file
     *
     * @param string $path Path OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function removeFile($path = null)
    {
        if (!$this->isURL($path) && ($path || $this->getRepository()->allowRemovePath($this->getStoragePath(), $this))) {
            $path = $path ?: $this->getStoragePath();
            \Includes\Utils\FileManager::deleteFile($path);
        }
    }

    /**
     * Renew storage 
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.11
     */
    public function renewStorage()
    {
        $result = $this->renew();

        foreach ($this->getDuplicates() as $storage) {
            $result = $result && $storage->renewDependentStorage();
        }

        return $result;
    }

    /**
     * Renew dependent storage
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.11
     */
    public function renewDependentStorage()
    {
        return $this->renew();
    }

    /**
     * Get duplicates storages
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.11
     */
    public function getDuplicates()
    {
        return $this->getRepository()->findByFullPath($this->getStoragePath() ?: $this->getPath(), $this);
    }

    /**
     * Prepare order before save data operation
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     * @PrePersist
     * @PreUpdate
     */
    public function prepareBeforeSave()
    {
        if (!$this->getDate()) {
            $this->setDate(time());
        }
    }

    /**
     * Prepare order before save data operation
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     * @PreRemove
     */
    public function prepareRemove()
    {
        if (!$this->isURL()) {
            $this->removeFile();
        }
    }

    /**
     * Save path into entity
     *
     * @param string $path Full path
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function savePath($path)
    {
        $this->loadError = null;

        // Remove old file
        $savePath = static::STORAGE_ABSOLUTE == $this->getStorageType() ? $path : $this->assembleSavePath($path);
        $toRemove = $this->getPath() && $this->getPath() != $savePath;

        $pathToRemove = $this->getPath();
        $this->setPath($savePath);
        $this->setFileName(basename($this->getPath()));

        $result = $this->renew() && $this->updatePathByMIME();

        $result = $result && $this->checkSecurity();

        if ($result && $toRemove) {

            $this->removeFile($pathToRemove);
        }

        return $result;
    }

    /**
     * Assemble path for save into DB
     * 
     * @param string $path Path
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function assembleSavePath($path)
    {
        return basename($path);
    }

    /**
     * Update file path - change file extension taken from MIME information.
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.8
     */
    protected function updatePathByMIME()
    {
        return true;
    }

    /**
     * Renew parameters
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function renew()
    {
        $result = false;

        list($path, $isTempFile) = $this->getLocalPath();

        if (!$isTempFile && $this->isURL($path)) {
            $request = new \PEAR2\HTTP\Request($path);
            $request->verb = 'HEAD';
            $response = $request->sendRequest();
            $exists = 200 == $response->code
                && isset($response->headers->ContentLength)
                && 0 < $response->headers->ContentLength;

        } else {
            $exists = file_exists($path);
        }

        $result = $exists && $this->renewByPath($path);

        if ($isTempFile || (!$result && !$this->isURL($path))) {
            \Includes\Utils\FileManager::deleteFile($path);
        }

        return $result;
    }

    /**
     * Renew properties by path 
     * 
     * @param string $path Path
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function renewByPath($path)
    {
        $this->setSize(intval(filesize($path)));
        $this->setDate(time());

        return true;
    }

    /**
     * Check storage security 
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function checkSecurity()
    {
        return $this->checkPathExtension();
    }

    /**
     * Check path extension 
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function checkPathExtension()
    {
        $result = true;

        if (preg_match('/\.(?:php3?|pl|cgi|py)$/Ss', $this->getPath())) {
            $this->loadError = 'extension';
            $result = false;
        }

        return $result;
    }

    /**
     * Get local path for file-based PHP functions
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLocalPath()
    {
        $isTempFile = false;

        if ($this->isURL()) {

            if (ini_get('allow_url_fopen')) {

                $path = $this->getPath();

            } else {

                $path = tempnam(LC_DIR_TMP, 'analyse_file');

                file_put_contents($path, $this->getBody());

                $isTempFile = true;
            }

        } elseif (static::STORAGE_RELATIVE == $this->getStorageType()) {

            $path = $this->getFileSystemRoot() . $this->getPath();

        } else {
            $path = $this->getPath();
        }

        return array($path, $isTempFile);
    }

    /**
     * Get storage path 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function getStoragePath()
    {
        $path = null;

        if (static::STORAGE_RELATIVE == $this->getStorageType()) {

            $path = $this->getFileSystemRoot() . $this->getPath();

        } elseif (static::STORAGE_ABSOLUTE == $this->getStorageType()) {
            $path = $this->getPath();
        }

        return $path;
    }

    /**
     * Get allowed file system root list
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function getAllowedFileSystemRoots()
    {
        return $this->getRepository()->getAllowedFileSystemRoots();
    }

    /**
     * Get valid file system storage root 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function getValidFileSystemRoot()
    {
        $path = $this->getFileSystemRoot();
        \Includes\Utils\FileManager::mkdirRecursive($path);

        return $path;
    }

    /**
     * Get valid file system storage root
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function getStoreFileSystemRoot()
    {
        return $this->getValidFileSystemRoot();
    }

    /**
     * Get file system images storage root path
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function getFileSystemRoot()
    {
        return $this->getRepository()->getFileSystemRoot();
    }

    /**
     * Get web images storage root path
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function getWebRoot()
    {
        return $this->getRepository()->getWebRoot();
    }

    // }}}
}
