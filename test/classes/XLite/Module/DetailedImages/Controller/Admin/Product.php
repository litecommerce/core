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
class XLite_Module_DetailedImages_Controller_Admin_Product extends XLite_Controller_Admin_Product implements XLite_Base_IDecorator
{
    public function __construct(array $params)
    {
		parent::__construct($params);

        $this->pages["detailed_images"] = "Detailed images";
        $this->pageTemplates["detailed_images"] = "modules/DetailedImages/detailed_images.tpl";
    }
    
    function action_add_detailed_image()
    {
        $d_img = new XLite_Module_DetailedImages_Model_DetailedImage();

		$data = XLite_Core_Request::getInstance()->getData();
		$data['is_zoom'] = isset($data['is_zoom']) ? 'Y' : '';

        $d_img->set("properties", $data); 
        $d_img->create();

        $img = $d_img->get("image");

        $img->handleRequest();
    }

    function action_delete_detailed_image()
    {
        $d_img = new XLite_Module_DetailedImages_Model_DetailedImage($this->image_id);
        $d_img->delete();
    }

    function action_update_detailed_images()
    {
        foreach ($this->alt as $image_id => $alt) {
            $img = new XLite_Module_DetailedImages_Model_DetailedImage($image_id);

            $img->set("alt", $alt);
            $img->set("order_by", $this->order_by[$image_id]);
            $img->set("is_zoom", (isset($this->is_zoom) && isset($this->is_zoom[$image_id])) ? 'Y' : '');

            $img->update();
        }    
    }
}
