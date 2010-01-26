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

// FIXME - need to completely revise

/**
* Waiting IP list for admin zone.
*
* @package Kernel
* @access public
* @version $Id$
*/

define("HTACCESS_NOT_EXISTS", "MISSING");
define("HTACCESS_WRONG", "FAILED");
define("CHECK_INTERVAL", 1 * 24 * 60);


class XLite_Model_Htaccess extends XLite_Model_Abstract
{	
    public $fields = array(
                    "id" => "0",
                    "filename" => "",
                    "content" => "",
                    "hash" => ""
                    );	

    public $autoIncrement = "id";	
    public $alias = "htaccess";	

    public $htaccess_list = array(
                            "var/.htaccess",
                            ".htaccess",
                            "classes/.htaccess",
                            "compat/.htaccess",
                            "etc/.htaccess",
                            "includes/.htaccess",
                            "lib/.htaccess",
                            "schemas/.htaccess",
                            "skins/.htaccess",
                            "sql/.htaccess",
                            "images/.htaccess",
                            "catalog/.htaccess",
                            "files/.htaccess"
                            );

    function hasImage()
    {
       return $this->find(""); 
    }

    function makeImage()
    {
        foreach($this->htaccess_list as $file){
            if(!($fp = @fopen($file, "r")))
                continue;
            $fs = intval(@filesize($file));
            if ($fs > 0 )
                $content = @fread($fp, $fs);
            else
                $content = "";
            @fclose($fp);
            $hash = $this->makeHash($content);
            $htaccess = new XLite_Model_Htaccess();
            $htaccess->set("filename", $file);
            $htaccess->set("content", $content);
            $htaccess->set("hash", $hash);
            $htaccess->create();
        }

        $config = new XLite_Model_Config();
        if($config->find("name = 'last_date' AND category = 'Htaccess'")){
            $now = time();

            $config->set("value", "0");
            $config->update();
        } else {
        	$config->createOption("Htaccess", "last_date", "0");
        }
    }

    function makeHash($string)
    {
        return md5($string);
    }

    function reImage()
    {
        $file = $this->get("filename");
        if(!($fp = @fopen($file, "r")))
                        return;
        $fs = intval(@filesize($file));
        if ($fs > 0 )
            $content = @fread($fp, $fs);
        else
            $content = "";
        @fclose($fp);
        $hash = $this->makeHash($content);
        $this->set("hash", $hash);
        $this->set("content", $content);
        $this->update();
    }

    function restoreFile()
    {
        $file = $this->get("filename");
        if(!($fp = @fopen($file, "w")))
            return;

        $content = $this->get("content");
        @fwrite($fp, $content);
        @fclose($fp);
    }

    function checkFiles()
    {
        $last_date = $this->getComplex('xlite.config.Htaccess.last_date');
        $now = time();
        if(($now - $last_date) < CHECK_INTERVAL)
            return;

        $config = new XLite_Model_Config();
        if($config->find("name = 'last_date' AND category = 'Htaccess'")){
            $config->set("value", $now);
            $config->update();
        } else {
        	$config->createOption("Htaccess", "last_date", "0");
        }

        $error_results = array();
        foreach((array) $this->findAll("", "filename") as $htaccess){
                $error = $htaccess->verify();
                if($error != ""){
                    $error_result = array("file" => $htaccess->get("filename"), "error" => $error);
                    $error_results[] = $error_result;
                } 
        }

        if(count($error_results) >= 1){
            $this->notifyAdmin($error_results);
        }
    }

    function checkEnvironment()
    {
        $results = array();

        foreach((array) $this->findAll("", "filename") as $htaccess){
            $result = array(
                        "id" => $htaccess->get("id"),
                        "filename" => $htaccess->get("filename"),
                        "status" => $htaccess->getStatus()
                        );

            $results[] = $result;
            
        }

        return $results;
    }

    function verify()
    {
        $error = "";

        $filename = $this->get("filename");
        if(!file_exists($filename))
            return HTACCESS_NOT_EXISTS;

        if($fp = @fopen($filename, "r")){
            $fs = intval(@filesize($filename));
            if ($fs > 0 )
                    $content = @fread($fp, $fs);
            else
                    $content = "";
            $file_hash = $this->makeHash($content);
            $db_hash = $this->get("hash");
            if($file_hash != $db_hash){
                return HTACCESS_WRONG;
            }
        }

        return $error;
    }

    function getStatus()
    {
        $error = $this->verify();
        $status = "ok";
        switch($error){
            case '': 
                    $status = "ok";
                    break;
            case HTACCESS_NOT_EXISTS:
                    $status = "not_exists";
                    break;
            case HTACCESS_WRONG:
                    $status = "wrong";
                    break;
        }

        return $status;
    }

    function notifyAdmin($errors)
    {
        $mail = new XLite_Model_Mailer();
        $mail->errors = $errors;
        $mail->adminMail = true;
        $mail->set("charset", $this->xlite->config->Company->locationCountry->get("charset"));
        $mail->compose(
                $this->config->getComplex('Company.site_administrator'),
                $this->config->getComplex('Company.site_administrator'),
                "htaccess_notify");
        $mail->send();
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
