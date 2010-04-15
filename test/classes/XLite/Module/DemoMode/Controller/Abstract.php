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
class XLite_Module_DemoMode_Controller_Abstract extends XLite_Controller_Abstract implements XLite_Base_IDecorator
{
	// FIXME
    function init()
    {
        $target = isset($_REQUEST["target"]) ? strtolower($_REQUEST["target"]) : "main";
        $action = isset($_REQUEST["action"]) ? strtolower($_REQUEST["action"]) : "default";
        if ($this->isDeniedAction($target, $action) && !$this->session->get("superUser")) {
            $this->redirect(XLite::ADMIN_SELF . "?target=demo_mode");
            die();
        }

        parent::init();
    }

    function isDeniedAction($target, $action)
    {
        return
        (
            $target == "catalog" && $action == "build" ||
            $target == "catalog" && $action == "clear" ||
            $target == "users" && $action == "delete" ||
            (($target == "category" || $target == "categories") && ($action == "delete" || $action == "delete_all")) ||
            $target == "wysiwyg" && $action != "default" ||
            $target == "import_catalog" && $action == "import_products" && isset($_REQUEST["delete_products"]) ||
            $target == "profile" && $action == "delete" ||
            $target == "db" && $action != "default" ||
			$target == "image_files" && $action != "default" ||
			$target == "image_edit" && $action != "default" ||
			$target == "css_edit" && $action == "save" ||
			$target == "css_edit" && $action == "restore_default" ||
			$target == "xcart_import" && $action != "default" || 
			$target == "files" || $target == "test" ||
            $target == "advanced_security" && $action != "default" ||
            $target == "template_editor" && $action != "default" && $action != "extra_pages" && $action != "advanced" && $action != "advanced_edit" && $action != "page_edit" ||
            ($target == "modules" && ($action == "install" || $action == "uninstall")) ||
            ($target == "module" && $action == "update" && $_REQUEST["page"] == "Egoods") ||
            ($target == "settings" && $action == "phpinfo") ||
            ($target == "ups_online_tool" && $action == "next" && $this->session->get("ups_step") == 2)
    	);
    }

    protected function redirect($url = null)
    {
        if (!$this->xlite->is("adminZone")) {
            $forward = $this->xlite->session->get("forwardUrl");
            if (isset($forward)) {
        		$currentUrl = $this->getUrl();
        		if (strpos($currentUrl, $forward) === false) {
                    $this->xlite->session->set("forwardUrl", null);
                    $this->xlite->session->writeClose();
        		}
        	}
        }

        parent::redirect($url);
    }
}
