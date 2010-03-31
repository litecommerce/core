<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

define('IMAGES_DIR', 'images');
define('IMAGE_FILE_EXISTS', 1);
define('IMAGE_OK', 0);
define('IMAGE_NOT_OK', 2);

/**
* Only-persistent object implementing images stored
* in the database
*
* @package Kernel
* @access public
* @version $Id$
**/
class XLite_Model_Image extends XLite_Model_Abstract implements XLite_Base_ISingleton
{
	protected $dataField   = '';
	protected $sourceField = '';
	protected $typeField   = '';

	public static function getInstance()
    {
        return self::getInternalInstance(__CLASS__);
    }

    /**
    * Register a new image class from module.
    */
    function registerImageClass($class, $comment, $tableName, $fieldPrefix, $idField)
    {
        global $_registered_image_classes;
        if (!isset($_registered_image_classes)) {
            $_registered_image_classes = $this->getDefaultImageClasses();
        }
        $_registered_image_classes[$class] = new XLite_Model_ImageClass();
        $_registered_image_classes[$class]->set("properties", array(
            'class' => $class,
            'comment' => $comment, 
            'tableName' => $tableName, 
            'fieldPrefix' => $fieldPrefix, 
            'idField' => $idField
            ));
    }

    function getImageClasses()
    {
        global $_registered_image_classes;
        if (!isset($_registered_image_classes)) {
            $_registered_image_classes = $this->getDefaultImageClasses();
        }
        return $_registered_image_classes;
    }

