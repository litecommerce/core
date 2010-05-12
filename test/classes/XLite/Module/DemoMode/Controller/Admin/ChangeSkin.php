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
class XLite_Module_DemoMode_Controller_Admin_ChangeSkin extends XLite_Controller_Admin_ChangeSkin implements XLite_Base_IDecorator
{
    function getCurrentSkin()
    {
        $skin_name = "";
        switch ($this->session->get('customSkin')) {
            case "1":
                $skin_name = "3-columns modern";
            break;

            case "2":
                $skin_name = "2-columns classic";
            break;

            case "3":
                $skin_name = "3-columns classic";
            break;

            default:
                $skin_name = "2-columns modern";
            break;
        }

        return $skin_name;
    }

    function isDisplayWarning()
    {
        return false;
    }

    function action_update()
    {
        switch ($this->layout) {
            default:
            case "2-columns_modern":
                $skin_code = 0;
            break;

            case "3-columns_modern":
                $skin_code = 1;
            break;

            case "2-columns_classic":
                $skin_code = 2;
            break;

            case "3-columns_classic":
                $skin_code = 3;
            break;
        }

        $this->session->set("customSkin", $skin_code);
    }
}
