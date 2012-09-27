<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
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
 * @category  LiteCommerce
 * @author    Vladimir Mityukov <mityukov@gmail.com>
 * @copyright Copyright (c) 2012 Vladimir Mityukov <mityukov@gmail.com>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   0.0.1
 */

namespace XLite\Module\VladimirMityukov\PriceBeater\View;

/**
 * ItemsList 
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class ItemsList extends \XLite\View\ItemsList\Product\Customer\ACustomer implements \XLite\Base\IDecorator
{
    /**
     * Determine if we need to display product market price
     *
     * @param \XLite\Model\Product $product Current product
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isShowPriceBeater(\XLite\Model\Product $product)
    {
        return \XLite\Module\VladimirMityukov\PriceBeater\Main::isShowPriceBeater($product);
    }
}
