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
class XLite_Controller_Admin_CardTypes extends XLite_Controller_Admin_Abstract
{
	function obligatorySetStatus($status)
	{
		if (!in_array("status", $this->params)) {
			$this->params[] = "status";
		}
		$this->set("status", $status);
	}

    function action_delete()
    {
        if (isset(XLite_Core_Request::getInstance()->code)) {
            $card = new XLite_Model_Card();
            if ($card->find("code='" . XLite_Core_Request::getInstance()->code . "'")) {
                $card->delete();
            }
        }

		$this->obligatorySetStatus("deleted");
    }

    function action_add()
    {
		if ( empty(XLite_Core_Request::getInstance()->code) ) {
			$this->set("valid", false);
			$this->obligatorySetStatus("code");
			return;
		}

		if ( empty(XLite_Core_Request::getInstance()->card_type) ) {
			$this->set("valid", false);
			$this->obligatorySetStatus("card_type");
			return;
		}

        // checkboxes
        if (!isset(XLite_Core_Request::getInstance()->cvv2)) {
			XLite_Core_Request::getInstance()->cvv2 = 0;
        }
        if (!isset(XLite_Core_Request::getInstance()->enabled)) {
			XLite_Core_Request::getInstance()->enabled = 0;
        }
        $card = new XLite_Model_Card();
        $card->set("properties", XLite_Core_Request::getInstance()->getData());
        if ($card->isExists()) {
            $this->set("valid", false);
            $this->obligatorySetStatus("exists");
            return;
        }
        
        $card->create();

		$this->obligatorySetStatus("added");
    }
    
    function action_update()
    {
        foreach (XLite_Core_Request::getInstance()->card_types as $id => $data) {
            $data["enabled"] = array_key_exists("enabled", $data) ? 1 : 0;
            $data["cvv2"]    = array_key_exists("cvv2",    $data) ? 1 : 0;
            $card = new XLite_Model_Card(); 
            $card->set("properties", $data);
            $card->update();
        }

		$this->obligatorySetStatus("updated");
    }
}
