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

namespace XLite\Module\ProductAdviser\View;

// FIXME - to revise

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class CRPAddToCart extends \XLite\View\Button
{
    /*public $p_id = null;
    
    function gethref()
    {
        $product = new \XLite\Model\Product($this->get('p_id'));
        if (class_exists('\XLite\Module\ProductOptions\Model\ProductOption') && $product->hasOptions()) {
            $product->checkSafetyMode();
            $c_id = $product->getComplex('category.category_id');
            return "cart.php?target=product&product_id=".$this->get('p_id')."&category_id=".$c_id;
        } else {
            return "javascript: document.add_to_cart.product_id.value=".$this->get('p_id')."; if (isValid()) document.add_to_cart.submit()";
        }
    }*/
}
