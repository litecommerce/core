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
class XLite_Module_Affiliate_Controller_Admin_Module extends XLite_Controller_Admin_Module implements XLite_Base_IDecorator
{
    /**
     * Update module settings 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function doActionUpdate()
    {
        // this is a fixing for the default module config handler
        // NOTE: LC v2.0.0 only
        if (version_compare($this->config->Version->version, "2.0.0", "eq")) {
            foreach ($_POST as $name => $value) {
                if (is_array($value)) {
                    $_POST[$name] = serialize($value);
                }
            }
        }
        parent::action_update();
    }
}
