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
abstract class Image extends \XLite\Model\Base\Storage
{
    /**
     * MIME type to extenstion translation table
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.10
     */
    protected static $types = array(
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
     * Image hash
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="fixedstring", length=32, nullable=true)
     */
    protected $hash;

    /**
     * Get image URL for customer front-end
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getFrontURL()
    {
        return (!$this->getRepository()->isCheckImage() || $this->checkImageHash()) ? parent::getFrontURL() : null;
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
        $result = true;

        if ($this->getHash()) {
            list($path, $isTempFile) = $this->getLocalPath();

            $hash = \Includes\Utils\FileManager::getHash($path);

            if ($isTempFile) {
                \Includes\Utils\FileManager::deleteFile($path);
            }

            $result = $this->getHash() === $hash;
        }

        return $result;
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
        return !is_null($this->getId());
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
        $result = parent::updatePathByMIME();

        if ($result && !$this->isURL()) {
            list($path, $isTempFile) = $this->getLocalPath();

            $newExtension = $this->getExtensionByMIME();
            $pathinfo = pathinfo($path);
            $newPath = $pathinfo['dirname'] . LC_DS . $pathinfo['filename'] . '.' . $newExtension;

            $result = rename($path, $newPath);

            if ($result) {
                $this->path = basename($newPath);
            }
        }

        return $result;
    }

    /**
     * Renew properties by path
     *
     * @param string $path Path
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function renewByPath($path)
    {
        $result = parent::renewByPath($path);

        if ($result) {
            $data = @getimagesize($path);

            if (is_array($data)) {

                $this->setWidth($data[0]);
                $this->setHeight($data[1]);
                $this->setMime($data['mime']);
                $hash = \Includes\Utils\FileManager::getHash($path);
                if ($hash) {
                    $this->setHash($hash);
                }

            } else {
                $result = false;
            }
        }

        return $result;
    }

    // {{{ Resized icons

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
        $size = ($width ?: 'x') . '.' . ($height ?: 'x');
        $name = $this->getId() . '.' . $this->getExtension();
        $path = $this->getResizedPath($size, $name);

        $url = $this->getResizedPublicURL($size, $name);

        if ($this->isResizedIconAvailable($path)) {
            list($newWidth, $newHeight) = \XLite\Core\ImageOperator::getCroppedDimensions(
                $this->getWidth(),
                $this->getHeight(),
                $width,
                $height
            );

        } else {
            $result = $this->resizeIcon($width, $height, $path);
            if ($result) {
                list($newWidth, $newHeight) = $result;

            } else {
                $url = $this->getURL();
            }
        }

        return array($newWidth, $newHeight, $url);
    }

    /**
     * Get resized file system path 
     * 
     * @param string $size Size prefix
     * @param string $name File name
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getResizedPath($size, $name)
    {
        return $this->getRepository()->getFileSystemCacheRoot($size) . $name;
    }

    /**
     * Get resized file public URL
     *
     * @param string $size Size prefix
     * @param string $name File name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getResizedPublicURL($size, $name)
    {
        return \XLite::getInstance()->getShopURL(
            $this->getRepository()->getWebCacheRoot($size) . '/' . $name,
            \XLite\Core\Request::getInstance()->isHTTPS()
        );
    }

    /**
     * Check - resized icon is available or not
     * 
     * @param string $path Resized image path
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function isResizedIconAvailable($path)
    {
        return \Includes\Utils\FileManager::isFile($path) && $this->getDate() < filemtime($path);
    }

    /**
     * Resize icon 
     * 
     * @param integer $width  Destination width
     * @param integer $height Destination height
     * @param string  $path   Write path
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function resizeIcon($width, $height, $path)
    {
        $operator = new \XLite\Core\ImageOperator($this);
        list($newWidth, $newHeight, $result) = $operator->resizeDown($width, $height);

        return false !== $result && \Includes\Utils\FileManager::write($path, $operator->getImage())
            ? array($newWidth, $newHeight)
            : null;
    }

    // }}}
}
