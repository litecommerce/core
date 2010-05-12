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

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Wysiwyg_ExportParser extends XLite_Core_FlexyCompiler 
{
    public $widgetClass = null;
    public $wysiwygMediator = null;
    public $configVars = null;

    function _parseTemplate($file, $params)
    {
        $this->source = $this->translateTemplate(file_get_contents($file));
        $this->widgets = array();
        $this->params = $params;
        $this->errorMessage = '';
        $this->parse();
    }

    function translateTemplate($src)
    {
        $lay = XLite_Model_Layout::getInstance();
        return str_replace(array('{*', '*}', 'skins/' . $lay->get("skin") . '/' . $lay->get("locale") . '/style.css'), array('<!--*', '*-->', 'style.css'), $src);
    }

    function postprocess()
    {
        if ($this->errorMessage) {
            return;
        }
        $namedWidgets = array();
        $attributes = array();
        $attributesEvaled = array();
        $insideWidget = false;
        $params = array_merge($this->params, $this->_getConfigVars());
        for ($i=0; $i<=count($this->tokens); $i++) {
            if ($i<count($this->tokens)) {
                $token = $this->tokens[$i];
            } else {
                $token = array("type"=>"eof");
            }
            if ($token["type"] == "attribute" && $insideWidget) {
                $attr = $token["name"];
                if (isset($this->tokens[$i+1]) && $this->tokens[$i+1]["type"] == "attribute-value") {
                    $i++;
                    $val = $this->getTokenText($i);
                    $attributes[$attr] = $val;
                    $attributesEvaled[$attr] = $this->wysiwygMediator->_replaceVal($val, $params);
                } else {
                    $attributes[$attr] = null;
                    $attributesEvaled[$attr] = null;
                }
            }
            if ($token["type"] != "attribute" && $token["type"] != "attribute-value" && $attributes && $insideWidget) {
////                if (isset($attributes["name"]) && !isset($attributes["class"]) && !isset($attributes["template"])) {
                    // fetch by name
////                    $w = $namedWidgets[$attributes["name"]];
////                } else {
                    $w = new $this->widgetClass;
                    $w->set("attributes", $attributes);
                    $w->set("attributesEvaled", $attributesEvaled);
                    if (isset($attributes["name"])) {
                        if (!$w->get("template")) {
                            $tw = $namedWidgets[$attributes["name"]];
                            if ($tw) {
                                $w->set("template", $tw->get("template"));
                            }
                        }

                        $namedWidgets[$attributes["name"]] = $w;
                    }
////                }
                $w->set("startOffset", $this->tokens[$widgetInd]["start"]);
                $w->set("endOffset", $this->tokens[$widgetInd]["end"]);
                $this->addWidget($w);
            }
            if ($token["type"] != "attribute" && $token["type"] != "attribute-value") {
                $insideWidget = false;
            }
            if ($token["type"] == "tag" || $token["type"] == "open-close-tag") {
                if (!strcasecmp($token["name"], "widget")) {
                    $attributes = $attributesEvaled = array();
                    if ($token["type"] == "open-close-tag") {
                        $attributes["open-close-tag"] = null;
                    }
                    $widgetInd = $i;
                    $insideWidget = true;
                }
            }
        }
    }
    
    function _getConfigVars()
    {
        if (is_null($this->configVars)) {
            $result = array();
            $config = new XLite_Model_Config();
            foreach ($config->findAll() as $c)
            {
                $result['config.'.$c->get("category").'.'.$c->get("name")] = $c->get("value");
            }
            $this->configVars = $result;
        }
        return $this->configVars;
    }
    
    function addWidget($w)
    {
        $this->widgets[] = $w;
    }

    function error($message)
    {
        // count \n
        $line = $col = 1;
        for ($i=0; $i < $this->offset; $i++) {
            if ($this->source{$i} == "\n") {
                $line ++;
                $col=0;
            }
            $col++;
        }
        $this->errorMessage = "File $this->file, line $line, col $col: $message";
        return false;
    }
}
