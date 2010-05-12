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
class XLite_Module_WholesaleTrading_Controller_Admin_Wholesale extends XLite_Controller_Admin_Abstract
{
    public $params = array('target');
    
    function action_options() 
    {
        $config = new XLite_Model_Config();
        $options = $config->getByCategory('WholesaleTrading');
        for ($i=0; $i<count($options); $i++) {
            $name = $options[$i]->get('name');
            if ($name == "bulk_categories") {
                if (count($_POST['bulk_categories']) > 0) {
                    $options[$i]->set("value", implode(";", $_POST['bulk_categories']));
                } else {
                    $options[$i]->set("value", "");
                }
            } else {
                $type = $options[$i]->get('type');
                if ($type=='checkbox') {
                    if (empty($_POST[$name])) {
                        $val = 'N';
                    } else {
                        $val = 'Y';
                    }
                } else {
                    $val = trim($_POST[$name]);
                }

                $options[$i]->set("value", $val);
            }
        }

        // write changes on success
        for ($i=0; $i<count($options); $i++) {
            $options[$i]->update();
        }
    }

}
