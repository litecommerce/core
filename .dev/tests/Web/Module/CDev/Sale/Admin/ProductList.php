<?php
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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.10
 *
 * @resource product
 */

class XLite_Web_Module_CDev_Sale_Admin_ProductList extends XLite_Web_Admin_AAdmin
{
    function testPutOnSale(){
        #Check checkboxes

        #iteration:
        #Click Put on sale
        #Data
        #Click save
        #check sale and msg

        #Data:
        #Percent > 100
        #Percent < 0
        #Sale price > price
        #Sale price < 0
        #Percent = 0
        #Sale price = 0
        #Percent > 0 < 100
        #Sale price > 0 < price

    }
}