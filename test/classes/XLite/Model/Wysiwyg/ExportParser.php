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

class XLite_Model_Wysiwyg_ExportParser extends XLite_Core_FlexyCompiler // {{{
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
} // }}}

