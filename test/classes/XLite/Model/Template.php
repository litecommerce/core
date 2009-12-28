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

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* Template class
*
* @package Kernel
* @access public
* @version $Id$
*/
class XLite_Model_Template extends XLite_Model_FileNode
{
    var $file; // file name
    var $comment; // template comment (null if not available)
    var $content; // template file content (null if not read)

    function constructor($path = null, $comment = null)
    {
        parent::constructor($path);
        if (isset($path)) {
            $this->setPath($path);
        }
        $this->comment = $comment;
    }

    function setFile($file)
    {
        $this->file = $file;
        global $options;
        $this->path = str_replace("skins/". $options["skin_details"]["skin"] . "/" . $options["skin_details"]["locale"], $file);
    }

    function setPath($path)
    {
        $this->path = $path;
        $l = new XLite_Model_Layout();
        global $options;
        $l->set("skin", $options["skin_details"]["skin"]);
        $l->set("locale", $options["skin_details"]["locale"]);
        $this->file = $l->getPath() . $path;
    }

    function setContent($content)
    {
        $this->content = $content;
    }

    function getContent()
    {
        if (!isset($this->content)) {
            $this->_read();
        }
        return $this->content;
    }

    /**
    * Use getContent instead
    */
    function _read()
    {
        $this->content = file_get_contents($this->file);
    }

    function write()
    {
        $fd = fopen($this->file, "wb");
        fwrite($fd, str_replace("\r", '', $this->content));
        fwrite($fd, "\n");
        fclose($fd);
        umask(0000);
        @chmod($this->file, get_filesystem_permissions(0777));
    }

    function save()
    {
        copyFile($this->file, $this->file . ".bak");
        $this->write();
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
