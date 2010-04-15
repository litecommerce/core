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

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_DetailedImages_Controller_Admin_ImportCatalog extends XLite_Controller_Admin_ImportCatalog implements XLite_Base_IDecorator
{
    public function __construct(array $params = array())
    {
        parent::__construct($params);
        $this->pages["detailed_images"] = "Import images";
        $this->pageTemplates["detailed_images"] = "modules/DetailedImages/import.tpl";
    }
    
    function action_import_detailed_images()
    {
        $this->startDump();
        // save column layout
        $this->action_layout("detailed_images_layout");

        $options = array(
                "file" => $this->getUploadedFile(),
                "layout" => $this->detailed_images_layout,
                "delimiter" => $this->delimiter,
                "text_qualifier"    => $this->text_qualifier,
                "images_directory" => $this->images_directory,
                "save_images" => isset($this->save_images) ? true : false,
				"return_error" => true,
                );

        $detailed_image = new XLite_Module_DetailedImages_Model_DetailedImage();
        $detailed_image->import($options);
		$this->importError = $detailed_image->importError;

		$text = "Import process failed.";
		if (!$this->importError) $text = "Detailed images imported successfully.";
		$text = $this->importError.'<br><br>'.$text.' <a href="admin.php?target=import_catalog&page=detailed_images"><u>Click here to return to admin interface</u></a><br><br>';

		echo $text;
		func_refresh_end();
		exit();
    }
}
