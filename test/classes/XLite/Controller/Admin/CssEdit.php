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

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* CSS editor dialog
*
* @package Dialog
* @access public
* @version $Id$
*
*/

class XLite_Controller_Admin_CssEdit extends XLite_Controller_Admin_Abstract
{
	protected $locale = null;

	protected $zone = null;
	
    public $params = array('target', 'mode', 'style_id', 'status');

	protected function getStyleAttribute($attr, $index)
	{
		$style = $this->getEditor()->getStyle();

		return isset($style[$attr][$index]) ? $style[$attr][$index] : null;
	}

    function getLocale() // {{{
    {
        if (is_null($this->locale)) {
            $this->locale = XLite::getInstance()->getOptions(array('skin_details', 'locale'));
        }
        return $this->locale;
    } // }}}

    function getZone() // {{{
    {
        if (is_null($this->zone)) {
            $this->zone = XLite::getInstance()->getOptions(array('skin_details', 'skin'));
        }
        return $this->zone;
    } // }}}

    function getEditor()
    {
        if (isset($this->editor)) {
            return $this->editor;
        }
        $this->editor = new XLite_Model_CssEditor($this->get("cssFile"));
        return $this->editor;
    }

    function getCssFile()
    {
        $skin   = $this->get("zone");
        $locale = $this->get("locale");
        return "skins/$skin/$locale/style.css";
    }

    function action_save()
    {
        $editor = $this->get("editor");
        $editor->set("style.style.$this->style_id", $this->style);

        $editor->save();
        $this->set("status", "updated");
    }
    
    function action_restore_default()
    {
        $editor = $this->get("editor");
        $editor->restoreDefault();
    }

    function css_style($index)
    {
		return $this->getStyleAttribute('style', $index);
    }

    function css_class($index)
    {
		return $this->getStyleAttribute('element', $index);
    }

    function css_comment($index)
    {
		return $this->getStyleAttribute('comment', $index);
    }

}

