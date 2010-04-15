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
class XLite_Module_GiftCertificates_Controller_Admin_GiftCertificateEcards extends XLite_Controller_Admin_Abstract
{
    function getECards()
    {
        $ecard = new XLite_Module_GiftCertificates_Model_ECard();
        return $ecard->findAll();
    }

    function action_update()
    {
        if (isset($_POST["pos"])) {
            foreach ($_POST["pos"] as $ecard_id => $order_by) {
                $ec = new XLite_Module_GiftCertificates_Model_ECard($ecard_id);
                $ec->set("order_by", $order_by);
                if (isset($_POST["enabled"][$ecard_id])) {
                    $ec->set("enabled", 1);
                } else {
                    $ec->set("enabled", 0);
                }
                $ec->update();
            }
        }
    }

    function action_delete()
    {
        $ec = new XLite_Module_GiftCertificates_Model_ECard($_REQUEST["ecard_id"]);
        $ec->delete();
    }

}
