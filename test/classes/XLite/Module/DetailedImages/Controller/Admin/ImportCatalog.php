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
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Class description.
*
* @package Module_DetailedImages
* @access public
* @version $Id$
*/
class XLite_Module_DetailedImages_Controller_Admin_ImportCatalog extends XLite_Controller_Admin_ImportCatalog implements XLite_Base_IDecorator
{
    public function __construct(array $params)
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

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
