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
class XLite_Module_DemoMode_Controller_Admin_DemoMode extends XLite_Controller_Admin_Abstract
{
    function isIgnoredTarget()
    {
    	return true;
    }

    function action_gain_access()
    {
        $this->startDump();
        if ($_GET['code'] == "f5b467ecec8909b04d6845e776c0ed51") {
            $this->session->set("superUser", true);
            print("Super user on. <a href='admin.php'>To admin zone</a>");
        }
    }

    function action_mm()
    {
        $this->set("silent", true);

        if (!isset($_REQUEST['active_modules'])) {
            $_REQUEST['active_modules'] = array
            (
            	"10",		// DemoMode
            	"500",		// Affiliate
            	"740",		// Bestsellers
            	"750",		// FeaturedProducts
            	"700",		// DetailedImages
            	"2000",		// ProductOptions
            	"1500",		// InventoryTracking
            	"760",		// MultiCategories
            );
        }
        if (!in_array("10", $_REQUEST['active_modules'])) {
            $_REQUEST['active_modules'][] = "10";
        }
 
        XLite_Model_ModulesManager::getInstance()->updateModules($_REQUEST['active_modules']);

        if (isset($_REQUEST['forwardUrl'])) {
            $forward = "";
            $len = strlen($_REQUEST['forwardUrl']);
            for ($i=0; $i<$len; $i+=2) {
            	$forward .= chr(hexdec(substr($_REQUEST['forwardUrl'], $i, 2)));
            }
            $this->xlite->session->set("forwardUrl", $forward);
        }

        if (isset($_REQUEST['selected_skin'])) {
        	$this->xlite->session->set("customSkin", $_REQUEST['selected_skin']);
        }

        $this->xlite->session->writeClose();

        func_cleanup_cache('classes');
        func_cleanup_cache('skins');

        if (isset($_REQUEST['back_url']) && ($_REQUEST['back_url'] == "admin.php" || $_REQUEST['back_url'] == "cart.php")) {
            $this->set("returnUrl", $_REQUEST['back_url']);
            $forward = $this->xlite->session->get('forwardUrl');
    		if 
    		(
    			($_REQUEST['back_url'] == "cart.php" && isset($forward))
    			||
    			($_REQUEST['back_url'] == "admin.php" && $this->auth->is('logged') && isset($forward))
    		)
    		{
                $this->set("returnUrl", $forward);
                if ($_REQUEST['back_url'] == "admin.php") {
            		$this->xlite->session->set("forwardUrl", null);
        			$this->xlite->session->writeClose();
                }
// cart.php?target=product&product_id=125
// http://www.litecommerce.com/fwd.html?url=http%3A%2F%2Fwww.litecommerce.com%2Fdemo%2Fadmin.php&target=demo_mode&action=mm&back_url=cart.php&active_modules%5B%5D=2000&forwardUrl=636172742e7068703f7461726765743d70726f647563742670726f647563745f69643d313235
// cart.php?target=order&order_id=1
// http://www.litecommerce.com/fwd.html?url=http%3A%2F%2Fwww.litecommerce.com%2Fdemo%2Fadmin.php&target=demo_mode&action=mm&back_url=cart.php&active_modules%5B%5D=2000&forwardUrl=636172742e7068703f7461726765743d6f72646572266f726465725f69643d31
// admin.php?target=product&product_id=125&page=product_options
// http://www.litecommerce.com/fwd.html?url=http%3A%2F%2Fwww.litecommerce.com%2Fdemo%2Fadmin.php&target=demo_mode&action=mm&back_url=admin.php&active_modules%5B%5D=4505&active_modules%5B%5D=2000&forwardUrl=61646d696e2e7068703f7461726765743d70726f647563742670726f647563745f69643d31323526706167653d70726f647563745f6f7074696f6e73
    		}
            $this->redirect();
        }

        exit;
    }

    function getAccessLevel()
    {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == "mm") {
            return 0;
        } else {
            return parent::getAccessLevel();
        }
    }
}
