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
* Class Widget is an abstract class for all visual components.
*
* @package Base
* @access public
* @version $Id$
*/
class XLite_View_Abstract extends XLite_Base_Object
{
    /**
    * Widget template filename.
    *
    * @var    string
    * @access private
    */
    var $template = null;

    var $visible = true;

    var $extraWidgets = null;

    var $widgets = array();

    /**
    * Initializes the widget tree. The only what it does is just compile
    * and include the corresponding var/run/skins/.../template.tpl.init.php 
    * file.
    *
    * @access public
    */
    function init()
    {
        $this->includeCompiledFile("initFile"); // see getInitFile()
    }

    function isVerbose()
    {
        if 
        (
        	$this->xlite->get("options.HTML_Template_Flexy.verbose") === true 
        	||
            $this->xlite->get("options.HTML_Template_Flexy.verbose") === "On" 
            ||
            $this->xlite->get("options.HTML_Template_Flexy.verbose") == 1
        ) 
        	return true;
        else
        	return false;
    }

    /**
    * Attempts to display the widget using it template.
    *
    * @access public
    * @return void
    */
    function display()
    {
        if (!$this->is("visible")) {
            return;
        }
        if ($this->isVerbose()) {
        	$templateName = $this->get("template");
        	if (is_array($this->xlite->layout->list) && isset($this->xlite->layout->list[$templateName])) {
        		$templateName = $this->xlite->layout->list[$templateName];
        	}
            print "<!-- start: $templateName -->";
        }
        $this->includeCompiledFile("displayFile"); // see getDisplayFile()
        if ($this->isVerbose()) {
            print "<!-- end: $templateName -->";
        }
    }

    function isVisible()
    {
        if ( is_null($this->get("mode")) || is_null($this->get("dialog")) ||
            !is_null($this->get("mode")) && in_array($this->get("dialog.mode"), explode(',',$this->get("mode")))) {
            if (!$this->visible) {
                return false;
            }
            if (isset($this->parentWidget) && !$this->parentWidget->isVisible()) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    function getTemplateFile()
    {
        if (isset($this->templateFile)) {
            // for debugging
            return $this->templateFile;
        }
        $layout = XLite_Model_Layout::getInstance();
        return $layout->getLayout($this->get("template"));
    }

    function getDisplayFile()
    {
        return $this->get("compileDir") . '/' . $this->get("templateFile") . ".php";
    }
    
    function getInitFile()
    {
        return $this->get("compileDir") . '/' . $this->get("templateFile") . ".init.php";
    }
 
    function getCompileDir()
    {
        return is_null($this->xlite->get("options.HTML_Template_Flexy.compileDir")) ? "var/run" : $this->xlite->get("options.HTML_Template_Flexy.compileDir");
    }
    
    function includeCompiledFile($includeFileProp)
    {
        global $options;
        if($this->get("xlite.suMode") == 0){
            $file_permission = isset($options['filesystem_permissions']['nonprivileged_permission_file']) ? base_convert($options['filesystem_permissions']['nonprivileged_permission_file'], 8, 10): 0644;
            $dir_permission = isset($options['filesystem_permissions']['nonprivileged_permission_dir']) ? base_convert($options['filesystem_permissions']['nonprivileged_permission_dir'], 8, 10) : 0755;
        } else {
            $file_permission = isset($options['filesystem_permissions']['privileged_permission_file']) ? base_convert($options['filesystem_permissions']['privileged_permission_file'], 8, 10) : 0600;
            $dir_permission = isset($options['filesystem_permissions']['privileged_permission_dir']) ? base_convert($options['filesystem_permissions']['privileged_permission_dir'], 8, 10) : 0711;
        }

        if ($this->get("template") == "") {
            func_die("template is not set");
        }
        $includeFile = $this->get($includeFileProp);
        $templateFile = $this->get("templateFile");
        $initFile = $this->get("initFile");
        $displayFile = $this->get("displayFile");
        if (!@filemtime($templateFile) && $this->is("ignoreErrors")) {
            return;
        }
        if (!file_exists($includeFile) || filemtime($includeFile) != filemtime($templateFile)) {
            // compiled file name
            // compile
            $fc = new XLite_Model_FlexyCompiler();
            $fc->set("source", file_get_contents($templateFile));
            $layout = XLite_Model_Layout::getInstance();
            $path = $layout->getPath();
            $fc->set("url_rewrite", "images:" . $path . "images");
            $fc->set("file", $templateFile);
            $fc->parse();
            mkdirRecursive(dirname($displayFile), $dir_permission);
            if($cfp = @fopen($displayFile, 'wb')) {
                fwrite($cfp,$fc->get("phpcode"));
                fclose($cfp);
                @chmod($displayFile, $file_permission);
                @touch($displayFile, filemtime($templateFile));
            }
            mkdirRecursive(dirname($initFile), $dir_permission);
            if($cfp = @fopen($initFile, 'wb')) {
                fwrite($cfp,$fc->get("phpinitcode"));
                fclose($cfp);
                @chmod($initFile, $file_permission);
                @touch($initFile, filemtime($templateFile));
            }
        }
        $t = $this->getThisVar();
        $caller = $t->widget;
        $t->widget = $this;
        $result = @include $includeFile;
        if (!$result) {
            $_error = "unable to read template file: $includeFile";
            if ($GLOBALS['XLITE_SELF'] == "cart.php") {
                func_shop_closed("Warning: $_error");
            } else {
                func_die("Error: $_error");
            }
        }

        $t->widget = $caller;
    }

    function &getThisVar()
    {
        if(isset($this->component)) {
            return $this->component;
        }
        return $this;
    }
    
    /**
    * Creates debug dump
    */
    function dump($variable = null)
    {
        if (is_null($variable)) {
            Var_Dump::display($this);
        } else {
            Var_Dump::display($variable);
        }
    }

    /** 
    * Compares two values.
    *
    * @return boolean True if value are equals /false otherwise
    */
    function isSelected($val1, $val2, $val3 = null)
    {
        if (isset($val3)) {
            return $val1->get($val2)==$val3;
        }
        return $val1 == $val2;
    }

    /**
    * Truncates the Base object property value to specified length.
    * 
    * @access public
    * @param Base $baseObject Base instance to get field value from
    * @param string $field The filed to get value
    * @param integer $length The filed length to truncate to
    * @param string $etc The string to add to truncated field value
    * @return string The truncated string
    */
    function truncate(&$baseObject, $field, $length = 0, $etc = "...", $break_words = false)
    {
        if (is_scalar($baseObject)) {
            $string = $baseObject;
            $length = $field;
        } else {
        	if (is_object($baseObject)) {
            	$string = $baseObject->get($field);
            }
        }
        if ($length == 0) return '';
        if (strlen($string) > $length) {
            $length -= strlen($etc);
            if (!$break_words) {
                $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length + 1));
            }
            return substr($string, 0, $length) . $etc;
        } else {
            return $string;
        }
    }

