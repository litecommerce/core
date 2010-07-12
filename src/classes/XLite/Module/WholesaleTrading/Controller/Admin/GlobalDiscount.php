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

namespace XLite\Module\WholesaleTrading\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class GlobalDiscount extends \XLite\Controller\Admin\AAdmin
{
    function getGlobalDiscounts()
    {
        $gd = new \XLite\Module\WholesaleTrading\Model\GlobalDiscount();
        $gd->defaultOrder = "subtotal";
        return $gd->findAll();
    }
                                            
    function action_add()
    {
        $gd = new \XLite\Module\WholesaleTrading\Model\GlobalDiscount();
        $gd->set('subtotal', $_POST['discount_subtotal']);
        $gd->set('discount', abs($_POST['discount_value']));
        $gd->set('discount_type', $_POST['discount_type']);
        $gd->set('membership', $_POST['discount_membership']);
        $gd->create();
    }

    function action_update()
    {
        $gd = new \XLite\Module\WholesaleTrading\Model\GlobalDiscount($_POST['discount_id']);
        $gd->set('subtotal', $_POST['gd_subtotal']);
        $gd->set('discount', abs($_POST['gd_value']));
        $gd->set('discount_type', $_POST['gd_type']);
        $gd->set('membership', $_POST['gd_membership']);
        $gd->update();
    }

    function action_delete()
    {
        $gd = new \XLite\Module\WholesaleTrading\Model\GlobalDiscount($_POST['discount_id']);
        $gd->delete();
    }
}
