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
*
* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4:
*/

define("COMMENT", 1);
define("CLASS_NAME", 1);
define("STYLE", 2);

/**
* CSS Editor class.
*
* @package Kernel
* @access public
* @version $Id: CssEditor.php,v 1.20 2008/11/17 15:16:58 vgv Exp $
*/
class CssEditor extends Object
{
    var $cssFile;
    var $style = array();

    function constructor($cssFile = null)
    {
        $this->set("cssFile", $cssFile);
        parent::constructor();
    }

    function &getItems()
    {
        $items = array();
        $style = $this->get("style");
        if (isset($style["style"])) {
            $items = array_keys($style["style"]);
        }    
        return $items;
    }

    function getStyle()
    {
        if (!empty($this->style)) {
            return $this->style;
        }
        $this->parseContent();
        return $this->style;
    }

    function parseContent()
    {
        $found = array();
        $content = @file_get_contents($this->get("cssFile"));
        $elements = explode("}", $content);

        for ($i = 0; $i < count($elements); $i ++) {
            $result = $this->_parseClass($elements[$i]);
            if ($result !== null) {
                $this->style["comment"][] = $result["comment"];
                $this->style["element"][] = $result["element"];
                $this->style["style"][] = $result["style"];
            }    
        }
    }

    function save()
    {
        $style = "";
        // update style
        for ($i = 0; $i < count($this->style["element"]); $i ++) {
            if (!empty($this->style["comment"][$i])) {
                $style .= "/*\n" . $this->style["comment"][$i] ."\n*/\n";
            }   
            $style .= $this->style["element"][$i] .
            " {\n\t" . $this->style["style"][$i] . "\n}\n\n";
        }   
        // save CSS file
        $file = $this->get("cssFile");
        $fp = fopen($file, "wb") or die("Write failed for file $file".
                                        ": permission denied");
        fwrite($fp, $style);
        fwrite($fp, "\n");
        fclose($fp);
        @chmod($file, get_filesystem_permissions(0666));

    }

    function _parseClass($class) 
    {
        $result = array();
        $result["comment"] = "";
        $result["element"] = "";
        $result["style"] = "";
        preg_match("/\/\*(.*)\*\//s", $class, $found);
        
        if (!empty($found)) {
            $comment = trim($found[COMMENT]);
            $comment = preg_replace("/\/\*/s", "", $comment);
            $comment = preg_replace("/\*\//s", "", $comment);
            $result["comment"] = $comment;
            $class = preg_replace("/\/\*(.*)\*\//s", "", $class);
        }    
        preg_match("/([^\{]+)\{([^\}]+)/i", $class, $found);
        if (!isset($found[CLASS_NAME]) || !isset($found[STYLE])) {
            return null;
        }
        $result["element"] = trim($found[CLASS_NAME]);
        $result["style"] = $this->removeSpaces(trim(strtr($found[STYLE], "\n", " ")));
        return $result;
    }   

    function restoreDefault()
    {
        $file = $this->get("cssFile");
        $orig = preg_replace("/^(skins)/", "schemas/templates/" . $this->config->get("Skin.skin"), $file);
        is_readable($orig) or die("$orig: file not found");
        if (is_writeable($file)) {
            unlink($file);
        }	
        copyFile($orig, $file) or die("unable to copy $orig to $file");
    }

	function removeSpaces($source)
	{
		while(preg_match("/  /", $source)) {
			$source = preg_replace("/  /", " ", $source);
		}
		return $source;
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
