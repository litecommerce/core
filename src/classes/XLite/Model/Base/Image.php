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
    protected $image_id;

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
     * @Column (type="fixedstring", length="32")
     */
    protected $hash = '';

    /**
     * Get unique id 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.11
     */
    public function getId()
    {
        return $this->getImageId();
    }

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

        $fn = $this->getId() . '.' . $this->getExtension();

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
     * Check - image hash is equal data from DB or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function checkImageHash()
    {
        list($path, $isTempFile) = $this->getLocalPath();

        $hash = \Includes\Utils\FileManager::getHash($path);

        if ($isTempFile) {
            \Includes\Utils\FileManager::deleteFile($path);
        }

        return $this->getHash() === $hash;
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

                $this->width    = $data[0];
                $this->height   = $data[1];
                $this->mime     = $data['mime'];
                $this->hash     = \Includes\Utils\FileManager::getHash($path);

            } else {
                $result = false;
            }
        }

        return $result;
    }
}
