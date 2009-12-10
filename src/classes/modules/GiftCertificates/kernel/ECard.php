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

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* E-Card information.
*
* @package Module_GiftCertificate
* @access public
* @version $Id: ECard.php,v 1.13 2008/10/23 11:54:47 sheriff Exp $
*/
class ECard extends Base
{
    var $alias = "ecards";
    var $fields = array(
        'ecard_id' => '',
        'template' => '', // use this template as e-mail body
        'order_by' => 0,
        'enabled' => 1);
    var $autoIncrement = 'ecard_id';
    var $defaultOrder = 'order_by';
    var $thumbnail = null;
    var $image = null;

    function &getThumbnail()
    {
        if (is_null($this->thumbnail)) {
            $this->thumbnail = func_new("Image","ecard_thumbnail", $this->get("ecard_id"));
        }
        return $this->thumbnail;
    }

    function &getImage()
    {
        if (is_null($this->image)) {
            $this->image = func_new("Image", 'ecard_image', $this->get("ecard_id"));
        }
        return $this->image;
    }

    function &getAllTemplates()
    {
        $templates = array();
        $layout = func_get_instance("Layout");
        // "skins/mail/" . $layout->get("locale") .
        // $layout->set("skin", "mail");
        // $path = $layout->getPath() . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "GiftCertificates" . DIRECTORY_SEPARATOR . "ecards";
        $path = "skins/mail/" . $layout->get("locale") . "/modules/GiftCertificates/ecards";
        if ($dh = opendir($path)) { 
            while (($file = readdir($dh)) !== false) { 
                if (is_file($path . DIRECTORY_SEPARATOR . $file) && substr($file,-4) == ".tpl") {
                    $templates[] = substr($file, 0, strlen($file)-4);
                } 
            } 
            closedir($dh); 
        } else {
            $this->_die("Cannot read directory $path");
        }
        return $templates;
    }

    function &getAllBorders()
    {
        $borders = array();
        $layout = func_get_instance("Layout");
        // $layout->set("skin","mail");
        // $path = $layout->getPath() . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "GiftCertificates" . DIRECTORY_SEPARATOR . "ecards" . DIRECTORY_SEPARATOR . "borders";
        $path = "skins/mail/" . $layout->get("locale") . "/modules/GiftCertificates/ecards/borders";
        if ($dh = opendir($path)) {
            while (($file = readdir($dh)) !== false) {
                if (is_file($path . DIRECTORY_SEPARATOR . $file) && substr($file,-4) == ".gif" && substr($file,-11) != "_bottom.gif") {
                    $borders[] = substr($file, 0, strlen($file)-4);
                }
            }
            closedir($dh);
        } else {
            $this->_die("Cannot read directory $path");
        }
        return $borders;
    }

    function delete()
    {
        $thumbnail =& $this->getThumbnail();
        $thumbnail->delete();
        $image =& $this->getImage();
        $image->delete();
        parent::delete();
    }

    /**
    * The border image must be chosen for this e-Card
    */
    function isNeedBorder()
    {
        $layout = func_get_instance("Layout");
        $template = "skins/mail/" . $layout->get("locale") . "/modules/GiftCertificates/ecards/" . $this->get("template") . ".tpl";
        $templateCode = file_get_contents($template);
        // does the e-Card template use the border?
        return preg_match('/gc\.border/', $templateCode);
    }

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
