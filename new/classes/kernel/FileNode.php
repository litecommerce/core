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
* File node
*
* @package Kernel
* @access public
* @version $Id$
*/
define('SHOW_FULL_PATH', 1);

class FileNode extends Object
{
    function constructor($path = null, $comment = null, $options = 0)
    {
        parent::constructor();

        $this->path = $path;
        $this->comment = $comment;
        $this->options = $options;
    }

    function getID()
    {
        return $this->path;
    }

    function getComment()
    {
        $this->checkAccess($this->path);

        if ($this->comment) {
            if ($this->comment == "EMPTY") {
                return "";
            } else {    
                return $this->comment;
            }    
        } else {
            $cnt = is_file($this->path) ? file_get_contents($this->path) : "";
            if (substr($cnt, 0, 2) == "{*") {
                // get comment from file
                if (preg_match('/{\*([^*]+)\*}/', $cnt, $matches) && isset($matches[1])) {
                    $this->comment = trim($matches[1]);
                    return $this->comment;
                }
            }
            $this->comment = "EMPTY";
            return "";
        }
    }

    function getName()
    {
        if (isset($this->name)) {
            return $this->name;
        }
        if ($this->options & SHOW_FULL_PATH) {
            return $this->path;
        } else {
            $pos = strrpos($this->path, '/');
            if ($pos) {
                return substr($this->path, $pos+1);
            }
            return $this->path;
        }
    }

    function getNode()
    {
        return ($this->path == "cart.html" || $this->path == "shop_closed.html") ? "" : dirname($this->path);
    }

    function getContent()
    {
        $this->checkAccess($this->path);
        return @file_get_contents($this->path);
    }

    function isLeaf()
    {
        return is_file($this->path);
    }
    
    function create()
    {
        $this->content = "";
        $this->write();
    }

    function remove()
    {
        $this->checkAccess($this->path);
        if ($this->isLeaf()) {
            @unlink($this->path);
        } else {
            unlinkRecursive($this->path);
        }
    }

    function createDir()
    {
        $this->checkAccess($this->path);
        umask(0000);
        @mkdir($this->path, get_filesystem_permissions(0755));
    }

    function copy()
    {
        $this->checkAccess($this->path);
        $this->checkAccess($this->newPath);
        copyRecursive($this->path, $this->newPath);
    }

    function rename()
    {
        $this->checkAccess($this->path);
        $this->checkAccess($this->newPath);
        rename($this->path, $this->newPath);
		is_dir($this->newPath) ? @chmod($this->newPath,get_filesystem_permissions(0755)) : @chmod($this->newPath, get_filesystem_permissions(0666));
    }

    function update()
    {
        $this->write();
    }

    function write()
    {
        if (is_null($this->path)) return;
        $this->checkAccess($this->path);
        $this->writePermitted = false;
        $fd = @fopen($this->path, "wb");
        if ($fd) {
            fwrite($fd, str_replace("\r", '', $this->content));
            if (!empty($this->content)) {
                fwrite($fd, "\n");
            }    
            fclose($fd);
            @chmod($this->path, get_filesystem_permissions(0666));
        } else {
        	$this->writePermitted = true;
        }
    }

    function checkAccess($file)
    {
        if (empty($file)) return;
        // add-on mode 
        if ($file == "cart.html" || $file == "shop_closed.html" || $file == "skins" || $file == "skins_original") return true;
        // check permission to access the file
        $i = 0;
        foreach (explode('/', $file) as $element) {
            if ($element == '..') $i--;
            if ($element != 'skins' && $element != 'skins_original' && $element != 'schemas' && $element != 'tests' && $i == 0) {
                $this->accessDenied();
            }
            if ($element != '.' && $element != '..') $i++;
        }
        if ($i <= 2) {
            $this->accessDenied();
        }
    }

    function accessDenied()
    {
        die("Access error! You have no permission to access file $this->path");
    }

    function isExists()
    {
        return file_exists($this->path);
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
