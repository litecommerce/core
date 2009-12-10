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
* @package Dialog
* @access public
* @version $Id: download.php,v 1.6 2008/10/23 11:53:38 sheriff Exp $
*/
class Dialog_download extends Dialog
{
    var $params = array("mode");

	function action_download()
	{
		if (isset($_REQUEST['acc']) && !empty($_REQUEST['acc'])) {
			$this->downloadByAccessKey();
		} else if (isset($_REQUEST['file_id']) && !empty($_REQUEST['file_id'])) {
			$this->downloadByFileId();
		}
	}

	function downloadByAccessKey() // {{{
	{
		$access_key = $_REQUEST['acc'];
		$dl =& func_new('DownloadableLink');
		$time = time();
		
		// check if the link with given access key exists
		if ($dl->find("access_key='" . $access_key . "'")) {
			
			// check for product download availability
			if (!$dl->is('active')) {
				$reason = $dl->get("deniedReason");
				$this->set('returnUrl', 'cart.php?target=download&mode=file_access_denied&reason=' . $reason); 
				return;
			}
			
			$df =& func_new('DownloadableFile', $dl->get('file_id'));
			// check for file
			if (!is_file($df->get('data'))) {
				$this->set('returnUrl', 'cart.php?target=download&mode=file_not_found&filename=' . 
					basename($df->get('data')) . 
					"&requested_url=" . $this->retriveRequestedUrl()
				);
				return;
			}
			
			// download the file
			$this->set("silent", true);
			$this->startDownload(basename($df->get('data')));
			$this->readFile($df->get('data'));

			// decrase downloads limit
			$dl->set('available_downloads', $dl->get('available_downloads') - 1);
			$dl->update();
			
			// save download statistics
			$ds =& func_new('DownloadsStatistics');
			$ds->set('file_id', $df->get('file_id'));
			$ds->set('date', $time);
			$ds->set('headers', "HTTP_REFERER=" . $_SERVER["HTTP_REFERER"] . ", REMOTE_ADDR=" . $_SERVER["REMOTE_ADDR"]);
			$ds->create();
			exit();
		} else {
			$this->set('returnUrl', 'cart.php?target=download&mode=file_access_denied');
		}
	} // }}}

	function downloadByFileId() // {{{
	{
		$file_id = $_REQUEST['file_id'];
		$time = time();
		$df =& func_new('DownloadableFile', $file_id);
		$product_id = $df->get('product_id');
		
		$product =& func_new('Product', $product_id);
		if (!$product->isFreeForMembership($this->get('cart.profile.membership'))) {
			$this->set('returnUrl', 'cart.php?target=download&mode=file_access_denied&reason=M');
			return;
		}
		

			// check for file
		if (!is_file($df->get('data'))) {
			$this->set('returnUrl', 'cart.php?target=download&mode=file_not_found&filename=' . 
				basename($df->get('data')) . 
				"&requested_url=" . $this->retriveRequestedUrl()
			);
			return;
		}
		
		// download the file
		$this->set("silent", true);
		$this->startDownload(basename($df->get('data')));
		$this->readFile($df->get('data'));

		// save download statistics
		$ds =& func_new('DownloadsStatistics');
		$ds->set('file_id', $df->get('file_id'));
		$ds->set('date', $time);
		$ds->set('headers', "HTTP_REFERER=" . $_SERVER["HTTP_REFERER"] . ", REMOTE_ADDR=" . $_SERVER["REMOTE_ADDR"]);
		$ds->create();
		exit();
	} // }}}

	function readFile($name)
	{
        $handle = @fopen($name, "rb");
        if ($handle) {
            while (!feof($handle)) {
              $contents = @fread($handle, 8192);
              echo $contents;
            }
            fclose($handle);
		}		
	}

	function retriveRequestedUrl()
	{
		return urlencode($_SERVER['QUERY_STRING']);
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