    /**
    * Formats date.
    */
    function date_format($base, $field = null, $format = null)
    {
        if (is_null($format)) {
            $format = $this->config->get("General.date_format");
        }
        if (!is_object($base)) {
            return strftime($format,$base);
        }
        return strftime($format, $base->get($field));
    }

    /**
    * Formats timestamp.
    */
    function time_format($base, $field = null, $format = null)
    {
        if (is_null($format)) {
            $format = $this->config->get("General.date_format") . ' ' . $this->config->get("General.time_format");
        }
        if (!is_object($base)) {
            return strftime($format,$base);
        }
        return strftime($format, $base->get($field));
    }

    function _price_format($value)
    {
        $price = sprintf("%.02f", round(doubleval($value), 2));
        return $price;
    }

    function price_format($base, $field = "", $thousand_delim = null, $decimal_delim = null)
    {
        if (is_null($thousand_delim)) {
            $thousand_delim = $this->config->get("General.thousand_delim");
        }
        if (is_null($decimal_delim)) {
            $decimal_delim = $this->config->get("General.decimal_delim");
        }
        if (is_Object($base)) {
            $price = $base->get($field);
        } else {
            $price = $base;
        }    
        if ($price{0} == '-' || $price{0} == '+') {
            $sign = $price{0};
            $price = substr($price, 1);
        } else {
            $sign = '';
        }
        $price = $this->_price_format($price);
        $pos = false;
        if ($decimal_delim) {
            $price = str_replace(".", $decimal_delim, $price);
            $pos = strpos($price, $decimal_delim);
        } else {
            // no fractional part
            $price = substr($price, 0, strpos($price, "."));
        }
        if (!$pos) $pos = strlen($price);
        for ($i = $pos -3; $i > 1; $i -= 3) {
            $price = substr($price, 0, $i).$thousand_delim.substr($price, $i);
        }
        return sprintf($this->config->get("General.price_format"), $sign.$price);
    }

