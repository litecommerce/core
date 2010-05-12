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
class XLite_Module_Egoods_Controller_Admin_Product extends XLite_Controller_Admin_Product implements XLite_Base_IDecorator
{
    public function __construct(array $params) 
    {
        parent::__construct($params);
        $this->pages['downloadable_files'] = "Egoods";
        $this->pageTemplates['downloadable_files'] = "modules/Egoods/downloadable_files.tpl";
        $this->pages['pin_codes'] = "PIN codes";
        $this->pageTemplates['pin_codes'] = "modules/Egoods/pin_codes.tpl";
        $this->params[] = "pin_enabled";
    }

    function fillForm() 
    {
        $nowPlusExp = time()+24*3600*$this->config->getComplex('Egoods.exp_days');
        $this->set("new_exp_date", $nowPlusExp);
        parent::fillForm();
    }

    function prepare_egood(&$df) { }

    function action_add_egood() 
    {
        if 
        (
            ($_POST['new_remote'] == "Y" && isset($_FILES['new_remote_file']["name"]) && strlen($_FILES['new_remote_file']["name"]) > 0)
            ||
            ($_POST['new_remote'] == "N" && !empty($_POST['new_local_file']))
        ) {
    		$df = new XLite_Module_Egoods_Model_DownloadableFile();
    		$df->set('product_id', $_POST['product_id']);
    		$df->set('store_type', 'F');
    		$df->set('delivery', $_POST['new_file_delivery']);
    		$this->prepare_egood($df);
    		$df->create();
    		
    		if ($_POST['new_remote'] == "Y") {
    			$path = $this->getComplex('xlite.config.Egoods.egoods_store_dir') . '/' . $df->get('file_id');
    			mkdirRecursive($path);
    			$file_name = $path . '/' . $_FILES['new_remote_file']['name'];
    			if (is_uploaded_file($_FILES['new_remote_file']['tmp_name'])) {
    				move_uploaded_file($_FILES['new_remote_file']['tmp_name'], $file_name);
    				$df->set('data', $file_name);
    			}
    		} else {
    			$df->set('data', $_POST['new_local_file']);
    		}
    		$df->update();
        }
    }

    function action_update_egood() 
    {
        $df = new XLite_Module_Egoods_Model_DownloadableFile($_POST['file_id']);
        $df->set("delivery", $_POST['delivery']);

        if 
        (
            ($_POST['remote'] == "Y" && isset($_FILES['remote_file']["name"]) && strlen($_FILES['remote_file']["name"]) > 0)
            ||
            ($_POST['remote'] == "N" && !empty($_POST['local_file']))
        ) {
    		
    		if ($_POST['remote'] == 'Y') {
    			$path = $this->getComplex('xlite.config.Egoods.egoods_store_dir') . '/' . $df->get('file_id');
    			mkdirRecursive($path);
    			$file_name = $path . '/' . $_FILES['remote_file']['name'];
    			if (is_uploaded_file($_FILES['remote_file']['tmp_name'])) {
    				@unlink($df->get('data'));
    				move_uploaded_file($_FILES['remote_file']['tmp_name'], $file_name);
    				$df->set('data', $file_name);
    			}
    		} else {
    			@unlink($df->get('data'));
    			$df->set('data', $_POST['local_file']);
    		}
    	}

        $this->prepare_egood($df);
        $df->update();
    }

    function action_delete_egood() 
    {
        if (!isset($_POST['file_id']) || empty($_POST['file_id'])) {
            return;
        }
        
        $df = new XLite_Module_Egoods_Model_DownloadableFile($_POST['file_id']);
        @unlink($df->get('data'));
        $df->delete();
        $link = new XLite_Module_Egoods_Model_DownloadableLink();
        $links = $link->findAll('file_id='. $_POST['file_id']);

        for ($i = 0; $i < count($links); $i++) {
            $links[$i]->delete();
        }
    }

    function getNewLinkAccessKey() 
    {
        return md5(microtime(true));
    }

    function action_add_link() 
    {
        $dl = new XLite_Module_Egoods_Model_DownloadableLink($_POST['new_acc']);
        $dl->set('file_id', $_POST['file_id']);
        $dl->set('exp_time', mktime(0, 0, 0, $_POST['new_exp_dateMonth'], $_POST['new_exp_dateDay'], $_POST['new_exp_dateYear']));
        $dl->set('available_downloads', $_POST['new_downloads']);
        $dl->set('expire_on', $_POST['new_expires']);
        $dl->set('link_type', 'M');
        $dl->create();
    }

