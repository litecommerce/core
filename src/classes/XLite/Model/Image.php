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

/**
 * Meta-image
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Image extends XLite_Model_AModel
{
    const IMAGES_DIR = 'images';
    const IMAGES_CACHE_DIR = 'cache';

    const IMAGE_FILE_EXISTS = 1;
    const IMAGE_OK = 0;
    const IMAGE_NOT_OK = 2;

    /**
     * Image data field name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $dataField   = '';

    /**
     * Image source field name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $sourceField = '';

    /**
     * Image type field name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $typeField   = '';

    /**
     * Width field name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $widthField  = '';

    /**
     * Height field name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $heightField = '';

    /**
     * Image size field name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $sizeField   = '';

    /**
     * Registered image classes 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $registeredImageClasses = null;


    /**
     * Register image class 
     * 
     * @param string $class       Image class
     * @param string $comment     Comment
     * @param string $tableName   Image table alias
     * @param string $fieldPrefix Image fields prefix
     * @param string $idField     Image unique id field name
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function registerImageClass($class, $comment, $tableName, $fieldPrefix, $idField)
    {
        if (!isset(self::$registeredImageClasses)) {
            self::$registeredImageClasses = self::getDefaultImageClasses();
        }

        self::$registeredImageClasses[$class] = new XLite_Model_ImageClass();
        self::$registeredImageClasses[$class]->set(
            'properties',
            array(
                'class'       => $class,
                'comment'     => $comment, 
                'tableName'   => $tableName, 
                'fieldPrefix' => $fieldPrefix, 
                'idField'     => $idField
            )
        );
    }

    /**
     * Get registered image classes 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getImageClasses()
    {
        if (!isset(self::$registeredImageClasses)) {
            self::$registeredImageClasses = self::getDefaultImageClasses();
        }

        return self::$registeredImageClasses;
    }

    /**
     * Get default image classes for registering
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getDefaultImageClasses()
    {
        $list = array(
            'product_thumbnail' => array(
                'comment'     => 'Product thumbnails',
                'tableName'   => 'products',
                'fieldPrefix' => 'thumbnail',
                'idField'     => 'product_id'
            ),
            'product_image' => array(
                'comment'     => 'Product images',
                'tableName'   => 'products',
                'fieldPrefix' => 'image',
                'idField'     => 'product_id'
            ),
            'category' => array(
                'comment'     => 'Category icons',
                'tableName'   => 'categories',
                'fieldPrefix' => 'image',
                'idField'     => 'category_id'
            ),
        );

        $result = array();

        foreach ($list as $key => $value) {
            $result[$key] = new XLite_Model_ImageClass();
            $result[$key]->set('properties', $value);
            $result[$key]->set('class', $key);
        }

        return $result;
    }

    /**
     * Constructor
     * 
     * @param string  $class Image class
     * @param integer $id    Image unique id
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct($class = null, $id = null)
    {
        parent::__construct();

        if (isset($class)) {

            $imageClasses = $this->get('imageClasses');
            $this->imageClass = $class;

            if (isset($imageClasses[$class])) {
                $imageClass = $imageClasses[$class];

                $this->alias         = $imageClass->tableName;
                $this->autoIncrement = $imageClass->idField;
                $this->primaryKey  = array($imageClass->idField);
                $this->fieldPrefix = $imageClass->fieldPrefix;
                $this->dataField   = $imageClass->fieldPrefix;
                $this->sourceField = $imageClass->fieldPrefix . '_source';
                $this->typeField   = $imageClass->fieldPrefix . '_type';
                $this->widthField  = $imageClass->fieldPrefix . '_width';
                $this->heightField = $imageClass->fieldPrefix . '_height';
                $this->sizeField   = $imageClass->fieldPrefix . '_size';

                $this->isPersistent = true;
                $this->fields = array(
                    $imageClass->idField => '',
                    $this->dataField     => '',
                    $this->sourceField   => '',
                    $this->typeField     => '',
                    $this->widthField    => 0,
                    $this->heightField   => 0,
                    $this->sizeField     => 0,
                );
                $this->set($imageClass->idField, $id);

            } else {
                $this->doDie('Image class ' . $class . ' is not registered');
            }
        }
    }

    function copyTo($id)
    {
        $newImg = new XLite_Model_Image($this->imageClass, $this->get($this->autoIncrement));

        if (!$this->isRead) {
            $this->read();
        }

        $newImg->properties = $this->properties;

        if ('F' == $newImg->get('source')) {
            $fnPrevious = $newImg->get('data');
        }

        $newImg->setComplex($this->autoIncrement, $id);

        if ('F' == $newImg->get('source')) {
            $fnNew = $newImg->createFileName($id);
            $newImg->copyImageFile($fnPrevious, $fnNew);
            $newImg->set('data', $fnNew);
        }
        $newImg->update();

        return $newImg;
    }

    function copyImageFile($src, $dest)
    {
        $src = $this->getFilePath($src);
        $dest = $this->getFilePath($dest);

        copyFile($src, $dest, get_filesystem_permissions(0644));
    }

    /**
     * Check - image is readable  or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isReadable()
    {
        $result = false;

        switch ($this->get($this->sourceField)) {
            case 'D';
                $result = 0 < strlen($this->get($this->dataField));
                break;

            case 'F':
                $fn = LC_ROOT_DIR . $this->getFilePath($this->get($this->dataField));
                $result = file_exists($fn) && is_readable($fn) && 0 < filesize($fn);
                break;

            default:
        }

        return $result;
    }

    /**
     * Show image
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function show()
    {
        $pictureSource = $this->get($this->sourceField);
        $pictureData = $this->get($this->dataField);

        header('Content-Type: ' . $this->get($this->typeField));
        if (0 < $this->get($this->sizeField)) {
            header('Content-Length: ' . $this->get($this->sizeField));
        }

        if ('D' == $pictureSource) {
            echo $pictureData;

        } elseif ('F' == $pictureSource) {
            readfile($this->getFilePath($pictureData));
        }

        exit();
    }

    /**
     * Get resized thumbnail URL 
     * 
     * @param integer $width  Crop width
     * @param integer $height Crop height
     *  
     * @return array New width + new height + URL
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getResizedThumbnailURL($width, $height)
    {
        list($neww, $newh) = $this->getCroppedDimensions($width, $height);

        if (
            !$this->isResizeEnabled()
            || ($neww == $this->get('width') && $newh == $this->get('height'))
            || !$this->isReadable()
        ) {
            $url = array($neww, $newh, $this->getURL());

        } else {
            $images_directory = $this->config->Images->images_directory;

            if (!(isset($images_directory) && strlen(trim($images_directory)) > 0)) {
                $images_directory = self::IMAGES_DIR;
            }

            $fileName = $this->alias . '.' . $this->fieldPrefix . '.' . $this->get('id')
                . '.' . $neww . '.' . $newh
                . '.' . preg_replace('/^image\//Ss', '', $this->get('type'));
            $dirName = LC_ROOT_DIR . $images_directory . LC_DS . self::IMAGES_CACHE_DIR;

            $path = $dirName . LC_DS . $fileName;
            $webPath = $images_directory . '/' . self::IMAGES_CACHE_DIR . '/' . $fileName;

            $success = true;

            if (!file_exists($path) || 0 == filesize($path)) {
                if (!file_exists($dirName)) {
                    mkdirRecursive($dirName);
                }
                $resizedImage = $this->resize($neww, $newh);
                if ($resizedImage) {
                    file_put_contents($path, $this->resize($neww, $newh));

                } else {
                    $success = false;
                }
            }

            if ($success) {
                $url = array($neww, $newh, XLite::getInstance()->getShopUrl($webPath));
            }

        }

        return $url;
    }

    /**
     * Check - resize functionality is enabled or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isResizeEnabled()
    {
        return function_exists('imagecreatefromjpeg')
            && function_exists('imagecreatetruecolor')
            && function_exists('imagealphablending')
            && function_exists('imagesavealpha')
            && function_exists('imagecopyresampled');
    }

    /**
     * Get cached files 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCachedFiles()
    {
        $images_directory = $this->config->Images->images_directory;

        if (!(isset($images_directory) && strlen(trim($images_directory)) > 0)) {
            $images_directory = self::IMAGES_DIR;
        }

        $path = LC_ROOT_DIR . $images_directory . LC_DS . self::IMAGES_CACHE_DIR . LC_DS . $this->fieldPrefix . '.' . $this->get('id') . '.*';

        return glob($path);
    }

    /**
     * Clear resized cached images
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function clearCache()
    {
        foreach ($this->getCachedFiles() as $file) {
            @unlink($file);
        }
    }

    /**
     * Getter
     * 
     * @param string $name Property name
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function get($name)
    {
        switch ($name) {
            case 'data':
                $result = parent::get($this->dataField);
                break;

            case 'source':
                $result = parent::get($this->sourceField);
                break;

            case 'type':
                $result = parent::get($this->typeField);
                break;

            case 'width':
                $result = parent::get($this->widthField);
                break;

            case 'height':
                $result = parent::get($this->heightField);
                break;

            case 'size':
                $result = parent::get($this->sizeField);
                break;

            case 'id':
                $result = parent::get($this->autoIncrement);
                break;

            default:
                $result = parent::get($name);
        }

        return $result;
    }

    /**
     * Setter
     * 
     * @param string $name Property name
     * @param mixed  $val  Property value
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function set($name, $val)
    {
        switch ($name) {
            case 'data':
                $result = parent::set($this->dataField, $val);
                break;

            case 'source':
                $result = parent::set($this->sourceField, $val);
                break;

            case 'type':
                $result = parent::set($this->typeField, $val);
                break;

            case 'width':
                $result = parent::set($this->widthField, $val);
                break;

            case 'height':
                $result = parent::set($this->heightField, $val);
                break;

            case 'size':
                $result = parent::set($this->sizeField, $val);
                break;

            case 'id':
                $result = parent::set($this->autoIncrement, $val);
                break;

            default:
                $result = parent::set($name, $val);
        }

        return $result;
    }

    /**
     * Common request handler
     * 
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function handleRequest()
    {
        $data = XLite_Core_Request::getInstance()->getData();

        $result = self::IMAGE_OK;

        if (
            isset($data[$this->fieldPrefix . '_delete'])
            && $data[$this->fieldPrefix . '_delete']
        ) {
            $this->delete();

        } else {
            $result = $this->upload(
                $this->fieldPrefix,
                isset($data[$this->fieldPrefix . '_filesystem']),
                true
            );
        }

        return $result;
    }

    function upload($image_field, $filesystem = false, $force = false)
    {
        $this->_shouldProcessUpload = false;

        $result = self::IMAGE_NOT_OK;

        if (
            isset($_FILES[$image_field])
            && is_uploaded_file($_FILES[$image_field]['tmp_name'])
        ) {
            $this->_shouldProcessUpload = true;
            $upload = new XLite_Model_Upload($_FILES[$image_field]);
            if (!file_exists(LC_TMP_DIR)) {
                mkdirRecursive(LC_TMP_DIR);
            }

            $dest_file = LC_TMP_DIR . $upload->getName();
            if (!$upload->move($dest_file)) {
                return self::IMAGE_NOT_OK;
            }

            if ($filesystem) {

                $this->set('DefaultSource', 'F');
                $this->set('type', $this->getImageType($dest_file));

                $dim = $this->detectImageDimensions($dest_file);
                $this->set('width', $dim['width']);
                $this->set('height', $dim['height']);

                $this->set('size', filesize($dest_file));

                $fn = $this->createFileName();
                $result = $this->importImage($dest_file, 'F', $fn, $force);

            } else {

                $this->set('DefaultSource', 'D');
                $result = $this->importImage($dest_file);

            }

            @unlink($dest_file);
        }

        return $result;
    }

    function getFilePath($filename, $webdir = false)
    {
        $images_directory = $this->config->Images->images_directory;

        if (!(isset($images_directory) && strlen(trim($images_directory)) > 0)) {
            $images_directory = self::IMAGES_DIR;
        }

        while (
            0 < strlen($images_directory)
            && LC_DS == substr($images_directory, strlen($images_directory) - 1, 1)
        ) {
            $images_directory = substr($images_directory, 0, strlen($images_directory) - 1);
        }

        if ($webdir) {
            if (preg_match('/\//', $filename)) {
                $image_name = implode('/', array_map('rawurlencode', explode('/', $filename)));

            } else {
                $image_name = rawurlencode($filename);
            }

            return $images_directory . '/' . $image_name;
        }

        return $images_directory . DIRECTORY_SEPARATOR . $filename;
    }
    
    public function importImage($image_file, $source = 'D', $filename = '', $force = false)
    {
        $this->set('type', $this->getImageType($image_file));

        $dim = $this->detectImageDimensions($image_file);
        $this->set('width', $dim['width']);
        $this->set('height', $dim['height']);

        $this->set('size', filesize($image_file));

        $filepath = $this->getFilePath($filename);
        if (
            $this->get('source') == 'F'
            && $source == 'F'
            && $this->getFilePath($this->get('data')) != $this->getFilePath($filename)
        ) {
            // rename file
            // check if the new file already exists
            if (file_exists($filepath) && !$force) {
                return self::IMAGE_FILE_EXISTS;
            }
        }

        if ($this->get('source') == 'D' && $source == 'F') {
            if (file_exists($filepath)  && !$force) {
                return self::IMAGE_FILE_EXISTS;
            }
        }

        $this->set('source', $source);
        $contents = file_get_contents($image_file);
        if ($this->get('source') == 'F') {
            @unlink($this->getFilePath($this->get('data')));
        }
        if ($source == 'D') {
            $this->set('data', $contents);

        } else {
            if (is_uploaded_file($image_file)) {
                $status = @move_uploaded_file($image_file, $filepath);
            } else {
                $status = @copy($image_file, $filepath);
            }
            
            if ($status === false) {
                return self::IMAGE_NOT_OK;
            }

            @chmod($filepath, get_filesystem_permissions(0644));
            $this->set('data', $filename);
        }

        $this->clearCache();

        $this->update();

        return self::IMAGE_OK;
    }

    function wrongImageType($image_file)
    {
        @unlink($image_file);

        echo "<font color=red>Invalid image file or file not found: $image_file</font>";

        if ($_REQUEST['target'] == "import_catalog"){
            echo '<bt /><br /><a href="admin.php?target=import_catalog"><u>Click here to return to admin interface</u></a>';
        }

        $this->doDie();
    }

    function getImageType($image_file)
    {
        $image_type = "image/gif";
        $r = @getimagesize($image_file) or $this->wrongImageType($image_file);
                            
        $image_types = array(
            "1"  => "image/gif",
            "2"  => "image/jpeg",
            "3"  => "image/png",
            "4"  => "image/swf",
            "5"  => "image/psd",
            "6"  => "image/bmp",
            "7"  => "image/tiff",
            "8"  => "image/tiff",
            "9"  => "image/jpc",
            "10" => "image/jp2",
            "11" => "image/jpx",
            "12" => "image/jp2",
            "13" => "image/swc",
            "14" => "image/iff"
        );
        if (isset($image_types[$r[2]])) {
            $image_type = $image_types[$r[2]];
        }
        return $image_type;
    }

    protected function detectImageDimensions($image_file)
    {
        $r = getimagesize($image_file);

        return array(
            'width'  => @intval($r[0]),
            'height' => @intval($r[1]),
        );
    }

    /**
     * Delete image
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function delete()
    {
        $source = $this->get('source');
        $data = $this->get('data');
        if ('F' == $source) {

            $sql = "SELECT COUNT(*) AS number FROM " . $this->db->getTableByAlias($this->alias) . " WHERE " . $this->sourceField . "='$source' AND " . $this->dataField . "='$data'";
            $images_number = $this->db->getOne($sql);
            if ($images_number == 1) {
                $fn = $this->getFilePath($data);
                // unlink the corresponding file
                @unlink($fn);
            }
        }

        $this->clearCache();

        $this->set('type', '');
        $this->set('source', '');
        $this->set('data', '');
        $this->set('width', 0);
        $this->set('height', 0);
        $this->set('size', 0);

        $this->update();
    }

    /**
     * Check - image is exists or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isExists()
    {
        return parent::isExists() && $this->get('type') != '';
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
        $result = null;

        if (XLite::isAdminZone()) {
            $action = $this->imageClass;

            $result = XLite_Core_Converter::buildURL(
                'image',
                $action,
                array('id' => $this->get('id'), '_' => rand()),
                XLite::CART_SELF
            );

        } elseif ($this->get('source') == 'D') {
            $result = XLite_Core_Converter::buildFullURL(
                'image',
                $this->imageClass,
                array('id' => $this->get('id'))
            );

        } else {
            $result = XLite::getInstance()->getShopUrl(
                $this->getFilePath($this->get('data'), true)
            );
        }

        return $result;
    }

    /**
     * Get default source 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDefaultSource()
    {
        if (!isset($this->config->Images->defaultSources)) {
            return 'D';
        }

        $key = $this->alias . ':' . $this->fieldPrefix;

        return isset($this->config->Images->defaultSources[$key])
            ? $this->config->Images->defaultSources[$key]
            : 'D';
    }

    /**
     * Set default source 
     * 
     * @param string $source Source code
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setDefaultSource($source)
    {
        $key = $this->alias . ':' . $this->fieldPrefix;

        if (!isset($this->config->Images->defaultSources)) {
            $this->config->Images->defaultSources = array($key => $source);

        } else {
            $this->config->Images->defaultSources[$key] = $source;
        }

        XLite_Core_Database::getRepo('XLite_Model_Config')->createOption(
            array(
                'category' => 'Images',
                'name'     => 'defaultSources',
                'value'    => serialize($this->config->Images->defaultSources),
                'type'     => 'serialized'
            )
        );
    }

    public function getFilesystemCount($type = 'F')
    {
        $sql = 'SELECT count(*) FROM ' . $this->db->getTableByAlias($this->alias)
            . ' WHERE ' . $this->sourceField . ' = \'' . $type . '\' AND ' . $this->dataField . ' <> \'\'';

        return $this->db->getOne($sql);
    }

    public function getDatabaseCount()
    {
        return $this->getFilesystemCount('D');
    }
    
    function moveToFilesystem($from = false)
    {
        // select images
        $type = $from ? 'F' : 'D';
        $sql = "SELECT $this->autoIncrement FROM " . $this->db->getTableByAlias($this->alias) . " WHERE " . $this->sourceField . "='$type' and " . $this->dataField . "<>''";
        $n = 0;
        $m = 0;
        $imagesArray = $this->db->getAll($sql);
        if ($from) {
            $imagesHash = array();
            foreach ($imagesArray as $row) {
                $image = new XLite_Model_Image($this->imageClass, $row[$this->autoIncrement]);
                $fn = $image->getFilePath($image->get('data'));
                if (!isset($imagesHash[$fn])) {
                    $imagesHash[$fn] = 1;
                } else {
                    $imagesHash[$fn] ++;
                }
            }
        }
        foreach ($imagesArray as $row) {
            $n++;
            $image = new XLite_Model_Image($this->imageClass, $row[$this->autoIncrement]);
            print ". ";
            func_flush();

            if ($from) {
                // from filesystem to database
                $fn = $image->getFilePath($image->get('data'));
                $image->importImage($fn, 'D');
                $m ++;
                // remove file from filesystem
                $imagesHash[$fn] --;
                if ($imagesHash[$fn] == 0) {
                    if (!@unlink($fn)) {
                        print "<span class=ErrorMessage>WARNING: unable to delete file $fn: permission denied</span><br>";
                    }
                }
            } else {
                // create filename for the image
                $fn = $image->createFileName();
                $filePath = $image->getFilePath($fn);
                //if (is_writable($image->getFilePath('.')) && !file_exists($filePath) || is_writable($filePath)) {
                if (is_writable($image->getFilePath('.'))) {
                    if (($fd = fopen($filePath, "wb"))) {
                        fwrite($fd, $image->get('data'));
                        fclose($fd);
                        @chmod($filePath, get_filesystem_permissions(0644));
                        $image->set('data', $fn);
                        $image->set('source', "F");
                        $image->update();
                        $m ++;
                    } else {
                        print "<span class=ErrorMessage>Error: directory '".$image->getFilePath('.'). "' is not writable!</span><br>\n";
                        func_flush();
                    }
                } else {
                    print "<span class=ErrorMessage>Error: file '$filePath' is not writable!</span><br>\n";
                    func_flush();
                }
            }
        }
        if ((!$from) && ($m > 0)) {
            $table = $this->db->getTableByAlias($this->alias);
            $query = "OPTIMIZE TABLE $table";
            $result = (array) $this->db->getAll($query);
            if (!empty($result)) print "<br>";
            foreach ($result as $row) {
                if ($row['Msg_text'] == "OK") {
                    print "<span class=SuccessMessage>Table '$row[Table]' is optimized.</span><br>\n";
                } else {
                    print "<span class=ErrorMessage>Table optimization failed for '$row[Table]' ('$row[Msg_text]')!</span><br>\n";
                }
            }
            func_flush();
        }
        $this->xlite->set('realyMovedImages', $m);
        return $n;
    }

    function moveToDatabase()
    {
        $this->moveToFilesystem(true);
    }

    function createFileName($id = null)
    {
        if (!isset($id)) {
            $id = $this->get($this->autoIncrement);
        }

        $ext = $this->get('type');
        $ext = empty($ext)
            ? '.gif'
            : ('.' . substr($this->get('type'), 6));

        return substr($this->alias, 0, 1) . substr($this->fieldPrefix, 0, 1) . '_' . $id . $ext;
    }

    /**
     * Resize image
     * 
     * @param integer $width  New width
     * @param integer $height New height
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function resize($width, $height)
    {
        static $types = array(
            'image/jpeg' => 'jpeg',
            'image/jpg'  => 'jpeg',
            'image/gif'  => 'gif',
            'image/xpm'  => 'xpm',
            'image/gd'   => 'gd',
            'image/gd2'  => 'gd2',
            'image/wbmp' => 'wbmp',
            'image/bmp'  => 'wbmp',
        );

        $type = $this->get('type');

        if (!isset($types[$type])) {
            return false;
        }

        $type = $types[$type];

        $func = 'imagecreatefrom' . $type;
        if (!function_exists($func)) {
            return false;
        }

        $source = $this->get($this->sourceField);
        $data = false;

        if ('D' == $source) {
            $data = $this->get($this->dataField);

        } elseif ('F' == $source) {
            $data = file_get_contents(LC_ROOT_DIR . $this->getFilePath($this->get($this->dataField)));
        }

        if (!$data) {
            return false;
        }

        if (function_exists('sys_get_temp_dir')) {
            $dir = sys_get_temp_dir();

        } else {
            $dir = LC_VAR_DIR;
        }

        $fn = tempnam($dir, 'image');

        file_put_contents($fn, $data);
        unset($data, $source);

        $image = $func($fn);
        unlink($fn);

        if (!$image) {
            return false;
        }

        $newImage = imagecreatetruecolor($width, $height);
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);

        $res = imagecopyresampled(
            $newImage,
            $image,
            0,
            0,  
            0,
            0,
            $width,
            $height,
            $this->get('width'),
            $this->get('height')
        );
        imagedestroy($image);

        require_once LC_EXT_LIB_DIR . 'phpunsharpmask.php';

        $unsharpImage = UnsharpMask($newImage);
        if ($unsharpImage) {
            $newImage = $unsharpImage;
        }

        $func = 'image' . $type;

        if (!$func($newImage, $fn)) {
            return false;
        }
        imagedestroy($newImage);

        $image = file_get_contents($fn);
        unlink($fn);

        return $image;
    }

    /**
     * Get cropped image dimensions 
     * 
     * @param integer $maxw Maximum width
     * @param integer $maxh Masimum height
     *  
     * @return array (new width & height)
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCroppedDimensions($maxw, $maxh)
    {
        return XLite_Core_Converter::getCroppedDimensions(
            $this->get('width'),
            $this->get('height'),
            $maxw,
            $maxh
        );
    }
}
