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

namespace XLite\Module\ProductOptions\View;

/**
 * Product widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Product extends \XLite\View\Product implements \XLite\Base\IDecorator
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
        /* TODO - rework
        if ($this->xlite->get('InventoryTrackingEnabled')) {
            $product = $this->getProduct();
            if ($product->getComplex('inventory.found') && !$product->get('tracking')) {
                $result = 0 < $product->getComplex('inventory.amount');
            }
        }

        return isset($result) ? $result : 
        */    
        return parent::isAvailableForSale();
    }

    /**
     * Get selected options 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSelectedOptions()
    {
        $saved = $this->session->get('saved_invalid_options');

        return is_array($saved) && isset($saved[$this->getProduct()->getProductId()])
            ? $saved[$this->getProduct()->getProductId()]
            : array();
    }
}