    function getDefaultImageClasses()
    {
        $result = array();
        foreach (array(
            'product_thumbnail' => array(
                'comment' => 'Product thumbnails',
                'tableName' => 'products',
                'fieldPrefix' => 'thumbnail',
                'idField' => 'product_id'),
            'product_image' => array(
                'comment' => 'Product images',
                'tableName' => 'products',
                'fieldPrefix' => 'image',
                'idField' => 'product_id'),
            'category' => array(
                'comment' => 'Category icons',
                'tableName' => 'categories',
                'fieldPrefix' => 'image',
                'idField' => 'category_id'),
             ) as $key => $value) {
             $result[$key] = new XLite_Model_ImageClass();
             $result[$key]->set("properties", $value);
             $result[$key]->set("class", $key);
         }

         return $result;
    }
	/**
	* Construct an image stored in the table alias $tableName
	* It must have the following fields: "$fielPrefix" (image data/filename),
	* "$fielPrefix_type" (mime type)
	* and "$fielPrefix_source", which is one of 'D' (data) or 'F' (filename).
	* $id specifies the row index (auto_increment).
	*/
    public function __construct($class = null, $id = null)
    {    
        parent::__construct();

        if (!is_null($class)) {
            $imageClasses = $this->get("imageClasses");
            $this->imageClass = $class;
            if (isset($imageClasses[$class])) {
				$imageClass = $imageClasses[$class];
                $this->alias = $imageClass->tableName;
                $this->autoIncrement = $imageClass->idField;
                $this->primaryKey = array($imageClass->idField);
                $this->fieldPrefix = $imageClass->fieldPrefix;
                $this->dataField = $imageClass->fieldPrefix;
                $this->sourceField = $imageClass->fieldPrefix."_source";
                $this->typeField = $imageClass->fieldPrefix."_type";
                $this->isPersistent = true;
                $this->fields = array(
                        $imageClass->idField  => "",
                        $this->dataField      => "",
                        $this->sourceField    => "",
                        $this->typeField      => ""
                        );
                $this->set($imageClass->idField, $id);
            } else {
                $this->doDie("Image class $class is not registered");
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
        if ($newImg->get("source") == "F") {
        	$fnPrevious = $newImg->get("data");
        }
        $newImg->setComplex($this->autoIncrement, $id);
        if ($newImg->get("source") == "F") {
        	$fnNew = $newImg->createFileName($id);
            $newImg->copyImageFile($fnPrevious, $fnNew);
			$newImg->set("data", $fnNew);
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
	* Send the image to the standard output.
	**/
    function show()
    {
		$picture_type = $this->get($this->typeField);
		$picture_source = $this->get($this->sourceField);
		$picture_data = $this->get($this->dataField);
        header("Content-type: $picture_type");
        if ($picture_source == "D") {
            echo $picture_data;
        } elseif ($picture_source == "F") {
            $picture_file = $this->getFilePath($picture_data);
            readfile ($picture_file);
        }
        exit();
    }

	/**
	* Define fields 'data', 'source' and 'type'
	**/
	function get($name)
	{
		switch ($name) {
			case 'data':
				return parent::get($this->dataField);
			case 'source':
				return parent::get($this->sourceField);
			case 'type':
				return parent::get($this->typeField);
	        case 'id':
				return parent::get($this->autoIncrement);
		}

		return parent::get($name);
	}

	/**
	* Define fields 'data', 'source' and 'type'
	**/
	function set($name, $val)
	{
		switch ($name) {
		case 'data':   return parent::set($this->dataField, $val);
		case 'source': return parent::set($this->sourceField, $val);
		case 'type':   return parent::set($this->typeField, $val);
        case 'id': return parent::set($this->autoIncrement, $val);
		default: return parent::set($name, $val);
		}
	}

	function handleRequest()
	{
		if (isset($_REQUEST[$this->fieldPrefix . "_delete"]) && $_REQUEST[$this->fieldPrefix . "_delete"]) {
			$this->delete();
			return IMAGE_OK;
		} else {
			if (isset($_REQUEST[$this->fieldPrefix . "_filesystem"])) {
				$filesystem = true;
			} else {
				$filesystem = false;
			}
			return $this->upload($this->fieldPrefix, $filesystem, true);
		}
	}

	function upload($image_field, $filesystem = false, $force = false)
	{
        $this->_shouldProcessUpload = false;
	    if (isset($_FILES[$image_field]) && is_uploaded_file($_FILES[$image_field]["tmp_name"])) {
	    	$this->_shouldProcessUpload = true;
            $upload = new XLite_Model_Upload($_FILES[$image_field]);
            $dest_file = "var/tmp/".$upload->getName();
            if (!$upload->move($dest_file)) {
                return IMAGE_NOT_OK;
            }

			if ($filesystem) {
				$this->set("DefaultSource", "F");
				$this->set("type", $this->getImageType($dest_file));
				$fn = $this->createFileName();
				$result = $this->importImage($dest_file, 'F', $fn, $force);
			} else {
				$this->set("DefaultSource", "D");
				$result = $this->importImage($dest_file);
			}
        	@unlink($dest_file);

        	return $result;
		}
		return IMAGE_NOT_OK;
	}

	function getFilePath($filename, $webdir = false)
	{
		$images_directory = $this->config->getComplex('Images.images_directory');
		if (!(isset($images_directory) && strlen(trim($images_directory)) > 0)) {
			$images_directory = IMAGES_DIR;
		}
		while ((strlen($images_directory) > 0) && (substr($images_directory,strlen($images_directory)-1,1) == DIRECTORY_SEPARATOR)) {
			$images_directory = substr($images_directory, 0, strlen($images_directory)-1);
		}

        if ($webdir) {
            if (preg_match("/\//", $filename)) {
                $filename = split('/', $filename);
                $image_name = array();
                foreach ($filename as $fname) {
                    $image_name[] = rawurlencode($fname);
                }
                $image_name = join('/', $image_name);
            } else {
                $image_name = rawurlencode($filename);
            }
            return $images_directory . "/" . $image_name;
        }
        return $images_directory . DIRECTORY_SEPARATOR . $filename;
	}
	
	public function importImage($image_file, $source = 'D', $filename = '', $force = false)
	{
		$this->set("type", $this->getImageType($image_file));
		$filepath = $this->getFilePath($filename);
		if ($this->get("source") == "F" && $source == "F" && $this->getFilePath($this->get("data")) != $this->getFilePath($filename)) { 
			// rename file
			// check if the new file already exists
			if (file_exists($filepath) && !$force) {
				return IMAGE_FILE_EXISTS;
			}
		}
		if ($this->get("source") == "D" && $source == "F") {
			if (file_exists($filepath)  && !$force) {
				return IMAGE_FILE_EXISTS;
			}
		}
		$this->set("source", $source);
		$contents = file_get_contents($image_file);
		if ($this->get("source") == "F") {
			@unlink($this->getFilePath($this->get("data")));
		}
		if ($source == "D") {
			$this->set("data", $contents);
		} else {
			if (is_uploaded_file($image_file)) {
				$status = @move_uploaded_file($image_file, $filepath);
			} else {
				$status = @copy($image_file, $filepath);
			}
			
			if ($status === false) {
				return IMAGE_NOT_OK;
			}

			@chmod($filepath, get_filesystem_permissions(0644));
			$this->set("data", $filename);
		}
		$this->update();

		return IMAGE_OK;
	}

	function wrongImageType($image_file)
	{
		@unlink($image_file);

        echo "<font color=red>Invalid image file or file not found: $image_file</font>";

        if($_REQUEST['target'] == "import_catalog"){
            echo '<bt /><br /><a href="admin.php?target=import_catalog"><u>Click here to return to admin interface</u></a>';    
        }

        die;
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

	function delete()
	{
		$source = $this->get("source");
		$data = $this->get("data");
		if ($source == "F") {
			$sql = "SELECT COUNT(*) AS number FROM " . $this->db->getTableByAlias($this->alias) . " WHERE " . $this->sourceField . "='$source' AND " . $this->dataField . "='$data'";
			$images_number = $this->db->getOne($sql);
			if ($images_number == 1) {
    			$fn = $this->getFilePath($data);
    			// unlink the corresponding file
    			@unlink($fn);
    		}
		}
		$this->set("type", "");
		$this->set("source", "");
		$this->set("data", "");
		$this->update();
	}

	function isExists()
	{
		return parent::isExists() && $this->get("type") != '';
	}
	
	function getURL()
	{
		if ($this->xlite->is("adminZone")) {
			$action = $this->imageClass;
			return "cart.php?target=image&action=$action&id=" . $this->get("id")."&_".rand();
		}

		if ($this->get("source") == "D") { // database, fetch php
			return XLite_Core_Converter::buildFullURL('image', $this->imageClass, array('id' => $this->get('id')));
		} else {
			return XLite::getInstance()->getShopUrl($this->getFilePath($this->get("data"), true));
		}
	}

	/**
	* Return last image source set by setDefaultSource for this image alias/
	* fieldPrefix pair
	*/
	function getDefaultSource()
	{
		if (!isset($this->config->Images->defaultSources)) {
			return "D"; // default is file system
		}
		$key = $this->alias . ":" . $this->fieldPrefix;
		return isset($this->config->Images->defaultSources[$key]) ? $this->config->Images->defaultSources[$key] : "D";
	}

	function setDefaultSource($source)
	{
		$c = new XLite_Model_Config();
		$c->set("category", "Images");
		$c->set("name", "defaultSources");
		$key = $this->alias . ":" . $this->fieldPrefix;
		if (!isset($this->config->Images->defaultSources)) {
			$this->config->Images->defaultSources = array($key => $source);
		} else {
			$this->config->Images->defaultSources[$key] = $source;
		}
		$c->set("value", serialize($this->config->Images->defaultSources));
		$c->update();
	}

	function getFilesystemCount($type = 'F')
	{
		$sql = "SELECT count(*) FROM " . $this->db->getTableByAlias($this->alias) . " WHERE " . $this->sourceField . "='$type' AND " . $this->dataField . "<>''";
		return $this->db->getOne($sql);
	}

	function getDatabaseCount()
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
				$fn = $image->getFilePath($image->get("data"));
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
				$fn = $image->getFilePath($image->get("data"));
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
				//if (is_writable($image->getFilePath(".")) && !file_exists($filePath) || is_writable($filePath)) {
                if (is_writable($image->getFilePath("."))) {
					if (($fd = fopen($filePath, "wb"))) {
						fwrite($fd, $image->get("data"));
						fclose($fd);
						@chmod($filePath, get_filesystem_permissions(0644));
						$image->set("data", $fn);
						$image->set("source", "F");
						$image->update();
						$m ++;
					} else {
						print "<span class=ErrorMessage>Error: directory '".$image->getFilePath("."). "' is not writable!</span><br>\n";
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
		$this->xlite->set("realyMovedImages", $m);
		return $n;
	}

	function moveToDatabase()
	{
		$this->moveToFilesystem(true);
	}

	function createFileName($id = null)
	{
		if (is_null($id)) {
			$id = $this->get($this->autoIncrement);
		}
		$ext = $this->get("type");
        $ext = (empty($ext)) ? ".gif" : ("." . substr($this->get("type"), 6));
		return $this->alias{0} . $this->fieldPrefix{0} . "_$id$ext";
	}
	
}

