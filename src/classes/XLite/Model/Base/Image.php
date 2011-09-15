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
 * Image abstract store
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @MappedSuperclass
 * @HasLifecycleCallbacks
 */
abstract class Image extends \XLite\Model\AEntity
{
    /**
     * Image unique id
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger", nullable=false)
     */
    protected $image_id;

    /**
     * Image path (URL or file name in images storage directory)
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="512", nullable=false)
     */
    protected $path;

    /**
     * MIME type
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="64", nullable=false)
     */
    protected $mime = 'image/jpeg';

    /**
     * Width
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $width;

    /**
     * Height
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $height;

    /**
     * Size
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $size;

    /**
     * Create / modify date (UNIX timestamp)
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $date = 0;

    /**
     * Image hash
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="fixedstring", length="32", nullable=true)
     */
    protected $hash = '';

    /**
     * Get image body
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getBody()
    {
        return $this->isURL()
            ? \XLite\Core\Operator::getURLContent($this->path)
            : \Includes\Utils\FileManager::read($this->getRepository()->getFileSystemRoot() . $this->path);
    }

    /**
     * Get image file extension
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
     * Get image file extension by MIME type
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getExtensionByMIME()
    {
        static $types = array(
            'image/jpeg' => 'jpeg',
            'image/jpg'  => 'jpeg',
            'image/gif'  => 'gif',
            'image/xpm'  => 'xpm',
            'image/gd'   => 'gd',
            'image/gd2'  => 'gd2',
            'image/wbmp' => 'bmp',
            'image/bmp'  => 'bmp',
            'image/png'  => 'png',
        );

        if (isset($types[$this->mime])) {
            $result = $types[$this->mime];

        } elseif ($this->mime) {
            list($tmp, $result) = explode('/', $this->mime, 2);

        } else {
            $result = 'jpeg';
        }

        return $result;
    }

    /**
     * Get image URL
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
     * Get image URL for customer front-end
     *
     * @return string|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getFrontURL()
    {
        return (!$this->getRepository()->isCheckImage() || $this->checkImageHash()) ? $this->getURL() : null;
    }

    /**
     * Get resized image URL
     *
     * @param integer $width  Width limit OPTIONAL
     * @param integer $height Height limit OPTIONAL
     *
     * @return array (new width + new height + URL)
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getResizedURL($width = null, $height = null)
    {
        $sizeName = ($width ? $width : 'x') . '.' . ($height ? $height : 'x');

        $path = $this->getRepository()->getFileSystemCacheRoot($sizeName);

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $fn = $this->image_id . '.' . $this->getExtension();

        if (
            file_exists($path . $fn)
            && filesize($path . $fn) > 0
        ) {

            // File exists
            list($newWidth, $newHeight) = \XLite\Core\ImageOperator::getCroppedDimensions(
                $this->width,
                $this->height,
                $width,
                $height
            );

            $url = \XLite::getInstance()->getShopURL(
                $this->getRepository()->getWebCacheRoot($sizeName) . '/' . $fn,
                \XLite\Core\Request::getInstance()->isHTTPS()
            );

        } else {

            // File does not exist
            $operator = new \XLite\Core\ImageOperator($this);

            list($newWidth, $newHeight, $result) = $operator->resizeDown($width, $height);

            $url = (false === $result || !file_put_contents($path . $fn, $operator->getImage()))
                ? $this->getURL()
                : \XLite::getInstance()->getShopURL(
                    $this->getRepository()->getWebCacheRoot($sizeName) . '/' . $fn,
                    \XLite\Core\Request::getInstance()->isHTTPS()
                );

            if (file_exists($path . $fn)) {
                chmod($path . $fn, 0644);
            }
        }

        return array(
            $newWidth,
            $newHeight,
            $url
        );
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
     * Load image from local file
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

            // File already in image storage
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
     * Load image from URL
     *
     * @param string  $url     URL
     * @param boolean $copy2fs Copy image to file system or not OPTIONAL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function loadFromURL($url, $copy2fs = false)
    {
        if (2 > func_num_args()) {
            $copy2fs = $this->getRepository()->isStoreRemoteImage();
        }

        $result = true;

        if ($copy2fs) {

            $fn = tempnam(LC_DIR_TMP, 'load_image');

            $image = \XLite\Core\Operator::getURLContent($url);

            $result = ($image && file_put_contents($fn, $image))
                ? $this->loadFromLocalFile($fn)
                : false;

            if (!$result) {
                \Includes\Utils\FileManager::deleteFile($fn);
            }

        } else {

            $this->path = $url;

            $result = $this->renewImageParameters();
        }

        return $result;
    }

    /**
     * Remove image file
     *
     * @param string $path Path to image OPTIONAL
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
     * Check - image hash is equal data from DB or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function checkImageHash()
    {
        list($path, $isTempFile) = $this->getImagePath();

        $hash = \Includes\Utils\FileManager::getHash($path);

        if ($isTempFile) {
            \Includes\Utils\FileManager::deleteFile($path);
        }

        return $this->hash === $hash;
    }

    /**
     * Check image is URL-based or not
     *
     * @param string $path Path to image OPTIONAL
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
     * Check - image is exists in DB or not
     * TODO - remove - old method
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isExists()
    {
        return !is_null($this->getImageId());
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
        // Remove old image
        $toRemove = $this->path && $this->path != basename($path);

        $pathToRemove = $this->path;
        $this->path = basename($path);

        $result = $this->renewImageParameters() && $this->updatePathByMIME();

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
        list($path, $isTempFile) = $this->getImagePath();

        $newExtension = $this->getExtensionByMIME();
        $pathinfo = pathinfo($path);
        $newPath = $pathinfo['dirname'] . LC_DS . $pathinfo['filename'] . '.' . $newExtension;

        $result = rename($path, $newPath);

        if ($result) {
            $this->path = basename($newPath);
        }

        return $result;
    }

    /**
     * Renew image parameters
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function renewImageParameters()
    {
        $result = false;

        list($path, $isTempFile) = $this->getImagePath();

        $data = @getimagesize($path);

        if (is_array($data)) {

            $this->width    = $data[0];
            $this->height   = $data[1];
            $this->mime     = $data['mime'];
            $this->hash     = \Includes\Utils\FileManager::getHash($path);
            $this->size     = intval(filesize($path));
            $this->date     = time();

            $result = true;
        }

        if ($isTempFile || !$result) {
            \Includes\Utils\FileManager::deleteFile($path);
        }

        return $result;
    }

    /**
     * Get image path for file-based PHP functions
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getImagePath()
    {
        $isTempFile = false;

        if ($this->isURL()) {

            if (ini_get('allow_url_fopen')) {

                $path = $this->path;

            } else {

                $path = tempnam(LC_DIR_TMP, 'analyse_image');

                file_put_contents($path, $this->getBody());

                $isTempFile = true;
            }

        } else {

            $path = $this->getRepository()->getFileSystemRoot() . $this->path;
        }

        return array($path, $isTempFile);
    }
}
