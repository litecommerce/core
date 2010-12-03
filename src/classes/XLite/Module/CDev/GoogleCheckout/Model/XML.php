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

namespace XLite\Module\CDev\GoogleCheckout\Model;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XML extends \XLite\Model\XML
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
                if (isset($values[$i]['value'])) {
                    $value = $values[$i]['value'];
                } else {
                    $value = null;
                }
                if (isset($values[$i]['attributes'])) {
                    $attributes = $values[$i]['attributes'];
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
                	if (isset($attributes['ID'])) {
                		$attrID = "ID";
                	}
                	if (isset($attributes['NAME'])) {
                		$attrID = "NAME";
                	}
                    if (isset($attributes['CODE'])) {
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
            $type = $values[$i]['type'];
            $tag = $values[$i]['tag'];
            if (isset($values[$i]['attributes'])) {
                $attributes = $values[$i]['attributes'];
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