    function addSlashes($base, $field = null)
    {
        if (is_object($base)) {
            return addslashes($base->get($field));
        } else {
            return addslashes($base);
        }
    }

    function isEqual(&$base1, &$base2, $field) {
        return $base1->get($field) == $base2->get($field);
    }

    function isEmpty(&$data)
    {	
        return empty($data);
    }

    function ini_get($name)
    {
        return ini_get($name);
    }

    function split($array, $count)
    {
        $result = array_chunk($array, $count);
        // get last chunk, if any
        if ($result) {
            while(count($result[count($result)-1]) < $count) {
                $result[count($result)-1][] = null;
            }
        }
        return $result;
    }
    function split3($array) 
    {
        return $this->split($array, 3);
    }
    function split4($array) 
    {
        return $this->split($array, 4);
    }

    function isArrayPointerNth($arrayPointer, $arrayPointerCheck)
    {
    	$arrayPointerCheck = intval($arrayPointerCheck);

    	if ($arrayPointerCheck <= 0) {
    		return false;
    	}

    	return (($arrayPointer % $arrayPointerCheck) == 0) ? true : false;
    }

    function isArrayPointerEven($arrayPointer)
    {
    	return ($this->isArrayPointerNth($arrayPointer, 2)) ? true : false;
    }

    function isArrayPointerOdd($arrayPointer)
    {
    	return ($this->isArrayPointerNth($arrayPointer, 2)) ? false : true;
    }

    function percent($count)
    {
        return round(100/$count);
    }

    function inc($val, $inc = 1)
    {
        return $val + $inc;
    }
    
    function rand()
    {
        return rand();
    }

	function isOddRow($row)
	{
		return (($row % 2) == 0) ? true : false;
	}

	function getRowClass($row, $odd_css_class, $even_css_class)
	{
		return ($this->isOddRow($row)) ? $odd_css_class : $even_css_class;
	}

    function wrap($object, $prop, $width)
    {
        if ($prop) {
            $text = $object->get($prop);
        } else {
            $text = $object;
        }
        $startPos = 0;
        $breaks = array(0);
        for ($i=0; $i<strlen($text); $i++) {
            $c = $text{$i};
            if ($c=='-' || $c==' ') {
                $startPos = $i;
            } else if ($c=='.' || $c==',' || $c=='@') {
                $nextWord = strcspn(substr($text, $i+1), '.,@- ');
                if ($i+$nextWord-$startPos >= $width) {
                    // break here
                    $breaks[] = $i+1;
                    $startPos = $i;
                }
            }
        }
        $breaks[] = strlen($text);
        // finally break text with \n
        $text1 = '';
        for ($i=0; $i<count($breaks)-1; $i++) {
            if ($i) {
                $text1 .= "\n";
            }
            $text1 .= substr($text, $breaks[$i], $breaks[$i+1]-$breaks[$i]);
        }
        return $text1;
    }

    function isInitRequired($attributes)
    {
        if (!$this->isDisplayRequired($attributes)) {
            return false;
        }
        if (isset($attributes["name"])) {
            $name = str_replace("->", ".", $attributes["name"]);
            if (!is_null($this->get($name))) {
                return false; // do not initialize twice
            }
        }
        return true;
    }

    function isDisplayRequired($attributes)
    {
        $this->_attributes = $attributes;

        if (isset($attributes["target"])) {
            if (isset($_REQUEST["target"]) && !in_array($_REQUEST["target"], explode(',',$attributes["target"]))) {
                return false;
            }
        }
        if (isset($attributes["module"])) {
            if (is_null($this->xlite->get("mm.activeModules." . $attributes["module"]))) {
                return false;
            }
        }
        return true;
    }
    
    function &getDialog()
    {
        $d = $this;
        while (!is_null($d) && !is_a($d, 'dialog__')) {
            $d = $d->component;
        }
        return $d;
    }

    function addWidget(&$w) 
    {
        $this->widgets[] = $w;
        $w->parentWidget = $this;
    }

    function getCurrentYear()
    {
        return date("Y", time());
    }

    function strMD5($string)
    {
    	return strtoupper(md5(strval($string)));
    }

