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

define('COMMENT', 1);
define('CLASS_NAME', 1);
define('STYLE', 2);

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_CssEditor extends XLite_Base
{	
    public $cssFile;	
    public $style = array();

    public function __construct($cssFile = null)
    {
        $this->set("cssFile", $cssFile);
    }

    function getItems()
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
        $orig = preg_replace("/^(skins)/", "schemas/templates/" . $this->config->getComplex('Skin.skin'), $file);
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
