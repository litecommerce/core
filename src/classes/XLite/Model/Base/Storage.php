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
     * MIME type to extenstion translation table
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.10
     */
    protected static $types = array();

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
     * Get body
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getBody()
    {
        return $this->isURL()
            ? \XLite\Core\Operator::getURLContent($this->getPath())
            : \Includes\Utils\FileManager::read($this->getRepository()->getFileSystemRoot() . $this->getPath());
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
        return $this->isURL()
            ? $this->getPath()
            : \XLite::getInstance()->getShopURL(
                $this->getRepository()->getWebRoot() . $this->getPath(),
                \XLite\Core\Request::getInstance()->isHTTPS()
            );
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

                $root = $this->getRepository()->getFileSystemRoot();

                $path = \Includes\Utils\FileManager::getUniquePath($root, $basename);

                if (move_uploaded_file($tmp, $path)) {

                    $mime = $subkey ? $cell['type'][$subkey] : $cell['type'];
                    if ($mime) {
                        $this->setMime($mime);
                    }
                    chmod($path, 0644);

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

        $root = $this->getRepository()->getFileSystemRoot();

        if (0 === strncmp($root, $path, strlen($root))) {

            // File already in storage
            $path = substr($path, strlen($root));

        } else {

            // Move file
            $newPath = \Includes\Utils\FileManager::getUniquePath($root, $basename ?: basename($path));

            if (!\Includes\Utils\FileManager::copy($path, $newPath)) {
                $result = false;
            }

            $path = $newPath;
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

        $result = true;

        if ($copy2fs) {

            $fn = tempnam(LC_DIR_TMP, 'load_file');

            $file = \XLite\Core\Operator::getURLContent($url);

            $result = ($file && file_put_contents($fn, $file))
                ? $this->loadFromLocalFile($fn)
                : false;

            if (!$result) {
                \Includes\Utils\FileManager::deleteFile($fn);
            }

        } else {

            $this->path = $url;

            $result = $this->renew();
        }

        return $result;
    }

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
        if (!$this->isURL($path)) {

            $path = $this->getRepository()->getFileSystemRoot() . (is_null($path) ? $this->path : $path);

            \Includes\Utils\FileManager::deleteFile($path);
        }
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
        return (bool) preg_match('/^https?:\/\//Ss', is_null($path) ? $this->path : $path);
    }

    /**
     * Get MIME type icon URL 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.10
     */
    public function getMimeIconURL()
    {
        return 'images/spacer.gif';
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
        return 'Unknown';
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
        // Remove old file
        $toRemove = $this->path && $this->path != basename($path);

        $pathToRemove = $this->path;
        $this->path = basename($path);
        $this->setFileName($this->path);

        $result = $this->renew() && $this->updatePathByMIME();

        if ($result && $toRemove) {

            $this->removeFile($pathToRemove);
        }

        return $result;
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

        $result = $this->renewByPath($path);

        if ($isTempFile || !$result) {
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

                $path = $this->path;

            } else {

                $path = tempnam(LC_DIR_TMP, 'analyse_file');

                file_put_contents($path, $this->getBody());

                $isTempFile = true;
            }

        } else {

            $path = $this->getRepository()->getFileSystemRoot() . $this->path;
        }

        return array($path, $isTempFile);
    }
}
