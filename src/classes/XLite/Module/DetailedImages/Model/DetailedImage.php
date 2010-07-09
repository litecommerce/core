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
 * Detailed image
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_DetailedImages_Model_DetailedImage extends XLite_Model_AModel
{
    /**
     * Model fields list
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $fields = array(
    	'image_id'     => 0,
        'product_id'   => 0,
        'image_source' => 'D',
        'image_type'   => 'image/jpeg',
        'alt'          => '',
        'enabled'      => 1,
        'order_by'     => 0,
        'is_zoom'      => '',
    );

    /**
     * Table alias 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $alias = 'images';

    /**
     * Auto increment field name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $autoIncrement = 'image_id';

    /**
     * Default order field name
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $defaultOrder = 'order_by';

    /**
     * Image object (cache)
     * 
     * @var    XLite_Model_Image
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $image = null;

    /**
     * Get image 
     * 
     * @return XLite_Model_Image
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getImage()
    {
        if (is_null($this->image)) {
            $this->image = new XLite_Model_Image('detailed_image', $this->get('image_id'));
        }

        return $this->image;
    }

    /**
     * Get image URL 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getImageURL()
    {
        return $this->getImage()->get('url');
    }

    /**
     * Find images by product id
     * 
     * @param int $product_id Product id
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findImages($product_id = 0)
    {
        return $this->findAll('product_id = \'' . $product_id . '\'');
    }

    /**
     * Find image for zoom functionality
     * 
     * @param int $product_id Product id
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findZoom($product_id = 0)
    {
        return $this->findAll('product_id = \'' . $product_id . '\' AND is_zoom = \'Y\'');
    }

    function getImportFields($layout = null)
    {
        $layout = array();
        if (isset($this->config->ImportExport->detailed_images_layout)) {
            $layout = explode(',', $this->config->ImportExport->detailed_images_layout);
        }

        // detailed image import fields
        $fields = array(
            'NULL'     => true,
            'sku'      => false,
            'name'     => false,
            'image'    => false,
            'alt'      => false,
            'enabled'  => false,
            'order_by' => false
        );

        $result = array();

        // build multiarray
        foreach ($fields as $name) {
            $result[] = $fields;
        }

        // fill fields array with the default layout
        foreach ($result as $id => $fields) {
            if (isset($layout[$id])) {
                $selected = $layout[$id];
                $result[$id][$selected] = true;
            }
        }

        return $result;
    }

    function deepCopyTo($id)
    {
        $_image = $this->getImage();

        $newImg = new XLite_Model_Image($_image->imageClass, $_image->get($_image->autoIncrement));

        if (!$_image->isRead) {
            $_image->read();
        }

        $newImg->properties = $_image->properties;
        if ($newImg->get('source') == 'F') {
            $fnPrevious = $newImg->get('data');
        }

        $newImg->setComplex($_image->autoIncrement, $id);
        if ($newImg->get('source') == 'F') {

            // createFileName
            if (is_null($id)) {
                   $id = $newImg->get($newImg->autoIncrement);
            }

            $ext = $newImg->get('type');
            $ext = (empty($ext)) ? '.gif' : ('.' . substr($newImg->get('type'), 6));
            $fnNew = $newImg->alias{0} . $newImg->fieldPrefix{0} . '_' . $id . $ext;

            // copyImageFile
            $src = $newImg->getFilePath($fnPrevious);
            $dest = $newImg->getFilePath($fnNew);
            copy($src, $dest);
            @chmod($dest, 0644);

            $newImg->set('data', $fnNew);
        }

        $newImg->update();

        return $newImg;
    }

    public function import(array $options)
    {
        static $line;

        if (!isset($line)) {
            $line = 1;

        } else {
            $line++;
        }

        $properties = $options['properties'];
        $save_images = $options['save_images'];
        $images_directory = $options['images_directory'];

        if (!empty($images_directory)) {

            // update images base directory
            XLite_Core_Database::getRepo('XLite_Model_Config')->createOption(
                array(
                    'category' => 'Images',
                    'name'     => 'images_directory',
                    'value'    => $images_directory
                )
            );

            // re-read config data
            XLite_Core_Config::readConfig();
        }

        $image = $properties['image'];

        $images_directory = isset($this->config->Images->images_directory)
            ? $this->config->Images->images_directory 
            : '';

        $image_path = empty($images_directory) 
            ? $image 
            : $images_directory . '/' . $image;

        $product = new XLite_Model_Product();
        $found = false;

        if (
            !empty($properties['sku'])
            && $product->find('sku = \'' . addslashes($properties['sku']) . '\'')
        ) {
            // try to find product by SKU
            $found = true;

        } elseif (
            empty($properties['sku']) &&
            !empty($properties['name']) && $product->find('name = \'' . addslashes($properties['name']) . '\'')
        ) {
            // .. or by NAME
            $found = true;
        }

        if (!$found) {
            $this->doDie(
                'line# ' . $line . ': No product found for detailed image ' . $image
            );
        }

        $detailed_image = new XLite_Module_DetailedImages_Model_DetailedImage();
        echo '<b>line# ' . $line . ':</b> Importing detailed image $image for product ' . $product->get('name' ) .'<br />' . "\n";

        // create detailed image
        $detailed_image->set('product_id', $product->get('product_id'));
        $detailed_image->set('properties', $properties);
        $detailed_image->create();

        // fill image content
        $img = $detailed_image->get('image');
        if ($save_images) {

            // save image content to database
            $img->import($image_path);

        } else {

            // update image info
            $img->set('data', $image);
            $img->set('source', 'F');
            $img->set('type', $img->getImageType($image_path));
            $img->update();
        }
    }
    
    function delete()
    {
        $this->getImage()->delete();
        parent::delete();
    }
}
