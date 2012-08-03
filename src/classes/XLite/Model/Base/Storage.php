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

namespace XLite\Model\Base;

/**
 * Storage abstract store
 *
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
     * @var array
     */
    protected static $types = array();

    /**
     * Unique id
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger")
     */
    protected $id;

    /**
     * Path (URL or file name in storage directory)
     *
     * @var string
     *
     * @Column (type="string", length=512)
     */
    protected $path;

    /**
     * File name
     *
     * @var string
     *
     * @Column (type="string")
     */
    protected $fileName = '';

    /**
     * MIME type
     *
     * @var string
     *
     * @Column (type="string", length=64)
     */
    protected $mime = 'application/octet-stream';

    /**
     * Storage type
     *
     * @var string
     *
     * @Column (type="string", length=1)
     */
    protected $storageType = self::STORAGE_RELATIVE;

    /**
     * Size
     *
     * @var integer
     *
     * @Column (type="uinteger")
     */
    protected $size = 0;

    /**
     * Create / modify date (UNIX timestamp)
     *
     * @var integer
     *
     * @Column (type="uinteger")
     */
    protected $date = 0;

    /**
     * Load error code
     * 
     * @var mstring
     */
    protected $loadError;

    // {{{ Getters / setters

    /**
     * Get body
     *
     * @return string
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
     * Get storage type 
     * 
     * @return string
     */
    public function getStorageType()
    {
        if (!$this->storageType) {
            $this->storageType = $this->isURL($this->getPath())
                ? static::STORAGE_URL
                : static::STORAGE_RELATIVE;
        }

        return $this->storageType;
    }

    /**
     * Read output 
     *
     * @param integer $start  Start popsition
     * @param integer $length Length
     * 
     * @return boolean
     */
    public function readOutput($start = null, $length = null)
    {
        $result = true;

        if ($this->isURL()) {
            $body = \XLite\Core\Operator::getURLContent($this->getPath());
            if ($body) {
                if (isset($start)) {
                    $body = isset($length) ? substr($body, $start, $length) : substr($body, $start);
                }
                print $body;

            } else {
                $result = false;
            }

        } elseif (isset($start)) {

            $fp = @fopen($this->getStoragePath(), 'rb');
            if ($fp) {
                fseek($fp, $start);
                if (isset($length)) {
                    print fread($fp, min($length, filesize($this->getStoragePath()) - $start));

                } else {
                    print fread($fp, filesize($this->getStoragePath()) - $start);
                }
                fclose($fp);

            } else {
                $result = false;
            }

        } else {
            $result = (bool)@readfile($this->getStoragePath());
        }

        return $result;
    }

    /**
     * Check if file exists
     * 
     * @param string  $path      Path to check OPTIONAL
     * @param boolean $forceFile Flag OPTIONAL
     *
     * @return boolean
     */
    public function isFileExists($path = null, $forceFile = false)
    {
        if ($this->isURL($path) && !$forceFile) {
            $request = new \XLite\Core\HTTP\Request($path ?: $this->getPath());
            $response = $request->sendRequest();

            $exists = 200 == $response->code && !empty($response->headers->ContentLength);

        } else {
            $exists = \Includes\Utils\FileManager::isFileReadable($path ?: $this->getStoragePath());

        }

        return $exists;
    }

    /**
     * Get URL
     *
     * @return string
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
     */
    public function getFrontURL()
    {
        return $this->getURL();
    }

    /**
     * Get attachment getter URL 
     * 
     * @return string
     */
    public function getGetterURL()
    {
        return \XLite\Core\Converter::buildURL('storage', 'download', $this->getGetterParams(), \XLite::CART_SELF);
    }

    /**
     * Get file extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->isURL() ? $this->getExtensionByMIME() : pathinfo($this->getPath(), PATHINFO_EXTENSION);
    }

    /**
     * Get file extension by MIME type
     *
     * @return string
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
     */
    public function isURL($path = null)
    {
        return (bool) preg_match('/^(https?|ftp):\/\//Ss', !isset($path) ? $this->getPath() : $path);
    }

    /**
     * Get MIME type icon URL
     *
     * @return string
     */
    public function getMimeClass()
    {
        return 'mime-icon-' . ($this->getExtension() ?: 'unknown');
    }

    /**
     * Get MIME type name
     *
     * @return string
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
     */
    protected function convertPathToURL($path)
    {
        return str_replace(LC_DS, '/', $path);
    }

    /**
     * Get getter parameters
     * 
     * @return array
     */
    protected function getGetterParams()
    {
        return array(
            'storage' => get_called_class(),
            'id'      => $this->getId(),
        );
    }

    // }}}

    // {{{ Loading

    /**
     * Load from request
     *
     * @param string $key Key in $_FILES service array
     *
     * @return boolean
     */
    public function loadFromRequest($key)
    {
        $path = \Includes\Utils\FileManager::moveUploadedFile($key, $this->getStoreFileSystemRoot());

        if ($path) {
            $this->setStorageType(static::STORAGE_RELATIVE);

            if (!empty($_FILES[$key]['type'])) {
                $this->setMime($_FILES[$key]['type']);
            }

            if (!$this->savePath($path)) {
                \Includes\Utils\FileManager::deleteFile($path);
                $path = null;
            }

        } else {
            \XLite\Logger::getInstance()->log('The file was not loaded', LOG_ERR);
        }

        return !empty($path);
    }

    /**
     * Load from local file
     *
     * @param string $path     Absolute path
     * @param string $basename File name OPTIONAL
     *
     * @return boolean
     */
    public function loadFromLocalFile($path, $basename = null)
    {
        $result = true;

        if (\Includes\Utils\FileManager::isExists($path)) {

            foreach ($this->getAllowedFileSystemRoots() as $root) {
                if (\Includes\Utils\FileManager::getRelativePath($path, $root)) {
                    $local = true;
                    break;
                }
            }

            if (empty($local)) {
                $newPath = \Includes\Utils\FileManager::getUniquePath(
                    $this->getStoreFileSystemRoot(),
                    $basename ?: basename($path)
                );

                if (\Includes\Utils\FileManager::copy($path, $newPath)) {
                    $path = $newPath;
                    $this->setStorageType(static::STORAGE_RELATIVE);

                } else {
                    \XLite\Logger::getInstance()->log(
                        '\'' . $path . '\' file could not be copied to a new location \'' . $newPath . '\'.',
                        LOG_ERR
                    );
                    $result = false;
                }

            } else {
                $this->setStorageType(static::STORAGE_ABSOLUTE);
            }

        } else {
            $result = false;
        }

        if ($result && $basename) {
            $this->setFileName($basename);
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
     */
    public function loadFromURL($url, $copy2fs = false)
    {
        $result = $this->isURL($url);

        if ($result) {
            $name = basename(parse_url($url, PHP_URL_PATH));

            if ($copy2fs) {
                $file = \XLite\Core\Operator::getURLContent($url);
                $result = !empty($file);

                if ($result) {
                    $tmp = LC_DIR_TMP . $name;
                    $result = \Includes\Utils\FileManager::write($tmp, $file);
                    if ($result) {
                        $result = $this->loadFromLocalFile($tmp);

                    } else {
                        \XLite\Logger::getInstance()->log(
                            'Unable to write data to file \'' . $tmp . '\'.',
                            LOG_ERR
                        );
                    }

                    if ($result) {
                        \Includes\Utils\FileManager::deleteFile($tmp);
                    }

                } else {
                    \XLite\Logger::getInstance()->log(
                        'Unable to get at the contents of \'' . $url . '\'.',
                        LOG_ERR
                    );
                }

            } else {
                $savedPath = $this->getPath();
                $this->setPath($url);
                $this->setFileName($name);

                $result = $this->renew();

                if ($result) {
                    $this->removeFile($savedPath);
                    $this->setStorageType(static::STORAGE_URL);
                }
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
     */
    public function removeFile($path = null)
    {
        $path = $this->getStoragePath($path);

        if (!$this->isURL($path) && $this->getRepository()->allowRemovePath($path, $this)) {
            \Includes\Utils\FileManager::deleteFile($path);
        }
    }

    /**
     * Renew storage 
     * 
     * @return boolean
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
     */
    public function renewDependentStorage()
    {
        return $this->renew();
    }

    /**
     * Get duplicates storages
     * 
     * @return array
     */
    public function getDuplicates()
    {
        return $this->getRepository()->findByFullPath($this->getStoragePath() ?: $this->getPath(), $this);
    }

    /**
     * Prepare order before save data operation
     *
     * @return void
     *
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
     *
     * @PreRemove
     */
    public function prepareRemove()
    {
        if (!$this->isURL()) {
            $this->removeFile();
        }
    }

    /**
     * Get storage path
     *
     * @param string $path Path to use OPTIONAL
     *
     * @return string
     */
    public function getStoragePath($path = null)
    {
        $result = null;

        if (static::STORAGE_RELATIVE == $this->getStorageType()) {
            $result = $this->getFileSystemRoot() . ($path ?: $this->getPath());

        } elseif (static::STORAGE_ABSOLUTE == $this->getStorageType()) {
            $result = ($path ?: $this->getPath());
        }

        return $result;
    }

    /**
     * Save path into entity
     *
     * @param string $path Full path
     *
     * @return boolean
     */
    protected function savePath($path)
    {
        $this->loadError = null;

        // Remove old file
        $savePath = static::STORAGE_ABSOLUTE == $this->getStorageType() ? $path : $this->assembleSavePath($path);
        $toRemove = $this->getPath() && $this->getPath() !== $savePath;

        $pathToRemove = $this->getPath();
        $this->setPath($savePath);
        if (!$this->getFileName()) {
            $this->setFileName(basename($this->getPath()));
        }

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
     */
    protected function assembleSavePath($path)
    {
        return basename($path);
    }

    /**
     * Update file path - change file extension taken from MIME information.
     *
     * @return boolean
     */
    protected function updatePathByMIME()
    {
        return true;
    }

    /**
     * Renew parameters
     *
     * @return boolean
     */
    protected function renew()
    {
        $result = false;
        list($path, $isTempFile) = $this->getLocalPath();

        $result = $this->isFileExists($path, $isTempFile) && $this->renewByPath($path);

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
     */
    protected function renewByPath($path)
    {
        $this->setSize(intval(\Includes\Utils\FileManager::getFileSize($path)));
        $this->setDate(time());

        return true;
    }

    /**
     * Check storage security 
     * 
     * @return boolean
     */
    protected function checkSecurity()
    {
        return $this->checkPathExtension();
    }

    /**
     * Check path extension 
     * 
     * @return boolean
     */
    protected function checkPathExtension()
    {
        $result = true;

        if (preg_match('/\.(?:php3?|pl|cgi|py|htaccess)$/Ss', $this->getPath())) {
            $this->loadError = 'extension';
            $result = false;
        }

        return $result;
    }

    /**
     * Get local path for file-based PHP functions
     *
     * @return string
     */
    protected function getLocalPath()
    {
        $isTempFile = false;

        if ($this->isURL()) {
            if (ini_get('allow_url_fopen')) {
                $path = $this->getPath();

            } else {
                $path = tempnam(LC_DIR_TMP, 'analyse_file');
                if (!\Includes\Utils\FileManager::write($path, $this->getBody())) {
                    \XLite\Logger::getInstance()->log(
                        'Unable to write data to file \'' . $path . '\'.',
                        LOG_ERR
                    );
                    $path = false;
                }
                $isTempFile = true;
            }

        } else {
            $path = $this->getStoragePath();
        }

        return array($path, $isTempFile);
    }

    /**
     * Get allowed file system root list
     * 
     * @return array
     */
    protected function getAllowedFileSystemRoots()
    {
        return $this->getRepository()->getAllowedFileSystemRoots();
    }

    /**
     * Get valid file system storage root 
     * 
     * @return string
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
     */
    protected function getStoreFileSystemRoot()
    {
        return $this->getValidFileSystemRoot();
    }

    /**
     * Get file system images storage root path
     * 
     * @return void
     */
    protected function getFileSystemRoot()
    {
        return $this->getRepository()->getFileSystemRoot();
    }

    /**
     * Get web images storage root path
     *
     * @return string
     */
    protected function getWebRoot()
    {
        return $this->getRepository()->getWebRoot();
    }

    // }}}
}
