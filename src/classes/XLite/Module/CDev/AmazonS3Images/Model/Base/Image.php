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

namespace XLite\Module\CDev\AmazonS3Images\Model\Base;

/**
 * Image abstract store
 *
 *
 * @MappedSuperclass
 *
 * @MappedSuperclass
 */
abstract class Image extends \XLite\Model\Base\Image implements \XLite\Base\IDecorator
{
    const STORAGE_S3 = '3';

    const IMAGES_NAMESPACE = 'images';

    /**
     * S3 icons cache
     *
     * @var array
     *
     * @Column (type="array", nullable=true)
     */
    protected $s3icons = array();

    /**
     * AWS S3 client
     * 
     * @var \XLite\Module\CDev\AmazonS3Images\Core\S3
     */
    protected $s3;

    /**
     * Forbid Amazon S3 storage for loading
     * 
     * @var boolean
     */
    protected $s3Forbid = false;

    /**
     * Set S3 forbid 
     * 
     * @param boolean $flag Flag OPTIONAL
     *  
     * @return void
     */
    public function setS3Forbid($flag = false)
    {
        $this->s3Forbid = $flag;
    }

    // {{{ Getters

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        if (self::STORAGE_S3 == $this->getStorageType()) {
            $body = $this->getS3() ? $this->getS3()->read($this->generateS3Path()) : null;

        } else {
            $body = parent::getBody();
        }

