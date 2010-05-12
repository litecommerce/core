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
class XLite_Controller_Admin_CcCert extends XLite_Controller_Admin_Abstract
{

    public $params = array("target", "cc_processor");
    
    function action_update()
    {
        $tf_name = $_FILES['cert_file']['tmp_name'];
        if (is_uploaded_file($tf_name)) {
            $filename = $_FILES['cert_file']["name"];
            $content = file_get_contents($tf_name);
            @unlink($tf_name);
            $this->saveParam("file_name", $filename);
            $this->saveParam("cert_text", $content);
        }
        $this->set('returnUrl', "admin.php?target=payment_method&payment_method=" . $this->get('cc_processor'));
    }

    function saveParam($name, $value)
    {
        $cfg = new XLite_Model_Config();
        $update = false;
        if ($cfg->find("category='" . $this->get('cc_processor') . "' and name='" . $name . "'")) {
            $update = true;
        }
        $cfg->set("category", $this->get('cc_processor'));
        $cfg->set("name", $name);
        $cfg->set("value", $value);
        if (true === $update) {
            $cfg->update();
        } else {
            $cfg->create();
        }
    }
}
