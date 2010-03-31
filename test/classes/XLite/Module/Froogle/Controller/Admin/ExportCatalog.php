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
* Class description.
*
* @package Module_Froogle
* @access public
* @version $Id$
*/
class XLite_Module_Froogle_Controller_Admin_ExportCatalog extends XLite_Controller_Admin_ExportCatalog implements XLite_Base_IDecorator
{	
    public $fp = null;

    public function __construct(array $params)
    {
        parent::__construct($params);
        $this->pages["export_froogle"] = "Froogle";
        $this->pageTemplates["export_froogle"] = "modules/Froogle/export_froogle.tpl";
    }

    function action_export_froogle()
    {
        // switch to customer's zone context
        $this->_sessionData = $this->session->_data;
        $this->session->_data = array();
        $cart = XLite_Model_Cart::getInstance();
        $cart = null;

		$this->goCustomer();

		if ($this->getComplex('config.Froogle.direct_product_url')) {
			$this->xlite->set("GlobalQuickCategoriesNumber", ($this->getComplex('config.Froogle.direct_product_url') == "always") ? true : false);
		}

        $uname = $this->getComplex('config.Froogle.froogle_username') ?
                 $this->getComplex('config.Froogle.froogle_username') : "froogle";
        $fname = $this->getComplex('config.Froogle.froogle_file_name') ? $this->getComplex('config.Froogle.froogle_file_name') : $uname . ".txt";
        if ($this->get("mod") == "download") {
            $this->startDownload($fname);
        } else {
            set_time_limit(3600);
            echo "Exporting products . . . ";
            ob_clean();

            $ufile = "var/tmp/".$fname;
            $this->fp = fopen($ufile, "wb") or $this->doDie("FAILED: write failed for $ufile");
            ob_start(array($this, 'outputHandler'));
        }
        $p = new XLite_Model_Product();
        $p->export("froogle", $delimiter = "\t", $where = 'price > 0', $orderby = "product_id", null);
		$this->goAdmin();

        if ($this->get("mod") == "download") {
            exit(); // nothing to do
        }

        ob_end_flush();
        if ($this->fp) {
            fclose($this->fp);
        }    
        $this->fp = null;

        print "[OK]<br>";
        $this->upload($fname);
    }

    function outputHandler($buffer)
    {
        static $counter = 0;
        fwrite($this->fp, $buffer);

        $counter++;
        if ($counter % 10 == 0) {
            return ". ";
        }    
    }

    function _die($reason)
    {
        print $reason . "<br><br>";
?>
<br><a href="admin.php?target=export_catalog&page=export_froogle">Return to admin zone</a>
<?php
        die();
    }

    function upload($fname)
    {
        $this->hasFTP() or $this->doDie("FAILED: FTP extension not found!");
        func_flush();
        // save buffered content to upload file
        $ufile = "var/tmp/".$fname;
        $fp = fopen($ufile, "rb");
        $froogle_host = $this->getComplex('config.Froogle.froogle_host');
        print "Connecting to Froogle host ".$froogle_host." .. ";
        $ftp = ftp_connect($froogle_host) or $this->doDie("FAILED: unable to connect to $froogle_host");
        print "[OK]<br>";
        print "Logging in .. ";
        ftp_login($ftp, $this->getComplex('config.Froogle.froogle_username'), $this->getComplex('config.Froogle.froogle_password')) or $this->doDie("FAILED: invalid login/password");
        print "[OK]<br>";
        print "Uploading file $fname .. ";
        ftp_fput($ftp, $fname, $fp, FTP_BINARY) or $this->doDie("FAILED: unable to upload file");
        print "[OK]<br>";
        ftp_quit($ftp);
        $this->doDie("FINISHED<BR>");
    }

    function hasFTP()
    {
        return function_exists("ftp_connect");
    }

	function goCustomer() // {{{
	{
		$this->xlite->set("adminZone", false);

		// save current (admin) environment and build new (customer)
		$this->_REQUEST = $_REQUEST;
		$this->_GET     = $_GET;
		$this->_POST    = $_POST;
		$this->_COOKIE  = $_COOKIE;
		$this->_SERVER  = $_SERVER;

		// reset autoglobals
		$_REQUEST = array();
		$_GET     = array();
		$_POST    = array();
		$_COOKIE  = array();

		$_SERVER["REQUEST_METHOD"] = "GET";
		// fake http
		if (isset($_SERVER['HTTPS'])) {
			unset($_SERVER['HTTPS']);
		}
		$_SERVER['SERVER_PORT'] = "80";

		// reset session content
		$this->_sessionData = $this->session->_data;
		$this->session->_data = array();

		// switch layout to customer's zone
		$layout = XLite_Model_Layout::getInstance();
		$layout->set("skin", "default");

		// empty cart
		$cart = XLite_Model_Cart::getInstance();
		$cart = null;
	} // }}}

	function goAdmin() // {{{
	{
		// switch XLite back to admin's zone
		$this->xlite->set("adminZone", true);
		$this->session->_data = $this->_sessionData;
	} // }}}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