        return $body;
    }

    /**
     * Read output
     *
     * @param integer $start  Start popsition OPTIONAL
     * @param integer $length Length OPTIONAL
     *
     * @return boolean
     */
    public function readOutput($start = null, $length = null)
    {
        if (self::STORAGE_S3 == $this->getStorageType()) {
            $result = false;
            $body = $this->getBody();
            if ($body) {
                if (isset($start)) {
                    $body = isset($length) ? substr($body, $start, $length) : substr($body, $start);
                }
                $result = true;
                print ($body);
            }

        } else {
            $result = parent::readOutput($start, $length);
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
        if (self::STORAGE_S3 == $this->getStorageType() && !$forceFile) {
            $exists = $this->getS3() ? $this->getS3()->isExists($this->generateS3Path($path)) : false;

        } else {
            $exists = parent::isFileExists($path, $forceFile);
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
        if (self::STORAGE_S3 == $this->getStorageType()) {
            $url = $this->getS3() ? $this->getS3()->getURL($this->generateS3Path()) : null;

        } else {
            $url = parent::getURL();
        }

        return $url;
    }

    /**
     * Get file extension
     *
     * @return string
     */
    public function getExtension()
    {
        return self::STORAGE_S3 == $this->getStorageType() ? $this->getExtensionByMIME() : parent::getExtension();
    }

    // }}}

    // {{{ Loading and service

    /**
     * Load from request
     *
     * @param string $key Key in $_FILES service array
     *
     * @return boolean
     */
    public function loadFromRequest($key)
    {
        if (!$this->s3Forbid && $this->getS3()) {

            $result = false;
            $path = \Includes\Utils\FileManager::moveUploadedFile($key, LC_DIR_TMP);
            if ($path) {
                $result = $this->loadFromLocalFile($path, $_FILES[$key]['name']);
                \Includes\Utils\FileManager::deleteFile($path);

            } else {
                \XLite\Logger::getInstance()->log('The file was not loaded', LOG_ERR);
            }

        } else {
            $result = parent::loadFromRequest($key);
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
     */
    public function loadFromLocalFile($path, $basename = null)
    {
        if (!$this->s3Forbid && $this->getS3()) {
            $result = false;

            if (\Includes\Utils\FileManager::isExists($path)) {
                $data = @getimagesize($path);
                if (is_array($data)) {
                    $basename = $basename ?: basename($path);
                    $s3Path = $this->generateS3Path($basename);
                    $s3Path = $this->getS3()->generateUniquePath($s3Path);

                    $headers = array(
                        'Content-Type'        => $data['mime'],
                        'Content-Disposition' => 'inline; filename="' . $basename . '"',
                    );

                    if ($this->getS3()->copy($path, $s3Path, $headers)) {
                        $this->setStorageType(static::STORAGE_S3);
                        $this->setMime($data['mime']);

                        if ($this->savePath($s3Path)) {
                            $result = true;
                        }

                    } else {
                        \XLite\Logger::getInstance()->log(
                            '[Amazon S3] The file \'' . $path . '\' was not copyed to \'' . $s3Path . '\'',
                            LOG_ERR
                        );
                    }
                }
            }

        } else {
            $result = parent::loadFromLocalFile($path, $basename);
        }

        return $result;
    }

    /**
     * Remove file
     *
     * @param string $path Path OPTIONAL
     *
     * @return void
     */
    public function removeFile($path = null)
    {
        if (self::STORAGE_S3 == $this->getStorageType()) {
            $this->getS3()->delete($this->getStoragePath($path));
            if ($this->getId()) {
                $dir = $this->getStoragePath('icon/' . $this->getId());
                if ($this->getS3()->isDir($dir)) {
                    $this->getS3()->deleteDirectory($dir);
                }
            }

        } else {
            parent::removeFile($path);
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
        if (self::STORAGE_S3 == $this->getStorageType()) {
            $result = $this->generateS3Path($path);

        } else {
            $result = parent::getStoragePath($path);
        }

        return $result;
    }

    /**
     * Get local path for file-based PHP functions
     *
     * @return array
     */
    protected function getLocalPath()
    {
        if (self::STORAGE_S3 == $this->getStorageType()) {

            $path = tempnam(LC_DIR_TMP, 'analyse_file');
            $result = \Includes\Utils\FileManager::write(
                $path,
                $this->getS3()->read($this->getStoragePath())
            );

            if (!$result) {
                \XLite\Logger::getInstance()->log(
                    'Unable to write data to file \'' . $path . '\'.',
                    LOG_ERR
                );
                $path = false;
            }

            $result = array($path, true);

        } else {
            $result = parent::getLocalPath();
        }

        return $result;
    }

    /**
     * Update file path - change file extension taken from MIME information.
     *
     * @return boolean
     */
    protected function updatePathByMIME()
    {
        return self::STORAGE_S3 == $this->getStorageType() ? true : parent::updatePathByMIME();
    }

    /**
     * Get S3 client
     * 
     * @return \XLite\Module\CDev\AmazonS3Images\Core\S3
     */
    protected function getS3()
    {
        if (!isset($this->s3)) {
            $this->s3 = \XLite\Module\CDev\AmazonS3Images\Core\S3::getInstance();
            if (!$this->s3->isValid()) {
                $this->s3 = false;
            }
        }

        return $this->s3;
    }

    /**
     * Generate AWS S3 short path 
     * 
     * @param string $path Path from DB OPTIONAL
     *  
     * @return string
     */
    protected function generateS3Path($path = null)
    {
        return self::IMAGES_NAMESPACE
            . '/' . $this->getRepository()->getStorageName()
            . '/' . ($path ?: $this->getPath());
    }

    // }}}

    // {{{ Resize icon

    /**
     * Get resized file system path
     *
     * @param string $size Size prefix
     * @param string $name File name
     *
     * @return string
     */
    protected function getResizedPath($size, $name)
    {
        return $this->isUseS3Icons()
            ? $this->generateS3Path('icon/' . $this->getId() . '/' . $size . '.' . $this->getExtension())
            : parent::getResizedPath($size, $name);
    }

    /**
     * Get resized file public URL
     *
     * @param string $size Size prefix
     * @param string $name File name
     *
     * @return string
     */
    protected function getResizedPublicURL($size, $name)
    {
        return $this->isUseS3Icons()
            ? $this->getS3()->getURL($this->getResizedPath($size, $name))
            : parent::getResizedPublicURL($size, $name);
    }

    /**
     * Check - resized icon is available or not
     *
     * @param string $path Resized image path
     *
     * @return boolean
     */
    protected function isResizedIconAvailable($path)
    {
        $icons = $this->getS3Icons();

        return ($this->isUseS3Icons() && $icons)
            ? !empty($icons[$path])
            : parent::isResizedIconAvailable($path);
    }

    /**
     * Resize icon
     *
     * @param integer $width  Destination width
     * @param integer $height Destination height
     * @param string  $path   Write path
     *
     * @return array
     */
    protected function resizeIcon($width, $height, $path)
    {
        $result = null;

        if ($this->isUseS3Icons()) {
           $operator = new \XLite\Core\ImageOperator($this);
            list($newWidth, $newHeight, $r) = $operator->resizeDown($width, $height);

            if (false !== $r) {
                $basename = $this->getFileName() ?: basename($this->getPath());
                $headers = array(
                    'Content-Type'        => $this->getMime(),
                    'Content-Disposition' => 'inline; filename="' . $basename . '"',
                );

                if ($this->getS3()->write($path, $operator->getImage(), $headers)) {
                    $icons = $this->getS3Icons();
                    $icons[$path] = true;
                    $this->setS3Icons($icons);
                    \XLite\Core\Database::getEM()->flush();
                    $result = array($newWidth, $newHeight);
                }
            }

        } else {
            $result = parent::resizeIcon($width, $height, $path);
        }

        return $result;
    }

    /**
     * Use S3 icons
     * 
     * @return boolean
     */
    protected function isUseS3Icons()
    {
        return static::STORAGE_S3 == $this->getStorageType() && $this->getS3();
    }

    // }}}
}
