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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_Froogle_Controller_Admin_ExportCatalog extends XLite_Controller_Admin_ExportCatalog implements XLite_Base_IDecorator
{
    public $fp = null;

    public function __construct(array $params)
    {
        parent::__construct($params);
        $this->pages['export_froogle'] = "Froogle";
        $this->pageTemplates['export_froogle'] = "modules/Froogle/export_froogle.tpl";
    }

    function action_export_froogle()
    {
        // switch to customer's zone context
        $this->_sessionData = $this->session->_data;
        $this->session->_data = array();
        $cart = XLite_Model_Cart::getInstance();
        $cart = null;

        $this->goCustomer();

        if ($this->config->Froogle->direct_product_url) {
            $this->xlite->set('GlobalQuickCategoriesNumber', ($this->config->Froogle->direct_product_url == "always") ? true : false);
        }

        $uname = $this->config->Froogle->froogle_username ?
                 $this->config->Froogle->froogle_username : "froogle";
        $fname = $this->config->Froogle->froogle_file_name ? $this->config->Froogle->froogle_file_name : $uname . ".txt";
        if ($this->get('mod') == "download") {
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
        $p->export('froogle', $delimiter = "\t", $where = 'price > 0', $orderby = "product_id", null);
        $this->goAdmin();

        if ($this->get('mod') == "download") {
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
        $froogle_host = $this->config->Froogle->froogle_host;
        print "Connecting to Froogle host ".$froogle_host." .. ";
        $ftp = ftp_connect($froogle_host) or $this->doDie("FAILED: unable to connect to $froogle_host");
        print "[OK]<br>";
        print "Logging in .. ";
        ftp_login($ftp, $this->config->Froogle->froogle_username, $this->config->Froogle->froogle_password) or $this->doDie("FAILED: invalid login/password");
        print "[OK]<br>";
        print "Uploading file $fname .. ";
        ftp_fput($ftp, $fname, $fp, FTP_BINARY) or $this->doDie("FAILED: unable to upload file");
        print "[OK]<br>";
        ftp_quit($ftp);
        $this->doDie("FINISHED<BR>");
    }

    function hasFTP()
    {
        return function_exists('ftp_connect');
    }

    function goCustomer() 
    {
        $this->xlite->set('adminZone', false);

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

        $_SERVER['REQUEST_METHOD'] = "GET";
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
        $layout->set('skin', "default");

        // empty cart
        $cart = XLite_Model_Cart::getInstance();
        $cart = null;
    }

    function goAdmin() 
    {
        // switch XLite back to admin's zone
        $this->xlite->set('adminZone', true);
        $this->session->_data = $this->_sessionData;
    }
}
