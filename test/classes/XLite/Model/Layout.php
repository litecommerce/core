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
* Class Layout provides access to skins templates.
*
* @package Kernel
* @access public
* @version $Id$
*/
class XLite_Model_Layout extends XLite_Base_Singleton
{
    /**
    * Skin templates list.
    *
    * @var Elements $elements Skin templates list.
    * @access private
    */
    var $list = array();
    var $skin = null;
    var $locale = null;

	/**
     * Return pointer to the single instance of current class
     *
     * @param string $className name of derived class
     *
     * @return XLite_Base_Singleton
     * @access public
     * @see    ____func_see____
     * @since  3.0
     */
    public static function getInstance($className = __CLASS__)
    {
        return parent::getInstance(__CLASS__);
    }

    function initFromGlobals()
    {
        $this->set("properties", array(
            "locale" => $this->xlite->get("options.skin_details.locale"),
            "skin" => $this->xlite->get("options.skin_details.skin")));
    }

    /**
    * Adds layout template file for the specified widget
    *
    * @param string $widgetName The widget name
    * @param string $templateName The template file name
    * @access public
    */
    function addLayout($widgetName, $templateName)
    {
        $this->list[$widgetName] = $templateName;
    }

    /**
    * Returns the widget template file name for this layout.
    *
    * @param string $widgetName The name of widget
    * @access public
    * @return string The widget tamplate name
    */
    function getLayout($templateName)
    {
        if (!isset($this->list[$templateName])) {
			return $this->get("path") . $templateName;
        }
        return $this->get("path") . $this->list[$templateName];
    }

    function hasLayout($widgetName)
    {
        return isset($this->list[$widgetName]);
    }
    
    /**
    * Returns the layout path.
    *
    * @access public
    */
    function getPath()
    {
        return sprintf("skins/%s/%s/", $this->get("skin"), $this->get("locale"));
    }
	
	function getSkins($includeAdmin = false)
	{
		$list = array();
		$dir = "skins";
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if (is_dir($dir . '/'. $file) && $file{0} != '.' && ($file != 'admin' || $includeAdmin) && $file != 'mail' && $file != 'CVS') {
					$list[] = $file;
				}
			}
		}
        closedir($dh);
		return $list;
	}

	function getLocales($skin)
	{
		$list = array();
		$dir = "skins/$skin/";
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if (is_dir($dir . $file) && $file{0} != '.' && $file != 'CVS') {
					$list[] = $file;
				}
			}
		}
		return $list;
	}

}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
