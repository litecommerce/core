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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.5
 */

namespace XLite\Module\CDev\VAT\View;

/**
 * Product option modifier widget
 *
 * @see   ____class_see____
 * @since 1.0.5
 *
 * LC_Dependencies ("CDev\ProductOptions")
 */
class ProductOptionModifier extends \XLite\Module\CDev\ProductOptions\View\ProductOptionModifier implements \XLite\Base\IDecorator
{
    /**
     * Get modifier personal template
     *
     * @param \XLite\Module\CDev\ProductOptions\Model\OptionSurcharge $surcharge Modifier
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.5
     */
    public function getModifierTemplate(\XLite\Module\CDev\ProductOptions\Model\OptionSurcharge $surcharge)
    {
        if ('price' == $surcharge->getType()) {
            $tpl = 'modules/CDev/VAT/product_option_modifier_price.tpl';

        } else {
            $tpl = parent::getModifierTemplate($surcharge);
        }

        return $tpl;
    }
}
