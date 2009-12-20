<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URL: http://www.litecommerce.com/license.php                |
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
| The Initial Developer of the Original Code is Ruslan R. Fazliev              |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2003 Creative        |
| Development. All Rights Reserved.                                            |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* @package GoogleCheckout
* @access public
* @version $Id$
*/
class XML_GoogleCheckout extends XML
{
    function _compileTreeNode($type, &$values, &$i, &$tree, &$value, &$attributes, $tag)
    {
    	switch ($type) {
    		case "open+":
                $i++; 
                $value = $this->_compileTree($values, $i);

                return false;
    		break;
    		case "complete+":
                if (isset($values[$i]["value"])) {
                    $value = $values[$i]["value"];
                } else {
                    $value = null;
                }
                if (isset($values[$i]["attributes"])) {
                    $attributes = $values[$i]["attributes"];
                } else {
                    $attributes = null;
                }

                return false;
    		break;
    		case "close+":
                return true;
    		break;
    		case "open-":
    		case "complete-":
				$attrID = null;
                if (!is_null($attributes)) {
                	if (isset($attributes["ID"])) {
                		$attrID = "ID";
                	}
                	if (isset($attributes["NAME"])) {
                		$attrID = "NAME";
                	}
					if (isset($attributes["CODE"])) {
						$attrID = "CODE";
					}
                }
                if (!is_null($attrID)) {
                    if (!isset($tree[$tag])) {
                        $tree[$tag] = array();
                    }

					if (is_null($value)) {
						$tree[$tag][] = array($attrID => $attributes[$attrID]);
					} else {
	                    $tree[$tag][$attributes[$attrID]] = $value;
					}
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

                return false;
    		break;
    	}
    }

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

            if ($this->_compileTreeNode($type . "+", $values, $i, $tree, $value, $attributes, $tag)) {
                return $tree;
            }
            $this->_compileTreeNode($type . "-", $values, $i, $tree, $value, $attributes, $tag);

            $i++;
        }
        return $tree;
    }
}

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
