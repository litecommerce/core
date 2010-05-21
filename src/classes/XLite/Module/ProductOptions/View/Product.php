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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Product widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_ProductOptions_View_Product extends XLite_View_Product implements XLite_Base_IDecorator
{
    /**
     * Check - available product for sale or not
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isAvailableForSale()
    {
        if ($this->xlite->get('InventoryTrackingEnabled')) {
            $product = $this->getProduct();
            if ($product->getComplex('inventory.found') && !$product->get('tracking')) {
                $result = 0 < $product->getComplex('inventory.amount');
            }
        }

        return isset($result) ? $result : parent::isAvailableForSale();
    }
}
