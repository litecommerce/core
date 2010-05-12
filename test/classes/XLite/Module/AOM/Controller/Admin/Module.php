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
class XLite_Module_AOM_Controller_Admin_Module extends XLite_Controller_Admin_Module implements XLite_Base_IDecorator
{
    function init()
    {
        parent::init();

        if ($this->page == "AOM") {
        	$lay = XLite_Model_Layout::getInstance();
        	$lay->addLayout("general_settings.tpl", "modules/AOM/config.tpl");
        }
    }

    /**
     * Update module settings 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function doActionUpdate()
    {
        if ($this->page == "AOM") {
            $value = (is_array($_REQUEST["order_update_notification"])) ? $_REQUEST["order_update_notification"] : array();

            $config = new XLite_Model_Config();
            $config->createOption("AOM", "order_update_notification", serialize($value));
        }

        parent::action_update();
    }

    function isEmailCheckedAOM($email)
    {
        $value = $this->xlite->config->getComplex('AOM.order_update_notification');
        if (!is_array($value))
            return false;

        return (in_array($email, $value)) ? true : false;
    }
}
