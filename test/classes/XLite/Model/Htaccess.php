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

define('HTACCESS_NOT_EXISTS', 'MISSING');
define('HTACCESS_WRONG', 'FAILED');
define('CHECK_INTERVAL', 1 * 24 * 60);

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
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
        $file = $this->get('filename');
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
        $file = $this->get('filename');
        if(!($fp = @fopen($file, "w")))
            return;

        $content = $this->get('content');
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
                    $error_result = array("file" => $htaccess->get('filename'), "error" => $error);
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
                        "id" => $htaccess->get('id'),
                        "filename" => $htaccess->get('filename'),
                        "status" => $htaccess->getStatus()
                        );

            $results[] = $result;
            
        }

        return $results;
    }

    function verify()
    {
        $error = "";

        $filename = $this->get('filename');
        if(!file_exists($filename))
            return HTACCESS_NOT_EXISTS;

        if($fp = @fopen($filename, "r")){
            $fs = intval(@filesize($filename));
            if ($fs > 0 )
                    $content = @fread($fp, $fs);
            else
                    $content = "";
            $file_hash = $this->makeHash($content);
            $db_hash = $this->get('hash');
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
        $mail->set("charset", $this->xlite->config->Company->locationCountry->get('charset'));
        $mail->compose(
                $this->config->getComplex('Company.site_administrator'),
                $this->config->getComplex('Company.site_administrator'),
                "htaccess_notify");
        $mail->send();
    }
}