    function getSidebarBoxStatus($boxHead)
    {
		if ($this->xlite->is("adminZone")) 
		{
    		$dialog = new XLite_Controller_Admin_Sbjs();
    	}
        $dialog->sidebar_box_id = $this->strMD5($boxHead);

        return $dialog->getSidebarBoxStatus();
    }

	function getXliteFormID()
	{
		if (is_null($this->xlite->_xlite_form_id)) {
			$this->xlite->_xlite_form_id = $this->generateXliteFormID();
		}
		return $this->xlite->_xlite_form_id;
	}

	function generateXliteFormID()
	{
		$form = new XLite_Model_XliteForm();
		$form_id = md5(uniqid(rand(0,time())));
		$session_id = $this->xlite->session->getID();
		$form->set("form_id", $form_id);
		$form->set("session_id", $session_id);
		$form->set("date", time());
		$form->create();
		$form->collectGarbage($session_id);
		return $form_id;
	}

	function isIgnoredTarget()
    {
		$ignoreTargets = array
		(
        	"image" => array("*"),
            "callback" => array("*"),
			"upgrade" => array("version", "upgrade")
		);
                            
        if (isset($ignoreTargets[$_REQUEST['target']]) && (in_array("*", $ignoreTargets[$_REQUEST['target']]) || in_array($_REQUEST['action'], $ignoreTargets[$_REQUEST['target']]))) { 
            return true;
        }

        $specialIgnoreTargets = array
        (
            "db" => array("backup", "delete"),
            "files" => array("tar", "tar_skins", "untar_skins"),
            "wysiwyg" => array("export", "import")
        );

        if(isset($specialIgnoreTargets[$_REQUEST['target']]) && (in_array("*", $specialIgnoreTargets[$_REQUEST['target']]) || in_array($_REQUEST['action'], $specialIgnoreTargets[$_REQUEST['target']])) && (isset($_POST['login']) && isset($_POST['password']))){
            $login = $this->xlite->auth->get("profile.login");
            $post_login = $_POST['login'];
            $post_password = $_POST['password'];

            if($login != $post_login)
                return false;

            if(!empty($post_login) && !empty($post_password)){
                $post_password = $this->xlite->auth->encryptPassword($post_password);
                $profile = new XLite_Model_Profile();
                if ($profile->find("login='".addslashes($post_login)."' AND ". "password='".addslashes($post_password)."'")) {
                    if ($profile->get("enabled") && $profile->is("admin")) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    } 
					
	function checkXliteForm()
	{
		if (!isset($_REQUEST['action'])) {
            return true;
        }    
			  
		if ($this->isIgnoredTarget()) {
            return true;
        }
				 
		if (!$this->xlite->is("adminZone")) {
            return true;
        }

		return $this->isXliteFormValid();
	}

	function isXliteFormValid()
	{
		if (!$this->xlite->config->get("Security.form_id_protection")) {
			return true;
		}

		if ($_REQUEST['target'] == 'payment_method' && $_REQUEST['action']=='callback') {
			return true;
		}

		$form_id = addslashes($_REQUEST['xlite_form_id']);
		$session_id = $this->xlite->session->getID();
		$form = new XLite_Model_XliteForm();
		if (!$form->find("form_id='$form_id' AND session_id='$session_id'")) {
			return false;
		}
		$form->collectGarbage();
		return true;
	}

    function isGDLibLoaded()
    {
    	// PHP 4 >= 4.3.0
    	// !!!
        return extension_loaded('gd') && function_exists("gd_info");
    }

    function getCaptchaPages()
    {
        return array(
                "on_contactus" => "",
                "on_register" => "",
                "on_add_giftcert" => "GiftCertificates",
                "on_partner_register" => "Affiliate"
                );
    }

    function getEnabledCaptchaPages()
    {
        $pages = (array) $this->get("captchaPages");
        foreach($pages as $widget_id => $dependency){
            if($dependency == "") continue;

            $module = $dependency;
            if(!$this->get("xlite.mm.activeModules." . $module)){
                unset($pages[$widget_id]);
            }
        }

        return $pages;
    }

    function isActiveCaptchaPage($widget_id)
    {
        $pages = $this->get("config.Captcha.active_captcha_pages");

        return (isset($pages[$widget_id]) && $this->get("xlite.config.Security.captcha_protection_system") == "Y");
    }
    
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
