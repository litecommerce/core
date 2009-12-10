<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2007 Creative Development <info@creativedevelopment.biz>  |
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
*
* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
*/

/**
* The Log_file class is a concrete implementation of the Log::
* abstract class which writes message to a text file.
* 
* @package kernel
* @access public
* @version $Id: file.php,v 1.3 2007/05/21 11:53:25 osipov Exp $
*/
class Log_file extends Log {
    
    // {{{ properties
    
    /** String holding the filename of the logfile. */
    var $filename = '';

    /** Integer holding the file handle. */
    var $fp = '';
    
    // }}}
    
    
    // {{{ constructor
    /**
     * Constructs a new logfile object.
     * 
     * @param $log_name The filename of the logfile.
     * @param $ident    (optional) The identity string.
     * @param $conf     (optional) The configuration array.
     */
    function Log_file ($log_name, $ident = '', $conf = false) {
        $this->filename = $log_name;
        $this->ident = $ident;
    }
    // }}}
    
    
    // {{{ open()
    /**
     * Opens the logfile for appending, if it has not already been opened.
     * If the file doesn't already exist, attempt to create it.  This is
     * implicitly called by log(), if necessary.
     */
    function open () {
        if (!$this->opened) {
            $this->fp = fopen($this->filename, 'a');
            $this->opened = true;
        }
    }
    // }}}
    
    // {{{ close()
    /**
     * Closes the logfile, if it is open.
     */
    function close () {
        if ($this->opened) {
            fclose($this->fp);
            $this->opened = false;
        }
    }
    // }}}
    
    // {{{ log()
    /**
     * Writes $message to the currently open logfile.  Calls open(), if
     * necessary.  Also, passes the message along to any Log_observer
     * instances that are observing this Log.
     * 
     * @param $message  The textual message to be logged.
     * @param $priority Logging facility
     */
    function log ($message, $priority) {
        if (!$this->opened)
            $this->open();
        
        $entry = sprintf("%s %s [%s] %s\n", strftime("%b %d %T"),
            $this->ident, $priority, $message);

        if ($this->fp)
            fwrite($this->fp, $entry);
    }
    // }}}
    
}

?>
