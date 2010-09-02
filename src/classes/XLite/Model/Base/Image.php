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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Model\Base;

/**
 * Image abstract store
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 *
 * @MappedSuperclass
 */
abstract class Image extends \XLite\Model\AEntity
{
    /**
     * Image unique id 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column (type="integer", length="11", nullable=false)
     */
    protected $image_id;

    /**
     * Image-owner id
     * TODO - remove - backward compatibility
     *
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer")
     */
    protected $id;

    /**
     * Image path (URL or file name in images storage directory)
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="512", nullable=false)
     */
    protected $path;

    /**
     * MIME type
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="64", nullable=false)
     */
    protected $mime = 'image/jpeg';

    /**
     * Width
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer")
     */
    protected $width;

    /**
     * Height
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer")
     */
    protected $height;

    /**
     * Size
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer")
     */
    protected $size;

    /**
     * Create / modify date (UNIX timestamp)
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer")
     */
    protected $date = 0;

    /**
     * Image hash
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="32")
     */
    protected $hash = '';

    /**
     * Set width (forbidden operation)
     *
     * @param mixed $value Value
     * 
     * @return false
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setWidth($value)
    {
        return false;
    }

    /**
     * Set height (forbidden operation)
     *
     * @param mixed $value Value
     *
     * @return false
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setHeight($value)
    {
        return false;
    }

    /**
     * Set size (forbidden operation)
     *
     * @param mixed $value Value
     *
     * @return false
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setSize($value)
    {
        return false;
    }

    /**
     * Set MIME type (forbidden operation)
     *
     * @param mixed $value Value
     *
     * @return false
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setMime($value)
    {
        return false;
    }

    /**
     * Set date (forbidden operation)
     *
     * @param mixed $value Value
     *
     * @return false
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setDate($value)
    {
        return false;
    }

    /**
     * Set hash (forbidden operation)
     *
     * @param mixed $value Value
     *
     * @return false
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setHash($value)
    {
        return false;
    }

    /**
     * Set path (forbidden operation)
     *
     * @param mixed $value Value
     *
     * @return false
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setPath($value)
    {
        return false;
    }

    /**
     * Get image body 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getBody()
    {
        return $this->isURL()
            ? \XLite\Core\Operator::getURLContent($this->path)
            : file_get_contents($this->getRepository()->getFileSystemRoot() . $this->path);
    }

    /**
     * Get image file extension 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getExtension()
    {
        return $this->isURL()
            ? $this->getExtensionByMIME()
            : pathinfo($this->path, PATHINFO_EXTENSION);
    }

    /**
     * Get image file extension by MIME type
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getURL()
    {
        return $this->isURL()
            ? $this->path
            : $this->getRepository()->getWebRoot() . $this->path;
    }

    /**
     * Get image URL for customer front-end
     * 
     * @return string or null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getFrontURL()
    {
        return (!$this->getRepository()->isCheckImage() || $this->checkImageHash())
            ? $this->getURL()
            : null;
    }

    /**
     * Get resized image URL 
     * 
     * @param integer $width  Width limit
     * @param integer $height Height limit
     *  
     * @return array (new width + new height + URL)
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getResizedURL($width = null, $height = null)
    {
        $sizeName = ($width ? $width : 'x') . '.' . ($height ? $height : 'x');
        $path = $this->getRepository()->getFileSystemCacheRoot($sizeName);

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $fn = $this->image_id . '.' . $this->getExtension();

        if (file_exists($path . $fn)) {

            // File is exists
            $result = \XLite\Core\Converter::getCroppedDimensions(
                $this->width,
                $this->height,
                $width,
                $height
            );

            $result[2] = $this->getRepository()->getWebCacheRoot($sizeName) . '/' . $fn;

        } else {

            // File is not exists
            $result = \XLite\Core\Converter::resizeImageSoft($this, $width, $height);
            $result[2] = (!$result[2] || !file_put_contents($path . $fn, $result[2]))
                ? $this->getURL()
                : $this->getRepository()->getWebCacheRoot($sizeName) . '/' . $fn;
        }

        return $result;
    }

    /**
     * Load from request 
     * 
     * @param string $key    Key in $_FILES service array
     * @param string $subkey Optional subkey
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
                    $this->path = basename($path);
                    $result = $this->renewImageParameters();
                    if (!$result) {
                        unlink($path);
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
     * @param string $basename File name
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
            if (!copy($path, $newPath)) {
                $result = false;
            }
            $path = $newPath;
        }

        if ($result) {
            $this->path = basename($path);
            $this->renewImageParameters();
        }

        return $result;
    }

    /**
     * Load image from URL 
     * 
     * @param string  $url     URL
     * @param boolean $copy2fs Copy image to file system or not
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function loadFromURL($url, $copy2fs = false)
    {
        if (2 > func_num_args()) {
            $copy2fs = $this->getRepository()->isStoreRemoteImage();
        }

        $result = true;

        if ($copy2fs) {
            $fn = tempnam(LC_TMP_DIR, 'load_image');
            $image = \XLite\Core\Operator::getURLContent($url);
            $result = ($image && file_put_contents($fn, $image))
                ? $this->loadFromLocalFile($fn)
                : false;

        } else {
            $this->path = $url;
            $this->renewImageParameters();
        }

        return $result;
    }

    /**
     * Renew image parameters 
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function renewImageParameters()
    {
        $result = false;

        list($path, $isTempFile) = $this->getImagePath();

        $data = @getimagesize($path);

        if (is_array($data)) {
            $this->width = $data[0];
            $this->height = $data[1];
            $this->mime = $data['mime'];
            $this->hash = md5_file($path);
            $this->size = intval(filesize($path));
            $this->date = time();

            $result = true;
        }

        if ($isTempFile) {
            unlink($path);
        }

        return $result;
    }

    /**
     * Check - image hash is equal data from DB or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function checkImageHash()
    {
        list($path, $isTempFile) = $this->getImagePath();

        $hash = md5_file($path);

        if ($isTempFile) {
            unlink($path);
        }

        return $this->hash == $hash;
    }

    /**
     * Check image is URL-based or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isURL()
    {
        return (bool)preg_match('/^https?:\/\//Ss', $this->path);
    }

    /**
     * Get image path for file-based PHP functions
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getImagePath()
    {
        $isTempFile = false;

        if ($this->isURL()) {
            if (ini_get('allow_url_fopen')) {
                $path = $this->path;

            } else {
                $path = tempnam(LC_TMP_DIR, 'analyse_image');
                file_put_contents($path, $this->getBody());
                $isTempFile = true;
            }

        } else {

            $path = $this->getRepository()->getFileSystemRoot() . $this->path;
        }

        return array($path, $isTempFile);
    }

    /**
     * Check - image is exists in DB or not
     * TODO - remove - old method
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isExists()
    {
        return isset($this->image_id);
    }
}
