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
* Mail explorer dialog
*
* @package Dialog
* @access public
* @version $Id$
*/
class XLite_View_MailExplorer extends XLite_View_ColumnList
{	
    public $subject = "subject.tpl";	
    public $body = "body.tpl";	
    public $signature = "signature.tpl";	
    public $template = "template_editor/mail_list.tpl";	
    public $templates = array();

    function getLocale() // {{{
    {
        if (is_null($this->locale)) {
            $this->locale = $this->get("xlite.options.skin_details.locale");
        }
        return $this->locale;
    } // }}}

    function getPath($zone = "mail")
    {
        return "skins/".$zone."/".$this->get('locale');
    }

    function getData()
    {
        // search for cached result
        if (!empty($this->templates)) {
            return $this->templates;
        }
        // search templates
        $path = $this->getPath();
        $this->findMail($path);
        return $this->templates;
    }

    function findMail($path)
    {
        if ($handle = @opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                if ($file{0} == ".") {
                    continue;
                }    
                if (is_dir($path .'/'.$file) && file_exists($path .'/'.$file.'/'.$this->body)) {
                    $body = new XLite_Model_FileNode($path .'/'.$file.'/'.$this->body);
                    array_unshift($this->templates, new XLite_Model_FileNode($path .'/'.$file, $body->get("comment")));
                }
                if (is_dir($path .'/'.$file)) {
                    $this->findMail($path .'/'.$file);
                }
            }
            closedir($handle); 
        }
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
