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

// Logger defaults
define('LOGGER_DEFAULT_TYPE', 'null');
define('LOGGER_DEFAULT_NAME', '/dev/null');
define('LOGGER_DEFAULT_LEVEL', LOG_DEBUG);
define('LOGGER_DEFAULT_IDENT', 'X-Lite');

/**
* Class Logger implements the logging facility.
*
* @package Kernel
* @access public
* @version $Id$
*/
class XLite_Logger extends XLite_Base implements XLite_Base_ISingleton
{
    /**
    * Logger options.
    *
    * @var    options
    * @access private
    */	
    public $_options = array(
            'type'  => LOGGER_DEFAULT_TYPE,
            'name'  => LOGGER_DEFAULT_NAME,
            'level' => LOGGER_DEFAULT_LEVEL,
            'ident' => LOGGER_DEFAULT_IDENT
        );

    /**
    * Constructor.
    *
    * @param  array $options (optional) Logger configuration options. 
    * @access public
    * @return void
    */
    public function __construct()
    {
        $this->_options = array_merge($this->_options, XLite::getInstance()->getOptions('log_details'));
    }
    
    public static function getInstance()
    {
        return self::getInternalInstance(__CLASS__);
    }

    /**
    * Writes message to log. Creates Log object instance if necessary.
    *
    * @param string $message  The textual message to be logged.
    * @param int    $level    (optional) The message priority level. Valid
    *                         values are: LOG_EMERG, LOG_ALERT, LOG_CRIT,
    *                         LOG_ERR, LOG_WARNING, LOG_NOTICE, LOG_INFO, and
    *                         LOG_DEBUG. The default is LOGGER_DEFAULT_LEVEL
    *
    * @access public
    * @param array   $options (optional) The configuration options.
    * @static
    */
    function log($message, $level = null)
    {
		require_once LC_ROOT_DIR . 'lib' . LC_DS . 'Log.php';

        $logger = Log::singleton($this->getType(),
                                  $this->getName(),
                                  $this->getIdent()
                                  );
        if (is_null($level)) {
            $level = $this->getLevel();
        }
        $logger->log($message, $level);
    }

    /**
    * Returns the Logger type
    * @access public
    */
    function getType()
    {
        return $this->_options["type"];
    }

    /**
    * Returns the Logger name
    * @access public
    */
    function getName()
    {
        return $this->_options["name"];
    }

    /**
    * Returns the Logger ident
    * @access public
    */
    function getIdent()
    {
        return $this->_options["ident"];
    }

    /**
    * Returns the Logger priority level
    * @access public
    */
    function getLevel()
    {
        $target = isset($_REQUEST["target"]) ? $_REQUEST["target"] : "main";
        $xself = isset($GLOBALS["XLITE_SELF"]) ? basename($GLOBALS["XLITE_SELF"], ".php") : 'unknown';
        return $xself . ":" . $target;
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
