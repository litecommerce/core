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

class XLite_Model_Wysiwyg_MediatorWidget extends XLite_View_Abstract // {{{
{
    var $attributes = array();
    var $attributesEvaled = array();
    var $code = "";
    var $parent = false;
    var $editing = false;
    var $parentWidget = null;
    var $templateType = null;

    function setAttributesEvaled($params)
    {
        $this->attributesEvaled = $params;
        // read widget's template
        if (isset($params['template'])) {
            $this->set("template", $params['template']);
        } else {
            if (isset($params['class'])) {
                $class = $params['class'];
                if (func_class_exists($class)) {
                    $component = new $class();
                    $this->set("template", $component->get("template"));
                }
            }
        }
    }
    function hasDefinedTemplate()
    {
        if (isset($this->attributes['template'])) {
            return $this->attributes['template'] == $this->attributesEvaled['template']; // no expressions in 'template' attribute
        } else {
            return $this->get("template") && file_exists($this->get("templateFile"));
        }
    }
    function getAttributesInTag()
    {
        $result = '';
        foreach ($this->get("attributes") as $name => $val) {
            if (is_null($val)) {
                $result .= ' ' . $name;
            } else {
                $result .= ' ' . $name . '="' . $val .'"';
            }
        }
        return $result;
    }

	function getTemplateType()
    {
        if (is_null($this->templateType)) {
            $t = $this->get("templateFile");
            if ($t && file_exists($t)) {
                $src = strtolower(file_get_contents($t));
                $tags = array('table', 'p', 'hr', 'center', 'br', 'h1', 'h2', 'h3', 'html', 'widget', 'div');
                $this->templateType = "plain";
                foreach ($tags as $tag) {
                    if (strpos($src,'<'. $tag) !== false) {
                        $this->templateType =  "paragraph";
                        break;
                    }
                }
                // find first tag
                $pos = strpos($src, '<');
                if ($pos !== false) {
                    $tag = substr($src, $pos+1, strcspn(substr($src, $pos+1), " \n\r\t>"));
                    if ($tag == 'tbody' || $tag == 'tr' || $tag == 'td') {
                        $this->templateType =  "in-table";
                    }
                }
            } else {
                $this->templateType =  "paragraph";
            }
        }
        return $this->templateType;
    }
}

