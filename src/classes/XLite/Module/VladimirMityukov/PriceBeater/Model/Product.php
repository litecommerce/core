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

namespace XLite\Module\VladimirMityukov\PriceBeater\Model;

/**
 * Product 
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Product extends \XLite\Model\Product implements \XLite\Base\IDecorator
{
    /**
     * Product market price
     *
     * @var   float
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="decimal", precision=14, scale=4)
     */
    protected $priceBeaterThreshold = 0.0000;
}
