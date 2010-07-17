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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ImportCatalog extends AAdmin
{
    public $params = array('target', "page", "import_error");
    public $page = "products"; // the default import page	
    public $pages = array('products' => 'Import products',
                       'extra_fields' => 'Import extra fields'
                       );
    public $pageTemplates = array("products" => "product/import.tpl",
                               "extra_fields" => "product/import_fields.tpl"
                               );
    public $category_id = null;

    function handleRequest()
    {
        if (substr($this->action, 0, 6) == "import" && !$this->checkUploadedFile()) {
            if ($_FILES['userfile']['tmp_name'] != "" && !is_uploaded_file($_FILES['userfile']['tmp_name'])) {
                $this->set('invalid_userfile', true);
                $this->set('invalid_userfile_state', "invalid_upload_file");
            }

            if (\XLite\Core\Request::getInstance()->localfile != "" && !is_readable(\XLite\Core\Request::getInstance()->localfile)) {
                $this->set('invalid_localfile', true);
                $this->set('invalid_localfile_state', "invalid_file");
            }
            
            if ($_FILES['userfile']['tmp_name'] == "" && \XLite\Core\Request::getInstance()->localfile == "") {
                $this->set('invalid_userfile', true);
                $this->set('invalid_userfile_state', "empty_file");
            }

            $this->set('invalid_file', true);
            $this->set('valid', false);
        }
        
        $name = "";
        if ($this->action == "import_products" || $this->action == "layout") {
            if (!\Includes\Utils\ArrayManager::isArrayUnique($this->product_layout, $name, array("NULL"))) {
                $this->set('valid', false);
                $this->set('invalid_field_order', true);
                $this->set('invalid_field_name', $name);
            }

            if ($this->action == "import_products" && !in_array('category', $this->product_layout) && $this->category_id == "") {
                $this->set('valid', false);
                $this->set('category_unspecified_error', true);
            }
        }

        if ( ($this->action == "import_fields" || $this->action == "fields_layout") && !\Includes\Utils\ArrayManager::isArrayUnique($this->fields_layout, $name, array("NULL")) ) {
            $this->set('valid', false);
            $this->set('invalid_field_order', true);
            $this->set('invalid_field_name', $name);
        }
        
        parent::handleRequest();
    }

    function action_import_products()
    {
        $this->startDump();
        $options = array(
            "file"              => $this->getUploadedFile(),
            "layout"            => $this->product_layout,
            "delimiter"         => $this->delimiter,
            "text_qualifier"    => $this->text_qualifier,
            "default_category"  => $this->category_id,
            "delete_products"   => isset($this->delete_products) ? true : false,
            "images_directory"  => ($this->images_directory == "") ? $this->getImagesDir() : $this->images_directory,
            "save_images"       => isset($this->save_images) ? true : false,
            "unique_identifier"	=> $this->unique_identifier,
            "return_error"		=> true,
            );

        $product = new \XLite\Model\Product();
        $product->import($options);
        $this->importError = $product->importError;
    }

    function action_layout($layout_name = "product_layout")
    {
        $layout = implode(',', \XLite\Core\Request::getInstance()->$layout_name);
        \XLite\Core\Database::getRepo('XLite\Model\Config')->createOption(
            array(
                'category' => 'ImportExport',
                'name'     => $layout_name,
                'value'    => $layout
            )
        );
    }

    function action_import_fields()
    {
        $this->startDump();
        $options = array(
            "file"              => $this->getUploadedFile(),
            "layout"            => $this->fields_layout,
            "delimiter"         => $this->delimiter,
            "text_qualifier"    => $this->text_qualifier,
            "return_error"		=> true,
            );
         
        $field = new \XLite\Model\ExtraField();
        $field->import($options);
        $this->importError = $field->importError;
    }

    function action_fields_layout()
    {
        $layout_name = "fields_layout";
        $layout = implode(',', \XLite\Core\Request::getInstance()->$layout_name);
        \XLite\Core\Database::getRepo('XLite\Model\Config')->createOption(
            array(
                'category' => 'ImportExport',
                'name'     => $layout_name,
                'value'    => $layout
            )
        );
    }

    function getPageReturnUrl()
    {
        $text = "Import process failed.";
        $url = "";
        switch ($this->action) {
            case "import_products":
                if (!$this->importError) $text = "Products imported successfully.";
                $url = array($this->importError.'<br>'.$text.' <a href="admin.php?target=import_catalog"><u>Click here to return to admin interface</u></a>');
            break;
            case "import_fields":
                if (!$this->importError) $text = "Product extra fields imported successfully.";
                $url = array($this->importError.'<br>'.$text.' <a href="admin.php?target=import_catalog&page=extra_fields"><u>Click here to return to admin interface</u></a>');
            break;
            default:
                $url = parent::getPageReturnUrl();
        }

        return $url;
    }

    /**
    * @param int     $i          field number
    * @param string  $value      current value
    * @param boolean $default    default state
    */
    function isOrderFieldSelected($id, $value, $default)
    {
        if (($this->action == "import_products" || $this->action == "layout") && $id < count($this->product_layout)) {
            return ($this->product_layout[$id] === $value);
        }
        if (($this->action == "import_fields" || $this->action == "fields_layout") && $id < count($this->fields_layout)) {
            return ($this->fields_layout[$id] === $value);
        }

        return $default;
    }

    function getImagesDir()
    {
        $image = new \XLite\Model\Image();
        return ($this->xlite->config->Images->images_directory != "") ? $this->xlite->config->Images->images_directory : \XLite\Model\Image::IMAGES_DIR;
    }
}
