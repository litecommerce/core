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
* A simple XML parser which parses and XML code to a nested associative array.
* Syntax: 
*  $xml = new XLite_Model_XML();
*  $array = $xml->parse("<tag><nested>value</nested><seri id=\"1\">first</seri><seri id=\"2\">second</seri></tag>");
*  print_r($array);
*
* Output:
* Array
* (
*     [TAG] => Array
*         (
*             [NESTED] => value
*             [SERI] => Array
*                 (
*                     [1] => first
*                     [2] => second
*                 )
*         )
* )
*     
*
* @package Kernel
* @access public
* @version $Id$
*/
class XLite_Model_XML extends XLite_Base
{
    var $xml_parser;

    function _xmlError($xml)
    {
        // store error information
        $errorCode = xml_get_error_code($this->xml_parser);
        $this->error = "XML Parse Error #$errorCode:  " .
            xml_error_string($errorCode);
        $pos = xml_get_current_byte_index ($this->xml_parser);
        $xml = substr($xml, 0, $pos). '$$$' . substr($xml, $pos);
        $xml = htmlspecialchars($xml);
        $xml = str_replace('$$$', '<font color="red"><b> HERE </b></font>', $xml);
        $this->xml = $xml;
    }

    function parse($xml) {
        if (!is_scalar($xml)) {
            foreach (debug_backtrace() as $trace) {
                print $trace["class"]."::".$trace["function"] . "(". basename($trace["file"]).":".$trace["line"].")\n";
            }
            die ("wrong xml");
        }
        $this->xml_parser = xml_parser_create();
        $xml = trim($xml);
        
        if (!xml_parse_into_struct($this->xml_parser, $xml, $values, $index)) {
            $this->_xmlError($xml);
            xml_parser_free($this->xml_parser);
            return array();
        }
        xml_parser_free($this->xml_parser);
        $i = 0;
        return $this->_compileTree($values, $i);
    }

    /**
    * Creates a tag tree after an XML parsed data $values
    */
    function _compileTree($values, &$i)
    {
        $tree = array();
        while ($i < count($values)) {
            $type = $values[$i]["type"];
            $tag = $values[$i]["tag"];
            if (isset($values[$i]["attributes"])) {
                $attributes = $values[$i]["attributes"];
            } else {
                $attributes = null;
            }
            if ($type == "open") {
                $i++; 
                $value = $this->_compileTree($values, $i);
            } else if ($type == "complete") {
                if (isset($values[$i]["value"])) {
                    $value = $values[$i]["value"];
                } else {
                    $value = null;
                }
            } else if ($type == "close") {
                return $tree;
            }
            if ($type == "open" || $type == "complete") {
                if (!is_null($attributes) &&
                        isset($attributes["ID"])) {
                    if (!isset($tree[$tag])) {
                        $tree[$tag] = array();
                    }
                    $tree[$tag][$attributes["ID"]] = $value;
                } else {
                    // repeating tag
                    $postfix = '';
                    while (isset($tree[$tag.$postfix])) {
                        if ($postfix == '') {
                            $postfix = 1;
                        } else {
                            $postfix++;
                        }    
                    }    
                    $tree[$tag.$postfix] = $value;
                }
            }
            $i++;
        }
        return $tree;
    }

}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
