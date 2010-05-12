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
class XLite_Module_Promotion_Controller_Admin_Taxes extends XLite_Controller_Admin_Taxes implements XLite_Base_IDecorator
{
    /*
     * This function required for configuring discounts taxing policy
     */
    function isDiscountUsedForTaxes()
    {
        return true;
    }

    function action_add_tax()	
    {
        parent::action_add_tax();
        $cfg = new XLite_Model_Config();
        $cfg->createOption("Taxes", "discounts_after_taxes", $_POST['discounts_after_taxes']);
    }

    function action_update_options()
    {
        parent::action_update_options();
        $cfg = new XLite_Model_Config();
        $cfg->createOption("Taxes", "discounts_after_taxes", $_POST['discounts_after_taxes']);
    }
}
