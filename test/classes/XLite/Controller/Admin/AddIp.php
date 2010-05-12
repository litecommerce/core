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
class XLite_Controller_Admin_AddIp extends XLite_Controller_Admin_Abstract
{
    public $template = "add_ip.tpl";

    function init()
    {
        parent::init();

        if($this->getComplex('xlite.config.Security.admin_ip_protection') == "Y" && $this->get('mode') == "add" && $this->get('unique_key') != ""){
            $key = $this->get('unique_key');
            $key_pattern = "/^([a-f]|\d){32,32}$/";

            $waiting_list = new XLite_Model_WaitingIP();
            if(preg_match($key_pattern, $key) && $waiting_list->find("unique_key = '$key'")){
                $this->waiting_ip = $waiting_list;
                $waiting_list->approveIP();
                $waiting_list->delete();
            } else {
                $this->redirect("admin.php?target=add_ip&mode=error");
                exit;
            }
        } elseif($this->get('mode') != "error") {
            $this->redirect("admin.php?target=add_ip&mode=error");
            exit;
        }
    }
}