    function action_delete_links() 
    {
        if (!isset($_POST['selected_links']) || !is_array($_POST['selected_links'])) {
            return;
        }
        foreach($_POST['selected_links'] as $access_key) {
            $dl = new XLite_Module_Egoods_Model_DownloadableLink();
            if ($dl->find('access_key=' . "'" . $access_key . "'")) {
                $dl->delete();
            }
        }
    }

    function action_update_free_charge() 
    {
        $product = $this->get('product');
        if (isset($_POST['free_charge']) && is_array($_POST['free_charge'])) {
            $product->set('egood_free_for_memberships', implode(',', $_POST['free_charge']));
        } else {
            $product->set('egood_free_for_memberships', '');
        }
        $product->update();
    }

    function action_add_pincode() 
    {
        $pin = new XLite_Module_Egoods_Model_PinCode();
        $pin->set('product_id', $this->get('product_id'));
        $pin->set('pin', $_POST['new_pin']);
        $pin->set('enabled', (int)$_POST['new_pin_enabled']);
        $pin->create();
    }

    function action_delete_pin_codes() 
    {
        if (!isset($_POST['selected_pins']) || !is_array($_POST['selected_pins'])) {
            return;
        }
        foreach($_POST['selected_pins'] as $pin_id) {
            $p = new XLite_Module_Egoods_Model_PinCode($pin_id);
            $p->delete();
        }
    }
    
    function action_disable_pin_codes() 
    {
        if (!isset($_POST['selected_pins']) || !is_array($_POST['selected_pins'])) {
            return;
        }
        foreach($_POST['selected_pins'] as $pin_id) {
            $p = new XLite_Module_Egoods_Model_PinCode($pin_id);
            $p->set('enabled', false);
            $p->update();
        }
    }

    function action_enable_pin_codes() 
    {
        if (!isset($_POST['selected_pins']) || !is_array($_POST['selected_pins'])) {
            return;
        }
        foreach($_POST['selected_pins'] as $pin_id) {
            $p = new XLite_Module_Egoods_Model_PinCode($pin_id);
            $p->set('enabled', true);
            $p->update();
        }
    }

    function action_free_pin_codes() 
    {
        if (!isset($_POST['selected_pins']) || !is_array($_POST['selected_pins'])) {
            return;
        }
        foreach($_POST['selected_pins'] as $pin_id) {
            $p = new XLite_Module_Egoods_Model_PinCode($pin_id);
            $p->set('order_id', 0);
            $p->set('item_id', '');
            $p->update();
        }
    }

    function getPinCodes() 
    {
        if (!isset($this->pin_codes)) {
            $p = new XLite_Module_Egoods_Model_PinCode();
            $this->pin_codes = $p->findAll('product_id=' . $this->get('product_id') . 
                                                ($this->get('pin_enabled') != null ? 
                                                ' AND enabled=' . intval($this->get('pin_enabled')) : 
                                                ''));
        }
        return $this->pin_codes;
    }

    function action_update_pin_src() 
    {
        $pin_settings = new XLite_Module_Egoods_Model_PinSettings();
        $action = "";
        if ($pin_settings->find('product_id=' . $this->getComplex('product.product_id'))) {
            $action = "update";
        } else {
            $action = "create";
            $pin_settings->set('product_id', $this->getComplex('product.product_id'));
        }
        $pin_settings->set('pin_type', $_POST['pin_src']);
        isset($this->low_available_limit) ? $pin_settings->set('low_available_limit',$this->low_available_limit) : $pin_settings->set('low_available_limit',0);
        $pin_settings->$action();
    }

    function action_update_pin_cmd_line() 
    {
        $pin_settings = new XLite_Module_Egoods_Model_PinSettings();
        $action = '';
        if (!$pin_settings->find("product_id=" . $this->getComplex('product.product_id'))) {
            $action = 'create';
            $pin_settings->set('product_id', $this->getComplex('product.product_id'));
        } else {
            $action = 'update';
        }
        $pin_settings->set('gen_cmd_line', $_POST['gen_cmd_line']);
        $pin_settings->$action();
    }

    function isValidEgoodsStoreDir() 
    {
        $store_dir = $this->getComplex('xlite.config.Egoods.egoods_store_dir');
        if (!is_dir($store_dir) || !is_writable($store_dir)) {
            return false;
        }
        return true;
    }
}
