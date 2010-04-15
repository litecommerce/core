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
class XLite_Controller_Admin_ImportUsers extends XLite_Controller_Admin_Abstract
{	
    public $import_error = false;

    function init()
    {
        $p = new XLite_Model_Profile();
        $this->import_fields = $p->get("importFields");
        parent::init();
    }
    
    function handleRequest()
    {
        if (substr($this->action, 0, 6) == "import" && !$this->checkUploadedFile()) {
        	$this->set("valid", false);
        	$this->set("invalid_file", true);
        }

        parent::handleRequest();
    }

    function action_import()
    {
        $this->startDump();
        $this->change_layout();
        $options = array(
            "file"              => $this->getUploadedFile(),
            "layout"            => $this->user_layout,
            "delimiter"         => $this->delimiter,
			"text_qualifier"    => $this->text_qualifier,
            "md5_import"        => $this->md5_import,
			"return_error"		=> true,
            );
        $p = new XLite_Model_Profile();
        $p->import($options);
		$this->importError = $p->importError;
    }

    function change_layout($layout_name = "user_layout")
    {
        $layout = implode(',', XLite_Core_Request::getInstance()->$layout_name);
        $this->config = new XLite_Model_Config();
        if ($this->config->find("name='$layout_name'")) {
            $this->config->set("value", $layout);
            $this->config->update();
        } else {
            $this->config->set("name", $layout_name);
            $this->config->set("category", "ImportExport");
            $this->config->set("value", $layout);
            $this->config->create();
        }
    }
    function action_layout($layout_name = "user_layout")
    {
        $this->change_layout($layout_name);
    }

	function getPageReturnUrl()
	{
		if ($this->action == "import") {
			$text = ($this->importError)?"Import process failed.":"Users are imported successfully.";
			return array($this->importError.'<br>'.$text.' <a href="admin.php?target=import_users"><u>Click here to return to admin interface</u></a>');
		} else {
			return parent::getPageReturnUrl();
		}
	}
}
